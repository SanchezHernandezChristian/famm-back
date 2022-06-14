<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InformacionAdicionalUsuario extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'informacion_extra_usuario';
    protected $primaryKey = 'IdInformacionExtra';

}
