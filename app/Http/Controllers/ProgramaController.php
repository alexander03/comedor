<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Programa;
use App\Area;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Support\Facades\Storage;
use Excel;
use App\Exports\ProgramaExport;

class ProgramaController extends Controller
{
    protected $folderview      = 'app.programa';
    protected $tituloAdmin     = 'Programa';
    protected $tituloRegistrar = 'Registrar programa';
    protected $tituloModificar = 'Modificar programa';
    protected $tituloEliminar  = 'Eliminar programa';
    protected $rutas           = array('create' => 'programa.create', 
            'edit'     => 'programa.edit', 
            'delete'   => 'programa.eliminar',
            'search'   => 'programa.buscar',
            'index'    => 'programa.index',
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
        $entidad          = 'Programa';
        $name             = Libreria::getParam($request->input('nombre'));
        $anio             = Libreria::getParam($request->input('anio'),'');
        $resultado        = Programa::join('area','area.id','=','programa.area_id')
                                ->where('programa.nombre','like','%'.$name.'%');
        if($anio!=""){
            $resultado = $resultado->where('programa.anio','=',$anio);
        }
        $lista            = $resultado->select('programa.*')->orderBy('area.orden','asc')->orderBy('programa.orden','asc')->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'Item', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Detalle', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Descripcion', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Responsable', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Frecuencia', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Año: '.$anio, 'numero' => '12','rows'=>'1');
        $cabecera[]       = array('valor' => 'Observaciones', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3','rows'=>'2');
        
        $cabecera2         = array();
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
        $entidad          = 'Programa';
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
        $entidad      = 'Programa';
        $programa  = null;
        $formData     = array('programa.store');
        $cboAnio = array("2022"=>"2022","2023"=>"2023","2024"=>"2024","2025"=>"2025","2026"=>"2026","2027"=>"2027");
        $cboArea = array();
        $area = Area::orderBy('orden', 'asc')->get();
        foreach ($area as $k => $v) {
            $cboArea = $cboArea + array($v->id => $v->descripcion);
        }
        $cboMes = array("1" => "Ene","2" => "Feb", "3" => "Mar", "4" => "Abr", "5" => "May", "6" => "Jun", "7" => "Jul", "8" => "Ago", "9" => "Set", "10" => "Oct", "11" => "Nov", "12" => "Dic");
        $cboFrecuencia = array("Mensual"=>"Mensual","Bimestral"=>"Bimestral","Trimestral"=>"Trimestral","Semestral"=>"Semestral","Anual"=>"Anual");
        $cboSubcategoria = array();
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('programa', 'formData', 'entidad', 'boton', 'listar','cboAnio','cboArea','cboMes','cboFrecuencia'));
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
        /*$validacion = Validator::make($request->all(),
            array(
                'fechainicio' => 'required|max:60',
                'fechafin' => 'required|max:60'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }*/
        $dat = array();
        $error = DB::transaction(function() use($request, &$dat){
            $obj       = new Programa();
            $obj->anio = $request->input('anio');
            $obj->area_id = $request->input('area_id');
            $obj->orden = $request->input('orden');
            $obj->nombre = $request->input('nombre');
            $obj->descripcion = $request->input('descripcion');
            $obj->responsable = $request->input('responsable');
            $obj->frecuencia = $request->input('frecuencia');
            $obj->mes = $request->input('mes');
            $obj->observacion = $request->input('observacion');
            $obj->save();
            $dat[0] = array("respuesta" => "OK", "programa_id" => $obj->id, 'accion' => 'store');
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
        $existe = Libreria::verificarExistencia($id, 'programa');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $programa  = Programa::find($id);
        $entidad      = 'Programa';
        $formData     = array('programa.update', $id);
        $cboAnio = array("2022"=>"2022","2023"=>"2023","2024"=>"2024","2025"=>"2025","2026"=>"2026","2027"=>"2027");
        $cboArea = array();
        $area = Area::orderBy('orden', 'asc')->get();
        foreach ($area as $k => $v) {
            $cboArea = $cboArea + array($v->id => $v->descripcion);
        }
        $cboMes = array("1" => "Ene","2" => "Feb", "3" => "Mar", "4" => "Abr", "5" => "May", "6" => "Jun", "7" => "Jul", "8" => "Ago", "9" => "Set", "10" => "Oct", "11" => "Nov", "12" => "Dic");
        $cboFrecuencia = array("Mensual"=>"Mensual","Bimestral"=>"Bimestral","Trimestral"=>"Trimestral","Semestral"=>"Semestral","Anual"=>"Anual");
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('programa', 'formData', 'entidad', 'boton', 'listar','cboAnio','cboArea','cboFrecuencia','cboMes'));
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
        $existe = Libreria::verificarExistencia($id, 'programa');
        if ($existe !== true) {
            return $existe;
        }
        /*$validacion = Validator::make($request->all(),
            array(
                'fechainicio' => 'required|max:60'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } */
        $dat = array();
        $error = DB::transaction(function() use($request, $id, &$dat){
            $obj       = Programa::find($id);
            $obj->anio = $request->input('anio');
            $obj->area_id = $request->input('area_id');
            $obj->orden = $request->input('orden');
            $obj->nombre = $request->input('nombre');
            $obj->descripcion = $request->input('descripcion');
            $obj->responsable = $request->input('responsable');
            $obj->frecuencia = $request->input('frecuencia');
            $obj->mes = $request->input('mes');
            $obj->observacion = $request->input('observacion');
            $obj->save();
            $dat[0] = array("respuesta" => "OK", "programa_id" => $obj->id, 'accion' => 'update');

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
        $existe = Libreria::verificarExistencia($id, 'programa');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Programa::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'programa');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Programa::find($id);
        $entidad  = 'Programa';
        $formData = array('route' => array('programa.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
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
                $old_image = $request->input('id') . '-' . (Programa::find($request->input('id'))->archivo);
                $old_path = public_path('image-programa/' . $old_image);
                if (file_exists($old_path)) {
                    @unlink($old_path);
                }
            }
            $nombre = $file->getClientOriginalName();
            $path = public_path('image-programa/' . $request->input('id') . '-' . $nombre);

            $file->move('image-programa', $request->input('id') . '-' . $nombre);

            $calibracion = Programa::find($request->input('id'));
            $calibracion->archivo = $nombre;
            $calibracion->save();
            return "archivo guardado";
        } else {
            return "Imagen no enviada";
        }
    }
    
    public function excel(Request $request){
        setlocale(LC_TIME, 'spanish');
        return Excel::download(new ProgramaExport($request->input('anio'),$request->input('nombre')), 'ReportePrograma-'.$request->input('anio').'.xlsx');
    }

}
