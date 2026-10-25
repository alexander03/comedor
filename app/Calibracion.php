<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calibracion extends Model
{
    use SoftDeletes;
    protected $table = 'calibracion';
    protected $dates = ['deleted_at'];

    public function subcategoria()
    {
        return $this->belongsTo('App\Subcategoria', 'subcategoria_id');
    }
    
    public function equipo()
    {
        return $this->belongsTo('App\Equipo', 'equipo_id');
    }

}
