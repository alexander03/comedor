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
if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    // Ignores notices and reports all other kinds... and warnings
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
}


Route::get('/', function () {
    return '<script>window.open(\'https://sistemasolmos.pe\',\'_self\');</script>';
    //return view('auth.login');
});

Route::get('/asd', function () {
    //return view('auth.login');
});

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});
//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});
//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});
//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});
//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});
//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('webservices/cargarMegaMenu', 'WebServicesController@cargarMegaMenu')->middleware('cors');
Route::get('webservices/principal', 'WebServicesController@principal')->middleware('cors');
Route::get('webservices/catalogo', 'WebServicesController@catalogo')->middleware('cors');
Route::get('webservices/buscarProducto', 'WebServicesController@buscarProducto')->middleware('cors');
Route::get('webservices/productoautocompletar', 'WebServicesController@productoautocompletar')->middleware('cors');
Route::get('webservices/producto', 'WebServicesController@producto')->middleware('cors');
Route::post('webservices/registrarPedido', 'WebServicesController@registrarPedido')->middleware('cors');
Route::get('/webservices/tomapedido', 'WebServicesController@tomapedido')->middleware('cors');
Route::get('/webservices/tomapedidoconfirmado', 'WebServicesController@tomapedidoconfirmado')->middleware('cors');
Route::post('/webservices/confirmartomapedido', 'WebServicesController@confirmartomapedido')->middleware('cors');
Route::get('/webservices/tomapedidocarnico', 'WebServicesController@tomapedidocarnico')->middleware('cors');
Route::get('/webservices/eliminardetalletomapedido', 'WebServicesController@eliminardetalletomapedido')->middleware('cors');
Route::get('/webservices/desayuno', 'WebServicesController@desayuno')->middleware('cors');
Route::get('/webservices/almuerzo', 'WebServicesController@almuerzo')->middleware('cors');
Route::get('/webservices/cena', 'WebServicesController@cena')->middleware('cors');
Route::get('/webservices/refrigerio', 'WebServicesController@refrigerio')->middleware('cors');
Route::post('/webservices/guardarProyectado', 'WebServicesController@guardarProyectado')->middleware('cors');

//Route::post('webservices/registrarPedido', 'WebServicesController@registrarPedidoComida')->middleware('cors')->name('webservices.registrarPedido');
Route::post('webservices/calcularContador', 'WebServicesController@calcularContador')->middleware('cors');

Route::get('/tomapedido', 'WebServicesController@formularioPedido')->middleware('cors');
Route::get('/tomapedidoGrupal', 'WebServicesController@formularioPedidoGrupal')->middleware('cors');
Route::get('/tomapedidoAdmin', 'WebServicesController@formularioPedidoAdmin')->middleware('cors');
//Route::get('/tomapedidoCarnico', 'WebServicesController@formularioPedidoCarnico')->middleware('cors');
//Route::get('/tomapedidoCarnicoAdmin', 'WebServicesController@formularioPedidoCarnicoAdmin')->middleware('cors');
Route::get('/searchAutocompletePersona/{query}', 'WebServicesController@searchAutocompletePersona')->middleware('cors');
Route::get('/searchAutocompleteEmpresa/{query}', 'WebServicesController@searchAutocompleteEmpresa')->middleware('cors');
Route::get('/searchAutocompleteLugar/{query}', 'WebServicesController@searchAutocompleteLugar')->middleware('cors');
Route::post('/guardarPedido', 'WebServicesController@guardarPedido')->middleware('cors')->name('webservices.guardarPedido');
Route::post('/guardarPedidoGrupal', 'WebServicesController@guardarPedidoGrupal')->middleware('cors')->name('webservices.guardarPedidoGrupal');
Route::post('/guardarPedidoCarnico', 'WebServicesController@guardarPedidoCarnico')->middleware('cors')->name('webservices.guardarPedidoCarnico');

Route::get('/registromovilizacion', 'WebServicesController@formularioMovilizacion')->middleware('cors');
Route::get('/regularizarmovilizacion', 'WebServicesController@formularioMovilizacion2')->middleware('cors');
Route::get('/searchAutocompletePersonaMovilizacion/{query}', 'WebServicesController@searchAutocompletePersonaMovilizacion')->middleware('cors');
Route::post('/consultarTarifa', 'WebServicesController@consultarTarifa')->middleware('cors');
Route::post('/guardarMovilizacion', 'WebServicesController@guardarMovilizacion')->middleware('cors')->name('webservices.guardarMovilizacion');

