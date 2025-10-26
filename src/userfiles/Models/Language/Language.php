<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', 1);
            //$builder->orderby('sorting');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1)->orderby('sorting');
    }
}
