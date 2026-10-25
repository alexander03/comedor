<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Documento;
use App\Person;
use App\Tipodocumento;
use App\Procesodocumento;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Support\Facades\Storage;
use Excel;
use App\Alerta;
use App\Exports\DocumentoExport;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class DocumentoController extends Controller
{
    protected $folderview      = 'app.documento';
    protected $tituloAdmin     = 'Control Documentario';
    protected $tituloRegistrar = 'Registrar documento';
    protected $tituloModificar = 'Modificar documento';
    protected $tituloEliminar  = 'Eliminar documento';
    protected $rutas           = array('create' => 'documento.create', 
            'edit'     => 'documento.edit', 
            'delete'   => 'documento.eliminar',
            'search'   => 'documento.buscar',
            'index'    => 'documento.index',
            'subir'    => 'documento.subir',
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
        $entidad          = 'Documento';
        $name             = Libreria::getParam($request->input('descripcion'));
        $tipodocumento             = Libreria::getParam($request->input('tipodocumento_id'),'');
        $proceso             = Libreria::getParam($request->input('proceso_id'),'');
        $resultado        = Documento::join('tipodocumento','tipodocumento.id','=','documento.tipodocumento_id')
                                ->where('documento.descripcion','like','%'.$name.'%');
        if($tipodocumento!="0"){
            $resultado = $resultado->where('documento.tipodocumento_id','=',$tipodocumento);
        }
        if($proceso!="0"){
            $resultado = $resultado->where('documento.proceso_id','=',$proceso);
        }
        
        $lista            = $resultado->select('documento.*')->orderBy('tipodocumento.descripcion','desc')->orderBy('documento.descripcion','desc')->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'N°', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Tipo Doc.', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Codigo', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Revision', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Fecha Aprobacion', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Proceso Involucrado', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Observacion', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Situacion', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '5','rows'=>'1');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $ruta             = $this->rutas;
        $user     = Auth::User();
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta', 'user'));
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
        $entidad          = 'Documento';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboTipodocumento = array("0"=>"Todos");
        $tipodoc = Tipodocumento::orderBy('descripcion','asc')->get();
        foreach($tipodoc as $k=>$v){
            $cboTipodocumento += array($v->id=>$v->descripcion);
        }
        $cboProceso = array("0"=>"Todos");
        $proc = Procesodocumento::orderBy('descripcion','asc')->get();
        foreach($proc as $k=>$v){
            $cboProceso += array($v->id=>$v->descripcion);
        }
        $user     = Auth::User();
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboTipodocumento','cboProceso','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Documento';
        $documento  = null;
        $formData     = array('documento.store');
        $cboTipodocumento = array();
        $tipodoc = Tipodocumento::orderBy('descripcion','asc')->get();
        foreach($tipodoc as $k=>$v){
            $cboTipodocumento += array($v->id=>$v->descripcion);
        }
        $cboProceso = array();
        $proc = Procesodocumento::orderBy('descripcion','asc')->get();
        foreach($proc as $k=>$v){
            $cboProceso += array($v->id=>$v->descripcion);
        }
        $cboPersona = array();
        $persona = Person::where('id','>',1)->orderBy('apellidopaterno', 'asc')->get();
        foreach ($persona as $k => $v) {
            $cboPersona = $cboPersona + array($v->id => $v->apellidopaterno.' '.$v->apellidomaterno.' '.$v->nombres);
        }
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('documento', 'formData', 'entidad', 'boton', 'listar','cboTipodocumento','cboProceso','cboPersona'));
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
                'descripcion' => 'required',
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $dat = array();
        $error = DB::transaction(function() use($request, &$dat){
            $lstResponsable = $request->input('lstResponsable');
            $personas = "";
            for($x=0;$x<count($lstResponsable);$x++){
                $personas .=$lstResponsable[$x]."|";
            }
            $personas = substr($personas,0,strlen($personas)-1);
            
            $obj       = new Documento();
            $obj->proceso_id = $request->input('proceso_id');
            $obj->tipodocumento_id = $request->input('tipodocumento_id');
            $obj->codigo = $request->input('codigo');
            $obj->descripcion = $request->input('descripcion');
            $obj->revision = $request->input('revision');
            $obj->fecha = $request->input('fecha');
            $obj->observacion = $request->input('observacion');
            $obj->situacion = "P";
            $obj->listaresponsable = $personas;
            $obj->save();
            $dat[0] = array("respuesta" => "OK", "documento_id" => $obj->id, 'accion' => 'store');
        });
        return is_null($error) ? json_encode($dat) : $error;
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
        $existe = Libreria::verificarExistencia($id, 'documento');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $documento    = Documento::find($id);
        $entidad      = 'Documento';
        $formData     = array('documento.update', $id);
        $cboTipodocumento = array();
        $tipodoc = Tipodocumento::orderBy('descripcion','asc')->get();
        foreach($tipodoc as $k=>$v){
            $cboTipodocumento += array($v->id=>$v->descripcion);
        }
        $cboProceso = array();
        $proc = Procesodocumento::orderBy('descripcion','asc')->get();
        foreach($proc as $k=>$v){
            $cboProceso += array($v->id=>$v->descripcion);
        }
        $cboPersona = array();
        $persona = Person::where('id','>',1)->orderBy('apellidopaterno', 'asc')->get();
        foreach ($persona as $k => $v) {
            $cboPersona = $cboPersona + array($v->id => $v->apellidopaterno.' '.$v->apellidomaterno.' '.$v->nombres);
        }
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('documento', 'formData', 'entidad', 'boton', 'listar','cboTipodocumento','cboProceso','cboPersona'));
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
        $existe = Libreria::verificarExistencia($id, 'documento');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'descripcion' => 'required',
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $dat = array();
        $error = DB::transaction(function() use($request, $id, &$dat){
            $lstResponsable = $request->input('lstResponsable');
            $personas = "";
            for($x=0;$x<count($lstResponsable);$x++){
                $personas .=$lstResponsable[$x]."|";
            }
            $personas = substr($personas,0,strlen($personas)-1);
            
            $obj       = Documento::find($id);
            $obj->proceso_id = $request->input('proceso_id');
            $obj->tipodocumento_id = $request->input('tipodocumento_id');
            $obj->codigo = $request->input('codigo');
            $obj->descripcion = $request->input('descripcion');
            $obj->revision = $request->input('revision');
            $obj->fecha = $request->input('fecha');
            $obj->observacion = $request->input('observacion');
            $obj->listaresponsable = $personas;
            $obj->save();
            $dat[0] = array("respuesta" => "OK", "documento_id" => $obj->id, 'accion' => 'update');

        });
        return is_null($error) ? json_encode($dat) : $error;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'documento');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Documento::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'documento');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Documento::find($id);
        $entidad  = 'Documento';
        $formData = array('route' => array('documento.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }


    
    public function archivos(Request $request)
    {
        //obtenemos el campo file definido en el formulario
        $file = $request->file('file-0');
        if ($file) {
            //obtenemos el nombre del archivo

            /*
            $carpeta = '/P'.$request->input('id');
            if (!file_exists($carpeta)) {
                \Storage::makeDirectory($carpeta);
            }
            */
            if ($request->input('accion') == 'update') {
                $old_image = $request->input('id') . '-' . (Documento::find($request->input('id'))->archivo);
                $old_path = public_path('image-documento/' . $old_image);
                if (file_exists($old_path)) {
                    @unlink($old_path);
                }
            }
            $nombre = $file->getClientOriginalName();
            $path = public_path('image-documento/' . $request->input('id') . '-' . $nombre);

            $file->move('image-documento', $request->input('id') . '-' . $nombre);

            $documento = Documento::find($request->input('id'));
            $documento->archivo = $nombre;
            $documento->save();
            
            if($documento->situacion=='P'){
                $correo = 'anadelaguilaworkpeople@gmail.com';
                $mensaje = '<p>Tienes pendiente la revision de: </p><table border="1"><tr><th>Tipo Doc.</th><th>Cod.</th><th>Nombre</th><th>Revision</th><th>Fecha Aprobacion</th><th>Observ.</th></tr><tr><td>'.$documento->tipodocumento->descripcion.'</td><td>'.$documento->codigo.'</td><td>'.utf8_decode($documento->descripcion).'</td><td>'.$documento->revision.'</td><td>'.$documento->fecha.'</td><td>'.$documento->observacion.'</td></tr></table>';
                $usuario = 'Ana';
                
                $client = new Client();
                $res = $client->get('http://159.223.205.225/sgisoft/PHPMailer/enviarCorreo.php?correo='.$correo. '&usuario='.$usuario.'&mensaje=' . $mensaje);
                if ($res->getStatusCode() == 200) { // 200 OK
                    $response_data = $res->getBody()->getContents();
                    $respuesta = json_decode($response_data);
                }
            }
            
            
            return "archivo guardado";
        } else {
            return "Imagen no enviada";
        }
    }
    
    public function archivos2(Request $request)
    {
        //obtenemos el campo file definido en el formulario
        $file = $request->file('file-0');
        if ($file) {
            //obtenemos el nombre del archivo

            /*
            $carpeta = '/P'.$request->input('id');
            if (!file_exists($carpeta)) {
                \Storage::makeDirectory($carpeta);
            }
            */
            //if ($request->input('accion') == 'update') {
                $old_image = $request->input('id') . '-' . (Documento::find($request->input('id'))->archivo);
                $old_path = public_path('image-documento/' . $old_image);
                if (file_exists($old_path)) {
                    @unlink($old_path);
                }
            //}
            $nombre = $file->getClientOriginalName();
            $path = public_path('image-documento/' . $request->input('id') . '-' . $nombre);
            
            $file->move('image-documento', $request->input('id') . '-' . $nombre);
            

            $documento = Documento::find($request->input('id'));
            
            $documento->archivo = $nombre;
            $documento->situacion=$request->input('situacion');
            $documento->save();
            
            if($documento->situacion=='P'){
                $correo = 'anadelaguilaworkpeople@gmail.com';
                $mensaje = '<p>Tienes pendiente la revision de: </p><table border="1"><tr><th>Tipo Doc.</th><th>Cod.</th><th>Nombre</th><th>Revision</th><th>Fecha Aprobacion</th><th>Observ.</th></tr><tr><td>'.$documento->tipodocumento->descripcion.'</td><td>'.$documento->codigo.'</td><td>'.utf8_decode($documento->descripcion).'</td><td>'.$documento->revision.'</td><td>'.$documento->fecha.'</td><td>'.$documento->observacion.'</td></tr></table>';
                $usuario = 'Ana';
            }elseif($documento->situacion=='D'){
                
            }
            if($documento->situacion=='P'){
                $client = new Client();
                $res = $client->get('http://159.223.205.225/sgisoft/PHPMailer/enviarCorreo.php?correo='.$correo. '&usuario='.$usuario.'&mensaje=' . $mensaje);
                if ($res->getStatusCode() == 200) { // 200 OK
                    $response_data = $res->getBody()->getContents();
                    $respuesta = json_decode($response_data);
                }
            }else{
                $correo = "";
                $listaPersona = explode("|",$documento->listaresponsable);
                for($x=0;$x<count($listaPersona);$x++){
                    $person = Person::find($listaPersona[$x]);
                    $correo.= $person->email."|";
                }
                $correo = substr($correo,0,strlen($correo)-1);
                $mensaje = utf8_decode('<p>La presente es para comunicarles que el siguiente documento <b>'.$documento->codigo.' - '.$documento->descripcion.'</b> se cargo a la plataforma SGISOFT y se encuentra disponible para su uso.</p>');
                $usuario = '';
                $client = new Client();
                $res = $client->get('http://159.223.205.225/sgisoft/PHPMailer/enviarCorreo.php?correo='.$correo. '&usuario='.$usuario.'&mensaje=' . $mensaje);
                if ($res->getStatusCode() == 200) { // 200 OK
                    $response_data = $res->getBody()->getContents();
                    $respuesta = json_decode($response_data);
                    $alerta = new Alerta();
                    $alerta->fecha = date("Y-m-d");
                    $alerta->tipo = 'Indicador';
                    $alerta->tabla_id = $documento->id;
                    $alerta->correo = $correo;
                    $alerta->save();
                }
            }
        
        
            return "archivo guardado";
        } else {
            return "Imagen no enviada";
        }
    }
    
    public function excel(Request $request){
        setlocale(LC_TIME, 'spanish');
        return Excel::download(new DocumentoExport($request->input('descripcion'),$request->input('tipodocumento_id'),$request->input('proceso_id')), 'ReporteDocumento.xlsx');
    }
    
    public function subir($id, $listarLuego, $situacion)
    {
        $existe = Libreria::verificarExistencia($id, 'documento');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $cboTipodocumento = array();
        $tipodoc = Tipodocumento::orderBy('descripcion','asc')->get();
        foreach($tipodoc as $k=>$v){
            $cboTipodocumento += array($v->id=>$v->descripcion);
        }
        $documento   = Documento::find($id);
        $entidad  = 'Documento';
        $formData = array('route' => array('documento.archivos2', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Subir';
        return view($this->folderview.'.subir')->with(compact('documento', 'formData', 'entidad', 'boton', 'listar','cboTipodocumento','situacion'));
    }

}
