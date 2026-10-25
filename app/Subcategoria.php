<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategoria extends Model
{
    use SoftDeletes;
    protected $table = 'subcategoria';
    protected $dates = ['deleted_at'];

    public function categoria()
    {
        return $this->belongsTo('App\Categoria', 'categoria_id');
    }

}
