<?php
namespace App\UseCases\Usuario\Interfaces;

use Illuminate\Support\Collection;

interface IObterUsuarios{
    public function obterUsuarios(): Collection;
}