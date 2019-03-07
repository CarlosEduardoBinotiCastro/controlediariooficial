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


// Rotas de Login

Auth::routes();
Route::get('/register', function(){
    return redirect()->route('home');
});

// Rotas relacionadas com a HomePage


Route::get('/home', 'HomeController@carregarHome')->name('home');
Route::get('/logo', 'HomeController@pegarLogo');
Route::get('/logoSis', 'HomeController@pegarLogoSis');
Route::get('/logoBrasao', 'HomeController@pegarBrasao');

Route::get('/', function () {
    return redirect()->route('home');
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


    Route::get('/listar', 'DiarioDataController@listar');
    Route::get('/listar/{filtro}', ['as' => 'listarDiarios', 'uses' => 'DiarioDataController@listar']);
    Route::post('/chamarListar', 'DiarioDataController@listarFiltro');
    Route::post('/anexar', 'DiarioDataController@anexarDiario');
    Route::post('/remover', 'DiarioDataController@remover');
    Route::get('/downloadDiario/{id}', 'DiarioDataController@download');

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
    Route::get('/listar/usuario/{nome}/protocolo/{protocolo}/diario/{diario}/situacao/{situacao}/orgao/{orgao}/titulo/{titulo}/{dataInicial}/{dataFinal}', ['as' => 'listarPublicacoes', 'uses' => 'PublicacoesController@listar']);
    Route::post('/chamarListar', 'PublicacoesController@listarFiltro');

    Route::get('/apagadas', 'PublicacoesController@apagadas');
    Route::get('/apagadas/usuario/{nome}/protocolo/{protocolo}/diario/{diario}/orgao/{orgao}/titulo/{titulo}', ['as' => 'listarApagadas', 'uses' => 'PublicacoesController@apagadas']);
    Route::post('/chamarApagadas', 'PublicacoesController@listarFiltroApagadas');

    Route::get('/cadastrar', 'PublicacoesController@cadastrar');
    Route::post('/salvar', 'PublicacoesController@salvar');
    Route::get('/editar/{protocolo}', 'PublicacoesController@editar');
    Route::get('/ver/{protocolo}', 'PublicacoesController@ver');

    Route::post('/publicar', 'PublicacoesController@publicar');
    Route::post('/aceitar', 'PublicacoesController@aceitar');
    Route::post('/apagar', 'PublicacoesController@apagar');
    Route::post('/rejeitar', 'PublicacoesController@rejeitar');

    Route::get('/gerarComprovante/{protocolo}', 'PublicacoesController@gerarComprovante');
    Route::post('/gerarPdf', 'PublicacoesController@gerarPdf');

    Route::get('/downloadPublicacao/{arquivo}', 'PublicacoesController@download');
});


// Rotas Relacionadas com Faturas

