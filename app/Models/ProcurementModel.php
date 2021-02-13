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
        'date_requested',
        'reason',
        'id_user',
        'status',
        'note',
    ];
}
