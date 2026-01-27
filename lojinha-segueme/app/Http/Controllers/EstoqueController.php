<?php

namespace App\Http\Controllers;

use App\Models\EntradaEstoque;
use App\Services\EstoqueService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

// -------------------------------
// ENCONTRO CONTROLLER
// -------------------------------
class EstoqueController extends BaseController
{
    protected EstoqueService $estoqueService;

    public function __construct(EstoqueService $estoqueService)
    {
        $this->estoqueService = $estoqueService;
    }

    public function entrada(Request $request)
    {
        return $this->estoqueService->entrada($request->all());
    }

    public function saidaProvisoria(Request $request)
    {
        return $this->estoqueService->saidaProvisoria($request->all());
    }

    public function baixa(Request $request)
    {
        return $this->estoqueService->baixaDefinitiva($request->all());
    }
}