Route::group(['prefix' => 'fatura'], function () {
    Route::get('/gerarTemplate', 'PhpWordController@criarTemplate');

    Route::get('/configuracao', 'FaturaController@carregarConfiguracao');
    Route::post('/salvarConfiguracao', 'FaturaController@salvarConfiguracao');

    Route::get('/cadastrar', 'FaturaController@cadastrar');
    Route::post('/salvar', 'FaturaController@salvar');
    Route::post('/formatar', 'FaturaController@formatar');
    Route::get('/ver/{protocolo}', 'FaturaController@ver');
    Route::post('/rejeitar', 'FaturaController@rejeitar');
    Route::post('/aceitar', 'FaturaController@aceitar');
    Route::post('/publicar', 'FaturaController@publicar');
    Route::post('/apagar', 'FaturaController@apagar');

    Route::get('/listar', 'FaturaController@listar');
    Route::get('/listar/cpfcnpj/{cpfCnpj}/protocolo/{protocolo}/diario/{diario}/situacao/{situacao}/empresa/{empresa}/subcategoria/{subcategoria}', ['as' => 'listarFaturas', 'uses' => 'FaturaController@listar']);
    Route::post('/chamarListar', 'FaturaController@listarFiltro');

    Route::get('/downloadOriginal/{protocolo}', 'FaturaController@downloadOriginal');
    Route::get('/downloadDAM/{protocolo}', 'FaturaController@downloadDAM');
    Route::get('/downloadFormatado/{protocolo}', 'FaturaController@downloadFormatado');
    Route::get('/downloadComprovantePago/{protocolo}', 'FaturaController@downloadComprovantePago');
    Route::get('/downloadTemp/{arquivoFormatadoTemp}', 'FaturaController@downloadTemp');

    Route::get('/relatorio', 'FaturaController@carregarRelatorio');
    Route::get('/relatorio/dataInicial/{dataInicio}/dataFinal/{dataFinal}/situacao/{situacao}', ['as' => 'carregarRelatorio', 'uses' => 'FaturaController@carregarRelatorio']);
    Route::post('/relatorioFiltro', 'FaturaController@carregarRelatorioFiltro');

    // Route::get('/caixaDeTexto', 'FaturaController@caixaDeTexto');

    Route::get('/visualizacaoTemp/{arquivoVisualizacao}', 'FaturaController@downloadVisualizacaoTemp');
    Route::get('/visualizacao/{arquivoVisualizacao}/{protocolo}', 'FaturaController@downloadVisualizacao');


    Route::get('/relatorioDetalhado', 'FaturaController@relatorioDetalhado');
    Route::get('/relatorioDetalhado/cpfcnpj/{cpfCnpj}/protocolo/{protocolo}/datainicial/{dataInicial}/datafinal/{dataFinal}/situacao/{situacao}/empresa/{empresa}/subcategoria/{subcategoria}', ['as' => 'relatorioDetalhado', 'uses' => 'FaturaController@relatorioDetalhado']);
    Route::post('/relatorioDetalhadoFiltro', 'FaturaController@relatorioDetalhadoFiltro');

    // chamar aceitas
    Route::get('/irParaCadastradas', 'FaturaController@chamarCadastradas');

    //editar anotações
    Route::post('/editarAnotacao', 'FaturaController@editarAnotacao');

    //editar anotações
    Route::post('/anexarDam', 'FaturaController@anexarDAM');

    // Area de teste
    Route::get('/testeDoc', 'FaturaController@convertToText');

    Route::get('/cabecalho', 'FaturaController@cabecalho');
    Route::get('/gerarComprovante/{protocoloID}', 'FaturaController@gerarComprovante');
    Route::post('/gerarPdf', 'FaturaController@gerarPdf');

});


// Rotas Relacionadas com SubCategoria

Route::group(['prefix' => 'subcategoria'], function () {
    Route::get('/listar', 'SubCategoriaController@listar');
    Route::get('/cadastrar', 'SubCategoriaController@cadastrar');
    Route::get('/deletar/{subcategoriaID}', 'SubCategoriaController@deletar');
    Route::get('/editar/{subcategoriaID}', 'SubCategoriaController@editar');
    Route::post('/salvar', 'SubCategoriaController@salvar');
});




// Rotas Relacionadas com Comunicado

Route::group(['prefix' => 'comunicado'], function () {

    Route::get('/listar', 'ComunicadoController@listar');
    Route::get('/listar/{filtro}', ['as' => 'listarComunicados', 'uses' => 'ComunicadoController@listar']);
    Route::post('/chamarListar', 'ComunicadoController@listarFiltro');

    Route::get('/cadastrar', 'ComunicadoController@cadastrar');
    Route::get('/deletar/{comunicadoID}', 'ComunicadoController@deletar');
    Route::get('/editar/{comunicadoID}', 'ComunicadoController@editar');
    Route::get('/ver/{comunicadoID}', 'ComunicadoController@ver');

    Route::post('/salvar', 'ComunicadoController@salvar');

    Route::post('/visualizarComunicado', 'ComunicadoController@visualizarComunicado');
});

Route::group(['prefix' => 'log'], function () {
    Route::get('/listar', 'LogController@listar');
    Route::get('/listar/{filtro}', ['as' => 'listarLogs', 'uses' => 'LogController@listar']);
    Route::post('/chamarListar', 'LogController@listarFiltro');
});


// rotas ajax
Route::get('searchajaxEmpresa', ['as'=>'searchajaxEmpresa','uses'=>'FaturaController@searchResponseEmpresa']);
