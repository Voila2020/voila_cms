<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $appends = ['rout'];

    protected $fillable = ['parent_id', 'link', 'page_id', 'type', 'name_ar','name_en'];
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', 1);
            $builder->orderby('sorting');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1)->orderby('sorting');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }
}
