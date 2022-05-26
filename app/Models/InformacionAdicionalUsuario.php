<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacionAdicionalUsuario extends Model
{
    use HasFactory;
    protected $table = 'informacion_extra_usuario';
    protected $primaryKey = 'IdInformacionExtra';

}
