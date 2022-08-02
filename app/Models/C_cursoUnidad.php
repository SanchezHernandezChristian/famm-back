<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_cursoUnidad extends Model
{
    use HasFactory;
    protected $table = 'curso_unidad';
    protected $primaryKey = 'idCurso';
}
