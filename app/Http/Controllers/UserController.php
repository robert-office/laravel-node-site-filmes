<?php

namespace App\Http\Controllers;

use App\Models\User;
use Error;
use Facade\FlareClient\Stacktrace\File;
use Hamcrest\Arrays\IsArray;
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
            $user = User::where('id', $idUser)->first();
            $fullImg_path = $user['path_img'];
            $pathExploded = explode('https://laravel-node-filme.herokuapp.com', $fullImg_path);

            return response(['path' => $pathExploded]);

            if( $pathExploded && is_array($pathExploded) ) {
                $oldPath = public_path($pathExploded[1]);
            }

            /// apaga  a img antiga
            if( $oldPath && $fullImg_path !== "" ) {
                try { 
                    $img_deleted = unlink($oldPath);
                } catch( \Exception $e ) {
                    return response(['erro' => $e->getMessage()], 401);
                }
            }

            $path = $imagem[0]->store('perfil', 'public');
            
            $user = User::where('id', $idUser)->update([
                'path_img' =>  asset( Storage::url( $path ) )
            ]);
            
            /// faz o selcet novamente para mandar as infos att
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
