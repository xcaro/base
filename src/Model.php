<?php

namespace Si6\Base;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Auth;
use Si6\Base\Utils\UniqueIdentity;
use Illuminate\Support\Facades\DB;

abstract class Model extends EloquentModel
{
    public $incrementing = false;

    public $createdBy = false;

    public $updatedBy = false;

    const CREATED_BY = 'created_by';

    const UPDATED_BY = 'updated_by';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            /** @var Model $model */
            if (!$model->getIncrementing() && $model->getKeyName()) {
                $model->{$model->getKeyName()} = self::generateId($model->getTable());
            }
            if ($model->createdBy) {
                $model->{$model->getCreatedByColumn()} = Auth::id();
            }
            if ($model->updatedBy) {
                $model->{$model->getUpdatedByColumn()} = Auth::id();
            }
        });
    }

    protected function getCreatedByColumn()
    {
        return self::CREATED_BY;
    }

    protected function getUpdatedByColumn()
    {
        return self::UPDATED_BY;
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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format(DATE_ISO8601);
    }
}
