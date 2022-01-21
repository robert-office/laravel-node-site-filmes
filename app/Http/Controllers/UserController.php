<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // valida os campos
        $filds = $request->validate([
            'name' => 'string|nullable',
            'descricao' => 'string|nullable'
        ]);

        $idUser = Auth::id();

        $user = User::where('id', $idUser)->update([
            'name' => $filds['name'],
            'descricao' => $filds['descricao']
        ]);

        $user = User::where('id', $idUser)->first();

        if( $user ) {
            return response($user);
        }
    }



    public function updateImgUser(Request $request)
    {
        $idUser = Auth::id();
        $imagem = $request->file('imagem');
        
        if ( $imagem ) {
            $path = $imagem[0]->store('perfil', 'public');
            
            $user = User::where('id', $idUser)->update([
                'path_img' =>  asset( Storage::url( $path ) )
            ]);
            
            $user = User::where('id', $idUser)->first();

            if( $user ) {
                return response([
                    'user' => $user
                ]);
            }
        }
    }

    public function saveImage()
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
