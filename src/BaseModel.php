<?php

namespace Si6\Base;

use Illuminate\Database\Eloquent\Model;
use Si6\Base\Utils\UniqueIdentity;
use Illuminate\Support\Facades\DB;

abstract class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = self::generateId(static::class);
        });
    }

    public static function generateId($entity)
    {
        return DB::transaction(function () use ($entity) {
            $nextValue = BaseModel::getNextSequence($entity);
            $id = UniqueIdentity::id($nextValue);
            BaseModel::updateSequence($entity);

            return $id;
        });
    }

    private static function getNextSequence($entity)
    {
        $sequent = DB::table('entity_sequences')
            ->select('next_value')
            ->where('entity', $entity)
            ->lockForUpdate()
            ->first();

        return $sequent->next_value ?? 1;
    }

    private static function updateSequence($entity)
    {
        DB::table('entity_sequences')
            ->where('entity', $entity)
            ->increment('next_value');
    }
}
