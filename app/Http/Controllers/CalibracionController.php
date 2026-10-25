<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Calibracion;
use App\Equipo;
use App\Categoria;
use App\Person;
use App\Subcategoria;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Support\Facades\Storage;
use Excel;
use App\Exports\CalibracionExport;

class CalibracionController extends Controller
{
    protected $folderview      = 'app.calibracion';
    protected $tituloAdmin     = 'Calibracion';
    protected $tituloRegistrar = 'Registrar calibracion';
    protected $tituloModificar = 'Modificar calibracion';
    protected $tituloEliminar  = 'Eliminar calibracion';
    protected $rutas           = array('create' => 'calibracion.create', 
            'edit'     => 'calibracion.edit', 
            'delete'   => 'calibracion.eliminar',
            'search'   => 'calibracion.buscar',
            'index'    => 'calibracion.index',
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
        $entidad          = 'Calibracion';
        $name             = Libreria::getParam($request->input('equipo'));
        $serie            = Libreria::getParam($request->input('serie'),'');
        $anio             = Libreria::getParam($request->input('anio'),'');
        $resultado        = Calibracion::join('equipo','equipo.id','=','calibracion.equipo_id')
                                ->join('subcategoria','calibracion.subcategoria_id','=','subcategoria.id')
                                ->join('categoria','categoria.id','=','subcategoria.categoria_id')
                                ->where('equipo.descripcion','like','%'.$name.'%')
                                ->where('equipo.serie','like','%'.$serie.'%');
        if($anio!=""){
            $resultado = $resultado->where('calibracion.anio','=',$anio);
        }
        $lista            = $resultado->select('calibracion.*')->orderBy('categoria.descripcion','desc')->orderBy('subcategoria.descripcion','desc')->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'N°', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Equipo', 'numero' => '5','rows'=>'1');
        $cabecera[]       = array('valor' => 'Fecha Adquisición', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Frente de Ubicación', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Área Responsable', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Frecuencia', 'numero' => '2','rows'=>'1');
        $cabecera[]       = array('valor' => 'Certificado', 'numero' => '2','rows'=>'1');
        $cabecera[]       = array('valor' => 'Próxima Calibración / Verifación', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Año: '.$anio, 'numero' => '12','rows'=>'1');
        $cabecera[]       = array('valor' => 'Comentario', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3','rows'=>'2');
        
        $cabecera2         = array();
        $cabecera2[]       = array('valor' => 'Código del Equipo', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'Descripción', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'Marca', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'Modelo', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'Serie', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'Calibración', 'numero' => '1','rows'=>'1');
        $cabecera2[]       = array('valor' => 'Verificación', 'numero' => '1','rows'=>'1');
        $cabecera2[]       = array('valor' => 'Código', 'numero' => '1','rows'=>'1');
        $cabecera2[]       = array('valor' => 'Fecha', 'numero' => '1','rows'=>'1');
        $cabecera2[]       = array('valor' => 'ENE', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'FEB', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'MAR', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'ABR', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'MAY', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'JUN', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'JUL', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'AGO', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'SET', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'OCT', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'NOV', 'numero' => '1');
        $cabecera2[]       = array('valor' => 'DIC', 'numero' => '1');
        
        
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta','cabecera2'));
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
        $entidad          = 'Calibracion';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboAnio = array("2022"=>"2022","2023"=>"2023","2024"=>"2024","2025"=>"2025","2026"=>"2026","2027"=>"2027");
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboAnio'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Calibracion';
        $calibracion  = null;
        $formData     = array('calibracion.store');
        $cboAnio = array("2022"=>"2022","2023"=>"2023","2024"=>"2024","2025"=>"2025","2026"=>"2026","2027"=>"2027");
        $cboCategoria = array();
        $categoria = Categoria::orderBy('descripcion', 'asc')->get();
        foreach ($categoria as $k => $v) {
            $cboCategoria = $cboCategoria + array($v->id => $v->descripcion);
        }
        $cboSubcategoria = array();
        $cboPersona = array();
        $persona = Person::where('id','>',1)->orderBy('apellidopaterno', 'asc')->get();
        foreach ($persona as $k => $v) {
            $cboPersona = $cboPersona + array($v->id => $v->apellidopaterno.' '.$v->apellidomaterno.' '.$v->nombres);
        }
        $equipo = null;
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('calibracion', 'formData', 'entidad', 'boton', 'listar','cboAnio','cboCategoria','cboSubcategoria','cboPersona','equipo'));
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
                'equipo_id' => 'required',
                'subcategoria_id' => 'required'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $dat = array();
        $error = DB::transaction(function() use($request, &$dat){
            $lstResponsable = $request->input('lstResponsable');
            $parametro = "";
            for($x=0;$x<count($lstResponsable);$x++){
                $parametro .=$lstResponsable[$x]."|";
            }
            $parametro = substr($parametro,0,strlen($parametro)-1);
            
            $obj       = new Calibracion();
            $obj->anio = $request->input('anio');
            $obj->subcategoria_id = $request->input('subcategoria_id');
            $obj->equipo_id = $request->input('equipo_id');
            $obj->orden = $request->input('orden');
            $obj->anio = $request->input('anio');
            $obj->frente = $request->input('frente');
            $obj->frecuenciacalibracion = $request->input('frecuenciacalibracion');
            $obj->area = $request->input('area');
            $obj->frecuenciaverificacion = $request->input('frecuenciaverificacion');
            $obj->codigocertificado = $request->input('codigocertificado');
            $obj->fechacertificado = $request->input('fechacertificado');
            $obj->proximacalibracion = $request->input('proximacalibracion');
            $obj->comentario = $request->input('comentario');
            $obj->fechaenvio1 = $request->input('fechaenvio1');
            $obj->fechaenvio2 = $request->input('fechaenvio2');
            $obj->listaresponsable = $parametro;
            $obj->save();
            $dat[0] = array("respuesta" => "OK", "calibracion_id" => $obj->id, 'accion' => 'store');
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
        $existe = Libreria::verificarExistencia($id, 'calibracion');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $calibracion  = Calibracion::find($id);
        $entidad      = 'Calibracion';
        $formData     = array('calibracion.update', $id);
        $cboAnio = array("2022"=>"2022","2023"=>"2023","2024"=>"2024","2025"=>"2025","2026"=>"2026","2027"=>"2027");
        $cboCategoria = array();
        $categoria = Categoria::orderBy('descripcion', 'asc')->get();
        foreach ($categoria as $k => $v) {
            $cboCategoria = $cboCategoria + array($v->id => $v->descripcion);
        }
        $cboSubcategoria = array();
        $subcategoria = Subcategoria::where('categoria_id','=',$calibracion->subcategoria->categoria_id)->orderBy('descripcion', 'asc')->get();
        foreach ($subcategoria as $k => $v) {
            $cboSubcategoria = $cboSubcategoria + array($v->id => $v->descripcion);
        }
        $cboPersona = array();
        $persona = Person::where('id','>',1)->orderBy('apellidopaterno', 'asc')->get();
        foreach ($persona as $k => $v) {
            $cboPersona = $cboPersona + array($v->id => $v->apellidopaterno.' '.$v->apellidomaterno.' '.$v->nombres);
        }
        $equipo = null;
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('calibracion', 'formData', 'entidad', 'boton', 'listar','cboAnio','cboCategoria','cboSubcategoria','cboPersona','equipo'));
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
        $existe = Libreria::verificarExistencia($id, 'calibracion');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'equipo_id' => 'required',
                'subcategoria_id' => 'required'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $dat = array();
        $error = DB::transaction(function() use($request, $id, &$dat){
            $lstResponsable = $request->input('lstResponsable');
            $parametro = "";
            for($x=0;$x<count($lstResponsable);$x++){
                $parametro .=$lstResponsable[$x]."|";
            }
            $parametro = substr($parametro,0,strlen($parametro)-1);
            
            $obj       = Calibracion::find($id);
            $obj->anio = $request->input('anio');
            $obj->equipo_id = $request->input('equipo_id');
            $obj->subcategoria_id = $request->input('subcategoria_id');
            $obj->orden = $request->input('orden');
            $obj->anio = $request->input('anio');
            $obj->frente = $request->input('frente');
            $obj->frecuenciacalibracion = $request->input('frecuenciacalibracion');
            $obj->area = $request->input('area');
            $obj->frecuenciaverificacion = $request->input('frecuenciaverificacion');
            $obj->codigocertificado = $request->input('codigocertificado');
            $obj->fechacertificado = $request->input('fechacertificado');
            $obj->proximacalibracion = $request->input('proximacalibracion');
            $obj->comentario = $request->input('comentario');
            $obj->fechaenvio1 = $request->input('fechaenvio1');
            $obj->fechaenvio2 = $request->input('fechaenvio2');
            $obj->listaresponsable = $parametro;
            $obj->save();
            $dat[0] = array("respuesta" => "OK", "calibracion_id" => $obj->id, 'accion' => 'update');

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
        $existe = Libreria::verificarExistencia($id, 'calibracion');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Calibracion::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'calibracion');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Calibracion::find($id);
        $entidad  = 'Calibracion';
        $formData = array('route' => array('calibracion.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function equipoautocompletar($searching,$subcategoria_id)
    {
        $resultado        = Equipo::
            where(function ($sql) use ($searching) {
                $sql->where(DB::raw('CONCAT(descripcion," - ",marca," - ",modelo," - ",serie)'), 'LIKE', '%' . strtoupper($searching) . '%');
            })
            ->where('subcategoria_id','=',$subcategoria_id)
            ->whereNull('equipo.deleted_at')->orderBy('descripcion', 'ASC');
        $list      = $resultado->select('equipo.*')->get();
        $data = array();
        foreach ($list as $key => $value) {
            $name = '';
            $name = $value->descripcion . " - " . $value->marca . " - " . $value->modelo . " - " . $value->serie;
            $data[] = array(
                'label' => trim($name),
                'id'    => $value->id,
                'value' => trim($name),
            );
        }
        return json_encode($data);
    }
    
    public function subcategoriaautocompletar(Request $request)
    {

        $term = trim($request->q);
        $idcat = trim($request->idcat);

        $resultado        = Subcategoria::where('subcategoria.descripcion', 'like', '%' . strtoupper($term) . '%');
        if (!empty($idcat) && $idcat != 0 && $idcat != '0') {
            $resultado = $resultado->where('subcategoria.categoria_id', '=', $idcat);
        }
        $resultado = $resultado->orderBy('subcategoria.descripcion', 'ASC');
        $tags     = $resultado->select('subcategoria.*')->get();
        $formatted_tags = [];
        //$formatted_tags[] = ['id' => '0', 'text' => 'Todos'];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->descripcion];
        }
        return \Response::json($formatted_tags);
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
                $old_image = $request->input('id') . '-' . (Calibracion::find($request->input('id'))->archivo);
                $old_path = public_path('image-calibracion/' . $old_image);
                if (file_exists($old_path)) {
                    @unlink($old_path);
                }
            }
            $nombre = $file->getClientOriginalName();
            $path = public_path('image-calibracion/' . $request->input('id') . '-' . $nombre);

            $file->move('image-calibracion', $request->input('id') . '-' . $nombre);

            $calibracion = Calibracion::find($request->input('id'));
            $calibracion->archivo = $nombre;
            $calibracion->save();
            return "archivo guardado";
        } else {
            return "Imagen no enviada";
        }
    }
    
    public function excel(Request $request){
        setlocale(LC_TIME, 'spanish');
        return Excel::download(new CalibracionExport($request->input('anio'),$request->input('equipo'),$request->input('serie')), 'ReporteCalibracion-'.$request->input('anio').'.xlsx');
    }

}
