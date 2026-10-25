<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarifa extends Model
{
    use SoftDeletes;
    protected $table = 'tarifa';
    protected $dates = ['deleted_at'];

    public function origen()
    {
        return $this->belongsTo('App\Lugar', 'origen_id');
    }

    public function destino()
    {
        return $this->belongsTo('App\Lugar', 'destino_id');
    }
}
