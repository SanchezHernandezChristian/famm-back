<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_docenteDocumento extends Model
{
    use HasFactory;
    protected $table = 'docente_documento';
    protected $primaryKey = 'idDocente';
}
