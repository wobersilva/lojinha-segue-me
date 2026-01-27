<?php

namespace App\Http\Controllers;

use App\Models\Paroquia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

// -------------------------------
// CONTROLLER
// -------------------------------
class Controller extends BaseController
{
    public function index()
    {
        return response()->noContent();
    }
}
