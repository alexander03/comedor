<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Indicador;
use App\Indicadorbase;
use App\Proceso;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Excel;
use App\Person;
use App\Exports\IndicadorExport;

class IndicadorController extends Controller
{
    protected $folderview      = 'app.indicador';
    protected $tituloAdmin     = 'Indicador';
    protected $tituloRegistrar = 'Registrar indicador';
    protected $tituloModificar = 'Modificar indicador';
    protected $tituloEliminar  = 'Eliminar indicador';
    protected $rutas           = array('create' => 'indicador.create', 
            'edit'     => 'indicador.edit', 
            'delete'   => 'indicador.eliminar',
            'search'   => 'indicador.buscar',
            'index'    => 'indicador.index',
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
        $entidad          = 'Indicador';
        $name             = Libreria::getParam($request->input('nombre'));
        $anio             = Libreria::getParam($request->input('anio'),'');
        $resultado        = Indicador::join('proceso','proceso.id','=','indicador.proceso_id')
                                ->where('indicador.indicador','like','%'.$name.'%');
        if($anio!=""){
            $resultado = $resultado->where('indicador.anio','=',$anio);
        }
        $lista            = $resultado->select('indicador.*')->orderBy('indicador.orden','asc')->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'N°', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Proceso', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Indicador', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Objectio del Indicador', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Fórmula', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Parámetro de Aceptacion', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Frecuencia', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Responsable de Generación de Data', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Responsable de Recopilar de Información', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Responsable de Análisis', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Año: '.$anio, 'numero' => '12','rows'=>'1');
        $cabecera[]       = array('valor' => 'Acumulado', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Observaciones/Acciones', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Responsable', 'numero' => '1','rows'=>'2');
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta','cabecera2','user'));
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
        $entidad          = 'Indicador';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboAnio = array("2022"=>"2022","2023"=>"2023","2024"=>"2024","2025"=>"2025","2026"=>"2026","2027"=>"2027");
        $user     = Auth::User();
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboAnio','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Indicador';
        $indicador  = null;
        $formData     = array('indicador.store');
        $cboAnio = array("2022"=>"2022","2023"=>"2023","2024"=>"2024","2025"=>"2025","2026"=>"2026","2027"=>"2027");
        $cboProceso = array();
        $proceso = Proceso::orderBy('descripcion', 'asc')->get();
        foreach ($proceso as $k => $v) {
            $cboProceso = $cboProceso + array($v->id => $v->descripcion);
        }
        $cboPersona = array();
        $persona = Person::where('id','>',1)->orderBy('apellidopaterno', 'asc')->get();
        foreach ($persona as $k => $v) {
            $cboPersona = $cboPersona + array($v->id => $v->apellidopaterno.' '.$v->apellidomaterno.' '.$v->nombres);
        }
        $cboFrecuencia = array("Mensual"=>"Mensual","Bimestral"=>"Bimestral","Trimestral"=>"Trimestral","Semestral"=>"Semestral","Anual"=>"Anual");
        $cboSubcategoria = array();
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        $user     = Auth::User();
        return view($this->folderview.'.mant')->with(compact('indicador', 'formData', 'entidad', 'boton', 'listar','cboAnio','cboProceso','cboFrecuencia','user','cboPersona'));
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
            $lstResponsable = $request->input('lstResponsable');
            $personas = "";
            for($x=0;$x<count($lstResponsable);$x++){
                $personas .=$lstResponsable[$x]."|";
            }
            $personas = substr($personas,0,strlen($personas)-1);
            
            $lstFrecuencia = $request->input('txtFrecuencia');
            $valores = "";
            for($x=0;$x<count($lstFrecuencia);$x++){
                $valores .=$lstFrecuencia[$x]."|";
            }
            $valores = substr($valores,0,strlen($valores)-1);
            $lstMinimo = $request->input('lstMinimo');
            $lstMaximo = $request->input('lstMaximo');
            $lstColor = $request->input('lstColor');
            $parametro = "";
            for($x=0;$x<count($lstMinimo);$x++){
                $parametro .=$lstMinimo[$x]."@".$lstMaximo[$x]."@".$lstColor[$x]."|";
            }
            $parametro = substr($parametro,0,strlen($parametro)-1);
            
