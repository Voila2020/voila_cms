<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MenuTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
