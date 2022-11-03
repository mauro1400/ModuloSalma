<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudArticulo extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subarticle_requests';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'subarticle_id', 'request_id', 'amount', 'amount_delivered', 'total_delivered', 'invalidate', 'observacion'];

    
}
