<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Pedido;
use App\DetallePedido;
use App\Servicio;
use App\Carnico;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Excel;
use App\Exports\PedidoExport;
use App\Curl;

class PedidoController extends Controller
{
    protected $folderview      = 'app.pedido';
    protected $tituloAdmin     = 'Pedidos';
    protected $tituloRegistrar = 'Registrar area';
    protected $tituloModificar = 'Modificar area';
    protected $tituloEliminar  = 'Eliminar pedido';
    protected $rutas           = array('create' => 'pedido.create', 
            'edit'     => 'pedido.edit', 
            'delete'   => 'pedido.eliminar',
            'search'   => 'pedido.buscar',
            'index'    => 'pedido.index',
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
        $resultado        = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                            ->join('servicio','servicio.id','=','detallepedido.servicio_id')
                            ->join('carnico','carnico.id','=','detallepedido.carnico_id')
                            ->join('campamento','campamento.id','=','pedido.concesionaria_id')
                            ->where(function($sql) use($desayuno,$almuerzo,$cena,$refrigerio,$frio){
                                if($desayuno=="0"){
                                    $sql->orWhere(function($sql2){
                                        $sql2->whereRaw('detallepedido.carnico_id = 3 and detallepedido.servicio_id=1');
                                    });
                                }elseif($desayuno!=""){
                                    $sql->orWhere(function($sql2) use($desayuno){
                                        $sql2->whereRaw('detallepedido.servicio_id = 1 and detallepedido.carnico_id = '.$desayuno);
                                    });
                                }
                                if($almuerzo=="0"){
                                    $sql->orWhere(function($sql2){
                                        $sql2->whereRaw('detallepedido.carnico_id in (3,4) and detallepedido.servicio_id=2');
                                    });
                                }elseif($almuerzo!=""){
                                    $sql->orWhere(function($sql2) use($almuerzo){
                                        $sql2->whereRaw('detallepedido.servicio_id = 2 and detallepedido.carnico_id = '.$almuerzo);
                                    });
                                }
                                if($cena=="0"){
                                    $sql->orWhere(function($sql2){
                                        $sql2->whereRaw('detallepedido.carnico_id in (3,4) and detallepedido.servicio_id=3');
                                    });
                                }elseif($cena!=""){
                                    $sql->orWhere(function($sql2) use($cena){
                                        $sql2->whereRaw('detallepedido.servicio_id = 3 and detallepedido.carnico_id = '.$cena);
                                    });
                                }
                                if($refrigerio=="0"){
                                    $sql->orWhere(function($sql2){
                                        $sql2->whereRaw('detallepedido.carnico_id in (23,24,25,26) and detallepedido.servicio_id=4');
                                    });
                                }elseif($refrigerio!=""){
                                    $sql->orWhere(function($sql2) use($refrigerio){
                                        $sql2->whereRaw('detallepedido.servicio_id = 4 and detallepedido.carnico_id = '.$refrigerio);
                                    });
                                }
                                if($frio=="0"){
                                    $sql->orWhereIn('detallepedido.carnico_id',[20,21,22]);
                                }elseif($frio!=""){
                                    $sql->orWhere('detallepedido.carnico_id','=',$frio);
                                }
                            })
                            ->whereNull('detallepedido.deleted_at')
                            ->orderBy('pedido.fecha','desc')
                            ->orderBy('pedido.id','desc')
                            ->select('pedido.*','servicio.nombre as servicio','carnico.nombre as carnico','detallepedido.dieta','detallepedido.empresa','detallepedido.persona','detallepedido.id as detallepedido_id','campamento.nombre as campamento');
        if($fechainicio!=""){
            $resultado = $resultado->where(DB::raw('date(pedido.fecha)'),'>=',DB::raw("'".$fechainicio."'"));
        }
        if($fechafin!=""){
            $resultado = $resultado->where(DB::raw('date(pedido.fecha)'),'<=',DB::raw("'".$fechafin."'"));
        }
        if($solicitante!=""){
            $resultado = $resultado->where('pedido.solicitante','like',DB::raw("'%".$solicitante."%'"));
        }
        if($lugar!="0"){
            $resultado = $resultado->where('pedido.lugar_id','=',$lugar);
        }
        if($persona!=""){
            $resultado = $resultado->where('detallepedido.persona','like',DB::raw("'%".$persona."%'"));
        }
        if($carnico!="0"){
            $resultado = $resultado->where('detallepedido.carnico_id','=',$carnico);
        }
        if($empresa!=""){
            $resultado = $resultado->where('detallepedido.empresa','like',DB::raw("'%".$empresa."%'"));
        }
        if($dieta!=""){
            $resultado = $resultado->where('detallepedido.dieta','like',DB::raw("'".$dieta."'"));
        }
        if($concesionaria_id!=""){
            $resultado = $resultado->where('pedido.concesionaria_id','=',$concesionaria_id);
        }
        $lista            = $resultado->get();
        $user     = Auth::User();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nro', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Solicitante', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Lider', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Lugar', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Observacion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Persona', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Servicio', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Op. Carnico', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Empresa', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Campamento', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha Reg.', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Dieta', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3');
        
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

            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta','user'));
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
        $cboDesayuno = array("0"=>"Todos","-1"=>"Ninguno") + Carnico::whereIn('id',[3])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboAlmuerzo = array("0"=>"Todos","-1"=>"Ninguno") + Carnico::whereIn('id',[3,4])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboCena = array("0"=>"Todos","-1"=>"Ninguno") + Carnico::whereIn('id',[3,4])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboRefrigerio = array("0"=>"Todos","-1"=>"Ninguno") + Carnico::whereIn('id',[23,24,25,26])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboFrio = array("0"=>"Todos","-1"=>"Ninguno") + Carnico::whereIn('id',[20,21,22])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
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
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboDesayuno','cboAlmuerzo','cboCena','cboRefrigerio','cboCarnico','cboDieta','cboLugar','user','cboFrio'));
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
        $existe = Libreria::verificarExistencia($id, 'detallepedido');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $user     = Auth::User();
            $obj = DetallePedido::find($id);
            $pedido_id = $obj->pedido_id;
            $obj->userdeleted_id = $user->id;
            $obj->save();
            $obj->delete();
            $detalle = DetallePedido::where('pedido_id','=',$pedido_id)->get();
            if(count($detalle)==0){
                $pedido = Pedido::find($pedido_id);
                $pedido->delete();
            }
        });
        
        $cc = new Curl(false,'https://sistemasolmos.pe/comedor/PHPMailer/notificacionDetallePedidoEliminado.php');
        $url = 'https://sistemasolmos.pe/comedor/PHPMailer/notificacionDetallePedidoEliminado.php?id='.$id;
        $Page = $cc->get($url,array());
        $datos = json_decode($Page);

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
        $existe = Libreria::verificarExistencia($id, 'detallepedido');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = DetallePedido::find($id);
        $entidad  = 'Pedido';
        $formData = array('route' => array('pedido.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
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
        $desayuno         = Libreria::getParam($request->input('desayuno'));
        $almuerzo         = Libreria::getParam($request->input('almuerzo'));
        $cena         = Libreria::getParam($request->input('cena'));
        $refrigerio         = Libreria::getParam($request->input('refrigerio'));
        $frio         = Libreria::getParam($request->input('frio'));
        $carnico         = Libreria::getParam($request->input('carnico'));
        $empresa         = Libreria::getParam($request->input('empresa'));
        $concesionaria_id         = Libreria::getParam($request->input('concesionaria_id'));
        
        return Excel::download(new PedidoExport($fechainicio,$fechafin,$solicitante,$lugar,$persona,$desayuno,$almuerzo,$cena,$refrigerio,$frio,$carnico,$empresa,$concesionaria_id), 'ReportePedido.xlsx');
    }
}
