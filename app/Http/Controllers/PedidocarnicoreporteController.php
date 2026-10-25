<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\PedidoCarnico;
use App\Servicio;
use App\Carnico;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Auth;
use Excel;
use App\Exports\ComidaExport;
use App\Sucursal;

class PedidocarnicoreporteController extends Controller
{
    protected $folderview      = 'app.pedidocarnicoreporte';
    protected $tituloAdmin     = 'Detalle Cambio Carnico Comedor';
    protected $tituloRegistrar = 'Reporte de Pedido';
    protected $tituloModificar = 'Modificar Caja';
    protected $tituloEliminar  = 'Eliminar Caja';
    protected $rutas           = array('create' => 'pedidocarnicoreporte.create', 
            'index'  => 'pedidocarnicoreporte.index',
            'search'   => 'pedidocarnicoreporte.buscar',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Pedidoreporte';
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $almuerzo         = Libreria::getParam($request->input('almuerzo'));
        $cena             = Libreria::getParam($request->input('cena'));
        $desayuno         = Libreria::getParam($request->input('desayuno'));
        $refrigerio       = Libreria::getParam($request->input('refrigerio'));
        $frio             = Libreria::getParam($request->input('frio'));
        $concesionaria_id      = Libreria::getParam($request->input('concesionaria_id'));

        $idServicio = array();
        if($desayuno=="0"){
            $idServicio[] = 1;
            $idServicio[] = 9;
        }elseif($desayuno!=""){
            $idServicio[] = $desayuno;
        }

        if($almuerzo=="0"){
            $idServicio[] = 2;
            $idServicio[] = 10;
        }elseif($almuerzo!=""){
            $idServicio[] = $almuerzo;
        }

        if($cena=="0"){
            $idServicio[] = 3;
            $idServicio[] = 11;
        }elseif($cena!=""){
            $idServicio[] = $cena;
        }

        if($refrigerio=="0"){
            $idServicio[] = 4;
            $idServicio[] = 7;
            $idServicio[] = 8;
            $idServicio[] = 12;
        }elseif($refrigerio!=""){
            $idServicio[] = $refrigerio;
        }

        if($frio=="0"){
            $idServicio[] = 5;
            $idServicio[] = 6;
        }elseif($frio!=""){
            $idServicio[] = $frio;
        }

        $listaServicio = Servicio::whereIn('id',$idServicio)->orderBy('nombre','asc')->get();

        $resultado        = PedidoCarnico::join('detallepedidocarnico','detallepedidocarnico.pedidocarnico_id','=','pedidocarnico.id')
                            ->orderBy('pedidocarnico.solicitante','desc')
                            ->whereNull('detallepedidocarnico.deleted_at')
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
                                    $sql->orWhereIn('detallepedidocarnico.servicio_id',[4,7,8,12]);
                                }elseif($refrigerio!=""){
                                    $sql->orWhere('detallepedidocarnico.servicio_id','=',$refrigerio);
                                }
                                if($frio=="0"){
                                    $sql->orWhereIn('detallepedidocarnico.servicio_id',[5,6]);
                                }elseif($frio!=""){
                                    $sql->orWhere('detallepedidocarnico.servicio_id','=',$frio);
                                }
                            })
                            ->select('pedidocarnico.id','detallepedidocarnico.persona',DB::raw('count(detallepedidocarnico.id) as cantidad'),'pedidocarnico.observacion',DB::raw('\'individual\' as tipo'))
                            ->groupBy('pedidocarnico.id')
                            ->groupBy('detallepedidocarnico.persona')
                            ->groupBy('pedidocarnico.lugar')
                            ->groupBy('pedidocarnico.observacion');
        if($fechainicio!=""){
            $resultado = $resultado->where(DB::raw('date(pedidocarnico.fecha)'),'>=',DB::raw("'".$fechainicio."'"));
        }
        if($fechafin!=""){
            $resultado = $resultado->where(DB::raw('date(pedidocarnico.fecha)'),'<=',DB::raw("'".$fechafin."'"));
        }
        if($concesionaria_id!="0"){
            $resultado = $resultado->where('pedidocarnico.concesionaria_id','=',$concesionaria_id);
        }

        $lista            = $resultado->get();
        $ruta             = $this->rutas;
        return view($this->folderview.'.list')->with(compact('lista', 'entidad','fechainicio','fechafin','almuerzo','desayuno','cena','refrigerio','frio','listaServicio'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entidad          = 'Pedidoreporte';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $user = Auth::user();
        $cboDesayuno = array("0"=>"Todos","-1"=>"Ninguno") + Servicio::whereIn('id',[1,9])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboAlmuerzo = array("0"=>"Todos","-1"=>"Ninguno") + Servicio::whereIn('id',[2,10])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboCena = array("0"=>"Todos","-1"=>"Ninguno") + Servicio::whereIn('id',[3,11])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboRefrigerio = array("0"=>"Todos","-1"=>"Ninguno") + Servicio::whereIn('id',[4,7,8,12])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboFrio = array("0"=>"Todos","-1"=>"Ninguno") + Servicio::whereIn('id',[5,6])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'ruta', 'user','cboDesayuno','cboAlmuerzo','cboCena','cboRefrigerio','cboFrio'));
    }


    public function show($id)
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function update(Request $request)
    {
        //
    }


    public function excelDetalle(Request $request){
        setlocale(LC_TIME, 'spanish');
        return Excel::download(new ComidaExport($request->input('fechainicio'),$request->input('fechafin'),$request->input('modalidad')), 'ReporteComida.xlsx');
    }

}