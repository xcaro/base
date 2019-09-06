<?php

namespace Si6\Base;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Si6\Base\Utils\HasCriteria;
use Si6\Base\Utils\UniqueIdentity;
use Illuminate\Support\Facades\DB;

abstract class Model extends EloquentModel
{
    use HasCriteria;

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            /** @var Model $model */
            if (!$model->getIncrementing()) {
                $model->{$model->getKeyName()} = self::generateId($model->getTable());
            }
        });
    }

    public static function generateId($entity)
    {
        return DB::transaction(function () use ($entity) {
            $nextValue = Model::getNextSequence($entity);
            $id        = UniqueIdentity::id($nextValue);
            Model::updateSequence($entity);

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

        if (isset($sequent->next_value)) {
            return $sequent->next_value;
        }

        DB::table('entity_sequences')
            ->insert([
                'entity'     => $entity,
                'next_value' => 1,
            ]);

        return 1;
    }

    private static function updateSequence($entity)
    {
        DB::table('entity_sequences')
            ->where('entity', $entity)
            ->increment('next_value');
    }
}
