<?php

namespace Modules\Mercado\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index($msg = null)
    {
        if ($msg != null) {
            session()->flash('error', $msg);
        }
       if (auth()->user()) {
        return view('mercado::master.index');

       }else {
        auth()->logout();
        return redirect('/login'); // Redireciona com mensagem

       }
    }
}
