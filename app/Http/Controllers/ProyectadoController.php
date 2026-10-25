<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Proyectado;
use App\Comedor;
use App\Pedido;
use App\Comida;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Curl;
use Excel;
use App\Exports\ProyectadoExport;
use Illuminate\Support\Facades\Auth;

class ProyectadoController extends Controller
{
    protected $folderview      = 'app.proyectado';
    protected $entidad         = 'Proyectado';
    protected $tituloAdmin     = 'Proyectado';
    protected $tituloRegistrar = 'Registrar Proyectado';
    protected $tituloModificar = 'Modificar Proyectado';
    protected $tituloEliminar  = 'Eliminar Proyectado';
    protected $rutas           = array('create' => 'proyectado.create', 
            'edit'     => 'proyectado.edit', 
            'delete'   => 'proyectado.eliminar',
            'search'   => 'proyectado.buscar',
            'index'    => 'proyectado.index',
        );

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("America/Caracas");
        setlocale(LC_ALL, 'es_VE.UTF-8','esp');
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
        $comedor          = Libreria::getParam($request->input('comedor'));
        $resultado        = Proyectado::where('fecha','>=',$fechainicio)->where('fecha','<=',$fechafin)->orderBy('fecha','desc');
        if($comedor!="0"){
            $resultado = $resultado->where('comedor_id','=',$comedor);
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Desayuno Proyectado', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Desayuno', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Almuerzo Proyectado', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Almuerzo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cena Proyectado', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cena', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Comedor', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Usuario', 'numero' => '1');
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
        $entidad          = 'Proyectado';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboComedor = array(0=>'Todos') + Comedor::orderBy('descripcion','asc')->pluck('descripcion','id')->toArray();
        $user     = Auth::User();
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboComedor','user'));
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
        $proyectado  = null;
        $formData     = array('proyectado.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        $cboComedor = array(''=>'Seleccione') + Comedor::orderBy('descripcion','asc')->pluck('descripcion','id')->toArray();
        $user     = Auth::User();
        return view($this->folderview.'.mant')->with(compact('proyectado', 'formData', 'entidad', 'boton', 'listar','cboComedor','user'));
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
                'fecha' => 'required|date|after:'.date("Y-m-d",strtotime('-1 day')),
                'desayuno' => 'required',
                'almuerzo' => 'required',
                'cena' => 'required',
                'comedor_id' => 'required'
                )
            );
        $user     = Auth::User();
        if($user->usertype_id==1 || $user->usertype_id==35){
            //nada
        }else{
            if ($validacion->fails()) {
                return $validacion->messages()->toJson();
            }
        }
        $proyectado = Proyectado::where('fecha','=',$request->input('fecha'))->where('comedor_id','=',$request->input('comedor_id'))->first();
        if(!is_null($proyectado)){
            return json_encode(array("fecha"=>["Fecha ya registrada para el comedor"]));
            exit();
        }
        if($user->usertype_id==1 || $user->usertype_id==35){
            //nada
        }else{
            if(strtotime($request->input('fecha'))>strtotime('+1 day')){
                return json_encode(array("fecha"=>["Fecha no puede ser mayor a la de hoy"]));
                exit();
            }
        }
        $id = 0;
        $error = DB::transaction(function() use($request,&$id){
            $user     = Auth::User();
            $obj       = new Proyectado();
            $obj->fecha = $request->input('fecha');
            $obj->desayuno = $request->input('desayuno');
            $obj->almuerzo = $request->input('almuerzo');
            $obj->cena = $request->input('cena');
            $obj->comedor_id = $request->input('comedor_id');
            $obj->user_id = $user->id;
            $obj->save();
            $id = $obj->id;
        });
        $cc = new Curl(false,'https://sistemasolmos.pe/comedor/PHPMailer/notificacionProyectado.php');
        $url = 'https://sistemasolmos.pe/comedor/PHPMailer/notificacionProyectado.php?id='.$id.'&modo=Nuevo';
        //dd($url);
        $Page = $cc->get($url,array());
        $datos = json_decode($Page);

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
        $existe = Libreria::verificarExistencia($id, 'proyectado');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $proyectado  = Proyectado::find($id);
        $entidad      = $this->entidad;
        $formData     = array('proyectado.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        $cboComedor = array(''=>'Seleccione') + Comedor::orderBy('descripcion','asc')->pluck('descripcion','id')->toArray();
        $user     = Auth::User();
        return view($this->folderview.'.mant')->with(compact('proyectado', 'formData', 'entidad', 'boton', 'listar','cboComedor','user'));
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
        $existe = Libreria::verificarExistencia($id, 'proyectado');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'fecha' => 'required',
                'desayuno' => 'required',
                'almuerzo' => 'required',
                'cena' => 'required',
                'comedor_id' => 'required'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $user     = Auth::User();
        $proyectado = Proyectado::where('fecha','=',$request->input('fecha'))->where('comedor_id','=',$request->input('comedor_id'))->whereNotIn('id',[$id])->first();
        if(!is_null($proyectado)){
            return json_encode(array("fecha"=>["Fecha ya registrada para el comedor"]));
            exit();
        }
        if($user->usertype_id==1 || $user->usertype_id==35){
            //nada
        }else{
            if(strtotime($request->input('fecha'))>strtotime('+1 day')){
                return json_encode(array("fecha"=>["Fecha no puede ser mayor a la de hoy"]));
                exit();
            }
        }
        $error = DB::transaction(function() use($request, $id){
            $obj       = Proyectado::find($id);
            $obj->fecha = $request->input('fecha');
            $obj->desayuno = $request->input('desayuno');
            $obj->almuerzo = $request->input('almuerzo');
            $obj->cena = $request->input('cena');
            $obj->comedor_id = $request->input('comedor_id');
            $obj->save();

        });

        $cc = new Curl(false,'https://sistemasolmos.pe/comedor/PHPMailer/notificacionProyectado.php');
        $url = 'https://sistemasolmos.pe/comedor/PHPMailer/notificacionProyectado.php?id='.$id.'&modo=Editar';
        //dd($url);
        $Page = $cc->get($url,array());
        $datos = json_decode($Page);
        
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
        $existe = Libreria::verificarExistencia($id, 'proyectado');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Proyectado::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'proyectado');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Proyectado::find($id);
        $entidad      = $this->entidad;
        $formData = array('route' => array('proyectado.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function excel(Request $request){
        setlocale(LC_TIME, 'spanish');
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $comedor          = Libreria::getParam($request->input('comedor'));
        
        return Excel::download(new ProyectadoExport($fechainicio,$fechafin,$comedor), 'ReporteProyectado.xlsx');
    }

    public function calcularComida(Request $request){
        $fecha = $request->input('fecha');
        $fecha = date("Y-m-d",strtotime("-1 day",strtotime($fecha)));
        $comedor = $request->input('comedor_id');
        $resultado        = Comida::join('comedor','comedor.id','=','comida.comedor_id')
                            ->join('servicio','servicio.id','=','comida.servicio_id')
                            ->orderBy('comida.fecha','desc')
                            ->select('comida.*','servicio.nombre as servicio2','comedor.descripcion as comedor2');
        $resultado = $resultado->where(DB::raw('date(comida.fecha)'),'=',DB::raw("'".$fecha."'"));
        $resultado = $resultado->where('comida.comedor_id','=',$comedor);

        $lista            = $resultado->get();

        $almuerzo = 0; $desayuno=0; $cena=0;
        foreach ($lista as $key => $value) {
            if($value->servicio_id==1){
                $desayuno++;
            }elseif($value->servicio_id==2){
                $almuerzo++;
            }elseif($value->servicio_id==3){
                $cena++;
            }
        }

        $resultado = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                    ->whereNull('detallepedido.deleted_at')
                    ->whereNull('pedido.deleted_at')
                    ->where(DB::raw('date(pedido.fecha)'),'=',DB::raw("'".$fecha."'"))
                    ->where('pedido.concesionaria_id','=',$comedor)
                    ->whereIn('detallepedido.servicio_id',[1,9])
                    ->select(DB::raw('count(detallepedido.id) as cantidad'))
                    ->first();
        if(!is_null($resultado)){
            $desayuno = $desayuno + $resultado->cantidad;
        }

        $resultado = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                    ->orderBy('pedido.solicitante','desc')
                    ->whereNull('detallepedido.deleted_at')
                    ->where(DB::raw('date(pedido.fecha)'),'=',DB::raw("'".$fecha."'"))
                    ->where('pedido.concesionaria_id','=',$comedor)
                    ->whereIn('detallepedido.servicio_id',[2,10])
                    ->select(DB::raw('count(detallepedido.id) as cantidad'))
                    ->first();
        if(!is_null($resultado)){
            $almuerzo = $almuerzo + $resultado->cantidad;
        }

        $resultado = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                    ->orderBy('pedido.solicitante','desc')
                    ->whereNull('detallepedido.deleted_at')
                    ->where(DB::raw('date(pedido.fecha)'),'=',DB::raw("'".$fecha."'"))
                    ->where('pedido.concesionaria_id','=',$comedor)
                    ->whereIn('detallepedido.servicio_id',[3,11])
                    ->select(DB::raw('count(detallepedido.id) as cantidad'))
                    ->first();
        if(!is_null($resultado)){
            $cena = $cena + $resultado->cantidad;
        }

        return date("d/m/Y",strtotime($fecha))." >> Desayuno: ".$desayuno." | Almuerzo: ".$almuerzo." | Cena: ".$cena;

    }
}
