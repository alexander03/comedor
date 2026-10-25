<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Pedido;
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

class PedidoreporteController extends Controller
{
    protected $folderview      = 'app.pedidoreporte';
    protected $tituloAdmin     = 'Detalle Pedido';
    protected $tituloRegistrar = 'Reporte de Pedido';
    protected $tituloModificar = 'Modificar Caja';
    protected $tituloEliminar  = 'Eliminar Caja';
    protected $rutas           = array('create' => 'pedidoreporte.create', 
            'index'  => 'pedidoreporte.index',
            'search'   => 'pedidoreporte.buscar',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Area';
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
            $idServicio[] = 13;
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

        $resultado        = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                            ->orderBy('pedido.solicitante','desc')
                            ->whereNull('detallepedido.deleted_at')
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
                            ->select('pedido.id','pedido.solicitante','pedido.lugar',DB::raw('count(detallepedido.id) as cantidad'),'pedido.observacion',DB::raw('\'individual\' as tipo'))
                            ->groupBy('pedido.id')
                            ->groupBy('pedido.solicitante')
                            ->groupBy('pedido.lugar')
                            ->groupBy('pedido.observacion');
        if($fechainicio!=""){
            $resultado = $resultado->where(DB::raw('date(pedido.fecha)'),'>=',DB::raw("'".$fechainicio."'"));
        }
        if($fechafin!=""){
            $resultado = $resultado->where(DB::raw('date(pedido.fecha)'),'<=',DB::raw("'".$fechafin."'"));
        }
        if($concesionaria_id!="0"){
            $resultado = $resultado->where('pedido.concesionaria_id','=',$concesionaria_id);
        }

        $grupal        = Pedido::join('detallepedidogrupal','detallepedidogrupal.pedido_id','=','pedido.id')
                            ->orderBy('pedido.solicitante','desc')
                            ->whereNull('detallepedidogrupal.deleted_at')
                            ->where(function($sql) use($desayuno,$almuerzo,$cena,$refrigerio,$frio){
                                if($desayuno=="0"){
                                    $sql->orWhere(function($sql2){
                                        $sql2->whereRaw('detallepedidogrupal.carnico_id = 3 and detallepedidogrupal.servicio_id=1');
                                    });
                                }elseif($desayuno!=""){
                                    $sql->orWhere(function($sql2) use($desayuno){
                                        $sql2->whereRaw('detallepedidogrupal.servicio_id = 1 and detallepedidogrupal.carnico_id = '.$desayuno);
                                    });
                                }
                                if($almuerzo=="0"){
                                    $sql->orWhere(function($sql2){
                                        $sql2->whereRaw('detallepedidogrupal.carnico_id in (3,4) and detallepedidogrupal.servicio_id=2');
                                    });
                                }elseif($almuerzo!=""){
                                    $sql->orWhere(function($sql2) use($almuerzo){
                                        $sql2->whereRaw('detallepedidogrupal.servicio_id = 2 and detallepedidogrupal.carnico_id = '.$almuerzo);
                                    });
                                }
                                if($cena=="0"){
                                    $sql->orWhere(function($sql2){
                                        $sql2->whereRaw('detallepedidogrupal.carnico_id in (3,4) and detallepedidogrupal.servicio_id=3');
                                    });
                                }elseif($cena!=""){
                                    $sql->orWhere(function($sql2) use($cena){
                                        $sql2->whereRaw('detallepedidogrupal.servicio_id = 3 and detallepedidogrupal.carnico_id = '.$cena);
                                    });
                                }
                                if($refrigerio=="0"){
                                    $sql->orWhere(function($sql2){
                                        $sql2->whereRaw('detallepedidogrupal.carnico_id in (23,24,25,26) and detallepedidogrupal.servicio_id=4');
                                    });
                                }elseif($refrigerio!=""){
                                    $sql->orWhere(function($sql2) use($refrigerio){
                                        $sql2->whereRaw('detallepedidogrupal.servicio_id = 4 and detallepedidogrupal.carnico_id = '.$refrigerio);
                                    });
                                }
                                if($frio=="0"){
                                    $sql->orWhereIn('detallepedidogrupal.carnico_id',[20,21,22]);
                                }elseif($frio!=""){
                                    $sql->orWhere('detallepedidogrupal.carnico_id','=',$frio);
                                }
                            })
                            ->select('pedido.id','pedido.solicitante','pedido.lugar',DB::raw('sum(detallepedidogrupal.cantidad) as cantidad'),'pedido.observacion',DB::raw('\'grupal\' as tipo'))
                            ->groupBy('pedido.id')
                            ->groupBy('pedido.solicitante')
                            ->groupBy('pedido.lugar')
                            ->groupBy('pedido.observacion');
        if($fechainicio!=""){
            $grupal = $grupal->where(DB::raw('date(pedido.fecha)'),'>=',DB::raw("'".$fechainicio."'"));
        }
        if($fechafin!=""){
            $grupal = $grupal->where(DB::raw('date(pedido.fecha)'),'<=',DB::raw("'".$fechafin."'"));
        }
        if($concesionaria_id!="0"){
            $grupal = $grupal->where('pedido.concesionaria_id','=',$concesionaria_id);
        }
        $lista            = $grupal->union($resultado)->get();
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
        $cboDesayuno = array("0"=>"Todos","-1"=>"Ninguno") + Carnico::whereIn('id',[3])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboAlmuerzo = array("0"=>"Todos","-1"=>"Ninguno") + Carnico::whereIn('id',[3,4])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboCena = array("0"=>"Todos","-1"=>"Ninguno") + Carnico::whereIn('id',[3,4])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboRefrigerio = array("0"=>"Todos","-1"=>"Ninguno") + Carnico::whereIn('id',[23,24,25,26])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboFrio = array("0"=>"Todos","-1"=>"Ninguno") + Carnico::whereIn('id',[20,21,22])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
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