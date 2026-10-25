<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comedor extends Model
{
    use SoftDeletes;
    protected $table = 'comedor';
    protected $dates = ['deleted_at'];


}
