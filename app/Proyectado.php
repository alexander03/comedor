<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyectado extends Model
{
    use SoftDeletes;
    protected $table = 'proyectado';
    protected $dates = ['deleted_at'];

    public function comedor()
    {
        return $this->belongsTo('App\Comedor', 'comedor_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
