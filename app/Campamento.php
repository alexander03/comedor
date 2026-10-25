<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campamento extends Model
{
    use SoftDeletes;
    protected $table = 'campamento';
    protected $dates = ['deleted_at'];


}
