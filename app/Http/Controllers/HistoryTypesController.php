<?php

namespace App\Http\Controllers;

use App\Models\HistoryTypes;
use Illuminate\Http\Request;

class HistoryTypesController extends Controller
{
    // retorna todos os types de hystori types
    public function show(){
        return HistoryTypes::all(['id', 'type']);
    }
}
