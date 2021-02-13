<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTypeModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_type';

    /**
     * @var array
     */
    protected $fillable = [
        'user_type',
    ];
}
