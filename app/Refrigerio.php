<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refrigerio extends Model
{
    use SoftDeletes;
    protected $table = 'refrigerio';
    protected $dates = ['deleted_at'];


}
