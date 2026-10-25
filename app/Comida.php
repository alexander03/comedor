<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comida extends Model
{
    use SoftDeletes;
    protected $table = 'comida';
    protected $dates = ['deleted_at'];

    public function persona()
    {
        return $this->belongsTo('App\Person', 'persona_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function servicio()
    {
        return $this->belongsTo('App\Servicio', 'servicio_id');
    }
}
