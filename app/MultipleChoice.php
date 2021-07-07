<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MultipleChoice extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'companies';
}