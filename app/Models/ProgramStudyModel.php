<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudyModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'program_study';

    /**
     * @var array
     */
    protected $fillable = [
        'program_study_name',
        'program_study-code',
    ];
}
