<?php
namespace App\Traits;

use Webpatser\Uuid\Uuid;

/**
 * Trait Uuids
 * @package App
 */
trait Uuids
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function bootUuids()
    {
        /**
         * Attach to the 'creating' Model Event to provide a UUID
         * for the `id` field (provided by $model->getKeyName())
         */
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string)Uuid::generate();
        });
    }
}
