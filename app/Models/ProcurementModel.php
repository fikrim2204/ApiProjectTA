<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'procurement';

    /**
     * @var array
     */
    protected $fillable = [
        'procurement',
        'total',
        'date_requested',
        'description',
        'id_user',
        'status',
        'note',
    ];
}
