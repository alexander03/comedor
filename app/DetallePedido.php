<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallePedido extends Model
{
    use SoftDeletes;
    protected $table = 'detallepedido';
    protected $dates = ['deleted_at'];

    public function pedido()
    {
        return $this->belongsTo('App\Pedido', 'pedido_id');
    }

    public function servicio()
    {
        return $this->belongsTo('App\Servicio', 'servicio_id');
    }

    public function carnico()
    {
        return $this->belongsTo('App\Carnico', 'carnico_id');
    }
}