Route::get('/alimentacionh2o', 'WebServicesController@formularioComedor')->middleware('cors');
Route::get('/alimentacioncto', 'WebServicesController@formularioComedor2')->middleware('cors');
Route::post('/guardarComedor', 'WebServicesController@guardarComedor')->middleware('cors')->name('webservices.guardarComedor');
Route::post('/consultarCiclico', 'WebServicesController@consultarCiclico')->middleware('cors');
Route::post('/consultarCarnico', 'WebServicesController@consultarCarnico')->middleware('cors');
Route::post('/consultarCarnico2', 'WebServicesController@consultarCarnico2')->middleware('cors');
Route::post('/uploadFirma', 'WebServicesController@uploadFirma')->middleware('cors')->name('webservices.uploadFirma');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', function () {
        return View::make('layouts.master');
    });
    Route::post('categoriaopcionmenu/buscar', 'CategoriaopcionmenuController@buscar')->name('categoriaopcionmenu.buscar');
    Route::get('categoriaopcionmenu/eliminar/{id}/{listarluego}', 'CategoriaopcionmenuController@eliminar')->name('categoriaopcionmenu.eliminar');
    Route::resource('categoriaopcionmenu', 'CategoriaopcionmenuController', array('except' => array('show')));

    Route::post('opcionmenu/buscar', 'OpcionmenuController@buscar')->name('opcionmenu.buscar');
    Route::get('opcionmenu/eliminar/{id}/{listarluego}', 'OpcionmenuController@eliminar')->name('opcionmenu.eliminar');
    Route::resource('opcionmenu', 'OpcionmenuController', array('except' => array('show')));

    Route::post('tipousuario/buscar', 'TipousuarioController@buscar')->name('tipousuario.buscar');
    Route::get('tipousuario/obtenerpermisos/{listar}/{id}', 'TipousuarioController@obtenerpermisos')->name('tipousuario.obtenerpermisos');
    Route::post('tipousuario/guardarpermisos/{id}', 'TipousuarioController@guardarpermisos')->name('tipousuario.guardarpermisos');
    Route::get('tipousuario/eliminar/{id}/{listarluego}', 'TipousuarioController@eliminar')->name('tipousuario.eliminar');
    Route::resource('tipousuario', 'TipousuarioController', array('except' => array('show')));

    Route::post('usuario/buscar', 'UsuarioController@buscar')->name('usuario.buscar');
    Route::get('usuario/eliminar/{id}/{listarluego}', 'UsuarioController@eliminar')->name('usuario.eliminar');
    Route::resource('usuario', 'UsuarioController', array('except' => array('show')));
    Route::get('usuario/personautocompletar/{searching}', 'UsuarioController@personautocompletar')->name('usuario.personautocompletar');
    Route::get('usuario/cambiarcaja', 'UsuarioController@cambiarcaja')->name('usuario.cambiarcaja');

    /* Categoria */
    Route::post('categoria/buscar', 'CategoriaController@buscar')->name('categoria.buscar');
    Route::get('categoria/eliminar/{id}/{listarluego}', 'CategoriaController@eliminar')->name('categoria.eliminar');
    Route::resource('categoria', 'CategoriaController', array('except' => array('show')));
    
    /* Subcategoria */
    Route::post('subcategoria/buscar', 'SubcategoriaController@buscar')->name('subcategoria.buscar');
    Route::get('subcategoria/eliminar/{id}/{listarluego}', 'SubcategoriaController@eliminar')->name('subcategoria.eliminar');
    Route::resource('subcategoria', 'SubcategoriaController', array('except' => array('show')));
    
    /* Equipo */
    Route::post('equipo/buscar', 'EquipoController@buscar')->name('equipo.buscar');
    Route::get('equipo/eliminar/{id}/{listarluego}', 'EquipoController@eliminar')->name('equipo.eliminar');
    Route::resource('equipo', 'EquipoController', array('except' => array('show')));
    Route::get('equipo/viewCalibracion/{id}/{listarluego}', 'EquipoController@viewCalibracion')->name('equipo.viewCalibracion');

    /* Zona -> Modalidad */
    Route::post('modalidad/buscar', 'ModalidadController@buscar')->name('modalidad.buscar');
    Route::get('modalidad/eliminar/{id}/{listarluego}', 'ModalidadController@eliminar')->name('modalidad.eliminar');
    Route::resource('modalidad', 'ModalidadController', array('except' => array('show')));
    
    /* Calibracion */
    Route::post('calibracion/buscar', 'CalibracionController@buscar')->name('calibracion.buscar');
    Route::get('calibracion/eliminar/{id}/{listarluego}', 'CalibracionController@eliminar')->name('calibracion.eliminar');
    Route::resource('calibracion', 'CalibracionController', array('except' => array('show')));
    Route::get('calibracion/equipoautocompletar/{searching}/{subcategoria_id}', 'CalibracionController@equipoautocompletar')->name('calibracion.equipoautocompletar');
    Route::get('calibracion/subcategoriaautocompletar', 'CalibracionController@subcategoriaautocompletar')->name('calibracion.subcategoriaautocompletar');
    Route::post('calibracion/archivos', 'CalibracionController@archivos')->name('calibracion.archivos');
    Route::get('calibracion/excel', 'CalibracionController@excel')->name('calibracion.excel');
    
    /* Area */
    Route::post('area/buscar', 'AreaController@buscar')->name('area.buscar');
    Route::get('area/eliminar/{id}/{listarluego}', 'AreaController@eliminar')->name('area.eliminar');
    Route::resource('area', 'AreaController', array('except' => array('show')));
    
    /* Programa */
    Route::post('programa/buscar', 'ProgramaController@buscar')->name('programa.buscar');
    Route::get('programa/eliminar/{id}/{listarluego}', 'ProgramaController@eliminar')->name('programa.eliminar');
    Route::resource('programa', 'ProgramaController', array('except' => array('show')));
    Route::post('programa/archivos', 'ProgramaController@archivos')->name('programa.archivos');
    Route::get('programa/excel', 'ProgramaController@excel')->name('programa.excel');
    
    /* Proceso */
    Route::post('proceso/buscar', 'ProcesoController@buscar')->name('proceso.buscar');
    Route::get('proceso/eliminar/{id}/{listarluego}', 'ProcesoController@eliminar')->name('proceso.eliminar');
    Route::resource('proceso', 'ProcesoController', array('except' => array('show')));
    
    /* Indicador base */
    Route::post('indicadorbase/buscar', 'IndicadorbaseController@buscar')->name('indicadorbase.buscar');
    Route::get('indicadorbase/eliminar/{id}/{listarluego}', 'IndicadorbaseController@eliminar')->name('indicadorbase.eliminar');
    Route::resource('indicadorbase', 'IndicadorbaseController', array('except' => array('show')));
    
    /* Indicador */
    Route::post('indicador/buscar', 'IndicadorController@buscar')->name('indicador.buscar');
    Route::get('indicador/eliminar/{id}/{listarluego}', 'IndicadorController@eliminar')->name('indicador.eliminar');
    Route::resource('indicador', 'IndicadorController', array('except' => array('show')));
    Route::post('indicador/archivos', 'IndicadorController@archivos')->name('indicador.archivos');
    Route::get('indicador/excel', 'IndicadorController@excel')->name('indicador.excel');
    Route::get('indicador/indicadorbaseautocompletar/{searching}/{proceso_id}', 'IndicadorController@indicadorbaseautocompletar')->name('indicador.indicadorbaseautocompletar');
    
    /* Configuracion */
    Route::post('configuracion/buscar', 'ConfiguracionController@buscar')->name('configuracion.buscar');
    Route::get('configuracion/eliminar/{id}/{listarluego}', 'ConfiguracionController@eliminar')->name('configuracion.eliminar');
    Route::resource('configuracion', 'ConfiguracionController', array('except' => array('show')));

    /* Configuracionhora */
    Route::post('configuracionhora/buscar', 'ConfiguracionhoraController@buscar')->name('configuracionhora.buscar');
    Route::get('configuracionhora/eliminar/{id}/{listarluego}', 'ConfiguracionhoraController@eliminar')->name('configuracionhora.eliminar');
    Route::resource('configuracionhora', 'ConfiguracionhoraController', array('except' => array('show')));

    /* SUCURSAL */
    Route::post('sucursal/buscar', 'sucursalController@buscar')->name('sucursal.buscar');
    Route::get('sucursal/eliminar/{id}/{listarluego}', 'sucursalController@eliminar')->name('sucursal.eliminar');
    Route::resource('sucursal', 'sucursalController', array('except' => array('show')));

    /* PERSONA */
    Route::post('persona/buscar', 'PersonaController@buscar')->name('persona.buscar');
    Route::get('persona/eliminar/{id}/{listarluego}', 'PersonaController@eliminar')->name('persona.eliminar');
    Route::get('persona/import', 'PersonaController@import')->name('persona.import');
    Route::post('persona/saveimport', 'PersonaController@saveimport')->name('persona.saveimport');
    Route::resource('persona', 'PersonaController', array('except' => array('show')));
    Route::post('persona/buscarDNI', 'PersonaController@buscarDNI')->name('persona.buscarDNI');
    Route::post('persona/buscarRUC', 'PersonaController@buscarRUC')->name('persona.buscarRUC');
    Route::get('persona/wp', 'PersonaController@wp')->name('persona.wp');
    Route::post('persona/archivos', 'PersonaController@archivos')->name('persona.archivos');

    /* PEDIDO */
    Route::post('pedido/buscar', 'PedidoController@buscar')->name('pedido.buscar');
    Route::get('pedido/eliminar/{id}/{listarluego}', 'PedidoController@eliminar')->name('pedido.eliminar');
    Route::resource('pedido', 'PedidoController', array('except' => array('show')));
    Route::get('pedido/excelLista', 'PedidoController@excelLista')->name('pedido.excelLista');

    /* PEDIDO CARNICO*/
    Route::post('pedidocarnico/buscar', 'PedidoCarnicoController@buscar')->name('pedidocarnico.buscar');
    Route::get('pedidocarnico/eliminar/{id}/{listarluego}', 'PedidoCarnicoController@eliminar')->name('pedidocarnico.eliminar');
    Route::resource('pedidocarnico', 'PedidoCarnicoController', array('except' => array('show')));
    Route::get('pedidocarnico/excelLista', 'PedidoCarnicoController@excelLista')->name('pedidocarnico.excelLista');

    /* PEDIDO GRUPAL */
    Route::post('pedidogrupal/buscar', 'PedidoGrupalController@buscar')->name('pedidogrupal.buscar');
    Route::get('pedidogrupal/eliminar/{id}/{listarluego}', 'PedidoGrupalController@eliminar')->name('pedidogrupal.eliminar');
    Route::resource('pedidogrupal', 'PedidoGrupalController', array('except' => array('show')));
    Route::get('pedidogrupal/excelLista', 'PedidoGrupalController@excelLista')->name('pedidogrupal.excelLista');

    /* PEDIDO REPORTE */
    Route::post('pedidoreporte/buscar', 'PedidoreporteController@buscar')->name('pedidoreporte.buscar');
    Route::resource('pedidoreporte', 'PedidoreporteController', array('except' => array('show')));

    /* LUGAR */
    Route::post('lugar/buscar', 'LugarController@buscar')->name('lugar.buscar');
    Route::get('lugar/eliminar/{id}/{listarluego}', 'LugarController@eliminar')->name('lugar.eliminar');
    Route::resource('lugar', 'LugarController', array('except' => array('show')));

    /* TARIFA */
    Route::post('tarifa/buscar', 'TarifaController@buscar')->name('tarifa.buscar');
    Route::get('tarifa/eliminar/{id}/{listarluego}', 'TarifaController@eliminar')->name('tarifa.eliminar');
    Route::resource('tarifa', 'TarifaController', array('except' => array('show')));

    /* MOVILIZACION */
    Route::post('movilizacion/buscar', 'MovilizacionController@buscar')->name('movilizacion.buscar');
    Route::get('movilizacion/eliminar/{id}/{listarluego}', 'MovilizacionController@eliminar')->name('movilizacion.eliminar');
    Route::resource('movilizacion', 'MovilizacionController', array('except' => array('show')));
    Route::get('movilizacion/excel', 'MovilizacionController@excel')->name('movilizacion.excel');
    Route::get('movilizacion/pdfReporte', 'MovilizacionController@pdfReporte')->name('movilizacion.pdfReporte');
    Route::get('movilizacion/reporte', 'MovilizacionController@reporte')->name('movilizacion.reporte');
    Route::get('movilizacion/pdfValorizacion', 'MovilizacionController@pdfValorizacion')->name('movilizacion.pdfValorizacion');

    /* COMEDOR */
    Route::post('comedor/buscar', 'ComedorController@buscar')->name('comedor.buscar');
    Route::get('comedor/eliminar/{id}/{listarluego}', 'ComedorController@eliminar')->name('comedor.eliminar');
    Route::resource('comedor', 'ComedorController', array('except' => array('show')));

    /* COMIDA REPORTE */
    Route::post('comidareporte/buscar', 'ComidareporteController@buscar')->name('comidareporte.buscar');
    Route::get('comidareporte/eliminar/{id}/{listarluego}', 'ComidareporteController@eliminar')->name('comidareporte.eliminar');
    Route::resource('comidareporte', 'ComidareporteController', array('except' => array('show')));
    Route::get('comidareporte/excel', 'ComidareporteController@excel')->name('comidareporte.excel');
    Route::get('comidareporte/buscarPersona', 'ComidareporteController@buscarPersona')->name('comidareporte.buscarPersona');
    Route::get('comidareporte/personautocompletar/{searching}', 'ComidareporteController@personautocompletar')->name('comidareporte.personautocompletar');
    Route::get('comidareporte/generatePDFTicket/{id}', 'ComidareporteController@generatePDFTicket')->name('comidareporte.generatePDFTicket');

    /* REFRIGERIO */
    Route::post('refrigerio/buscar', 'RefrigerioController@buscar')->name('refrigerio.buscar');
    Route::get('refrigerio/eliminar/{id}/{listarluego}', 'RefrigerioController@eliminar')->name('refrigerio.eliminar');
    Route::resource('refrigerio', 'RefrigerioController', array('except' => array('show')));

    /* DESAYUNO */
    Route::post('desayuno/buscar', 'DesayunoController@buscar')->name('desayuno.buscar');
    Route::get('desayuno/eliminar/{id}/{listarluego}', 'DesayunoController@eliminar')->name('desayuno.eliminar');
    Route::resource('desayuno', 'DesayunoController', array('except' => array('show')));

    /* ALMUERZO */
    Route::post('almuerzo/buscar', 'AlmuerzoController@buscar')->name('almuerzo.buscar');
    Route::get('almuerzo/eliminar/{id}/{listarluego}', 'AlmuerzoController@eliminar')->name('almuerzo.eliminar');
    Route::resource('almuerzo', 'AlmuerzoController', array('except' => array('show')));

    /* CENA */
    Route::post('cena/buscar', 'CenaController@buscar')->name('cena.buscar');
    Route::get('cena/eliminar/{id}/{listarluego}', 'CenaController@eliminar')->name('cena.eliminar');
    Route::resource('cena', 'CenaController', array('except' => array('show')));

    /* PROYECTADO */
    Route::post('proyectado/buscar', 'ProyectadoController@buscar')->name('proyectado.buscar');
    Route::get('proyectado/eliminar/{id}/{listarluego}', 'ProyectadoController@eliminar')->name('proyectado.eliminar');
    Route::resource('proyectado', 'ProyectadoController', array('except' => array('show')));
    Route::get('proyectado/excel', 'ProyectadoController@excel')->name('proyectado.excel');
    Route::post('proyectado/calcularComida', 'ProyectadoController@calcularComida')->name('proyectado.calcularComida');

    /* PEDIDO CARNICO REPORTE */
    Route::post('pedidocarnicoreporte/buscar', 'PedidocarnicoreporteController@buscar')->name('pedidocarnicoreporte.buscar');
    Route::resource('pedidocarnicoreporte', 'PedidocarnicoreporteController', array('except' => array('show')));
});
