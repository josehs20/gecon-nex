<?php

namespace App\UseCases\Dashboard;

use App\UseCases\Dashboard\Interfaces\IRenderizarView;
use App\UseCases\Dashboard\Requests\RenderizarViewsRequest;
use App\UseCases\Dashboard\Views\RenderizarViewCompra;
use App\UseCases\Dashboard\Views\RenderizarViewCotacao;
use App\UseCases\Dashboard\Views\RenderizarViewPedido;

class RenderizarViews implements IRenderizarView
{
    private RenderizarViewsRequest $request;

    public function __construct(RenderizarViewsRequest $request)
    {
       $this->request = $request;
    }

    public function handle(): array
    {
        return $this->tratar();
    }
    
    private function tratar(){
        switch ($this->request->getView()) {
            case 'pedido':
                $renderizar = new RenderizarViewPedido($this->request->getLojaId());
                return $renderizar->handle();
                break;
        
            case 'cotacao':
                $renderizar = new RenderizarViewCotacao($this->request->getLojaId());
                return $renderizar->handle();
                break;

            case 'compra':
                $renderizar = new RenderizarViewCompra($this->request->getLojaId());
                return $renderizar->handle();
                break;
    
            default:
                throw new \Exception("A view " . $this->request->getView() . " não existe!", 400);
                break;
        }
    }

}
