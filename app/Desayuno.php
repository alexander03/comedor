<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Desayuno extends Model
{
    use SoftDeletes;
    protected $table = 'desayuno';
    protected $dates = ['deleted_at'];


}
