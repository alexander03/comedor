<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\Category;
use App\Producto;
use App\Promocion;
use App\Detallemovimiento;
use App\Pedido;
use App\PedidoCarnico;
use App\DetallePedido;
use App\DetallePedidoCarnico;
use App\DetallePedidoGrupal;
use App\Servicio;
use App\Carnico;
use App\Lugar;
use App\Campamento;
use App\Tarifa;
use App\Movilizacion;
use App\Locacion;
use App\Motivo;
use App\Configuracion;
use App\Comida;
use App\Desayuno;
use App\Almuerzo;
use App\Cena;
use App\Refrigerio;
use App\Proyectado;
use Illuminate\Support\Facades\DB;
use App\Librerias\Libreria;
use DateTime;
use App\Curl;

class WebServicesController extends Controller
{
    public function __construct()
    {
        define("DESAYUNO_INICIO", trim(Configuracion::where("nombre", "=", "DESAYUNO HORA INI")->first()->valor));
        define("DESAYUNO_FIN", trim(Configuracion::where("nombre", "=", "DESAYUNO HORA FIN")->first()->valor));
        define("ALMUERZO_INICIO", trim(Configuracion::where("nombre", "=", "ALMUERZO HORA INI")->first()->valor));
        define("ALMUERZO_FIN", trim(Configuracion::where("nombre", "=", "ALMUERZO HORA FIN")->first()->valor));
        define("CENA_INICIO", trim(Configuracion::where("nombre", "=", "CENA HORA INI")->first()->valor));
        define("CENA_FIN", trim(Configuracion::where("nombre", "=", "CENA HORA FIN")->first()->valor));
    }

    public function cargarMegaMenu(){
    	$categorias = Category::join("categoria","categoria.categoria_id","=","category.id")->whereNull("categoria.deleted_at")->orderBy("category.nombre","ASC")->select("category.*")->distinct("category.id")->get();
    	foreach ($categorias as $key => $value) {
    		$value->categorias = Categoria::join("producto","categoria.id","=","producto.categoria_id")->where("categoria.categoria_id","=",$value->id)->whereNull("producto.deleted_at")->select("categoria.*")->distinct("categoria.id")->get();
            foreach ($value->categorias as $key => $value2) {
                $value2->productos;
            }
    	}
    	return json_encode(array("categorias"=>$categorias));
    }

    public function principal(){
    	$detalles = Detallemovimiento::join("movimiento","movimiento.id","=","detallemovimiento.movimiento_id")->join("producto","producto.id","=","detallemovimiento.producto_id")->whereNull("producto.deleted_at")->where("movimiento.tipomovimiento_id","=","2")->whereNotIn('movimiento.situacion',['A'])->groupBy("detallemovimiento.producto_id")->select("detallemovimiento.producto_id",DB::raw('sum(detallemovimiento.cantidad) as sumcantidad'))->orderBy("sumcantidad","DESC")->LIMIT(10)->get();
    	foreach ($detalles as $key => $value) {
    		$value->producto = Producto::find($value->producto_id);
    		//$value->producto->marca;
    		//$value->producto->unidad;
    	}

        $detalles2 = Category::whereNotNull("orderweb")->orderBy("orderweb","ASC")->get();
        foreach ($detalles2 as $key => $value) {
         $value->categoria = Category::find($value->id);
        }
    	//return json_encode($detalles2);
    	return json_encode(array("productos"=>$detalles,"categorias"=>$detalles2));
    }

    public function catalogo(Request $request){
        $categorias = Category::join("categoria","categoria.categoria_id","=","category.id")->join("producto","categoria.id","=","producto.categoria_id")->whereNull("producto.deleted_at")->whereNull("categoria.deleted_at")->groupBy("category.id")->select("category.id", "category.nombre", DB::raw('count(category.id) as cantidad'))->orderBy("category.nombre","ASC")->get();
        foreach ($categorias as $key => $value) {
            $value->categorias =  Categoria::join("producto","categoria.id","=","producto.categoria_id")->where("categoria.categoria_id","=",$value->id)->whereNull("producto.deleted_at")->groupBy("categoria.id")->select("categoria.id", "categoria.nombre", DB::raw('count(categoria.id) as cantidad'))->orderBy("categoria.nombre","ASC")->get();
        }
    	return json_encode(array("categorias"=>$categorias));
    }

    public function buscarProducto(Request $request){
    	$categorias_id = explode(",", $request->input("categorias"));
    	$pagina = $request->input("page");

    	$resultado = Producto::orderBy("producto.nombre","ASC");
    	if($request->input("categorias")!= null){
    		$resultado = $resultado->whereIn("producto.categoria_id",$categorias_id);
    	}
    	$productos = $resultado->get();

    	$filas = "12";

    	$clsLibreria     = new Libreria();
        $paramPaginacion = $clsLibreria->generarPaginacion($productos, $pagina, $filas, "PRODUCTO");
        $paginacion      = $paramPaginacion['cadenapaginacion'];
        $paginaactual    = $paramPaginacion['nuevapagina'];
        $productos       = $resultado->paginate($filas);
        //$request->replace(array('page' => $paginaactual));

    	return json_encode(array("productos"=>$productos,"paginacion"=>$paginacion,"paginaactual"=>$paginaactual));
    }

