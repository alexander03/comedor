<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Refrigerio;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RefrigerioController extends Controller
{
    protected $folderview      = 'app.refrigerio';
    protected $tituloAdmin     = 'Refrigerio';
    protected $entidad         = 'Refrigerio';
    protected $tituloRegistrar = 'Registrar Refrigerio';
    protected $tituloModificar = 'Modificar Refrigerio';
    protected $tituloEliminar  = 'Eliminar Refrigerio';
    protected $rutas           = array('create' => 'refrigerio.create', 
            'edit'     => 'refrigerio.edit', 
            'delete'   => 'refrigerio.eliminar',
            'search'   => 'refrigerio.buscar',
            'index'    => 'refrigerio.index',
        );

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el resultado de b煤squedas
     * 
     * @return Response 
     */
    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = $this->entidad;
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $tipo             = Libreria::getParam($request->input('tipo'));
        $resultado        = Refrigerio::where('fecha','>=',$fechainicio)
                                    ->where('fecha','<=',$fechafin)
                                    ->orderBy('fecha','asc');
        if($tipo!=""){
            $resultado = $resultado->where('tipo','like','%'.$tipo.'%');
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Bebida', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Principal', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Complemento', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Fruta', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Extra', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $ruta             = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('lista', 'entidad'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidad          = $this->entidad;
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboTipo = array(''=>'Todos','Refrigerio Desayuno' => 'Refrigerio Desayuno','Refrigerio Operador' => 'Refrigerio Operador');//,'Refrigerio Comun'=>'Refrigerio Comun'
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboTipo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = $this->entidad;
        $refrigerio  = null;
        $formData     = array('refrigerio.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        $cboTipo = array(''=>'Seleccione','Refrigerio Desayuno' => 'Refrigerio Desayuno','Refrigerio Operador' => 'Refrigerio Operador');//,'Refrigerio Comun'=>'Refrigerio Comun'
        return view($this->folderview.'.mant')->with(compact('refrigerio', 'formData', 'entidad', 'boton', 'listar','cboTipo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $validacion = Validator::make($request->all(),
            array(
                'tipo' => 'required',
                'fecha' => 'required',
                //'bebida' => 'required',
                //'kcal_bebida' => 'required',
                'sandwich' => 'required',
                'kcal_sandwich' => 'required',
                //'complemento' => 'required',
                //'kcal_complemento' => 'required',
                //'fruta' => 'required',
                //'kcal_fruta' => 'required',
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $obj       = new Refrigerio();
            $obj->tipo = $request->input('tipo');
            $obj->fecha = $request->input('fecha');
            $obj->bebida = $request->input('bebida');
            $obj->kcal_bebida = $request->input('kcal_bebida');
            $obj->sandwich = $request->input('sandwich');
            $obj->kcal_sandwich = $request->input('kcal_sandwich');
            $obj->complemento = $request->input('complemento');
            $obj->kcal_complemento = $request->input('kcal_complemento');
            $obj->fruta = $request->input('fruta');
            $obj->kcal_fruta = $request->input('kcal_fruta');
            if($obj->tipo=="Refrigerio Comun"){
                $obj->extra = $request->input('extra');
                $obj->kcal_extra = $request->input('kcal_extra');
            }else{
                $obj->extra = '';
                $obj->kcal_extra = 0;
            }
            $obj->save();
        });
        return is_null($error) ? "OK" : $error;
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
    public function edit($id, Request $request)
    {
        $existe = Libreria::verificarExistencia($id, 'refrigerio');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $refrigerio  = Refrigerio::find($id);
        $entidad          = $this->entidad;
        $formData     = array('refrigerio.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        $cboTipo = array(''=>'Seleccione','Refrigerio Desayuno' => 'Refrigerio Desayuno','Refrigerio Operador' => 'Refrigerio Operador');//,'Refrigerio Comun'=>'Refrigerio Comun'
        return view($this->folderview.'.mant')->with(compact('refrigerio', 'formData', 'entidad', 'boton', 'listar','cboTipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'refrigerio');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'tipo' => 'required',
                'fecha' => 'required',
                //'bebida' => 'required',
                //'kcal_bebida' => 'required',
                'sandwich' => 'required',
                'kcal_sandwich' => 'required',
                //'complemento' => 'required',
                //'kcal_complemento' => 'required',
                //'fruta' => 'required',
                //'kcal_fruta' => 'required',
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $obj       = Refrigerio::find($id);
            $obj->tipo = $request->input('tipo');
            $obj->fecha = $request->input('fecha');
            $obj->bebida = $request->input('bebida');
            $obj->kcal_bebida = $request->input('kcal_bebida');
            $obj->sandwich = $request->input('sandwich');
            $obj->kcal_sandwich = $request->input('kcal_sandwich');
            $obj->complemento = $request->input('complemento');
            $obj->kcal_complemento = $request->input('kcal_complemento');
            $obj->fruta = $request->input('fruta');
            $obj->kcal_fruta = $request->input('kcal_fruta');
            if($obj->tipo=="Refrigerio Comun"){
                $obj->extra = $request->input('extra');
                $obj->kcal_extra = $request->input('kcal_extra');
            }else{
                $obj->extra = '';
                $obj->kcal_extra = 0;
            }
            $obj->save();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'refrigerio');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Refrigerio::find($id);
            $obj->delete();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Funci贸n para confirmar la eliminaci贸n de un registrlo
     * @param  integer $id          id del registro a intentar eliminar
     * @param  string $listarLuego consultar si luego de eliminar se listar谩
     * @return html              se retorna html, con la ventana de confirmar eliminar
     */
    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'refrigerio');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Refrigerio::find($id);
        $entidad          = $this->entidad;
        $formData = array('route' => array('refrigerio.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
