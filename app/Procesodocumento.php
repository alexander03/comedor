<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procesodocumento extends Model
{
    use SoftDeletes;
    protected $table = 'procesodocumento';
    protected $dates = ['deleted_at'];


}
