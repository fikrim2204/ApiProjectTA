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
        'title',
        'total',
        'unit',
        'date_requested',
        'description',
        'id_user',
        'status',
        'note',
        'img_1',
        'img_2',
        'img_3',
    ];
}
