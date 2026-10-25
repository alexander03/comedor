<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Indicadorbase;
use App\Proceso;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndicadorbaseController extends Controller
{
    protected $folderview      = 'app.indicadorbase';
    protected $tituloAdmin     = 'Indicador';
    protected $tituloRegistrar = 'Registrar indicador';
    protected $tituloModificar = 'Modificar indicador';
    protected $tituloEliminar  = 'Eliminar indicador';
    protected $rutas           = array('create' => 'indicadorbase.create', 
            'edit'     => 'indicadorbase.edit', 
            'delete'   => 'indicadorbase.eliminar',
            'search'   => 'indicadorbase.buscar',
            'index'    => 'indicadorbase.index',
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
        $entidad          = 'Indicador';
        $name             = Libreria::getParam($request->input('name'));
        $resultado        = Indicadorbase::where('nombre','like','%'.strtoupper($name).'%')->orderBy('nombre','asc');
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'Proceso', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Objetivo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Formula', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Parametro de Aceptacion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Frecuencia', 'numero' => '1');
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
        $entidad          = 'Indicador';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Indicador';
        $indicador  = null;
        $formData     = array('indicadorbase.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        $cboProceso = array();
        $proceso = Proceso::orderBy('descripcion', 'asc')->get();
        foreach ($proceso as $k => $v) {
            $cboProceso = $cboProceso + array($v->id => $v->descripcion);
        }
        $cboFrecuencia = array("Mensual"=>"Mensual","Bimestral"=>"Bimestral","Trimestral"=>"Trimestral","Semestral"=>"Semestral","Anual"=>"Anual");
        return view($this->folderview.'.mant')->with(compact('indicador', 'formData', 'entidad', 'boton', 'listar','cboProceso','cboFrecuencia'));
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
                'nombre' => 'required|max:600'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $lstMinimo = $request->input('lstMinimo');
            $lstMaximo = $request->input('lstMaximo');
            $lstColor = $request->input('lstColor');
            $parametro = "";
            for($x=0;$x<count($lstMinimo);$x++){
                $parametro .=$lstMinimo[$x]."@".$lstMaximo[$x]."@".$lstColor[$x]."|";
            }
            $parametro = substr($parametro,0,strlen($parametro)-1);
            
            $obj       = new Indicadorbase();
            $obj->proceso_id = $request->input('proceso_id');
            $obj->nombre = $request->input('nombre');
            $obj->objetivo = $request->input('objetivo');
            $obj->formula = $request->input('formula');
            $obj->parametro = $parametro;
            $obj->frecuencia = $request->input('frecuencia');
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
        $existe = Libreria::verificarExistencia($id, 'indicadorbase');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $indicador  = Indicadorbase::find($id);
        $entidad      = 'Indicador';
        $formData     = array('indicadorbase.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        $cboProceso = array();
        $proceso = Proceso::orderBy('descripcion', 'asc')->get();
        foreach ($proceso as $k => $v) {
            $cboProceso = $cboProceso + array($v->id => $v->descripcion);
        }
        $cboFrecuencia = array("Mensual"=>"Mensual","Bimestral"=>"Bimestral","Trimestral"=>"Trimestral","Semestral"=>"Semestral","Anual"=>"Anual");
        return view($this->folderview.'.mant')->with(compact('indicador', 'formData', 'entidad', 'boton', 'listar','cboProceso','cboFrecuencia'));
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
        $existe = Libreria::verificarExistencia($id, 'indicadorbase');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'nombre' => 'required|max:600'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $lstMinimo = $request->input('lstMinimo');
            $lstMaximo = $request->input('lstMaximo');
            $lstColor = $request->input('lstColor');
            $parametro = "";
            for($x=0;$x<count($lstMinimo);$x++){
                $parametro .=$lstMinimo[$x]."@".$lstMaximo[$x]."@".$lstColor[$x]."|";
            }
            $parametro = substr($parametro,0,strlen($parametro)-1);
            
            $obj       = Indicadorbase::find($id);
            $obj->proceso_id = $request->input('proceso_id');
            $obj->nombre = $request->input('nombre');
            $obj->objetivo = $request->input('objetivo');
            $obj->formula = $request->input('formula');
            $obj->parametro = $parametro;
            $obj->frecuencia = $request->input('frecuencia');
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
        $existe = Libreria::verificarExistencia($id, 'indicadorbase');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Indicadorbase::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'indicadorbase');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Indicadorbase::find($id);
        $entidad  = 'Indicador';
        $formData = array('route' => array('indicadorbase.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