            $obj       = new Indicador();
            $obj->anio = $request->input('anio');
            $obj->proceso_id = $request->input('proceso_id');
            $obj->indicadorbase_id = $request->input('indicadorbase_id');
            $obj->orden = $request->input('orden');
            $obj->indicador = $request->input('indicador');
            $obj->objetivo = $request->input('objetivo');
            $obj->formula = $request->input('formula');
            $obj->parametro = $parametro;
            $obj->valores = $valores;
            $obj->responsable = $request->input('responsable');
            $obj->responsable1 = $request->input('responsable1');
            $obj->responsable2 = $request->input('responsable2');
            $obj->responsable3 = $request->input('responsable3');
            $obj->frecuencia = $request->input('frecuencia');
            $obj->observaciones = $request->input('observaciones');
            $obj->listaresponsable = $personas;
            $obj->save();
            $dat[0] = array("respuesta" => "OK", "indicador_id" => $obj->id, 'accion' => 'store');
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
        $existe = Libreria::verificarExistencia($id, 'indicador');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $indicador  = Indicador::find($id);
        $entidad      = 'Indicador';
        $formData     = array('indicador.update', $id);
        $cboAnio = array("2022"=>"2022","2023"=>"2023","2024"=>"2024","2025"=>"2025","2026"=>"2026","2027"=>"2027");
        $cboProceso = array();
        $proceso = Proceso::orderBy('descripcion', 'asc')->get();
        foreach ($proceso as $k => $v) {
            $cboProceso = $cboProceso + array($v->id => $v->descripcion);
        }
        $cboPersona = array();
        $persona = Person::where('id','>',1)->orderBy('apellidopaterno', 'asc')->get();
        foreach ($persona as $k => $v) {
            $cboPersona = $cboPersona + array($v->id => $v->apellidopaterno.' '.$v->apellidomaterno.' '.$v->nombres);
        }
        $cboFrecuencia = array("Mensual"=>"Mensual","Bimestral"=>"Bimestral","Trimestral"=>"Trimestral","Semestral"=>"Semestral","Anual"=>"Anual");
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        $user     = Auth::User();
        return view($this->folderview.'.mant')->with(compact('indicador', 'formData', 'entidad', 'boton', 'listar','cboAnio','cboProceso','cboFrecuencia','user','cboPersona'));
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
        $existe = Libreria::verificarExistencia($id, 'indicador');
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
        $user     = Auth::User();
        
        $error = DB::transaction(function() use($request, $id, $user, &$dat){
            $lstResponsable = $request->input('lstResponsable');
            $personas = "";
            for($x=0;$x<count($lstResponsable);$x++){
                $personas .=$lstResponsable[$x]."|";
            }
            $personas = substr($personas,0,strlen($personas)-1);
            
            $lstFrecuencia = $request->input('txtFrecuencia');
            $valores = "";
            for($x=0;$x<count($lstFrecuencia);$x++){
                $valores .=$lstFrecuencia[$x]."|";
            }
            $valores = substr($valores,0,strlen($valores)-1);
            $lstMinimo = $request->input('lstMinimo');
            $lstMaximo = $request->input('lstMaximo');
            $lstColor = $request->input('lstColor');
            $parametro = "";
            for($x=0;$x<count($lstMinimo);$x++){
                $parametro .=$lstMinimo[$x]."@".$lstMaximo[$x]."@".$lstColor[$x]."|";
            }
            $parametro = substr($parametro,0,strlen($parametro)-1);
            
            $obj       = Indicador::find($id);
            $obj->anio = $request->input('anio');
            $obj->proceso_id = $request->input('proceso_id');
            $obj->indicadorbase_id = $request->input('indicadorbase_id');
            $obj->orden = $request->input('orden');
            $obj->indicador = $request->input('indicador');
            $obj->objetivo = $request->input('objetivo');
            $obj->formula = $request->input('formula');
            $obj->parametro = $parametro;
            $obj->valores = $valores;
            $obj->responsable = $request->input('responsable');
            $obj->responsable1 = $request->input('responsable1');
            $obj->responsable2 = $request->input('responsable2');
            $obj->responsable3 = $request->input('responsable3');
            $obj->frecuencia = $request->input('frecuencia');
            $obj->listaresponsable = $personas;
            if($user->usertype_id=="5" && $request->input('archivo2')=="Sin sustento"){
                $obj->observaciones = $request->input('observaciones'). " / Falta sustento";
            }else{
                $obj->observaciones = $request->input('observaciones');
            }
            $obj->save();
            $dat[0] = array("respuesta" => "OK", "indicador_id" => $obj->id, 'accion' => 'update');

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
        $existe = Libreria::verificarExistencia($id, 'indicador');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Indicador::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'indicador');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Indicador::find($id);
        $entidad  = 'Indicador';
        $formData = array('route' => array('indicador.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
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
                $old_image = $request->input('id') . '-' . (Indicador::find($request->input('id'))->archivo);
                $old_path = public_path('image-indicador/' . $old_image);
                if (file_exists($old_path)) {
                    @unlink($old_path);
                }
            }
            $nombre = $file->getClientOriginalName();
            $path = public_path('image-indicador/' . $request->input('id') . '-' . $nombre);

            $file->move('image-indicador', $request->input('id') . '-' . $nombre);

            $calibracion = Indicador::find($request->input('id'));
            $calibracion->archivo = $nombre;
            $calibracion->save();
            return "archivo guardado";
        } else {
            return "Imagen no enviada";
        }
    }
    
    public function excel(Request $request){
        setlocale(LC_TIME, 'spanish');
        return Excel::download(new IndicadorExport($request->input('anio'),$request->input('nombre')), 'ReporteIndicador-'.$request->input('anio').'.xlsx');
    }
    
    public function indicadorbaseautocompletar($searching,$proceso_id)
    {
        $resultado        = Indicadorbase::
            where(function ($sql) use ($searching) {
                $sql->where('nombre', 'LIKE', '%' . strtoupper($searching) . '%');
            })
            ->where('proceso_id','=',$proceso_id)
            ->whereNull('indicadorbase.deleted_at')->orderBy('nombre', 'ASC');
        $list      = $resultado->select('indicadorbase.*')->get();
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = array(
                'label' => trim($value->nombre),
                'id'    => $value->id,
                'value' => trim($value->nombre),
                'objetivo' => $value->objetivo,
                'indicador' => $value->indicador,
                'formula' => $value->formula,
                'frecuencia' => $value->frecuencia,
                'parametro' => $value->parametro,
            );
        }
        return json_encode($data);
    }

}
