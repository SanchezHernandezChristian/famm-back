<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class c_FactibilidadYJustificacionCursos extends Model
{
    use HasFactory;
    protected $table = 'factibilidad_justificacion_cursos';
    protected $primaryKey = 'idFactibilidad';
}
