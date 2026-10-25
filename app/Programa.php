<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Programa extends Model
{
    use SoftDeletes;
    protected $table = 'programa';
    protected $dates = ['deleted_at'];

    public function area()
    {
        return $this->belongsTo('App\Area', 'area_id');
    }
    
}
