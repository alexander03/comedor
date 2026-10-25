<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use SoftDeletes;
    protected $table = 'equipo';
    protected $dates = ['deleted_at'];

    public function subcategoria()
    {
        return $this->belongsTo('App\Subcategoria', 'subcategoria_id');
    }

}
