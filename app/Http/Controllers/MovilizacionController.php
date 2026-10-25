<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Movilizacion;
use App\Lugar;
use App\Campamento;
use App\Tarifa;
use App\Motivo;
use App\Person;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Excel;
use App\Exports\MovilizacionExport;
use Barryvdh\DomPDF\Facade as PDF;

class MovilizacionController extends Controller
{
    protected $folderview      = 'app.movilizacion';
    protected $tituloAdmin     = 'Traslados';
    protected $tituloRegistrar = 'Registrar movilizacion';
    protected $tituloModificar = 'Modificar traslado';
    protected $tituloEliminar  = 'Eliminar traslado';
    protected $rutas           = array('create' => 'movilizacion.create', 
            'edit'     => 'movilizacion.edit', 
            'delete'   => 'movilizacion.eliminar',
            'search'   => 'movilizacion.buscar',
            'index'    => 'movilizacion.index',
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
        $entidad          = 'Movilizacion';
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $campamento         = Libreria::getParam($request->input('campamento'));
        $persona         = Libreria::getParam($request->input('persona'));
        $origen         = Libreria::getParam($request->input('origen'));
        $destino         = Libreria::getParam($request->input('destino'));
        $persona         = Libreria::getParam($request->input('persona'));
        $empresa         = Libreria::getParam($request->input('empresa'));
        $resultado        = Movilizacion::join('lugar as origen','origen.id','=','movilizacion.origen_id')
                            ->join('lugar as destino','destino.id','=','movilizacion.destino_id')
                            ->orderBy('movilizacion.fecha','desc')
                            ->select('movilizacion.*','origen.nombre as origen','destino.nombre as destino');
        if($fechainicio!=""){
            $resultado = $resultado->where(DB::raw('date(movilizacion.fecha)'),'>=',DB::raw("'".$fechainicio."'"));
        }
        if($fechafin!=""){
            $resultado = $resultado->where(DB::raw('date(movilizacion.fecha)'),'<=',DB::raw("'".$fechafin."'"));
        }
        if($persona!=""){
            $resultado = $resultado->where('movilizacion.persona','like',DB::raw("'%".$persona."%'"));
        }
        if($origen!=""){
            $resultado = $resultado->where('movilizacion.origen_id','=',$origen);
        }
        if($destino!=""){
            $resultado = $resultado->where('movilizacion.destino_id','=',$destino);
        }
        if($campamento!=""){
            $resultado = $resultado->where('movilizacion.campamento_id','=',$campamento);
        }
        if($empresa!=""){
            $resultado = $resultado->where('movilizacion.empresa','like',DB::raw("'%".$empresa."%'"));
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Beneficiario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Empresa', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Campamento', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Origen', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Destino', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tarifa', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Motivo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha Registro', 'numero' => '1');
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
        $entidad          = 'Movilizacion';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboOrigen = array(""=>"Todos") + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboDestino = array(""=>"Todos") + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboCampamento = array(""=>"Todos") + Campamento::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboOrigen','cboDestino','cboCampamento'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Area';
        $area  = null;
        $formData     = array('area.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('area', 'formData', 'entidad', 'boton', 'listar'));
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
                'descripcion' => 'required|max:60'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $obj       = new Area();
            $obj->descripcion = $request->input('descripcion');
            $obj->orden = $request->input('orden');
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
        $existe = Libreria::verificarExistencia($id, 'movilizacion');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $movilizacion  = Movilizacion::find($id);
        $movilizacion->monto = $movilizacion->tarifa;
        $entidad      = 'Movilizacion';
        $formData     = array('movilizacion.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        $cboOrigen = array(''=>'Seleccione') + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboDestino = array(''=>'Seleccione') + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboCampamento = Campamento::orderBy('nombre','asc')->pluck('nombre','id');
        $cboMotivo = array(''=>'Seleccione') + Motivo::orderBy('descripcion','asc')->pluck('descripcion','descripcion')->toArray();
        return view($this->folderview.'.mant')->with(compact('movilizacion', 'formData', 'entidad', 'boton', 'listar','cboOrigen','cboDestino','cboCampamento','cboMotivo'));
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
        $existe = Libreria::verificarExistencia($id, 'movilizacion');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'persona' => 'required|max:600'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $obj       = Movilizacion::find($id);
            $obj->persona= $request->input('persona');
            $obj->dni= '';
            $obj->persona_id= $request->input('persona_id');
            $obj->empresa_id= $request->input('empresa_id');
            $obj->contratista_id= $request->input('contratista_id');
            $obj->empresa= $request->input('empresa');
            $obj->campamento_id= $request->input('campamento_id');
            $obj->origen_id= $request->input('origen_id');
            $obj->destino_id= $request->input('destino_id');
            $obj->tarifa= $request->input('monto')+0;
            $obj->fecha= $request->input('fecha');
            $obj->motivo= mb_strtoupper($request->input('motivo'), 'utf-8');
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
        $existe = Libreria::verificarExistencia($id, 'movilizacion');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Movilizacion::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'movilizacion');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Movilizacion::find($id);
        $entidad  = 'Movilizacion';
        $formData = array('route' => array('movilizacion.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function excel(Request $request){
        setlocale(LC_TIME, 'spanish');
        return Excel::download(new MovilizacionExport($request->input('fechainicio'),$request->input('fechafin')), 'ReporteTraslados.xlsx');
    }

    public function reporte()
    {
        $entidad          = 'Movilizacion2';
        $cboCampamento = array(""=>"Todos") + Campamento::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboPersona = array(""=>"Seleccione...") + Person::join('user','user.person_id','=','person.id')
                            ->whereIn('user.usertype_id',[1,33,35])
                            ->orderBy('person.nombres','asc')
                            ->pluck('person.nombres','person.id')
                            ->toArray();
        return view($this->folderview.'.reporte')->with(compact('entidad','cboCampamento','cboPersona'));
    }

    public function pdfReporte(Request $request){
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $campamento         = Libreria::getParam($request->input('campamento_id'));
        $resultado        = Movilizacion::join('lugar as origen','origen.id','=','movilizacion.origen_id')
                            ->join('lugar as destino','destino.id','=','movilizacion.destino_id')
                            ->orderBy('movilizacion.fecha','desc')
                            ->select('movilizacion.*','origen.nombre as origen','destino.nombre as destino');
        if($fechainicio!=""){
            $resultado = $resultado->where(DB::raw('date(movilizacion.fecha)'),'>=',DB::raw("'".$fechainicio."'"));
        }
        if($fechafin!=""){
            $resultado = $resultado->where(DB::raw('date(movilizacion.fecha)'),'<=',DB::raw("'".$fechafin."'"));
        }
        if($campamento!=""){
            $resultado = $resultado->where('movilizacion.campamento_id','=',$campamento);
        }
        $lista            = $resultado->get();
        $periodo = $request->input('periodo');
        $empresa = $request->input('empresa');
        $personal = Person::find($request->input('person_id'));
        $pdf = PDF::loadView('app.movilizacion.verpdf',compact('lista','periodo','empresa','campamento','personal'));  
        return $pdf->stream('reporte-traslado.pdf');
    
    }

    public function pdfValorizacion(Request $request){
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $campamento         = Libreria::getParam($request->input('campamento_id'));
        $resultado        = Movilizacion::join('lugar as origen','origen.id','=','movilizacion.origen_id')
                            ->join('lugar as destino','destino.id','=','movilizacion.destino_id')
                            ->select(DB::raw('count(movilizacion.id) as pasajero'),'origen.nombre as origen','destino.nombre as destino','movilizacion.tarifa',DB::raw('date(movilizacion.fecha) as fecha'));
        if($fechainicio!=""){
            $resultado = $resultado->where(DB::raw('date(movilizacion.fecha)'),'>=',DB::raw("'".$fechainicio."'"));
        }
        if($fechafin!=""){
            $resultado = $resultado->where(DB::raw('date(movilizacion.fecha)'),'<=',DB::raw("'".$fechafin."'"));
        }
        if($campamento!=""){
            $resultado = $resultado->where('movilizacion.campamento_id','=',$campamento);
        }
        $lista            = $resultado->groupBy(DB::raw('date(movilizacion.fecha)'))
                                ->groupBy('origen_id')
                                ->groupBy('destino_id')
                                ->groupBy('origen.nombre')
                                ->groupBy('destino.nombre')
                                ->groupBy('movilizacion.tarifa')
                                ->get();
        $periodo = $request->input('periodo');
        $empresa_id = $request->input('empresa_id');
        $contratista_id = $request->input('contratista_id');
        if($contratista_id!="0"){
            $consulta = "select concat(ruc,' ',razonsocial) as descripcion,0 as empresa_id,id as contratista_id,razonsocial,ruc,direccion from contratista where
                deleted_at IS NULL AND
                id=$contratista_id";
            //echo $consulta;
            $res = DB::connection('mysql3')->select($consulta);
        }else{
            $consulta = "select concat(ruc,' ',razonsocial) as descripcion,id as empresa_id,0 as contratista_id,razonsocial,ruc,direccion from empresa
                where deleted_at is NULL and 
                id=$empresa_id ";
            //echo $consulta;
            $res = DB::connection('mysql3')->select($consulta);
        }
        
        foreach($res as $k=>$v){
            $empresa = $v;
        }
        $listaTarifa = Tarifa::get();
        $personal = Person::find($request->input('person_id'));
        //return view('app.movilizacion.valorizacionpdf',compact('lista','periodo','empresa','listaTarifa'));  
        $pdf = PDF::loadView('app.movilizacion.valorizacionpdf',compact('lista','periodo','empresa','listaTarifa','fechafin','campamento','personal'));  
        return $pdf->stream('reporte-traslado.pdf');
    
    }
}
