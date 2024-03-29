<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'class';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'id_program_study'
    ];
}
