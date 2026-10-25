<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Comida;
use App\Person;
use App\Comedor;
use App\Pedido;
use App\Servicio;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Auth;
use Excel;
use App\Exports\ComidaExport;
use App\Sucursal;
use App\Curl;
use App\Configuracion;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ComidareporteController extends Controller
{
    protected $folderview      = 'app.comidareporte';
    protected $tituloAdmin     = 'Servicios de Alimentación';
    protected $tituloRegistrar = 'Servicios de Alimentación';
    protected $tituloModificar = 'Modificar Caja';
    protected $tituloEliminar  = 'Eliminar Servicio';
    protected $rutas           = array('create' => 'comidareporte.create', 
            'index'  => 'comidareporte.index',
            'search'  => 'comidareporte.buscar',
            'delete' => 'comidareporte.eliminar',
        );

    public function __construct()
    {
        $this->middleware('auth');
        define("DESAYUNO_INICIO", trim(Configuracion::where("nombre", "=", "DESAYUNO HORA INI")->first()->valor));
        define("DESAYUNO_FIN", trim(Configuracion::where("nombre", "=", "DESAYUNO HORA FIN")->first()->valor));
        define("ALMUERZO_INICIO", trim(Configuracion::where("nombre", "=", "ALMUERZO HORA INI")->first()->valor));
        define("ALMUERZO_FIN", trim(Configuracion::where("nombre", "=", "ALMUERZO HORA FIN")->first()->valor));
        define("CENA_INICIO", trim(Configuracion::where("nombre", "=", "CENA HORA INI")->first()->valor));
        define("CENA_FIN", trim(Configuracion::where("nombre", "=", "CENA HORA FIN")->first()->valor));
    }

    public function buscar(Request $request)
    {
        Comida::where('situacion','like','P')->delete();
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Comidareporte';
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $comedor       = Libreria::getParam($request->input('comedor'));
        $persona          = Libreria::getParam($request->input('persona'));
        $servicio         = Libreria::getParam($request->input('servicio'));
        $resultado        = Comida::join('comedor','comedor.id','=','comida.comedor_id')
                            ->join('servicio','servicio.id','=','comida.servicio_id')
                            ->orderBy('comida.fecha','desc')
                            ->select('comida.*','servicio.nombre as servicio2','comedor.descripcion as comedor2');
        if($fechainicio!=""){
            $resultado = $resultado->where(DB::raw('date(comida.fecha)'),'>=',DB::raw("'".$fechainicio."'"));
        }
        if($fechafin!=""){
            $resultado = $resultado->where(DB::raw('date(comida.fecha)'),'<=',DB::raw("'".$fechafin."'"));
        }
        if($persona!=""){
            $resultado = $resultado->where('comida.persona','like',DB::raw("'%".$persona."%'"));
        }
        if($comedor!="0"){
            $resultado = $resultado->where('comida.comedor_id','=',$comedor);
        }
        if($servicio!="0"){
            $resultado = $resultado->where('comida.servicio_id','=',$servicio);
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Persona', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Empresa', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Servicio', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Costo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Comedor', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Firma', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Usuario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3');
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
        $almuerzo1 = 0; $desayuno1 = 0;$cena1 = 0;
        if($servicio=="0" || $servicio==1){
            $res = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                        ->whereNull('detallepedido.deleted_at')
                        ->whereNull('pedido.deleted_at')
                        ->where(DB::raw('date(pedido.fecha)'),'=',DB::raw("'".$fechainicio."'"))
                        ->whereIn('detallepedido.servicio_id',[1,9]);
            if($comedor!="0"){
                $res = $res->where('pedido.concesionaria_id','=',$comedor);
            }
            $res = $res->select(DB::raw('count(detallepedido.id) as cantidad'))
                        ->first();
            if(!is_null($res)){
                $desayuno1 = $desayuno1 + $res->cantidad;
            }
        }

        if($servicio=="0" || $servicio==2){
            $res = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                        ->orderBy('pedido.solicitante','desc')
                        ->whereNull('detallepedido.deleted_at')
                        ->where(DB::raw('date(pedido.fecha)'),'=',DB::raw("'".$fechainicio."'"))
                        ->whereIn('detallepedido.servicio_id',[2,10]);
            if($comedor!="0"){
                $res = $res->where('pedido.concesionaria_id','=',$comedor);
            }
            $res = $res->select(DB::raw('count(detallepedido.id) as cantidad'))
                        ->first();
            if(!is_null($res)){
                $almuerzo1 = $almuerzo1 + $res->cantidad;
            }
        }

        if($servicio=="0" || $servicio==3){
            $res = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                        ->orderBy('pedido.solicitante','desc')
                        ->whereNull('detallepedido.deleted_at')
                        ->where(DB::raw('date(pedido.fecha)'),'=',DB::raw("'".$fechainicio."'"))
                        ->whereIn('detallepedido.servicio_id',[3,11]);
            if($comedor!="0"){
                $res = $res->where('pedido.concesionaria_id','=',$comedor);
            }
            $res = $res->select(DB::raw('count(detallepedido.id) as cantidad'))
                        ->first();
            if(!is_null($res)){
                $cena1 = $cena1 + $res->cantidad;
            }
        }
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $ruta             = $this->rutas;
        if (count($lista) > 0) {
            $user = Auth::user();
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));

            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta','user','desayuno','almuerzo','cena','fechainicio','fechafin','desayuno1','almuerzo1','cena1'));
        }
        return view($this->folderview.'.list')->with(compact('lista', 'entidad'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entidad          = 'Comidareporte';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $titulo_registrar = $this->tituloRegistrar;
        $sucursales = Sucursal::all();
        $cboServicio = array('' => '--Seleccione--') + array('0' => 'Todos') + Servicio::whereIn('id',[1,2,3])->orderBy('id','asc')->pluck('nombre','id')->toArray();
        $cboComedor = array('' => '--Seleccione--') + array('0' => 'Todos') + Comedor::orderBy('id','asc')->pluck('descripcion','id')->toArray();
        $user = Auth::user();
        return view($this->folderview.'.admin')->with(compact('sucursales','entidad', 'title', 'ruta', 'user','cboServicio','cboComedor','titulo_registrar'));
    }

    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Comidareporte';
        $comida  = null;
        $formData     = array('comidareporte.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        $cboServicio = array('' => 'Seleccione') + Servicio::whereIn('id',[1,2,3])->orderBy('id','asc')->pluck('nombre','id')->toArray();
        $cboComedor = array('' => 'Seleccione') + Comedor::orderBy('id','asc')->pluck('descripcion','id')->toArray();
        return view($this->folderview.'.mant')->with(compact('comida', 'formData', 'entidad', 'boton', 'listar','cboComedor','cboServicio'));
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
                'fecha' => 'required',
                'servicio_id' => 'required',
                'comedor_id' => 'required',
                'persona' => 'required'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $comida = Comida::where(DB::raw('cast(fecha as date)'),'=',date("Y-m-d",strtotime($request->input('fecha'))))->where('persona_id','=',$request->input('persona_id'))->where('personawp_id','=',$request->input('personawp_id'))->where('servicio_id','=',$request->input('servicio_id'))->first();
        if(!is_null($comida)){
            return json_encode(array("fecha"=>["Ya se registro servicio para el comensal"]));
            exit();
        }

        $now = strtotime('now');
        $desayunoinicio = strtotime(date("Y-m-d ").DESAYUNO_INICIO.":00");
        $desayunofin = strtotime(date("Y-m-d ").DESAYUNO_FIN.":00");
        $almuerzoinicio = strtotime(date("Y-m-d ").ALMUERZO_INICIO.":00");
        $almuerzofin = strtotime(date("Y-m-d ").ALMUERZO_FIN.":00");
        $cenainicio = strtotime(date("Y-m-d ").CENA_INICIO.":00");
        $cenafin = strtotime(date("Y-m-d ").CENA_FIN.":00");
        $servicio2_id=array();
        if($now>=$desayunoinicio && $now<=$desayunofin){
            $tipo="DESAYUNO";
            $servicio_id=1;
            $servicio2_id[]=1;
            $servicio2_id[]=4;
            $servicio2_id[]=9;
        }elseif($now>=$almuerzoinicio && $now<=$almuerzofin){
            $tipo="ALMUERZO";
            $servicio_id=2;
            $servicio2_id[]=2;
            $servicio2_id[]=5;
            $servicio2_id[]=7;
            $servicio2_id[]=10;
        }elseif($now>=$cenainicio && $now<=$cenafin){
            $tipo="CENA";
            $servicio_id=3;
            $servicio2_id[]=3;
            $servicio2_id[]=6;
            $servicio2_id[]=8;
            $servicio2_id[]=11;
        }else{
            $tipo='-';
            $servicio_id = 0;
            $servicio2_id[]=0;
        }

        if($servicio_id==$request->input('servicio_id')){
            return json_encode(array("fecha"=>["No se puede registrar ".$tipo." en pleno horario regular."]));
            exit();
        }

        $pedido = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                            ->join('servicio','detallepedido.servicio_id','=','servicio.id')
                            ->where(DB::raw('cast(pedido.fecha as date)'),'=',date("Y-m-d"))
                            ->whereIn('detallepedido.servicio_id',$servicio2_id)
                            ->where('detallepedido.persona_id','=',$request->input('persona_id'))
                            ->where('detallepedido.personawp_id','=',$request->input('personawp_id'))
                            ->whereNull('detallepedido.deleted_at')
                            ->select('servicio.nombre as servicio')
                            ->first();
        if(!is_null($pedido)){
            return json_encode(array("fecha"=>["No puede registrar debido a que solicito un ".str_replace("REFRIG.", "TRANSPORTADO", strtoupper($pedido->servicio))]));
            exit();
        }

        $dat = array();
        $user = Auth::user();
        $error = DB::transaction(function() use($request,&$dat,$user){
            $servicio = Servicio::find($request->input('servicio_id'));
            $obj       = new Comida();
            $obj->persona_id = $request->input('persona_id');
            $obj->personawp_id = $request->input('personawp_id');
            $obj->fecha = str_replace("T", " ", $request->input('fecha'));
            $obj->servicio_id = $request->input('servicio_id');
            $obj->comedor_id = $request->input('comedor_id');
            $obj->persona = $request->input('persona');
            $obj->costo = $servicio->costo;
            $obj->ua_id = $request->input('ua_id');
            $obj->ua = $request->input('ua');
            $obj->user_id = $user->id;
            $obj->save();

            $dat[0] = array("respuesta" => "OK", "comida_id" => $obj->id, 'accion' => 'store');
        });

        $this->generatePDFSaveTicket($dat[0]["comida_id"]);

        $cc = new Curl(false,'https://h2olmos.pe/comedor/PHPMailer/notificacionAlimentacion.php');
        $url = 'https://h2olmos.pe/comedor/PHPMailer/notificacionAlimentacion.php?id='.$dat[0]["comida_id"];
        $Page = $cc->get($url,array());
        $datos = json_decode($Page);

        return is_null($error) ? json_encode($dat) : $error;
    }


    public function show($id)
    {
        //
    }

    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'comida');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Comida::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'comida');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Comida::find($id);
        $entidad  = 'Comidareporte';
        $formData = array('route' => array('comidareporte.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function excel(Request $request){
        setlocale(LC_TIME, 'spanish');
        return Excel::download(new ComidaExport($request->input('fechainicio'),$request->input('fechafin'),$request->input('persona'),$request->input('comedor'),$request->input('servicio')), 'ReporteServicioAlimentacion.xlsx');
    }

    public function buscarPersona(Request $request){
        $dni = $request->input('dni');
        $consulta = "select p.id, concat(p.nombres,' ',p.apellidos) as descripcion,concat(p.nombres,' ',p.apellidos) as persona,contratista as empresa,empresa_id,contratista_id,correo,p.ua_id,concat(ua.codigo,' ',ua.descripcion) as ua
        from persona as p 
        left join ua on ua.id=p.ua_id
        where p.deleted_at IS NULL AND
            (p.dni LIKE '{$dni}' or p.otros like '".strtoupper($dni)."')";
        $res = DB::connection('mysql3')->select($consulta);
        $band = false;
        if(count($res)>0){
            $person = $res[0];
            $nombres = $person->descripcion;
            $persona_id = $person->id;
            $personawp_id = 0;
            $ua_id = $person->ua_id;
            $ua = $person->ua;
            $band = true;
        }else{
            $lista = DB::connection('mysql2')->select("select * from personal where activo='1' and docid like '".$dni."'");
            if(count($lista)>0){
                $person = $lista[0];
                $nombres = $person->nombre;
                $personawp_id = $person->id;
                $persona_id = 0;
                $ua_id = 0;
                $ua = "";
                $band=true;
            }
        }
        if($band){
            return json_encode(array("ua_id"=>$ua_id,"ua"=>$ua,"person"=>$person,"persona_id"=>$persona_id,"personawp_id"=>$personawp_id,"nombres"=>$nombres));
        }else{
            return json_encode(array());
        }
    }

    public function personautocompletar($searching){
        $consulta = "select p.id, concat(p.nombres,' ',p.apellidos) as descripcion,concat(p.nombres,' ',p.apellidos) as persona,contratista as empresa,empresa_id,contratista_id,correo,p.ua_id,concat(ua.codigo,' ',ua.descripcion) as ua,p.dni,p.otros
        from persona as p 
        left join ua on ua.id=p.ua_id
        where p.deleted_at IS NULL AND
            (concat(p.nombres,' ',p.apellidos) like '%".strtoupper($searching)."%')";
        $res = DB::connection('mysql3')->select($consulta);
        $data = array();
        if(count($res)>0){
            foreach ($res as $key => $value) {
                $person = $value;
                $nombres = $person->descripcion;
                $persona_id = $person->id;
                $personawp_id = 0;
                $ua_id = $person->ua_id;
                $ua = $person->ua;
                $dni = trim($person->dni.''.$person->otros);
                $data[] = array("value"=>$nombres,"ua_id"=>$ua_id,"ua"=>$ua,"person"=>$person,"persona_id"=>$persona_id,"personawp_id"=>$personawp_id,"nombres"=>$nombres,"dni"=>$dni);
            }
        }
        $lista = DB::connection('mysql2')->select("select * from personal where activo='1' and nombre like '%".$searching."%'");
        if(count($lista)>0){
            foreach ($lista as $key => $value) {
                $person = $value;
                $nombres = $person->nombre;
                $personawp_id = $person->id;
                $persona_id = 0;
                $ua_id = 0;
                $ua = "";
                $band=true;
                $dni = $person->docid;
                $data[] = array("value"=>$nombres,"ua_id"=>$ua_id,"ua"=>$ua,"person"=>$person,"persona_id"=>$persona_id,"personawp_id"=>$personawp_id,"nombres"=>$nombres,"dni"=>$dni);
            }
        }
        return json_encode($data);
    }

    public function generatePDFSaveTicket($id) {
        $comida = Comida::find($id);
        $namefile = 'Ticket-'.$comida->id.'.pdf'; 
        $pdf = PDF::loadView('app.comida.pdf.template',compact('comida','namefile'))->setPaper(array(0, 0, 200,300), 'landscape');  
        //return $pdf->stream($namefile);
        $pdf->save('pdf/'.$namefile);
    }

    public function generatePDFTicket($id) {
        $comida = Comida::find($id);
        $namefile = 'Ticket-'.$comida->id.'.pdf'; 
        $pdf = PDF::loadView('app.comida.pdf.template',compact('comida','namefile'))->setPaper(array(0, 0, 200,300), 'landscape');  
        return $pdf->stream($namefile);
    }
}