<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_cedulaPreautorizacion extends Model
{
    use HasFactory;
    protected $table = 'preautorizacion_curso';
    protected $primaryKey = 'id';
}
