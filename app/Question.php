<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'companies';
    protected $guarded = ['id'];
}
