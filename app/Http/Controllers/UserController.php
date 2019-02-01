<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Caderno;
use App\OrgaoRequisitante;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;



class UserController extends Controller
{
    //
    private $paginacao = 10;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listarFiltro(Request $request){
        return redirect()->route('listarUsuarios', ['filtro' => $request->nomeDoc]);
    }

    public function listar($filtro = null){
        if(Gate::allows('administrador', Auth::user())){

            $usuarios = User::orderBy('name');
            $usuarios->join('status', 'status.statusID', 'users.statusID');
            $usuarios->join('orgaorequisitante', 'orgaorequisitante.orgaoID', 'users.orgaoID');
            $usuarios->select('users.*', 'status.descricao', 'orgaorequisitante.orgaoNome as orgao');

            if(!is_numeric($filtro)){
                $arrayPalavras = explode(' ', $filtro);
                foreach ($arrayPalavras as $palavra) {
                    $usuarios->where('name', 'like', '%' . $palavra . '%');
                }
            }else{
                $usuarios->where('cpf', 'like', "%".$filtro."%");
            }


            $usuarios = $usuarios->paginate($this->paginacao);

            return view('usuario.listar', ['usuarios' => $usuarios]);

        }else{
            return redirect()->route('home');
        }

    }

    public function cadastrar(){
        if(Gate::allows('administrador', Auth::user())){

            $cadernos = Caderno::orderBy('cadernoNome')->get();
            $orgaosRequisitantes = OrgaoRequisitante::orderBy('orgaoNome')->get();

            return view('usuario.cadastrar', ['cadernos' => $cadernos, 'orgaosRequisitantes' => $orgaosRequisitantes]);
        }else{
            return redirect()->route('home');
        }
    }


    public function editar($id){

        // Verificar se o usuario comum esta tentando acessar o editar de outro usuario

        $userAcess = false;
        if(!Gate::allows('administrador', Auth::user())){
            if($id == Auth::user()->id){
                $userAcess = true;
            }
        }else{
            $userAcess = true;
        }

        // url Voltar, para poder voltar para origem
        if((url()->previous() != url()->current())){
            Session::put('urlVoltar', url()->previous());
        }

        if($userAcess){
            $usuario = User::orderBy('name')->where('id', '=', $id)->first();
            $orgaosRequisitantes = OrgaoRequisitante::orderBy('orgaoNome')->get();
            $usuarioCaderno = Caderno::orderBy('cadernoNome');
            $usuarioCaderno->join('usuariocaderno', 'usuariocaderno.cadernoID', 'caderno.cadernoID');
            $usuarioCaderno->where('usuariocaderno.usuarioID', '=', $id);
            $usuarioCaderno->select('caderno.cadernoID', 'caderno.cadernoNome');
            $usuarioCaderno = $usuarioCaderno->get();

            $cadernos = Caderno::orderBy('cadernoNome');
            $cadernos->select('*');

            foreach($usuarioCaderno as $caderno){
                $cadernos->where('cadernoID', '!=', $caderno->cadernoID);
            }
            $cadernos = $cadernos->get();
            return view('usuario.editar', ['cadernos' => $cadernos, 'usuario' => $usuario, 'usuarioCaderno' => $usuarioCaderno, 'orgaosRequisitantes' => $orgaosRequisitantes]);
        }else{
            return redirect()->route('home');
        }

    }


