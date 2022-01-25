<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function add(Request $request)
    {
        $idUser = Auth::id();

        History::create([
            'id_person' => $idUser,
            'type_id' => $request['type'],
            'aditional_content' => $request['ad_content']
        ]);

        return response(['Criado com sucesso'], 201);
    }

    public function show()
    {
        $idUser     = Auth::id();
        $historys   = History::where('id_person', $idUser)
            ->latest()
            ->take(10)
            ->get();

        if ($historys) {
            $typesController = new HistoryTypesController();
            $AllTypes = $typesController->show();

            $formatedHistory = [];

            foreach ($historys as $history) {
                $type = '';

                foreach ($AllTypes as $actualType) {
                    if ($actualType['id'] == $history['type_id']) {
                        $type = $actualType['type'];
                    }
                }

                array_push($formatedHistory, [
                    'id'            => $history['id'],
                    'type'          => $type,
                    'type_id'       => $history['type_id'],
                    'ad_content'    => $history['aditional_content'],
                    'created_at'    => $history['created_at']
                ]);
            }

            return response([
                'lenght' => count( $formatedHistory ),
                'history' => $formatedHistory
            ], 200);
        } else {
            return response([
                'lenght' => 0,
                'history' => []
            ], 200);
        }
    }
}
