<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_especialidades extends Model
{
    use HasFactory;
    protected $table = 'c_especialidad';
    protected $primaryKey = 'idEspecialidad';
}
