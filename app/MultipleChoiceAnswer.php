<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MultipleChoiceAnswer extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'companies'; 
}