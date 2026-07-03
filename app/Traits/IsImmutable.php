<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

trait IsImmutable
{
    /**
     * Boot the trait to listen for model events.
     */
    public static function bootIsImmutable()
    {
        static::updating(function (Model $model) {
            if ($model->isImmutable()) {
                throw ValidationException::withMessages([
                    'id' => __('This record is a system record and cannot be modified.'),
                ]);
            }
        });

        static::deleting(function (Model $model) {
            if ($model->isImmutable()) {
                throw ValidationException::withMessages([
                    'id' => __('This record is a system record and cannot be deleted.'),
                ]);
            }
        });
    }

    /**
     * Determine if the model is immutable.
     * By default, checks if tenant_id is null (Global/System record).
     */
    public function isImmutable(): bool
    {
        // If tenant_id is null, it's a System Record (Global).
        // Only Super Admins might bypass this via specific flags if needed,
        // but for now, we lock it down strictly.
        return is_null($this->tenant_id);
    }
}
