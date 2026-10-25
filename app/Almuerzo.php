<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Almuerzo extends Model
{
    use SoftDeletes;
    protected $table = 'almuerzo';
    protected $dates = ['deleted_at'];


}
