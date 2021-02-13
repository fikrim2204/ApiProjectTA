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
        'complaint',
        'date_reported',
        'date_required',
        'date_repaired',
        'repair_result',
        'status',
        'id_lecturer',
        'id_technician',
        'id_room',
    ];
}
