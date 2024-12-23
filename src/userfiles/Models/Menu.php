<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Menu extends Model implements TranslatableContract
{
    protected $appends = ['rout'];
    use Translatable;

    public $translatedAttributes = ['name'];
    protected $fillable = ['parent_id', 'link', 'page_id', 'type'];

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
