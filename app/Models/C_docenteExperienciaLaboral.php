<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_docenteExperienciaLaboral extends Model
{
    use HasFactory;
    protected $table = 'docente_experiencia_laboral';
    protected $primaryKey = 'idDocente';
}
