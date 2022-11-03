<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reportea extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reportes';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'fecha_entrega';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['descripcion'];

    
}
