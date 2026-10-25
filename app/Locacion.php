<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locacion extends Model
{
    use SoftDeletes;
    protected $table = 'locacion';
    protected $dates = ['deleted_at'];


}
