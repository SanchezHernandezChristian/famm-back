<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class C_cursos extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'c_cursos';
    protected $primaryKey = 'idCurso';

    protected $fillable = [
        'nombre',
        'descripcion',
        'horas_teoricas',
        'horas_practicas',
        'fecha_inicio',
        'fecha_fin',
    ];

}
