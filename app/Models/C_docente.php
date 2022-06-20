<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class C_docente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'c_docente';
    protected $primaryKey = 'idDocente';
}
