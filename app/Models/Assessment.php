<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'assessments';
    public $timestamps = false;
    use HasFactory;
}
