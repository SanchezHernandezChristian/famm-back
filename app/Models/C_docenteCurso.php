<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_docenteCurso extends Model
{
    use HasFactory;
    protected $table = 'docente_curso';
    protected $primaryKey = 'idDocente';
}
