<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_roles extends Model
{
    use HasFactory;
    protected $table = 'c_roles';
    protected $primaryKey = 'idRol';
}
