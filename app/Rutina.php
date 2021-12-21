<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Rutina extends Model
{
    protected $table = "tra_rutinas";
    protected $primaryKey = 'id_rutina';
    protected $keyType  = "int";
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'nombre','ciclo'
    ];
}
