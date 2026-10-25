<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Desayuno;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DesayunoController extends Controller
{
    protected $folderview      = 'app.desayuno';
    protected $tituloAdmin     = 'Desayuno';
    protected $entidad         = 'Desayuno';
    protected $tituloRegistrar = 'Registrar Desayuno';
    protected $tituloModificar = 'Modificar Desayuno';
    protected $tituloEliminar  = 'Eliminar Desayuno';
    protected $rutas           = array('create' => 'desayuno.create', 
            'edit'     => 'desayuno.edit', 
            'delete'   => 'desayuno.eliminar',
            'search'   => 'desayuno.buscar',
            'index'    => 'desayuno.index',
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
        $entidad          = $this->entidad;
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $dieta            = Libreria::getParam($request->input('dieta'));
        $resultado        = Desayuno::where('fecha','>=',$fechainicio)
                                    ->where('fecha','<=',$fechafin)
                                    ->orderBy('fecha','asc');
        if($dieta!=""){
            $resultado = $resultado->where('dieta','like','%'.$dieta.'%');
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Jugo de Fruta', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Fruta/Postre', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Cereal Caliente', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Plato de Fondo o Sopa', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Guarnicion 1', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Guarnicion 2', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Huevo a la Orden', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Pan', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Café o Infusión', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Opcional', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Dieta', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3');
        
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta'));
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
        $entidad          = $this->entidad;
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboDieta = array(''=>'Todos','S' => 'SI','N' => 'No');
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboDieta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = $this->entidad;
        $desayuno  = null;
        $formData     = array('desayuno.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        $cboDieta = array(''=>'Seleccione','S' => 'SI','N' => 'No');
        return view($this->folderview.'.mant')->with(compact('desayuno', 'formData', 'entidad', 'boton', 'listar','cboDieta'));
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
        if($request->input('dieta')=="S"){
            $validacion = Validator::make($request->all(),
                array(
                    'fecha' => 'required',
                    //'fruta' => 'required',
                    //'kcal_fruta' => 'required',
                    'fondo' => 'required',
                    'kcal_fondo' => 'required',
                    //'guarnicion1' => 'required',
                    //'kcal_guarnicion1' => 'required',
                    //'guarnicion2' => 'required',
                    //'kcal_guarnicion2' => 'required',
                    //'dieta' => 'required',
                    )
                );
        }else{
            $validacion = Validator::make($request->all(),
                array(
                    'fecha' => 'required',
                    //'jugo' => 'required',
                    //'kcal_jugo' => 'required',
                    //'fruta' => 'required',
                    //'kcal_fruta' => 'required',
                    //'cereal' => 'required',
                    //'kcal_cereal' => 'required',
                    'fondo' => 'required',
                    'kcal_fondo' => 'required',
                    //'guarnicion1' => 'required',
                    //'kcal_guarnicion1' => 'required',
                    //'guarnicion2' => 'required',
                    //'kcal_guarnicion2' => 'required',
                    //'huevo' => 'required',
                    //'kcal_huevo' => 'required',
                    //'pan' => 'required',
                    //'kcal_pan' => 'required',
                    //'cafe' => 'required',
                    //'kcal_cafe' => 'required',
                    //'opcional' => 'required',
                    //'kcal_opcional' => 'required',
                    //'dieta' => 'required',
                    )
                );
        }
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $obj       = new Desayuno();
            $obj->dieta = $request->input('dieta');
            $obj->fecha = $request->input('fecha');
            $obj->guarnicion1 = $request->input('guarnicion1');
            $obj->kcal_guarnicion1 = $request->input('kcal_guarnicion1');
            $obj->guarnicion2 = $request->input('guarnicion2');
            $obj->kcal_guarnicion2 = $request->input('kcal_guarnicion2');
            $obj->fruta = $request->input('fruta');
            $obj->kcal_fruta = $request->input('kcal_fruta');
            $obj->fondo = $request->input('fondo');
            $obj->kcal_fondo = $request->input('kcal_fondo');
            if($obj->dieta=="S"){
                $obj->jugo = '';
                $obj->kcal_jugo = 0;
                $obj->cereal = '';
                $obj->kcal_cereal = 0;
                $obj->huevo = '';
                $obj->kcal_huevo = 0;
                $obj->pan = '';
                $obj->kcal_pan = 0;
                $obj->cafe = '';
                $obj->kcal_cafe = 0;
                $obj->opcional = '';
                $obj->kcal_opcional = 0;
            }else{
                $obj->jugo = $request->input('jugo');
                $obj->kcal_jugo = $request->input('kcal_jugo');
                $obj->cereal = $request->input('cereal');
                $obj->kcal_cereal = $request->input('kcal_cereal');
                $obj->huevo = $request->input('huevo');
                $obj->kcal_huevo = $request->input('kcal_huevo');
                $obj->pan = $request->input('pan');
                $obj->kcal_pan = $request->input('kcal_pan');
                $obj->cafe = $request->input('cafe');
                $obj->kcal_cafe = $request->input('kcal_cafe');
                $obj->opcional = $request->input('opcional');
                $obj->kcal_opcional = $request->input('kcal_opcional');
            }
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
        $existe = Libreria::verificarExistencia($id, 'desayuno');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $desayuno  = Desayuno::find($id);
        $entidad          = $this->entidad;
        $formData     = array('desayuno.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        $cboDieta = array(''=>'Seleccione','S' => 'SI','N' => 'No');
        return view($this->folderview.'.mant')->with(compact('desayuno', 'formData', 'entidad', 'boton', 'listar','cboDieta'));
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
        $existe = Libreria::verificarExistencia($id, 'desayuno');
        if ($existe !== true) {
            return $existe;
        }
        if($request->input('dieta')=="S"){
            $validacion = Validator::make($request->all(),
                array(
                    'fecha' => 'required',
                    //'fruta' => 'required',
                    //'kcal_fruta' => 'required',
                    'fondo' => 'required',
                    'kcal_fondo' => 'required',
                    //'guarnicion1' => 'required',
                    //'kcal_guarnicion1' => 'required',
                    //'guarnicion2' => 'required',
                    //'kcal_guarnicion2' => 'required',
                    //'dieta' => 'required',
                    )
                );
        }else{
            $validacion = Validator::make($request->all(),
                array(
                    'fecha' => 'required',
                    //'jugo' => 'required',
                    //'kcal_jugo' => 'required',
                    //'fruta' => 'required',
                    //'kcal_fruta' => 'required',
                    //'cereal' => 'required',
                    //'kcal_cereal' => 'required',
                    'fondo' => 'required',
                    'kcal_fondo' => 'required',
                    //'guarnicion1' => 'required',
                    //'kcal_guarnicion1' => 'required',
                    //'guarnicion2' => 'required',
                    //'kcal_guarnicion2' => 'required',
                    //'huevo' => 'required',
                    //'kcal_huevo' => 'required',
                    //'pan' => 'required',
                    //'kcal_pan' => 'required',
                    //'cafe' => 'required',
                    //'kcal_cafe' => 'required',
                    //'opcional' => 'required',
                    //'kcal_opcional' => 'required',
                    //'dieta' => 'required',
                    )
                );
        }
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $obj       = Desayuno::find($id);
            $obj->dieta = $request->input('dieta');
            $obj->fecha = $request->input('fecha');
            $obj->guarnicion1 = $request->input('guarnicion1');
            $obj->kcal_guarnicion1 = $request->input('kcal_guarnicion1');
            $obj->guarnicion2 = $request->input('guarnicion2');
            $obj->kcal_guarnicion2 = $request->input('kcal_guarnicion2');
            $obj->fruta = $request->input('fruta');
            $obj->kcal_fruta = $request->input('kcal_fruta');
            $obj->fondo = $request->input('fondo');
            $obj->kcal_fondo = $request->input('kcal_fondo');
            if($obj->dieta=="S"){
                $obj->jugo = '';
                $obj->kcal_jugo = 0;
                $obj->cereal = '';
                $obj->kcal_cereal = 0;
                $obj->huevo = '';
                $obj->kcal_huevo = 0;
                $obj->pan = '';
                $obj->kcal_pan = 0;
                $obj->cafe = '';
                $obj->kcal_cafe = 0;
                $obj->opcional = '';
                $obj->kcal_opcional = 0;
            }else{
                $obj->jugo = $request->input('jugo');
                $obj->kcal_jugo = $request->input('kcal_jugo');
                $obj->cereal = $request->input('cereal');
                $obj->kcal_cereal = $request->input('kcal_cereal');
                $obj->huevo = $request->input('huevo');
                $obj->kcal_huevo = $request->input('kcal_huevo');
                $obj->pan = $request->input('pan');
                $obj->kcal_pan = $request->input('kcal_pan');
                $obj->cafe = $request->input('cafe');
                $obj->kcal_cafe = $request->input('kcal_cafe');
                $obj->opcional = $request->input('opcional');
                $obj->kcal_opcional = $request->input('kcal_opcional');
            }
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
        $existe = Libreria::verificarExistencia($id, 'desayuno');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Desayuno::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'desayuno');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Desayuno::find($id);
        $entidad          = $this->entidad;
        $formData = array('route' => array('desayuno.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
