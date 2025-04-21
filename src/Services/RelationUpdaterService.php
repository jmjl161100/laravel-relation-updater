<?php

namespace Jmjl161100\RelationUpdater\Services;

use Illuminate\Database\Eloquent\Model;
use Jmjl161100\RelationUpdater\Contracts\RelationUpdater;

class RelationUpdaterService implements RelationUpdater
{
    public function update(
        ?array $input,
        array $fields,
        string $foreignKey,
        int $parentId,
        Model $childModel,
        ?string $softDeleteField = null,
        mixed $softDeleteValue = 1
    ): array {
        $created = collect();
        $updated = collect();
        $existing = $childModel->where($foreignKey, $parentId)->get();

        // 无输入数据时删除所有关联
        if (empty($input)) {
            $deleted = $existing;
            $softDeleteField
                ? $childModel->where($foreignKey, $parentId)->update([$softDeleteField => $softDeleteValue])
                : $childModel->where($foreignKey, $parentId)->delete();

            return compact('created', 'deleted', 'updated');
        }

        $existingIds = $existing->pluck('id')->all();
        $remainingIds = $existingIds;

        // 判断字段定义是简单数组还是映射关系
        $isMapping = ! array_is_list($fields);

        foreach ($input as $item) {
            // 更新现有记录
            if (isset($item['id']) && in_array($item['id'], $existingIds)) {
                $model = $existing->firstWhere('id', $item['id']);

                // 准备更新数据
                $changes = [];
                if ($isMapping) {
                    // 映射关系模式 ['input_key' => 'db_field']
                    foreach ($fields as $inputKey => $dbField) {
                        if (array_key_exists($inputKey, $item) && $item[$inputKey] != $model->{$dbField}) {
                            $changes[$dbField] = $item[$inputKey];
                        }
                    }
                } else {
                    // 简单数组模式 ['field1', 'field2']
                    foreach ($fields as $field) {
                        if (array_key_exists($field, $item)) {
                            $changes[$field] = $item[$field];
                        }
                    }
                    // 只更新有变化的字段
                    $changes = array_diff_assoc($changes, $model->only($fields));
                }

                if (! empty($changes)) {
                    $model->update($changes);
                    $updated->push($model);
                }

                $remainingIds = array_diff($remainingIds, [$item['id']]);
            } else { // 创建新记录
                $newData = [$foreignKey => $parentId];

                if ($isMapping) {
                    // 映射关系模式
                    foreach ($fields as $inputKey => $dbField) {
                        if (array_key_exists($inputKey, $item)) {
                            $newData[$dbField] = $item[$inputKey];
                        }
                    }
                } else {
                    // 简单数组模式
                    foreach ($fields as $field) {
                        if (array_key_exists($field, $item)) {
                            $newData[$field] = $item[$field];
                        }
                    }
                }

                $created->push($childModel->create($newData));
            }
        }

        // 处理需要删除的记录
        $deleted = $existing->whereIn('id', $remainingIds);
        if ($remainingIds) {
            $softDeleteField
                ? $childModel->whereIn('id', $remainingIds)->update([$softDeleteField => $softDeleteValue])
                : $childModel->whereIn('id', $remainingIds)->delete();
        }

        return compact('created', 'deleted', 'updated');
    }
}