    public function productoautocompletar(Request $request)
    {
    	$searching = $request->input("query");
        $resultado        = Producto::
        	where(function ($sql) use ($searching) {
                $sql->where("producto.nombre", 'LIKE', '%' . strtoupper($searching) . '%');
            })
            ->whereNull('producto.deleted_at')->orderBy('nombre', 'ASC');
        $list = $resultado->select('producto.*')->get();
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = array(
                'label' => trim($value->nombre),
                'id'    => $value->id,
                'name' => trim($value->nombre),
                'precio'   => number_format($value->precioventa,2),
                'descripcion'   => "",
                'imagen'   => $value->archivo,
            );
        }
        return json_encode($data);
    }

    public function producto(Request $request)
    {
    	$producto_id = $request->input("producto_id");
        $producto = Producto::find($producto_id);
        $producto->marca;
        $producto->unidad;
        $producto->categoria;
        $producto->categoria->categoriapadre;
        $producto->precioventa = number_format($producto->precioventa,2);

        $productos = Producto::where("categoria_id","=",$producto->categoria_id)->where("id","<>",$producto_id)->orderBy("producto.nombre")->limit(10)->get();
        return json_encode(array("producto"=>$producto,"productos"=>$productos));
    }

    public function registrarPedido(Request $request){
        
        $dat = array();

        try {
            $error = DB::transaction(function () use ($request,  &$dat) {
                //-------------------CREAR PEDIDO------------------------
                    $Pedido       = new Pedido();
                    $Pedido->cliente_id = 1;
                    $Pedido->nombre = Libreria::getParam($request->input('nombre'));
                    $Pedido->ruc = Libreria::getParam($request->input('ruc'));
                    $Pedido->dni = Libreria::getParam($request->input('dni'));
                    $Pedido->telefono = Libreria::getParam($request->input('telefono'));
                    $Pedido->direccion = Libreria::getParam($request->input('direccion'));
                    $Pedido->referencia = Libreria::getParam($request->input('referencia'));
                    $Pedido->detalle = Libreria::getParam($request->input('detalle'));
                    $Pedido->tipodocumento_id = $request->input('tipodocumento');
                    $Pedido->delivery = $request->input('delivery');
                    $Pedido->modopago = $request->input('modopago');
                    $Pedido->cantidadpago = $request->input('cantidadpago');
                    if($request->input('modopago')=='TARJETA'){
                        $Pedido->tarjeta =$request->input('tarjeta');
                    }
                    $Pedido->estado = "N";
                    
                    $Pedido->responsable_id = null;
                    $Pedido->sucursal_id = $request->input('sucursal_id');
                    //$Pedido->fechaaceptado =new DateTime();
                    $Pedido->subtotal = 0;
                    $Pedido->igv = 0;
                    $Pedido->total = 0; 
                    $Pedido->save();
                //---------------------FIN CREAR PEDIDO------------------------

                //---------------------DETALLES VENTA------------------------------
                    $detalles = json_decode($request->input('listProducto'));
                    $total = 0;
                    //dd($request);
                    foreach ($detalles as $detalle) {
                        $Detalle = new Detallepedido();
                        $Detalle->pedido_id = $Pedido->id;
                        $precioventa = 0;
                        if($detalle->tipo =="P"){
                            $productoOb = Producto::find($detalle->producto_id);
                            $precioventa = $productoOb->precioventa;
                            $Detalle->producto_id=$detalle->producto_id;
                        }else{
                            $productoOb = Promocion::find($detalle->producto_id);
                            $precioventa = $productoOb->precioventa;
                            $Detalle->promocion_id=$detalle->producto_id;
                        }
                        $Detalle->cantidad = $detalle->cantidad;
                        // $Detalle->precioventa = $detalle->precio;
                        $Detalle->precioventa = $precioventa;
                        $total = $total + $precioventa * $detalle->cantidad;
                        $Detalle->preciocompra = 0;
                        $Detalle->save();
                    }
                //-----------------------FIN DETALLES VENTA------------------------------

                    if ($request->input('tipodocumento') == "4" || $request->input('tipodocumento') == "3") { //FACTURA O BOLETA
                        $Pedido->subtotal = round($total / 1.18, 2); //82%
                        $Pedido->igv = round($total - $Pedido->subtotal, 2); //18%
                    } else { //TICKET
                        $Pedido->subtotal = $total;
                        $Pedido->igv = 0;
                    }
                    $Pedido->total = $total; 

                $dat[0] = array("respuesta" => "OK");
            });
        } catch (\Exception $e) {
            return json_encode(array("respuesta"=>"ERROR", "msg"=>$e->getMessage()));
        }
        return is_null($error) ? json_encode($dat[0]) : $error;
    }

    public function formularioComedor()
    {
        $entidad  = 'Comedor';
        $horainicio = Configuracion::find(4)->valor;
        $horafin = Configuracion::find(5)->valor;
        $fechainicio = date("Y-m-d")." ".Configuracion::find(4)->valor;
        $fechafin = date("Y-m-d")." ".Configuracion::find(5)->valor;
        $resultado        = Comida::join('comedor','comedor.id','=','comida.comedor_id')
                            ->join('servicio','servicio.id','=','comida.servicio_id')
                            ->orderBy('comida.fecha','desc')
                            ->select('comida.*','servicio.nombre as servicio2','comedor.descripcion as comedor2');
        $resultado = $resultado->where(DB::raw('date(comida.fecha)'),'=',DB::raw("'".date("Y-m-d")."'"));
        $resultado = $resultado->where('comida.comedor_id','=',1);
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
        $now = strtotime('now');
        $desayunoinicio = strtotime(date("Y-m-d ").DESAYUNO_INICIO.":00");
        $desayunofin = strtotime(date("Y-m-d ").DESAYUNO_FIN.":00");
        $almuerzoinicio = strtotime(date("Y-m-d ").ALMUERZO_INICIO.":00");
        $almuerzofin = strtotime(date("Y-m-d ").ALMUERZO_FIN.":00");
        $cenainicio = strtotime(date("Y-m-d ").CENA_INICIO.":00");
        $cenafin = strtotime(date("Y-m-d ").CENA_FIN.":00");
        if($now>=$desayunoinicio && $now<=$desayunofin){
            $segundos=$desayunofin - $now;
        }elseif($now>=$almuerzoinicio && $now<=$almuerzofin){
            $segundos=$almuerzofin - $now;
        }elseif($now>=$cenainicio && $now<=$cenafin){
            $segundos=$cenafin - $now;
        }else{
            $segundos=0;
        }

        $divContador = 'Desayunos: <b id="bDesayuno">'.$desayuno.'</b>  | Almuerzo: <b id="bAlmuerzo">'.$almuerzo.'</b>  | Cena: <b id="bCena">'.$cena.'</b>';
        return view('app.comida.registroComedor')->with(compact( 'entidad','horainicio','horafin','fechainicio','fechafin','divContador','segundos'));
    }

    public function formularioComedor2()
    {
        $entidad  = 'Comedor';
        $horainicio = Configuracion::find(4)->valor;
        $horafin = Configuracion::find(5)->valor;
        $fechainicio = date("Y-m-d")." ".Configuracion::find(4)->valor;
        $fechafin = date("Y-m-d")." ".Configuracion::find(5)->valor;

        $resultado        = Comida::join('comedor','comedor.id','=','comida.comedor_id')
                            ->join('servicio','servicio.id','=','comida.servicio_id')
                            ->orderBy('comida.fecha','desc')
                            ->select('comida.*','servicio.nombre as servicio2','comedor.descripcion as comedor2');
        $resultado = $resultado->where(DB::raw('date(comida.fecha)'),'=',DB::raw("'".date("Y-m-d")."'"));
        $resultado = $resultado->where('comida.comedor_id','=',2);
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
        $now = strtotime('now');
        $desayunoinicio = strtotime(date("Y-m-d ").DESAYUNO_INICIO.":00");
        $desayunofin = strtotime(date("Y-m-d ").DESAYUNO_FIN.":00");
        $almuerzoinicio = strtotime(date("Y-m-d ").ALMUERZO_INICIO.":00");
        $almuerzofin = strtotime(date("Y-m-d ").ALMUERZO_FIN.":00");
        $cenainicio = strtotime(date("Y-m-d ").CENA_INICIO.":00");
        $cenafin = strtotime(date("Y-m-d ").CENA_FIN.":00");
        if($now>=$desayunoinicio && $now<=$desayunofin){
            $segundos=$desayunofin - $now;
        }elseif($now>=$almuerzoinicio && $now<=$almuerzofin){
            $segundos=$almuerzofin - $now;
        }elseif($now>=$cenainicio && $now<=$cenafin){
            $segundos=$cenafin - $now;
        }else{
            $segundos=0;
        }
        $divContador = 'Desayunos: <b id="bDesayuno">'.$desayuno.'</b>  | Almuerzo: <b id="bAlmuerzo">'.$almuerzo.'</b>  | Cena: <b id="bCena">'.$cena.'</b>';
        return view('app.comida.registroComedor2')->with(compact( 'entidad','horainicio','horafin','fechainicio','fechafin','divContador','segundos'));
    }


    public function formularioPedido()
    {
        $entidad  = 'Comida';
        $cboCarnico = array(''=>'Seleccione') + Carnico::whereNotIn('id',[5,7,8,9,10,11,12,13])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboServicio = array(''=>'Seleccione') + Servicio::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $horainicio = Configuracion::find(4)->valor;
        $horafin = Configuracion::find(5)->valor;
        $fechainicio = date("Y-m-d")." ".Configuracion::find(4)->valor;
        $fechafin = date("Y-m-d")." ".Configuracion::find(5)->valor;
        $desayuno = "-";
        $almuerzo = "-";
        $cena = "-";
        return view('app.comida.registro')->with(compact('cboCarnico', 'entidad','cboServicio','horainicio','horafin','fechainicio','fechafin','desayuno','almuerzo','cena'));
    }

    public function formularioPedidoAdmin()
    {
        $entidad  = 'Comida';
        $cboCarnico = array(''=>'Seleccione') + Carnico::whereNotIn('id',[5,7,8,9,10,11,12,13])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboServicio = array(''=>'Seleccione') + Servicio::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $desayuno = "-";
        $almuerzo = "-";
        $cena = "-";
        return view('app.comida.registroAdmin')->with(compact('cboCarnico', 'entidad','cboServicio','desayuno','almuerzo','cena'));
    }

    public function formularioPedidoGrupal()
    {
        $entidad  = 'Comida';
        $cboCarnico = array(''=>'Seleccione') + Carnico::whereNotIn('id',[5,7,8,9,10,11,12,13])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboServicio = array(''=>'Seleccione') + Servicio::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $desayuno = "-";
        $almuerzo = "-";
        $cena = "-";
        return view('app.comida.registroGrupal')->with(compact('cboCarnico', 'entidad','cboServicio','desayuno','almuerzo','cena'));
    }

    public function formularioPedidoCarnico()
    {
        $entidad  = 'Comida';
        $cboCarnico = array(''=>'Seleccione') + Carnico::whereNotIn('id',[3,7,8,9,10])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboServicio = array(''=>'Seleccione') + Servicio::whereNotIn('id',[4,7,8,12,5,6,13])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $horainicio = Configuracion::find(4)->valor;
        $horafin = Configuracion::find(5)->valor;
        $fechainicio = date("Y-m-d")." ".Configuracion::find(4)->valor;
        $fechafin = date("Y-m-d")." ".Configuracion::find(5)->valor;
        $desayuno = "-";
        $almuerzo = "-";
        $cena = "-";
        return view('app.comida.registroCarnico')->with(compact('cboCarnico', 'entidad','cboServicio','horainicio','horafin','fechainicio','fechafin','desayuno','almuerzo','cena'));
    }

    public function formularioPedidoCarnicoAdmin()
    {
        $entidad  = 'Comida';
        $cboCarnico = array(''=>'Seleccione') + Carnico::whereNotIn('id',[3,7,8,9,10])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboServicio = array(''=>'Seleccione') + Servicio::whereNotIn('id',[4,7,8,12,5,6,13])->orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $horainicio = Configuracion::find(4)->valor;
        $horafin = Configuracion::find(5)->valor;
        $fechainicio = date("Y-m-d")." ".Configuracion::find(4)->valor;
        $fechafin = date("Y-m-d")." ".Configuracion::find(5)->valor;
        $desayuno = "-";
        $almuerzo = "-";
        $cena = "-";
        return view('app.comida.registroCarnicoAdmin')->with(compact('cboCarnico', 'entidad','cboServicio','horainicio','horafin','fechainicio','fechafin','desayuno','almuerzo','cena'));
    }

    public function searchAutocompletePersona($query){
    
        $consulta = "select id, concat(nombres,' ',apellidos) as descripcion,concat(nombres,' ',apellidos) as persona,contratista as empresa,empresa_id,contratista_id,correo 
        from persona where
            deleted_at IS NULL AND
            (dni LIKE '%{$query}%' or concat(nombres,' ',apellidos) like '%".strtoupper($query)."%')";
        $res = DB::connection('mysql3')->select($consulta);
        $data = array();
        $listaData = array();
        foreach($res as $k=>$v){
            if(!isset($listaData[$v->id."-0"])){
                $data[] = array("persona_id"=>$v->id,
                        "personawp_id"=>0,
                        "descripcion"=>$v->descripcion,
                        "persona" => $v->persona,
                        "empresa" => $v->empresa,
                        "empresa_id" => $v->empresa_id,
                        "contratista_id" => $v->contratista_id,
                        "correo" => $v->correo,
                        );
                $listaData[$v->id."-0"] = $v->id."-0";
            }
        }
        //$lista = DB::connection('mysql2')->select("call SP_CARGO_LISTAR(?,?,?,?,?,?,?)",['3',0,0,'','','','0']);
        $lista = DB::connection('mysql2')->select("select p.*,t.contratista_id,us.email from 
            personal p 
            left join trabajador t on t.personal_id=p.id
            left join usuario us on us.id=p.usuario_id
            where p.activo='1' and p.nombre like '%".strtoupper($query)."%'");
        foreach($lista as $k=>$v){
            if(!isset($listaData["0-".$v->id])){
                $data[] = array("persona_id"=>0,
                        "personawp_id"=>$v->id,
                        "descripcion"=>$v->nombre,
                        "persona" => $v->nombre,
                        "empresa"=>$v->contratista_id=="12"?"H2OLMOS S.A.":"CONCESIONARIA TRASVASE OLMOS S.A.",
                        "empresa_id" => 0,
                        "contratista_id"=>$v->contratista_id,
                        "correo" => $v->email,
                        );
                $listaData["0-".$v->id] = "0-".$v->id;
            }
        }
        return response() -> json($data);
    }    

    public function searchAutocompleteEmpresa($query){
    
        $consulta = "select concat(ruc,' ',razonsocial) as descripcion,0 as empresa_id,id as contratista_id,razonsocial from contratista where
            deleted_at IS NULL AND
            (ruc LIKE '%{$query}%' or razonsocial like '%".strtoupper($query)."%') 
             union all
            select concat(ruc,' ',razonsocial) as descripcion,id as empresa_id,0 as contratista_id,razonsocial from empresa
            where deleted_at is NULL and 
            (ruc LIKE '%{$query}%' or razonsocial like '%".strtoupper($query)."%') ";
        //echo $consulta;
        $res = DB::connection('mysql3')->select($consulta);
        
        return response() -> json($res);
    }

    public function searchAutocompleteLugar($query){
    
        $consulta = "select id, nombre as descripcion from lugar where
            deleted_at IS NULL AND
            (nombre like '%".strtoupper($query)."%') ";
        //echo $consulta;
        $res = DB::connection('mysql3')->select($consulta);
        $data = array();
        foreach($res as $k=>$v){
            $data[] = array('lugar_id'=>$v->id,
                    'locacion_id' => 0,
                    'descripcion' => $v->descripcion,
                    );
        }
        $locacion = Locacion::orderBy('nombre','asc')->get();
        foreach($locacion as $k=>$v){
            $data[] = array('lugar_id'=>0,
                    'locacion_id' => $v->id,
                    'descripcion' => $v->nombre,
                    );
        }
        return response() -> json($data);
    }

    public function guardarPedido(Request $request){
        $id=0;
        $fecha = substr($request->input('fecha'),0,10);
        $fechainicio = $fecha." ".Configuracion::find(4)->valor;
        $fechafin = $fecha." ".Configuracion::find(5)->valor;
        
        if($request->input('saltar_validacion')=="N" || trim($request->input('saltar_validacion'))==""){
            if(strtotime($request->input('fecha'))>=strtotime($fechainicio) && strtotime($request->input('fecha'))<=strtotime($fechafin)){
                if(strtotime($fecha)==strtotime(date("Y-m-d"))){//todo ok

                }/*elseif(strtotime($fecha)<strtotime("+2 day",strtotime(date("Y-m-d")))){
                    return json_encode(array("respuesta"=>"ERROR", "msg"=>"Está fuera del horario de solicitud de pedidos al comedor"));
                    exit();
                }*/
            }else{
                return json_encode(array("respuesta"=>"ERROR", "msg"=>"Fecha y Hora fuera del horario de atención del comedor"));
                exit();
            }
        }
        /*if(strtotime('now')>=strtotime($fechainicio) && strtotime('now')<=strtotime($fechafin)){

        }elseif($request->input('saltar_validacion')=="N" || trim($request->input('saltar_validacion'))==""){
            return json_encode(array("respuesta"=>"ERROR", "msg"=>"No se puede registrar el pedido, fuera de horario de atención"));
            exit();
        }*/
        //return json_encode(array("respuesta"=>"ERROR", "msg"=>"Paso Validación"));
        //die();
        $error = DB::transaction(function() use($request,&$id){
            $obj = new Pedido();
            $obj->solicitante= $request->input('solicitante');
            $obj->solicitante_id= $request->input('solicitante_id');
            $obj->solicitantewp_id= $request->input('solicitantewp_id');
            $obj->lider= $request->input('lider');
            $obj->lider_id= $request->input('lider_id');
            $obj->liderwp_id= $request->input('liderwp_id');
            $obj->lugar_id= $request->input('lugar_id')+0;
            $obj->locacion_id= $request->input('locacion_id')+0;
            $obj->lugar= $request->input('lugar');
            $obj->fecha= $request->input('fecha');
            $obj->concesionaria_id= $request->input('concesionaria_id');
            $obj->observacion= mb_strtoupper($request->input('observacion'), 'utf-8');
            $obj->correo= $request->input('correo');
            $obj->saltar_validacion= $request->input('saltar_validacion');
            $obj->save();

            $persona = $request->input('personaList');
            $servicioLista = $request->input('servicioLista');
            $servicio = $request->input('servicioList');
            $carnico = $request->input('carnicoList');
            $dieta = $request->input('dietaList');
            $personaId = $request->input('personaId');
            $personaWPId = $request->input('personaWPId');
            $empresaId = $request->input('empresaId');
            $contratistaId = $request->input('contratistaId');
            $empresaList = $request->input('empresaList');

            if($persona!=""){
                for ($i = 0; $i < count($persona); $i++) {
                    $detalle = DetallePedido::join('pedido','pedido.id','=','detallepedido.pedido_id')
                                        ->where(DB::raw('date(pedido.fecha)'),'=',DB::raw("'".substr($request->input('fecha'),0,10)."'"))
                                        ->where('detallepedido.servicio_id','=',$servicio[$i])
                                        ->where('detallepedido.persona_id','=',$personaId[$i])
                                        ->where('detallepedido.personawp_id','=',$personaWPId[$i])
                                        ->whereNull('pedido.deleted_at')
                                        ->whereNull('detallepedido.deleted_at')
                                        ->first();
                    if(!is_null($detalle)){
                        DB::rollback();
                        return json_encode(array("respuesta"=>"ERROR", "msg"=>"Ya se hizo el pedido de ".$servicioLista[$i]." para esta persona: ".$persona[$i]));
                        exit();
                    }
                    if($servicio[$i]=="2"){//almuerzo
                        $detalle = DetallePedido::join('pedido','pedido.id','=','detallepedido.pedido_id')
                                            ->where(DB::raw('date(pedido.fecha)'),'=',DB::raw("'".substr($request->input('fecha'),0,10)."'"))
                                            ->where('detallepedido.servicio_id','=',10)
                                            ->where('detallepedido.persona_id','=',$personaId[$i])
                                            ->where('detallepedido.personawp_id','=',$personaWPId[$i])
                                            ->whereNull('pedido.deleted_at')
                                            ->whereNull('detallepedido.deleted_at')
                                            ->first();
                        if(!is_null($detalle)){
                            DB::rollback();
                            return json_encode(array("respuesta"=>"ERROR", "msg"=>"Ya se hizo el pedido de almuerzo para esta persona: ".$persona[$i]));
                            exit();
                        }
                    }elseif($servicio[$i]=="10"){//almuerzo dieta
                        $detalle = DetallePedido::join('pedido','pedido.id','=','detallepedido.pedido_id')
                                            ->where(DB::raw('date(pedido.fecha)'),'=',DB::raw("'".substr($request->input('fecha'),0,10)."'"))
                                            ->where('detallepedido.servicio_id','=',2)
                                            ->where('detallepedido.persona_id','=',$personaId[$i])
                                            ->where('detallepedido.personawp_id','=',$personaWPId[$i])
                                            ->whereNull('pedido.deleted_at')
                                            ->whereNull('detallepedido.deleted_at')
                                            ->first();
                        if(!is_null($detalle)){
                            DB::rollback();
                            return json_encode(array("respuesta"=>"ERROR", "msg"=>"Ya se hizo el pedido de almuerzo dieta para esta persona: ".$persona[$i]));
                            exit();
                        }
                    }
                    $detalle = Comida::where(DB::raw('date(comida.fecha)'),'=',DB::raw("'".substr($request->input('fecha'),0,10)."'"))
                                    ->where('comida.servicio_id','=',$servicio[$i])
                                    ->where('comida.persona_id','=',$personaId[$i])
                                    ->where('comida.personawp_id','=',$personaWPId[$i])
                                    ->where('comida.situacion','like','F')
                                    ->first();
                    if(!is_null($detalle)){
                        DB::rollback();
                        return json_encode(array("respuesta"=>"ERROR", "msg"=>"No puede solicitar pedido de ".$servicioLista[$i]." para esta persona: ".$persona[$i]." porque consumio en comedor"));
                        exit();
                    }
                    if($request->input('saltar_validacion')=="N" || trim($request->input('saltar_validacion'))==""){
                        if($servicio[$i]=="1" || $servicio[$i]=="4" || $servicio[$i]=="9"){//SOLO DESAYUNOS
                            if(substr($request->input('fecha'),0,10)==date("Y-m-d")){
                                if(strtotime(date("Y-m-d")." 05:00")<=strtotime('now') && strtotime(date("Y-m-d")." 07:30")>=strtotime('now')){
                                    //dentro de la hora
                                }else{
                                    DB::rollback();
                                    return json_encode(array("respuesta"=>"ERROR", "msg"=>"No se puede realizar el pedido de desayuno para ".$persona[$i].", solo permitido desde 05:00 - 07:30"));
                                    exit();
                                }
                            }
                        }
                        if($servicio[$i]=="2" || $servicio[$i]=="10" || $servicio[$i]=="5" || $servicio[$i]=="7"){//SOLO ALMUERZOS
                            if(substr($request->input('fecha'),0,10)==date("Y-m-d")){
                                if(strtotime(date("Y-m-d")." 05:00")<=strtotime('now') && strtotime(date("Y-m-d")." 09:30")>=strtotime('now')){
                                    //dentro de la hora
                                }else{
                                    DB::rollback();
                                    return json_encode(array("respuesta"=>"ERROR", "msg"=>"No se puede realizar el pedido de almuerzo para ".$persona[$i].", solo permitido desde 05:00 - 09:30"));
                                    exit();
                                }
                            }
                        }
                        if($servicio[$i]=="3" || $servicio[$i]=="11" || $servicio[$i]=="6" || $servicio[$i]=="8"){//SOLO CENAS
                            if(substr($request->input('fecha'),0,10)==date("Y-m-d")){
                                if(strtotime(date("Y-m-d")." 05:00")<=strtotime('now') && strtotime(date("Y-m-d")." 15:00")>=strtotime('now')){
                                    //dentro de la hora
                                }else{
                                    DB::rollback();
                                    return json_encode(array("respuesta"=>"ERROR", "msg"=>"No se puede realizar el pedido de cena para ".$persona[$i].", solo permitido desde 05:00 - 15:00"));
                                    exit();
                                }
                            }
                        }
                        if(substr($request->input('fecha'),0,10)==date("Y-m-d")){//mismo dia y CTO
                            if($servicio[$i]=="4" || $servicio[$i]=="7" || $servicio[$i]=="8" || $servicio[$i]=="12" || $servicio[$i]=="13"){//SOLO REFRIGERIO
                                if(strtotime(date("Y-m-d")." 15:00")<=strtotime('now')){
                                    DB::rollback();
                                    return json_encode(array("respuesta"=>"ERROR", "msg"=>"No se puede realizar el pedido de refrigerio para ".$persona[$i].", solo permitido hasta las 15:00"));
                                    exit();
                                }
                            }
                        }
                    }
                    $detalle = new DetallePedido();
                    $detalle -> pedido_id =$obj->id;
                    $detalle -> dieta  = $dieta[$i];
                    $detalle -> persona  = $persona[$i];
                    $detalle -> persona_id  = $personaId[$i];
                    $detalle -> personawp_id  = $personaWPId[$i];
                    $detalle -> empresa_id  = $empresaId[$i];
                    $detalle -> contratista_id  = $contratistaId[$i];
                    $detalle -> empresa  = $empresaList[$i];
                    $detalle -> servicio_id  = $servicio[$i];
                    $detalle -> carnico_id  = $carnico[$i];
                    $detalle -> save();
                }
            }
            $id = $obj->id;
        });
        /*if($id!=0){
            $cc = new Curl(false,'https://sistemasolmos.pe/comedor/PHPMailer/notificacionPedido.php');
            $url = 'https://sistemasolmos.pe/comedor/PHPMailer/notificacionPedido.php?id='.$id;
            $Page = $cc->get($url,array());
            $datos = json_decode($Page);
        }*/

        return is_null($error) ? "OK" : $error;
    }

    public function guardarPedidoGrupal(Request $request){
        $id=0;
        $fecha = substr($request->input('fecha'),0,10);
        $fechainicio = $fecha." ".Configuracion::find(4)->valor;
        $fechafin = $fecha." ".Configuracion::find(5)->valor;
        
        $error = DB::transaction(function() use($request,&$id){
            $obj = new Pedido();
            $obj->solicitante= $request->input('solicitante');
            $obj->solicitante_id= $request->input('solicitante_id');
            $obj->solicitantewp_id= $request->input('solicitantewp_id');
            $obj->lider= $request->input('lider');
            $obj->lider_id= $request->input('lider_id');
            $obj->liderwp_id= $request->input('liderwp_id');
            $obj->lugar_id= $request->input('lugar_id')+0;
            $obj->locacion_id= $request->input('locacion_id')+0;
            $obj->lugar= $request->input('lugar');
            $obj->fecha= $request->input('fecha');
            $obj->concesionaria_id= $request->input('concesionaria_id');
            $obj->observacion= mb_strtoupper($request->input('observacion'), 'utf-8');
            $obj->correo= $request->input('correo');
            $obj->save();

            $detalle = new DetallePedidoGrupal();
            $detalle -> pedido_id =$obj->id;
            $detalle -> dieta  = $request->input('dieta');
            $detalle -> cantidad  = $request->input('cantidad');
            $detalle -> empresa_id  = $request->input('empresa_id');
            $detalle -> contratista_id  = $request->input('contratista_id');
            $detalle -> empresa  = $request->input('empresa');
            $detalle -> servicio_id  = $request->input('servicio');
            $detalle -> carnico_id  = $request->input('carnico');
            $detalle -> save();
            $id = $obj->id;
        });
        if($id!=0){
            $cc = new Curl(false,'https://sistemasolmos.pe/comedor/PHPMailer/notificacionPedidoGrupal.php');
            $url = 'https://sistemasolmos.pe/comedor/PHPMailer/notificacionPedidoGrupal.php?id='.$id;
            $Page = $cc->get($url,array());
            $datos = json_decode($Page);
        }

        return is_null($error) ? "OK" : $error;
    }

    public function guardarPedidoCarnico(Request $request){
        $id=0;
        $fecha = substr($request->input('fecha'),0,10);
        $fechainicio = $fecha." ".Configuracion::find(4)->valor;
        $fechafin = $fecha." ".Configuracion::find(5)->valor;
        
        if($request->input('saltar_validacion')=="N" || trim($request->input('saltar_validacion'))==""){
            if(strtotime($request->input('fecha'))>=strtotime($fechainicio) && strtotime($request->input('fecha'))<=strtotime($fechafin)){
                if(strtotime($fecha)==strtotime(date("Y-m-d"))){//todo ok

                }/*elseif(strtotime($fecha)<strtotime("+2 day",strtotime(date("Y-m-d")))){
                    return json_encode(array("respuesta"=>"ERROR", "msg"=>"Está fuera del horario de solicitud de pedidos al comedor"));
                    exit();
                }*/
            }else{
                return json_encode(array("respuesta"=>"ERROR", "msg"=>"Fecha y Hora fuera del horario de atención del comedor"));
                exit();
            }
        }
        //return json_encode(array("respuesta"=>"ERROR", "msg"=>"Paso Validación"));
        //die();
        $error = DB::transaction(function() use($request,&$id){
            $obj = new PedidoCarnico();
            $obj->solicitante= $request->input('solicitante');
            $obj->solicitante_id= $request->input('solicitante_id');
            $obj->solicitantewp_id= $request->input('solicitantewp_id');
            $obj->lider= $request->input('lider');
            $obj->lider_id= $request->input('lider_id');
            $obj->liderwp_id= $request->input('liderwp_id');
            $obj->lugar_id= $request->input('lugar_id')+0;
            $obj->locacion_id= $request->input('locacion_id')+0;
            $obj->lugar= $request->input('lugar');
            $obj->fecha= $request->input('fecha');
            $obj->concesionaria_id= $request->input('concesionaria_id');
            $obj->observacion= mb_strtoupper($request->input('observacion'), 'utf-8');
            $obj->correo= $request->input('correo');
            $obj->save();

            $persona = $request->input('personaList');
            $servicioLista = $request->input('servicioLista');
            $servicio = $request->input('servicioList');
            $carnico = $request->input('carnicoList');
            $dieta = $request->input('dietaList');
            $personaId = $request->input('personaId');
            $personaWPId = $request->input('personaWPId');
            $empresaId = $request->input('empresaId');
            $contratistaId = $request->input('contratistaId');
            $empresaList = $request->input('empresaList');

            if($persona!=""){
                for ($i = 0; $i < count($persona); $i++) {
                    $detalle = DetallePedidoCarnico::join('pedidocarnico','pedidocarnico.id','=','detallepedidocarnico.pedidocarnico_id')
                                        ->where(DB::raw('date(pedidocarnico.fecha)'),'=',DB::raw("'".substr($request->input('fecha'),0,10)."'"))
                                        ->where('detallepedidocarnico.servicio_id','=',$servicio[$i])
                                        ->where('detallepedidocarnico.persona_id','=',$personaId[$i])
                                        ->where('detallepedidocarnico.personawp_id','=',$personaWPId[$i])
                                        ->whereNull('pedidocarnico.deleted_at')
                                        ->whereNull('detallepedidocarnico.deleted_at')
                                        ->first();
                    if(!is_null($detalle)){
                        DB::rollback();
                        return json_encode(array("respuesta"=>"ERROR", "msg"=>"Ya se hizo el pedido de ".$servicioLista[$i]." para esta persona: ".$persona[$i]));
                        exit();
                    }
                    if($servicio[$i]=="1" || $servicio[$i]=="9"){//desayuno y desayuno dieta
                        if(strtotime("-1 day",strtotime(substr($request->input('fecha'),0,10)." 20:00:00")) < strtotime('now')){
                            DB::rollback();
                            return json_encode(array("respuesta"=>"ERROR", "msg"=>"No se puede registrar el pedido de ".$servicioLista[$i]." para ".$persona[$i].", plazo máximo antes de las 20:00"));
                            exit();
                        }
                    }
                    if($request->input('saltar_validacion')=="N" || trim($request->input('saltar_validacion'))==""){
                        if($servicio[$i]=="2" || $servicio[$i]=="10" || $servicio[$i]=="5"){//almuerzo
                            if(strtotime(substr($request->input('fecha'),0,10)." 09:00:00") < strtotime('now')){
                                DB::rollback();
                                return json_encode(array("respuesta"=>"ERROR", "msg"=>"No se puede registrar el pedido de ".$servicioLista[$i]." para ".$persona[$i].", plazo máximo antes de las 09:00"));
                                exit();
                            }
                        }
                        if($servicio[$i]=="3" || $servicio[$i]=="11" || $servicio[$i]=="6"){//cena
                            if(strtotime(substr($request->input('fecha'),0,10)." 15:00:00") < strtotime('now')){
                                DB::rollback();
                                return json_encode(array("respuesta"=>"ERROR", "msg"=>"No se puede registrar el pedido de ".$servicioLista[$i]." para ".$persona[$i].", plazo máximo antes de las 15:00"));
                                exit();
                            }
                        }
                    }
                    $detalle = new DetallePedidoCarnico();
                    $detalle -> pedidocarnico_id =$obj->id;
                    $detalle -> dieta  = $dieta[$i];
                    $detalle -> persona  = $persona[$i];
                    $detalle -> persona_id  = $personaId[$i];
                    $detalle -> personawp_id  = $personaWPId[$i];
                    $detalle -> empresa_id  = $empresaId[$i];
                    $detalle -> contratista_id  = $contratistaId[$i];
                    $detalle -> empresa  = $empresaList[$i];
                    $detalle -> servicio_id  = $servicio[$i];
                    $detalle -> carnico_id  = $carnico[$i];
                    $detalle -> save();
                }
            }
            $id = $obj->id;
        });
        if($id!=0){
            $cc = new Curl(false,'https://sistemasolmos.pe/comedor/PHPMailer/notificacionPedidoCarnico.php');
            $url = 'https://sistemasolmos.pe/comedor/PHPMailer/notificacionPedidoCarnico.php?id='.$id;
            $Page = $cc->get($url,array());
            $datos = json_decode($Page);
        }

        return is_null($error) ? "OK" : $error;
    }

    public function formularioMovilizacion()
    {
        $entidad  = 'Movilizacion';
        $cboOrigen = array(''=>'Seleccione') + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboDestino = array(''=>'Seleccione') + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboCampamento = array(''=>'Seleccione') + Campamento::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboMotivo = array(''=>'Seleccione') + Motivo::orderBy('descripcion','asc')->pluck('descripcion','descripcion')->toArray();
        return view('app.movilizacion.registro')->with(compact('cboOrigen', 'entidad','cboDestino','cboCampamento','cboMotivo'));
    }

    public function formularioMovilizacion2()
    {
        $entidad  = 'Movilizacion';
        $cboOrigen = array(''=>'Seleccione') + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboDestino = array(''=>'Seleccione') + Lugar::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboCampamento = array(''=>'Seleccione') + Campamento::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        $cboMotivo = array(''=>'Seleccione') + Motivo::orderBy('descripcion','asc')->pluck('descripcion','descripcion')->toArray();
        return view('app.movilizacion.regularizar')->with(compact('cboOrigen', 'entidad','cboDestino','cboCampamento','cboMotivo'));
    }

    public function searchAutocompletePersonaMovilizacion($query){
    
        $consulta = "select persona.id, concat(nombres,' ',apellidos) as descripcion,concat(nombres,' ',apellidos) as persona,empresa_id,persona.contratista as empresa,persona.contratista_id,persona.empresa_id 
        from persona
        left join empresa on empresa.id=persona.empresa_id
        left join contratista on contratista.id=persona.contratista_id
        where
            persona.deleted_at IS NULL AND
            (dni LIKE '%{$query}%' or concat(nombres,' ',apellidos) like '%".strtoupper($query)."%')";
        $res = DB::connection('mysql3')->select($consulta);
        $data = array();
        foreach($res as $k=>$v){
            $data[] = array("id"=>$v->id,
                        "descripcion"=>$v->descripcion,
                        "persona" => $v->persona,
                        "empresa" => $v->empresa,
                        "empresa_id" => $v->empresa_id,
                        "contratista_id" => $v->contratista_id,
                        );
        }
        return response() -> json($data);
    }    

    public function consultarTarifa(Request $request){
        $tarifa = Tarifa::where('origen_id','=',$request->input('origen_id'))->where('destino_id','=',$request->input('destino_id'))->first();
        if(!is_null($tarifa)){
            echo $tarifa->monto;
        }else{
            echo "0";
        }
    }

    public function guardarMovilizacion(Request $request){
        if($request->input('validar')=="S"){
            if(strtotime(str_replace("T", " ", $request->input('fecha').":00"))>=strtotime('+1 hour',strtotime('now'))){
                //nada
            }else{
                return "Debiste registrar con una hora de anticipacion";
            }
        }
        $id=0;
        $error = DB::transaction(function() use($request,&$id){
            $obj = new Movilizacion();
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
            $id = $obj->id;
        });

        $cc = new Curl(false,'https://sistemasolmos.pe/comedor/PHPMailer/notificacionMovilizacion.php');
        $url = 'https://sistemasolmos.pe/comedor/PHPMailer/notificacionMovilizacion.php?id='.$id;
        $Page = $cc->get($url,array());
        $datos = json_decode($Page);

        return is_null($error) ? "OK" : $error;
    }

    public function guardarComedor(Request $request)
    {
        Comida::where('situacion','like','P')->delete();
        $dni = $request->input('name');
        $otros = $request->input('otros');
        if($dni!=""){
            
        }else{
            $dni = $otros;
        }
        $person = null;
        $empresa = "";
        $consulta = "select p.id, concat(p.nombres,' ',p.apellidos) as descripcion,concat(p.nombres,' ',p.apellidos) as persona,contratista as empresa,empresa_id,contratista_id,correo,p.ua_id,concat(ua.codigo,' ',ua.descripcion) as ua
        from persona as p 
        left join ua on ua.id=p.ua_id
        where p.deleted_at IS NULL AND
            (p.dni LIKE '{$dni}' or p.otros like '".strtoupper($dni)."')";
        $res = DB::connection('mysql3')->select($consulta);
        if(count($res)>0){
            $person = $res[0];
            $nombres = $person->descripcion;
            $persona_id = $person->id;
            $personawp_id = 0;
            $ua_id = $person->ua_id;
            $ua = $person->ua;
            $empresa = $person->empresa;
            if(trim($empresa)=="CONCESIONARIA TRASVASE OLMOS S.A."){
                $empresa = "CTO";
            }
            if(trim($empresa)=="H2OLMOS S.A."){
                $empresa = "H2O";
            }
        }else{
            $lista = DB::connection('mysql2')->select("select p.*,tr.contratista_id,ua.descripcion as ua 
                from personal p 
                inner join trabajador tr on tr.personal_id=p.id 
                left join ua on ua.id = p.ua_id
                where p.docid like '".$dni."'");
            if(count($lista)>0){
                $person = $lista[0];
                $nombres = $person->nombre;
                $personawp_id = $person->id;
                $persona_id = 0;
                $ua_id = $person->ua_id;
                $empresa = $person->contratista_id==27?"CTO":"H2O";
                $ua = $person->ua;
            }
        }
        $comida  = null;
        if(is_null($person)){
            $mensaje = "DNI no está registrado en la base de datos";
            $persona = '';
            $tipo = '';
            $fecha = '';
            $color = "danger";
            $esdieta = "NO";
        }else{
            $persona = $nombres;
            $now = strtotime('now');
            $fecha = date("d/m/Y H:i:s");
            $fecha2 = date("Y-m-d H:i:s");
            $desayunoinicio = strtotime(date("Y-m-d ").DESAYUNO_INICIO.":00");
            $desayunofin = strtotime(date("Y-m-d ").DESAYUNO_FIN.":00");
            $almuerzoinicio = strtotime(date("Y-m-d ").ALMUERZO_INICIO.":00");
            $almuerzofin = strtotime(date("Y-m-d ").ALMUERZO_FIN.":00");
            $cenainicio = strtotime(date("Y-m-d ").CENA_INICIO.":00");
            $cenafin = strtotime(date("Y-m-d ").CENA_FIN.":00");
            $color = "info";
            $servicio2_id=array();
            $carnico_id=array();
            if($now>=$desayunoinicio && $now<=$desayunofin){
                $tipo="DESAYUNO";
                $servicio_id=1;
                $servicio2_id[]=1;
                $carnico_id[]=3;
                $carnico_id[]=20;
                $carnico_id[]=23;
                /*$servicio2_id[]=4;
                $servicio2_id[]=9;*/
            }elseif($now>=$almuerzoinicio && $now<=$almuerzofin){
                $tipo="ALMUERZO";
                $servicio_id=2;
                $servicio2_id[]=2;
                $carnico_id[]=3;
                $carnico_id[]=4;
                $carnico_id[]=21;
                /*$servicio2_id[]=5;
                $servicio2_id[]=7;
                $servicio2_id[]=10;*/
            }elseif($now>=$cenainicio && $now<=$cenafin){
                $tipo="CENA";
                $servicio_id=3;
                $servicio2_id[]=3;
                $carnico_id[]=3;
                $carnico_id[]=4;
                $carnico_id[]=22;
                /*$servicio2_id[]=6;
                $servicio2_id[]=8;
                $servicio2_id[]=11;*/
            }else{
                $tipo='-';
                $servicio_id=0;
                $servicio2_id[]=0;
                $carnico_id[]=0;
                $mensaje='NO EXISTE SERVICIO PARA ESTE HORARIO';
            }
            $esdieta="NO";

            $pedido = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                            ->join('servicio','detallepedido.servicio_id','=','servicio.id')
                            ->where(DB::raw('cast(pedido.fecha as date)'),'=',date("Y-m-d"))
                            ->whereIn('detallepedido.servicio_id',$servicio2_id)
                            ->whereIn('detallepedido.carnico_id',$carnico2_id)
                            ->where('detallepedido.persona_id','=',$persona_id)
                            ->where('detallepedido.personawp_id','=',$personawp_id)
                            ->whereNull('detallepedido.deleted_at')
                            ->select('servicio.nombre as servicio')
                            ->first();
            if(!is_null($pedido)){
                $mensaje = "NO PUEDE REGISTRAR DEBIDO A QUE SOLICITO UN ".str_replace("REFRIG.", "TRANSPORTADO", strtoupper($pedido->servicio));
                $tipo='-';
                $color='danger';
            }

            if($tipo!="-"){
                $comida = Comida::where(DB::raw('cast(fecha as date)'),'=',date("Y-m-d"))->where('persona_id','=',$persona_id)->where('personawp_id','=',$personawp_id)->where('servicio_id','=',$servicio_id)->first();
                if(is_null($comida)){
                    $servicio = Servicio::find($servicio_id);
                    $comida = new Comida();
                    $comida->persona_id = $persona_id;
                    $comida->personawp_id = $personawp_id;
                    $comida->fecha = $fecha2;
                    $comida->servicio_id = $servicio_id;
                    $comida->comedor_id = $request->input('comedor_id');
                    $comida->persona = $persona;
                    $comida->costo = $servicio->costo;
                    $comida->ua_id = $ua_id;
                    $comida->ua = $ua;
                    if($persona_id==918 && $request->input('comedor_id')=="1"){//excepcion
                        $comida->ua_id = 4755;
                        $comida->ua = "RESPONSABILIDAD SOCIAL IRRIGACIÓN OLMOS";
                    }elseif($persona_id==918 && $request->input('comedor_id')=="2"){//excepcion
                        $comida->ua_id = 5081;
                        $comida->ua = "ADENDA CTO AMPLIACION DE PLAZO";
                    }
                    $comida->save();

                    $mensaje = "ENTREGAR ".$tipo;
                    $color = "success";
                }else{
                    $mensaje = "YA SE REGISTRO ".$tipo;
                    $color = "danger";
                }
            }
        }

        $entidad      = 'Comida';
        $formData     = array('route' => null, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        $listar = 'SI';
        return view('app.comida.mantView')->with(compact('comida', 'formData', 'entidad', 'boton', 'listar','persona','tipo','fecha','mensaje','color','esdieta','dni','empresa'));
    }

    public function uploadFirma(Request $request)
    {
        $folderPath = public_path('image-firma-comedor/');
        
        $image_parts = explode(";base64,", $request->signed);
              
        $image_type_aux = explode("image/", $image_parts[0]);
           
        $image_type = $image_type_aux[1];
           
        $image_base64 = base64_decode($image_parts[1]);
           
        $file = $folderPath . $request->id . '.png';
        file_put_contents($file, $image_base64);
        Comida::withTrashed()->where('id', '=', $request->id)->first()->restore();
        $comida = Comida::find($request->id);
        $comida->situacion='F';
        $comida->save();
        Comida::withTrashed()->where('id', '=', $request->id)->first()->restore();
        //return back()->with('success', 'success Full upload signature');
        return "OK";
    }

    public function consultarCiclico(Request $request){
        $desayuno = Desayuno::where('fecha','=',substr($request->input('fecha'),0,10))->where(function($sql){$sql->where('dieta','like','N')->orWhereNull('dieta');})->first();
        $desayunodieta = Desayuno::where('fecha','=',substr($request->input('fecha'),0,10))->where('dieta','like','S')->first();
        $almuerzo = Almuerzo::where('fecha','=',substr($request->input('fecha'),0,10))->where('dieta','like','N')->first();
        $almuerzodieta = Almuerzo::where('fecha','=',substr($request->input('fecha'),0,10))->where('dieta','like','S')->first();
        $cena = Cena::where('fecha','=',substr($request->input('fecha'),0,10))->where('dieta','like','N')->first();
        $cenadieta = Cena::where('fecha','=',substr($request->input('fecha'),0,10))->where('dieta','like','S')->first();
        $refrigerio = Refrigerio::where('fecha','=',substr($request->input('fecha'),0,10))->where('tipo','like','Refrigerio Operador')->first();
        return response() -> json(["desayuno"=>$desayuno,"almuerzo"=>$almuerzo,"cena"=>$cena,'desayunodieta'=>$desayunodieta,'almuerzodieta'=>$almuerzodieta,'cenadieta'=>$cenadieta,'refrigerio'=>$refrigerio]);
    }

    public function consultarCarnico(Request $request){
        if($request->input('servicio_id')==4 || $request->input('servicio_id')==7 || $request->input('servicio_id')==8 ){
            $cboCarnico = "<option value=''>Seleccione</option>";
            if($request->input('servicio_id')==4){
                $carnico = Carnico::whereIn('id',[23,24,25,26])->orderBy('nombre','asc')->get();
            }else{
                $carnico = Carnico::whereIn('id',[3])->orderBy('nombre','asc')->get();
            }
            foreach ($carnico as $k=>$v) {
                $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
            }
        }else{
            if($request->input('servicio_id')==10){//ALMUERZO DIETA
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereIn('id',[3])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }elseif($request->input('servicio_id')==11){//CENA DIETA
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereIn('id',[3])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }elseif($request->input('servicio_id')==1){//DESAYUNO
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereIn('id',[3,20])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }elseif($request->input('servicio_id')==9){//DESAYUNO DIETA
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereIn('id',[15])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }elseif($request->input('servicio_id')==5 || $request->input('servicio_id')==6 || $request->input('servicio_id')==14){//ALMUERZO CENA FRIA
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereIn('id',[1])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }elseif($request->input('servicio_id')==12){//REF. OPERADOR
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereIn('id',[3])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }elseif($request->input('servicio_id')==13){//REF. CAMPO
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereIn('id',[3])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }elseif($request->input('servicio_id')==2){//ALMUERZO
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereIn('id',[3,4,21])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }elseif($request->input('servicio_id')==3){//CENA
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereIn('id',[3,4,22])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }else{
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereNotIn('id',[5,7,8,9,10,11,12,13,15])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }
        }     
        return $cboCarnico;

    }

    public function consultarCarnico2(Request $request){
        if($request->input('servicio_id')==2 || $request->input('servicio_id')==3){
            $cboCarnico = "<option value=''>Seleccione</option>";
            $carnico = Carnico::whereIn('id',[1,2,5])->orderBy('nombre','asc')->get();
            foreach ($carnico as $k=>$v) {
                $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
            }
        }else{
            if($request->input('servicio_id')==9){//DESAYUNO DIETA
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereIn('id',[15])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }elseif($request->input('servicio_id')==1){//DESAYUNO
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereIn('id',[3,5,15])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }elseif($request->input('servicio_id')==10 || $request->input('servicio_id')==11){//ALMUERZO O CENA DIETA
                $cboCarnico = "<option value=''>Seleccione</option>";
                $carnico = Carnico::whereIn('id',[1,6,11,12])->orderBy('nombre','asc')->get();
                foreach ($carnico as $k=>$v) {
                    $cboCarnico.="<option value='".$v->id."'>".$v->nombre."</option>";
                }
            }
        }
        return $cboCarnico;
    }

    public function calcularContador(Request $request){
        $resultado        = Comida::join('comedor','comedor.id','=','comida.comedor_id')
                            ->join('servicio','servicio.id','=','comida.servicio_id')
                            ->orderBy('comida.fecha','desc')
                            ->select('comida.*','servicio.nombre as servicio2','comedor.descripcion as comedor2');
        $resultado = $resultado->where(DB::raw('date(comida.fecha)'),'=',DB::raw("'".date("Y-m-d")."'"));
        $resultado = $resultado->where('comida.comedor_id','=',$request->input('comedor_id'));
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
        return 'Desayunos: <b id="bDesayuno">'.$desayuno.'</b>  | Almuerzo: <b id="bAlmuerzo">'.$almuerzo.'</b>  | Cena: <b id="bCena">'.$cena.'</b>';
    }

    public function tomapedido(){
        $lista =  Pedido::where('sincronizado','like','N')->orderBy("pedido.id", "ASC")->limit(10)->get();
        $data = array();
        foreach ($lista as $key => $value) {
            $dat = $value;
            $dat->detalle = DetallePedido::where('pedido_id','=',$value->id)->get();
            $data[] = $dat;
            $val = Pedido::find($value->id);
            $val->sincronizado = 'S';
            $val->save();
        }
        return json_encode($data);
    }

    public function tomapedidocarnico(){
        $lista =  PedidoCarnico::where('sincronizado','like','N')->orderBy("pedidocarnico.id", "ASC")->limit(10)->get();
        $data = array();
        foreach ($lista as $key => $value) {
            $dat = $value;
            $dat->detalle = DetallePedidoCarnico::where('pedidocarnico_id','=',$value->id)->get();
            $data[] = $dat;
            $val = PedidoCarnico::find($value->id);
            $val->sincronizado = 'S';
            $val->save();
        }
        return json_encode($data);
    }

    public function tomapedidoconfirmado(){
        $lista =  Pedido::where('confirmado','like','N')->orderBy("pedido.id", "ASC")->limit(20)->get();
        $data = array();
        foreach ($lista as $key => $value) {
            $dat = $value;
            $dat->detalle = DetallePedido::where('pedido_id','=',$value->id)->get();
            $data[] = $dat;
        }
        return json_encode($data);
    }

    public function confirmartomapedido(Request $request){
        $pedido = Pedido::where('id','=',$request->input('id'))->first();
        $pedido->confirmado='S';
        $pedido->save();
        return "OK";
    }

    public function eliminardetalletomapedido(Request $request){
        $detalle = DetallePedido::where('pedido_id','=',$request->input('pedido_id'))
                    ->where('persona_id','=',$request->input('persona_id'))
                    ->where('personawp_id','=',$request->input('personawp_id'))
                    ->where('servicio_id','=',$request->input('servicio_id'))
                    ->first();
        $detalle->delete();
        return "OK";
    }

    public function desayuno(Request $request){
        $obj = Desayuno::where('fecha','=',$request->input('fecha'))->get();
        return json_encode($obj);
    }

    public function almuerzo(Request $request){
        $obj = Almuerzo::where('fecha','=',$request->input('fecha'))->get();
        return json_encode($obj);
    }

    public function cena(Request $request){
        $obj = Cena::where('fecha','=',$request->input('fecha'))->get();
        return json_encode($obj);
    }

    public function refrigerio(Request $request){
        $obj = Refrigerio::where('fecha','=',$request->input('fecha'))->get();
        return json_encode($obj);
    }

    public function guardarProyectado(Request $request){
        $obj = Proyectado::where('fecha','=',$request->input('fecha'))->where('comedor_id','=',$request->input('comedor_id'))->first();
        if(is_null($obj)){
            $obj  = new Proyectado();
        }
        $obj->comedor_id = $request->input('comedor_id');
        $obj->fecha = $request->input('fecha');
        $obj->user_id = $request->input('user_id');
        $obj->desayuno = $request->input('desayuno');
        $obj->almuerzo = $request->input('almuerzo');
        $obj->cena = $request->input('cena');
        $obj->save();
        return "OK";
    }
}
