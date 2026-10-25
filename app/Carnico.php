<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carnico extends Model
{
    use SoftDeletes;
    protected $table = 'carnico';
    protected $dates = ['deleted_at'];


}
