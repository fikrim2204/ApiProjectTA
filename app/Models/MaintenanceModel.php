<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'maintenance';

    /**
     * @var array
     */
    protected $fillable = [
        'no_computer',
        'title',
        'date_reported',
        'date_required',
        'date_repaired',
        'repair_result',
        'status',
        'img_1',
        'img_2',
        'img_3',
        'id_lecturer',
        'id_technician',
        'id_room',
    ];
}
