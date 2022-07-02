<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_docenteExperiencia extends Model
{
    use HasFactory;
    protected $table = 'docente_experiencia';
    protected $primaryKey = 'idDocente';
}
