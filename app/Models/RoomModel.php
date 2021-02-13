<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'room';

    /**
     * @var array
     */
    protected $fillable = [
        'lab_name',
    ];
}
