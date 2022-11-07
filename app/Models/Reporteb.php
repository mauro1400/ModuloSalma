<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporteb extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'report';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'codigo';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['codigo,
    cod_ant,
    description,
    uni_med,
    partida,
    num_fac,
    detalle,
    precio_u,
    fecha_e,
    fecha_e2,
    fecha_s,
    ingreso,
    egreso,
    saldo,
    ingreso_e,
    egreso_e,
    saldo_e']; 

    
}
