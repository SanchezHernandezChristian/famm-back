<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class C_cursos extends Model
{
    use HasFactory;
    protected $table = 'c_cursos';
    protected $primaryKey = 'idCurso';

    protected $fillable = [
        'nombre_curso',
        'duracion_horas',
        'clave_curso',
        'idEspecialidad'
    ];
}
