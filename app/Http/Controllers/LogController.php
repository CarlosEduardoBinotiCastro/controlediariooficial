<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LogController extends Controller
{
    //
    private $paginacao = 20;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listar(){

        if(Gate::allows('administrador', Auth::user()) ){
            $logs = Log::orderBy('logData', 'desc');
            $logs = $logs->paginate($this->paginacao);
            return view('log.listar', ['logs' => $logs]);

        }else{
            return redirect('/home');
        }
    }



}
