<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lugar extends Model
{
    use SoftDeletes;
    protected $table = 'lugar';
    protected $dates = ['deleted_at'];

    public function campamento()
    {
        return $this->belongsTo('App\Campamento', 'campamento_id');
    }
}
