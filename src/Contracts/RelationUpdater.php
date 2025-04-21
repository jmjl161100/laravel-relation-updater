<?php

namespace Jmjl161100\RelationUpdater\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RelationUpdater
{
    /**
     * 更新关联子模型数据（支持字段映射）
     *
     * @param  array|null  $input  请求数据数组
     * @param  array  $fields  需要更新的字段列表或映射关系
     * @param  string  $foreignKey  外键字段名
     * @param  int  $parentId  父模型 ID
     * @param  Model  $childModel  子模型实例
     * @param  string|null  $softDeleteField  软删除字段名
     * @param  mixed  $softDeleteValue  软删除值
     * @return array 返回操作结果
     */
    public function update(
        ?array $input,
        array $fields,
        string $foreignKey,
        int $parentId,
        Model $childModel,
        ?string $softDeleteField,
        mixed $softDeleteValue
    ): array;
}
