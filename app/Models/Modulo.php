<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFctory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulos';
    protected $connection = 'gecon';

    protected $fillable = ['id', 'nome'];
}
