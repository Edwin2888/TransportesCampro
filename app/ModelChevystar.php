<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelChevystar extends Model
{
    protected $connection = 'sqlsrvCxParque';
    protected $table = "km_chevystar";
    protected $primaryKey = 'id';    
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'id','kilometraje','placa','fecha','kmTotal'
    ];
}
