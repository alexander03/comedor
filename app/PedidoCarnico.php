<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoCarnico extends Model
{
    use SoftDeletes;
    protected $table = 'pedidocarnico';
    protected $dates = ['deleted_at'];
}
