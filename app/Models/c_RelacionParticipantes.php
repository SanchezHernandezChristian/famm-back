<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class c_RelacionParticipantes extends Model
{
    use HasFactory;
    protected $table = 'relacion_participantes';
    protected $primaryKey = 'idParticipante';
}
