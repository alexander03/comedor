<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\PedidoCarnico;
use App\DetallePedidoCarnico;
use App\Servicio;
use App\Carnico;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Excel;
use App\Exports\PedidoCarnicoExport;

class PedidoCarnicoController extends Controller
{
    protected $folderview      = 'app.pedidocarnico';
    protected $tituloAdmin     = 'Cambio Carnico';
    protected $tituloRegistrar = 'Registrar area';
    protected $tituloModificar = 'Modificar area';
    protected $tituloEliminar  = 'Eliminar cambio carnico';
    protected $rutas           = array('create' => 'pedidocarnico.create', 
            'edit'     => 'pedidocarnico.edit', 
            'delete'   => 'pedidocarnico.eliminar',
            'search'   => 'pedidocarnico.buscar',
            'index'    => 'pedidocarnico.index',
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
        $entidad          = 'Pedido';
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $solicitante         = Libreria::getParam($request->input('solicitante'));
        $lugar         = Libreria::getParam($request->input('lugar'));
        $persona         = Libreria::getParam($request->input('persona'));
        $almuerzo         = Libreria::getParam($request->input('almuerzo'));
        $cena             = Libreria::getParam($request->input('cena'));
        $desayuno         = Libreria::getParam($request->input('desayuno'));
        $refrigerio       = Libreria::getParam($request->input('refrigerio'));
        $frio       = Libreria::getParam($request->input('frio'));
        $carnico         = Libreria::getParam($request->input('carnico'));
        $empresa         = Libreria::getParam($request->input('empresa'));
        $dieta         = Libreria::getParam($request->input('dieta'));
        $concesionaria_id         = Libreria::getParam($request->input('concesionaria_id'));
        $resultado        = PedidoCarnico::join('detallepedidocarnico','detallepedidocarnico.pedidocarnico_id','=','pedidocarnico.id')
                            ->join('servicio','servicio.id','=','detallepedidocarnico.servicio_id')
                            ->join('carnico','carnico.id','=','detallepedidocarnico.carnico_id')
                            ->where(function($sql) use($desayuno,$almuerzo,$cena,$refrigerio,$frio){
                                if($desayuno=="0"){
                                    $sql->orWhereIn('detallepedidocarnico.servicio_id',[1,9]);
                                }elseif($desayuno!=""){
                                    $sql->orWhere('detallepedidocarnico.servicio_id','=',$desayuno);
                                }
                                if($almuerzo=="0"){
                                    $sql->orWhereIn('detallepedidocarnico.servicio_id',[2,10]);
                                }elseif($almuerzo!=""){
                                    $sql->orWhere('detallepedidocarnico.servicio_id','=',$almuerzo);
                                }
                                if($cena=="0"){
                                    $sql->orWhereIn('detallepedidocarnico.servicio_id',[3,11]);
                                }elseif($cena!=""){
                                    $sql->orWhere('detallepedidocarnico.servicio_id','=',$cena);
                                }
                                if($refrigerio=="0"){
                                    $sql->orWhereIn('detallepedidocarnico.servicio_id',[4,7,8,12,13]);
                                }elseif($refrigerio!=""){
                                    $sql->orWhere('detallepedidocarnico.servicio_id','=',$refrigerio);
                                }
                                if($frio=="0"){
                                    $sql->orWhereIn('detallepedidocarnico.servicio_id',[5,6]);
                                }elseif($frio!=""){
                                    $sql->orWhere('detallepedidocarnico.servicio_id','=',$frio);
                                }
                            })
                            ->whereNull('detallepedidocarnico.deleted_at')
                            ->orderBy('pedidocarnico.fecha','desc')
                            ->orderBy('pedidocarnico.id','desc')
                            ->select('pedidocarnico.*','servicio.nombre as servicio','carnico.nombre as carnico','detallepedidocarnico.dieta','detallepedidocarnico.empresa','detallepedidocarnico.persona','detallepedidocarnico.id as detallepedidocarnico_id','detallepedidocarnico.servicio_id');
        if($fechainicio!=""){
            $resultado = $resultado->where(DB::raw('date(pedidocarnico.fecha)'),'>=',DB::raw("'".$fechainicio."'"));
        }
        if($fechafin!=""){
            $resultado = $resultado->where(DB::raw('date(pedidocarnico.fecha)'),'<=',DB::raw("'".$fechafin."'"));
        }
        if($solicitante!=""){
            $resultado = $resultado->where('pedidocarnico.solicitante','like',DB::raw("'%".$solicitante."%'"));
        }
        if($lugar!="0"){
            $resultado = $resultado->where('pedidocarnico.lugar_id','=',$lugar);
        }
        if($persona!=""){
            $resultado = $resultado->where('detallepedidocarnico.persona','like',DB::raw("'%".$persona."%'"));
        }
        if($carnico!="0"){
            $resultado = $resultado->where('detallepedidocarnico.carnico_id','=',$carnico);
        }
        if($empresa!=""){
            $resultado = $resultado->where('detallepedidocarnico.empresa','like',DB::raw("'%".$empresa."%'"));
        }
        if($dieta!=""){
            $resultado = $resultado->where('detallepedidocarnico.dieta','like',DB::raw("'".$dieta."'"));
        }
        if($concesionaria_id!=""){
            $resultado = $resultado->where('pedidocarnico.concesionaria_id','=',$concesionaria_id);
        }
        $lista            = $resultado->get();
        $user     = Auth::User();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Solicitante', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Lider', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Lugar', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Observacion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Persona', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Servicio', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Op. Carnico', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Empresa', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha Reg.', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Dieta', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $ruta             = $this->rutas;
        $almuerzo = 0; $desayuno=0; $cena=0; $refrigerio=0;
        foreach ($lista as $key => $value) {
            if($value->servicio_id==1 || $value->servicio_id==9){
                $desayuno++;
            }elseif($value->servicio_id==2 || $value->servicio_id==5 || $value->servicio_id==10){
                $almuerzo++;
            }elseif($value->servicio_id==3 || $value->servicio_id==6 || $value->servicio_id==11){
                $cena++;
            }else{
                $refrigerio++;
            }
        }

        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));

            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta','user','desayuno','almuerzo','cena','refrigerio'));
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
        $entidad          = 'Pedido';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboDesayuno = array("0"=>"Todos","-1"=>"Ninguno") + Servicio::whereIn('id',[1,9])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboAlmuerzo = array("0"=>"Todos","-1"=>"Ninguno") + Servicio::whereIn('id',[2,10])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboCena = array("0"=>"Todos","-1"=>"Ninguno") + Servicio::whereIn('id',[3,11])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboRefrigerio = array("0"=>"Todos","-1"=>"Ninguno") + Servicio::whereIn('id',[4,7,8,12,13])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboFrio = array("0"=>"Todos","-1"=>"Ninguno") + Servicio::whereIn('id',[5,6])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboCarnico = array("0"=>"Todos") + Carnico::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboDieta = array(""=>"Todos","SI"=>"SI","NO"=>"NO");
        $consulta = "select id, nombre as value from lugar where
            deleted_at IS NULL order by nombre asc";
        $res = DB::connection('mysql3')->select($consulta);
        $cboLugar = array("0"=>"Todos");
        foreach ($res as $key => $value) {
            $cboLugar += array($value->id => $value->value);
        }
        $user     = Auth::User();
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboDesayuno','cboAlmuerzo','cboCena','cboRefrigerio','cboFrio','cboCarnico','cboDieta','cboLugar','user'));
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
        $existe = Libreria::verificarExistencia($id, 'area');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $area  = Area::find($id);
        $entidad      = 'Area';
        $formData     = array('area.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('area', 'formData', 'entidad', 'boton', 'listar'));
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
        $existe = Libreria::verificarExistencia($id, 'area');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'descripcion' => 'required|max:60'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $obj       = Area::find($id);
            $obj->descripcion = $request->input('descripcion');
            $obj->orden = $request->input('orden');
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
        $existe = Libreria::verificarExistencia($id, 'detallepedidocarnico');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = DetallePedidoCarnico::find($id);
            $pedido_id = $obj->pedidocarnico_id;
            $obj->delete();
            $detalle = DetallePedidoCarnico::where('pedidocarnico_id','=',$pedido_id)->get();
            if(count($detalle)==0){
                $pedido = PedidoCarnico::find($pedido_id);
                $pedido->delete();
            }
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
        $existe = Libreria::verificarExistencia($id, 'detallepedidocarnico');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = DetallePedidoCarnico::find($id);
        $entidad  = 'Pedido';
        $formData = array('route' => array('pedidocarnico.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function excelLista(Request $request){
        setlocale(LC_TIME, 'spanish');
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $solicitante         = Libreria::getParam($request->input('solicitante'));
        $lugar         = Libreria::getParam($request->input('lugar'));
        $persona         = Libreria::getParam($request->input('persona'));
        $servicio         = Libreria::getParam($request->input('servicio'));
        $carnico         = Libreria::getParam($request->input('carnico'));
        $empresa         = Libreria::getParam($request->input('empresa'));
        $concesionaria_id         = Libreria::getParam($request->input('concesionaria_id'));
        
        return Excel::download(new PedidoCarnicoExport($fechainicio,$fechafin,$solicitante,$lugar,$persona,$servicio,$carnico,$empresa,$concesionaria_id), 'ReportePedidoCarnico.xlsx');
    }
}
