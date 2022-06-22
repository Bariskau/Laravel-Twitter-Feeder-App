<?php

namespace App\Traits;

use App\Exceptions\InvalidUuidException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

/**
 * Trait HasUuid
 * @package App\Traits
 */
trait HasUuid
{
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (is_null($model->uuid))
                $model->uuid = Str::uuid();
        });
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param string|null $field
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws \Throwable
     */
    public function resolveRouteBinding($value, $field = null)
    {
        throw_if(!Str::isUuid($value), new InvalidUuidException('Uuid is not valid', 422));

        return $this->findByUuid($value);
    }

    /**
     * @param Builder $query
     * @param $uuid
     * @return Builder|Model
     *
     * @throws ModelNotFoundException
     */
    public static function scopeFindByUuid(Builder $query, $uuid)
    {
        return $query->where('uuid', $uuid)
            ->firstOrFail();
    }

    /**
     * @param Builder $query
     * @param $uuid
     * @param $data
     * @return int
     */
    public static function scopeUpdateByUuid(Builder $query, $uuid, $data)
    {
        return $query->where('uuid', $uuid)
            ->update($data);
    }

    /**
     * @param Builder $query
     * @param $uuids
     * @return Collection
     */
    public static function scopeFindByUuidArray(Builder $query, $uuids)
    {
        return $query->whereIn('uuid', $uuids)
            ->get();
    }

    /**
     * @param Builder $query
     * @param $uuids
     * @return mixed
     */
    public static function scopeDeleteByUuid(Builder $query, $uuids)
    {
        return $query->whereIn('uuid', $uuids)->delete();
    }
}
