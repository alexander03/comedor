<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicadorbase extends Model
{
    use SoftDeletes;
    protected $table = 'indicadorbase';
    protected $dates = ['deleted_at'];

    public function proceso()
    {
        return $this->belongsTo('App\Proceso', 'proceso_id');
    }
}