    public function salvar(Request $request){

        switch ($this->validar($request)){

            case 1:
                return redirect()->back()->with('erro', "Email já Cadastrado!")->withInput();
            break;

            case 2:
                return redirect()->back()->with('erro', "Login Ja Cadastrado!")->withInput();
            break;

            case 3:
                return redirect()->back()->with('erro', "CPF Ja Cadastrado!")->withInput();
            break;

            case 4:
                return redirect()->back()->with('erro', "Usuário deve possuir permissão em ao menos 1 caderno!")->withInput();
            break;

            case 5:
                return redirect()->back()->with('erro', "Senhas não são iguais!")->withInput();
            break;

            case 6:
                return redirect()->back()->with('erro', "Impossível desativar o próprio usuário!")->withInput();
            break;

            case 7:
                return redirect()->back()->with('erro', "Login ou Senha não podem conter espaços em branco!")->withInput();
            break;

            case 8:
                return redirect()->back()->with('erro', "CPF invalido!")->withInput();
            break;

            default:

                $cadernos = json_decode($request->cadernos);

                if(isset($request->usuarioID)){
                    DB::beginTransaction();
                    try {

                        if(Gate::allows('administrador', Auth::user())){
                            if(isset($request->alterarSenha)){
                                DB::table('users')->where('id', '=', $request->usuarioID)->update(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->senha), 'login' => $request->login, 'cpf' => $request->cpf, 'telefoneSetor' => $request->telefoneSetor, 'telefoneCelular' => $request->telefoneCelular, 'orgaoID' => $request->orgaoID, 'statusID' => $request->statusID, 'grupoID' => $request->grupoID, 'horaEnvio' => $request->horaEnvio]);
                            }else{
                                DB::table('users')->where('id', '=', $request->usuarioID)->update(['name' => $request->name, 'email' => $request->email, 'login' => $request->login, 'cpf' => $request->cpf, 'telefoneSetor' => $request->telefoneSetor, 'telefoneCelular' => $request->telefoneCelular, 'orgaoID' => $request->orgaoID, 'statusID' => $request->statusID, 'grupoID' => $request->grupoID, 'horaEnvio' => $request->horaEnvio]);
                            }

                            DB::table('usuariocaderno')->where('usuarioID', '=', $request->usuarioID)->delete();

                            foreach($cadernos as $caderno){
                                DB::table('usuariocaderno')->insert(['cadernoID' => $caderno->cadernoID, 'usuarioID' => $request->usuarioID]);
                            }
                            DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Editou o usuário '.$request->name]);
                            DB::commit();
                            return redirect('/usuario/listar')->with("sucesso", "Usuario Editado");
                        }else{

                            if(isset($request->alterarSenha)){
                                DB::table('users')->where('id', '=', Auth::user()->id)->update(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->senha), 'login' => $request->login, 'cpf' => $request->cpf, 'telefoneSetor' => $request->telefoneSetor, 'telefoneCelular' => $request->telefoneCelular]);
                            }else{
                                DB::table('users')->where('id', '=', Auth::user()->id)->update(['name' => $request->name, 'email' => $request->email, 'login' => $request->login, 'cpf' => $request->cpf, 'telefoneSetor' => $request->telefoneSetor, 'telefoneCelular' => $request->telefoneCelular]);
                            }
                            DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Editou o usuário '.$request->name]);

                            DB::commit();
                            return redirect('/home')->with("sucesso", "Usuario Editado");
                        }


                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                    }

                }else{
                    DB::beginTransaction();
                    try {

                        DB::table('users')->insert(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->senha), 'login' => $request->login, 'cpf' => $request->cpf, 'telefoneSetor' => $request->telefoneSetor, 'telefoneCelular' => $request->telefoneCelular, 'orgaoID' => $request->orgaoID, 'statusID' => $request->statusID, 'grupoID' => $request->grupoID, 'horaEnvio' => $request->horaEnvio, 'primeiroLogin' => 1]);

                        $usuarioID = DB::table('users')->max('id');

                        foreach($cadernos as $caderno){
                            DB::table('usuariocaderno')->insert(['cadernoID' => $caderno->cadernoID, 'usuarioID' => $usuarioID]);
                        }
                        DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Cadastrou o usuário '.$request->name]);

                        DB::commit();
                        return redirect('/usuario/listar')->with("sucesso", "Usuario Cadastrado");
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return redirect()->back()->with('erro', $e);
                    }
                }

            break;

        }

    }

    public function validar($request){

        if(isset($request->usuarioID)){

            if($request->cadernos == "[]"){
                return 4;
            }

            if(DB::table('users')->where('email', '=', $request->email)->where('id', '!=', $request->usuarioID)->count()){
                return 1;
            }

            if(DB::table('users')->where('login', '=', $request->login)->where('id', '!=', $request->usuarioID)->count()){
                return 2;
            }

            if(DB::table('users')->where('cpf', '=', $request->cpf)->where('id', '!=', $request->usuarioID)->count()){
                return 3;
            }

            if(isset($request->alterarSenha)){
                if($request->senha != $request->confirmarSenha){
                    return 5;
                }
            }

            if($request->usuarioID == Auth::user()->id && $request->statusID == 2){
                return 6;
            }

            $senha = str_replace(' ', '', $request->senha);
            if (strlen($senha) != strlen($request->senha)){
                return 7;
            }
            $login = str_replace(' ', '', $request->login);
            if (strlen($login) != strlen($request->login)){
                return 7;
            }

        }else{

            if($request->cadernos == "[]"){
                return 4;
            }

            if(DB::table('users')->where('email', '=', $request->email)->count()){
                return 1;
            }

            if(DB::table('users')->where('login', '=', $request->login)->count()){
                return 2;
            }

            if(DB::table('users')->where('cpf', '=', $request->cpf)->count()){
                return 3;
            }

            if(isset($request->alterarSenha)){
                if($request->senha != $request->confirmarSenha){
                    return 5;
                }
            }

            $senha = str_replace(' ', '', $request->senha);
            if (strlen($senha) != strlen($request->senha)){
                return 7;
            }
            $login = str_replace(' ', '', $request->login);
            if (strlen($login) != strlen($request->login)){
                return 7;
            }

        }

        if(!$this->validaCPF($request->cpf)){
            return 8;
        }

    }

    function validaCPF($cpf = null) {

        // Verifica se um número foi informado
        if(empty($cpf)) {
            return false;
        }

        // Elimina possivel mascara
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Verifica se o numero de digitos informados é igual a 11
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999') {
            return false;
         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
         } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    public function desativar($id){
        if(Gate::allows('administrador', Auth::user())){

            switch ($this->validarDesativar($id)){
                case 1:
                    return redirect()->back()->with('erro', "Usuário já está desativado!");
                break;

                case 2:
                    return redirect()->back()->with('erro', "Não é possivel desativar o próprio usuário!");
                break;

                default:
                    DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Desativou o usuário de id '.$id]);

                    DB::table('users')->where('id', '=', $id)->update(['statusID' => 2]);
                    return redirect()->back()->with('sucesso', "Usuário desativado!");
                break;
            }

        }else{
            return redirect()->route('home');
        }
    }

    public function validarDesativar($id){

        if(DB::table('users')->where('id', '=', $id)->where('statusID', '=', 2)->count()){
            return 1;
        }

        if(Auth::user()->id == $id){
            return 2;
        }
    }

    public function cadernoFatura(){

        $cadernoID = DB::table('configuracaofatura')->select('cadernoID')->first();
        $cadernoID = $cadernoID->cadernoID;
        if($cadernoID == null){
            return true;
        }
        if(User::orderBy('name')->join('usuariocaderno', 'usuariocaderno.usuarioID', 'users.id')->where('cadernoID', '=', $cadernoID)->where('users.id', '=', Auth::user()->id)->count()){
            return true;
        }else{
            return false;
        }
    }

    public function orgaoNome(){
        $orgao = DB::table('orgaorequisitante')->select('orgaoNome')->where('orgaoID', '=', Auth::user()->orgaoID)->first();
        return $orgao->orgaoNome;

    }
}
