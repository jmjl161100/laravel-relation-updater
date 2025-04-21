<?php

namespace Jmjl161100\RelationUpdater\Traits;

use Jmjl161100\RelationUpdater\Contracts\RelationUpdater;

trait HasRelationUpdater
{
    public function __call($method, $arguments)
    {
        if (str_ends_with($method, 'Update')) {
            $relationName = lcfirst(substr($method, 0, -6)); // 去掉 Update 后缀

            if (! method_exists($this, $relationName)) {
                throw new \BadMethodCallException("Relation method [$relationName] not found.");
            }

            $input = $arguments[0] ?? null;
            $fields = $arguments[1] ?? []; // 支持传字段配置
            $softDeleteField = $arguments[2] ?? null;
            $softDeleteValue = $arguments[3] ?? 1;

            $relation = $this->$relationName();
            $childModel = $relation->getRelated();
            $foreignKey = $relation->getForeignKeyName();

            return app(RelationUpdater::class)->update(
                $input,
                $fields,
                $foreignKey,
                $this->getKey(),
                $childModel,
                $softDeleteField,
                $softDeleteValue
            );
        }

        return parent::__call($method, $arguments);
    }
}
