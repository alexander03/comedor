<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cena extends Model
{
    use SoftDeletes;
    protected $table = 'cena';
    protected $dates = ['deleted_at'];


}
