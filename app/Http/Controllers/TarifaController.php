<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Lugar;
use App\Tarifa;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TarifaController extends Controller
{
    protected $folderview      = 'app.tarifa';
    protected $tituloAdmin     = 'Tarifa';
    protected $tituloRegistrar = 'Registrar tarifa';
    protected $tituloModificar = 'Modificar tarifa';
    protected $tituloEliminar  = 'Eliminar tarifa';
    protected $rutas           = array('create' => 'tarifa.create', 
            'edit'     => 'tarifa.edit', 
            'delete'   => 'tarifa.eliminar',
            'search'   => 'tarifa.buscar',
            'index'    => 'tarifa.index',
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
        $entidad          = 'Tarifa';
        $origen             = Libreria::getParam($request->input('origen'));
        $destino             = Libreria::getParam($request->input('destino'));
        $resultado        = Tarifa::join('lugar as origen','origen.id','=','tarifa.origen_id')
                            ->join('lugar as destino','destino.id','=','tarifa.destino_id')
                            ->select('origen.nombre as origen','destino.nombre as destino','tarifa.monto','tarifa.*')
                            ->orderBy('origen.nombre','asc');
        if($origen!=""){
            $resultado = $resultado->where('origen.id','=',$origen);
        }
        if($destino!=""){
            $resultado = $resultado->where('destino.id','=',$destino);
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'Origen', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Destino', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tarifa', 'numero' => '1');
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
        $entidad          = 'Tarifa';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboOrigen = array(''=>'Todos') + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboDestino = array(''=>'Todos') + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboOrigen','cboDestino'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Tarifa';
        $tarifa  = null;
        $formData     = array('tarifa.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        $cboOrigen = array(''=>'Seleccione') + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboDestino = array(''=>'Seleccione') + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        return view($this->folderview.'.mant')->with(compact('tarifa', 'formData', 'entidad', 'boton', 'listar','cboOrigen','cboDestino'));
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
                'origen_id' => 'required',
                'destino_id' => 'required',
                'monto' => 'required'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $obj       = new Tarifa();
            $obj->origen_id = $request->input('origen_id');
            $obj->destino_id = $request->input('destino_id');
            $obj->monto = $request->input('monto');
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
        $existe = Libreria::verificarExistencia($id, 'tarifa');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $tarifa  = Tarifa::find($id);
        $entidad      = 'Tarifa';
        $formData     = array('tarifa.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        $cboOrigen = array(''=>'Seleccione') + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboDestino = array(''=>'Seleccione') + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        return view($this->folderview.'.mant')->with(compact('tarifa', 'formData', 'entidad', 'boton', 'listar','cboOrigen','cboDestino'));
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
        $existe = Libreria::verificarExistencia($id, 'tarifa');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'origen_id' => 'required',
                'destino_id' => 'required',
                'monto' => 'required'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $obj       = Tarifa::find($id);
            $obj->origen_id = $request->input('origen_id');
            $obj->destino_id = $request->input('destino_id');
            $obj->monto = $request->input('monto');
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
        $existe = Libreria::verificarExistencia($id, 'tarifa');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Tarifa::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'tarifa');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Tarifa::find($id);
        $entidad  = 'Tarifa';
        $formData = array('route' => array('tarifa.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
