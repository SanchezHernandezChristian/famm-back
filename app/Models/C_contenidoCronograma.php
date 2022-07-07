<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_contenidoCronograma extends Model
{
    use HasFactory;
    protected $table = 'contenido_cronograma';
    protected $primaryKey = 'idTemario';
}
