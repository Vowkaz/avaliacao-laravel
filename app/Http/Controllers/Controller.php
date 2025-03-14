<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;

    protected function unauthorizedResponse(string $route)
    {
        return redirect()->route($route)->with('error', 'Você não tem permissão para executar esta ação.');
    }
}
