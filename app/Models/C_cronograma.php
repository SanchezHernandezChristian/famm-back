<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_cronograma extends Model
{
    use HasFactory;
    protected $table = 'cronograma';
    protected $primaryKey = 'idCronograma';
}
