<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documento extends Model
{
    use SoftDeletes;
    protected $table = 'documento';
    protected $dates = ['deleted_at'];

    public function proceso()
    {
        return $this->belongsTo('App\Procesodocumento', 'proceso_id');
    }
    
    public function tipodocumento()
    {
        return $this->belongsTo('App\Tipodocumento', 'tipodocumento_id');
    }


}
