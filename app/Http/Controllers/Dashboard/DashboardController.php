<?php

namespace App\Http\Controllers\Dashboard;

use App\Application\DashboardApplication;
use App\Http\Controllers\ControllerBase;
use App\UseCases\Dashboard\Requests\RenderizarViewsRequest;
use Illuminate\Http\Request;

class DashboardController extends ControllerBase
{
    private function isAdmin(): bool{
        return auth()->user()->isAdmin();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        // if($this->isAdmin()){
        //     return view('admin.dashboard.index');
        // }
        return view('mercado::dashboard.index');
    }

    public function renderizar(string $view){
        try {
            $loja_id = auth()->user()->usuarioMercado->loja_id;
            $view_renderizada = DashboardApplication::renderizarViews(
                new RenderizarViewsRequest(
                    $view,
                    $loja_id
                )
            );
            return view('mercado::dashboard.index', ['view_renderizada' => $view_renderizada]);
        } catch (\Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return redirect()->back();
        }
    }
}
