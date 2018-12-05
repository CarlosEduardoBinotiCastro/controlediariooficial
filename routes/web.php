<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// rotas misc

Route::get('/carregando', function(){
    return view('carregar');
});


// Rotas de Login

Auth::routes();
Route::get('/register', function(){
    return redirect()->route('home');
});

// Rotas relacionadas com a HomePage


Route::get('/home', 'HomeController@carregarHome')->name('home');
Route::get('/logo', 'HomeController@pegarLogo');

Route::get('/', function () {
    return redirect()->route('home');
});


// Rotas relacionadas com as Publicações

Route::group(['prefix' => 'publicacoes'], function () {
    Route::get('/listar', 'PublicacoesController@listar');
});


// Rotas relacionadas com os Dias não Úteis

Route::group(['prefix' => 'diasnaouteis'], function () {
    Route::get('/listar', 'DiasNaoUteisController@listar');
    Route::get('/cadastrar', 'DiasNaoUteisController@cadastrar');
    Route::post('/salvar', 'DiasNaoUteisController@salvar');
    Route::get('/editar/{id}', 'DiasNaoUteisController@editar');
    Route::get('/deletar/{id}', 'DiasNaoUteisController@deletar');
});


// Rotas relacionadas com os Diarios Datas

Route::group(['prefix' => 'diariodata'], function () {
    Route::get('/listar', 'DiarioDataController@listar');
    Route::get('/cadastrar', 'DiarioDataController@cadastrar');
    Route::post('/salvar', 'DiarioDataController@salvar');
    Route::get('/editar/{id}', 'DiarioDataController@editar');
    Route::get('/deletar/{id}', 'DiarioDataController@deletar');
});

// Rotas relacionadas com os Tipos Documentos

Route::group(['prefix' => 'tipodocumento'], function () {
    Route::get('/listar', 'TipoDocumentoController@listar');
    Route::get('/cadastrar', 'TipoDocumentoController@cadastrar');
    Route::post('/salvar', 'TipoDocumentoController@salvar');
    Route::get('/editar/{id}', 'TipoDocumentoController@editar');
    Route::get('/deletar/{id}', 'TipoDocumentoController@deletar');
});


// Rotas Relacionadas com Orgão Requisitantes

Route::group(['prefix' => 'orgaorequisitante'], function () {
    Route::get('/listar', 'OrgaoRequisitanteController@listar');
    Route::get('/cadastrar', 'OrgaoRequisitanteController@cadastrar');
    Route::post('/salvar', 'OrgaoRequisitanteController@salvar');
    Route::get('/editar/{id}', 'OrgaoRequisitanteController@editar');
    Route::get('/deletar/{id}', 'OrgaoRequisitanteController@deletar');
});


// Rotas Relacionadas com Orgão cadernos

Route::group(['prefix' => 'caderno'], function () {
    Route::get('/listar', 'CadernoController@listar');
    Route::get('/cadastrar', 'CadernoController@cadastrar');
    Route::post('/salvar', 'CadernoController@salvar');
    Route::get('/editar/{id}', 'CadernoController@editar');
    Route::get('/deletar/{id}', 'CadernoController@deletar');
});


// Rotas Relacionadas com Usuarios

Route::group(['prefix' => 'usuario'], function () {

    Route::get('/listar', 'UserController@listar');
    Route::get('/listar/{filtro}', ['as' => 'listarUsuarios', 'uses' => 'UserController@listar']);
    Route::post('/chamarListar', 'UserController@listarFiltro');

    Route::get('/cadastrar', 'UserController@cadastrar');
    Route::post('/salvar', 'UserController@salvar');
    Route::get('/editar/{id}', 'UserController@editar');
    Route::get('/desativar/{id}', 'UserController@desativar');
});


// Rotas Relacionadas com Publicações

Route::group(['prefix' => 'publicacao'], function () {

    Route::get('/listar', 'PublicacoesController@listar');
    Route::get('/listar/usuario/{nome}/protocolo/{protocolo}/diario/{diario}/situacao/{situacao}/orgao/{orgao}', ['as' => 'listarPublicacoes', 'uses' => 'PublicacoesController@listar']);
    Route::post('/chamarListar', 'PublicacoesController@listarFiltro');

    Route::get('/apagadas', 'PublicacoesController@apagadas');
    Route::get('/apagadas/usuario/{nome}/protocolo/{protocolo}/diario/{diario}/orgao/{orgao}', ['as' => 'listarApagadas', 'uses' => 'PublicacoesController@apagadas']);
    Route::post('/chamarApagadas', 'PublicacoesController@listarFiltroApagadas');

    Route::get('/cadastrar', 'PublicacoesController@cadastrar');
    Route::post('/salvar', 'PublicacoesController@salvar');
    Route::get('/editar/{protocolo}', 'PublicacoesController@editar');
    Route::get('/ver/{protocolo}', 'PublicacoesController@ver');
    Route::get('/download/{arquivo}', 'PublicacoesController@download');
    Route::post('/publicar', 'PublicacoesController@publicar');
    Route::post('/aceitar', 'PublicacoesController@aceitar');
    Route::post('/apagar', 'PublicacoesController@apagar');
    Route::post('/rejeitar', 'PublicacoesController@rejeitar');
});

