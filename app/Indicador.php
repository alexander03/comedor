<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicador extends Model
{
    use SoftDeletes;
    protected $table = 'indicador';
    protected $dates = ['deleted_at'];

    public function proceso()
    {
        return $this->belongsTo('App\Proceso', 'proceso_id');
    }
    
    public function indicadorbase()
    {
        return $this->belongsTo('App\Indicadorbase', 'indicadorbase_id');
    }


}
