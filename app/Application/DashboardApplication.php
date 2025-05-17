<?php

namespace App\Application;

use App\UseCases\Dashboard\RenderizarViews;
use App\UseCases\Dashboard\Requests\RenderizarViewsRequest;

class DashboardApplication
{
    public static function renderizarViews(RenderizarViewsRequest $request){
        $interact = new RenderizarViews($request);
        return $interact->handle();
    }
}
