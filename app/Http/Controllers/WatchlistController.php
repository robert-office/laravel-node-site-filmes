<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $idUser = Auth::id();

        $watchlists = Watchlist::where('id_person', $idUser)->get();

        $movies = [];

        foreach ($watchlists as $watchlist) {
            $movie = Content::where('id', $watchlist['id_content'])->first();

            array_push($movies, [
                'id'            => $movie['id'],
                'id_movie'      => $movie['id_movie'],
                'name'          => $movie['name'],
                'title'         => $movie['title'],
                'poster_path'   => $movie['poster_path']
            ]);
        }

        $response = [
            'lenght'    => sizeof($movies),
            'contents' => $movies
        ];

        return response($response, 200);
    }

    public function check(Request $request)
    {
        $idUser = Auth::id();
        $content = Content::where('id_movie', $request['id_movie'])->first();

        if ($content) {
            $id_content = $content['id'];

            if (Watchlist::where([['id_person', $idUser], ['id_content', $id_content]])->exists()) {
                return response(['response' => true], 200);
            }
        }
        return response(['response' => false], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Response $response)
    {
        // para criar o novo registro de watchlist, primeiro deve ser criado CASO ainda não exista, um registro na tabela
        // CONTENTS, que é responsavel por armazenar os dados dos filmes e das séries
        $haveContentOrNull = Content::where('id_movie', $request['id_movie'])->first();

        /// já tem, basta apenas pegar o seu id e criar um registro nos watchlists com o seu id no content
        if ($haveContentOrNull) {
            $this->createWatchlist($haveContentOrNull);
        } else {
            $Content = Content::create([
                'id_movie' => $request['id_movie'],
                'name' => $request['name'],
                'title' => $request['title'],
                'poster_path' => $request['poster_path'],
            ]);

            $this->createWatchlist($Content);
        }

        return response(['message' => 'criado com sucesso'], 201);
    }


    public function createWatchlist($content)
    {
        $idUser = Auth::id();

        $watchlist = Watchlist::create([
            'id_person' => $idUser,
            'id_content' => $content['id']
        ]);

        return $watchlist;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {

        $content = Content::where('id_movie', $id)->first();

        if ($content) {
            // deleta a entidade do registro
            $deleted = Watchlist::where('id_content', $content['id'])->delete();
            return response(['message' => 'deletado com sucesso!'], 200);
        }

        return response(['message' => 'não foi deletado!!!!'], 200);
    }
}
