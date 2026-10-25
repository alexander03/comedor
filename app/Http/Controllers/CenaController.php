<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Cena;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CenaController extends Controller
{
    protected $folderview      = 'app.cena';
    protected $tituloAdmin     = 'Cena';
    protected $entidad         = 'Cena';
    protected $tituloRegistrar = 'Registrar Cena';
    protected $tituloModificar = 'Modificar Cena';
    protected $tituloEliminar  = 'Eliminar Cena';
    protected $rutas           = array('create' => 'cena.create', 
            'edit'     => 'cena.edit', 
            'delete'   => 'cena.eliminar',
            'search'   => 'cena.buscar',
            'index'    => 'cena.index',
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
        $resultado        = Cena::where('fecha','>=',$fechainicio)
                                    ->where('fecha','<=',$fechafin)
                                    ->orderBy('fecha','asc');
        if($dieta!=""){
            $resultado = $resultado->where('dieta','like','%'.$dieta.'%');
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Sopa', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Plato de Fondo', 'numero' => '1');        
        //$cabecera[]       = array('valor' => 'Plato de Fondo 2', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Guarnicion 2', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Infusión', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Pan', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Salad Bar', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Postre o Fruta', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Opcional', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Dieta', 'numero' => '1');
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
        $cena  = null;
        $formData     = array('cena.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        $cboDieta = array(''=>'Seleccione','S' => 'SI','N' => 'No');
        return view($this->folderview.'.mant')->with(compact('cena', 'formData', 'entidad', 'boton', 'listar','cboDieta'));
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
                    'dieta' => 'required',
                    )
                );
        }else{
            $validacion = Validator::make($request->all(),
                array(
                    'fecha' => 'required',
                    //'salad' => 'required',
                    //'kcal_salad' => 'required',
                    //'fruta' => 'required',
                    //'kcal_fruta' => 'required',
                    //'pan' => 'required',
                    //'kcal_pan' => 'required',
                    //'sopa' => 'required',
                    //'kcal_sopa' => 'required',
                    'fondo' => 'required',
                    'kcal_fondo' => 'required',
                    //'guarnicion1' => 'required',
                    //'kcal_guarnicion1' => 'required',
                    //'guarnicion2' => 'required',
                    //'kcal_guarnicion2' => 'required',
                    //'infusion' => 'required',
                    //'kcal_infusion' => 'required',
                    //'opcional' => 'required',
                    //'kcal_opcional' => 'required',
                    'dieta' => 'required',
                    )
                );
        }
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $obj       = new Cena();
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
                $obj->salad = '';
                $obj->kcal_salad = 0;
                $obj->pan = '';
                $obj->kcal_pan = 0;
                $obj->infusion = '';
                $obj->kcal_infusion = 0;
                $obj->sopa = '';
                $obj->kcal_sopa = 0;
                $obj->opcional = '';
                $obj->kcal_opcional = 0;
            }else{
                $obj->salad = $request->input('salad');
                $obj->kcal_salad = $request->input('kcal_salad');
                $obj->pan = $request->input('pan');
                $obj->kcal_pan = $request->input('kcal_pan');
                $obj->infusion = $request->input('infusion');
                $obj->kcal_infusion = $request->input('kcal_infusion');
                $obj->sopa = $request->input('sopa');
                $obj->kcal_sopa = $request->input('kcal_sopa');
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
        $existe = Libreria::verificarExistencia($id, 'cena');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $cena  = Cena::find($id);
        $entidad          = $this->entidad;
        $formData     = array('cena.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        $cboDieta = array(''=>'Seleccione','S' => 'SI','N' => 'No');
        return view($this->folderview.'.mant')->with(compact('cena', 'formData', 'entidad', 'boton', 'listar','cboDieta'));
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
        $existe = Libreria::verificarExistencia($id, 'cena');
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
                    'dieta' => 'required',
                    )
                );
        }else{
            $validacion = Validator::make($request->all(),
                array(
                    'fecha' => 'required',
                    //'salad' => 'required',
                    //'kcal_salad' => 'required',
                    //'fruta' => 'required',
                    //'kcal_fruta' => 'required',
                    //'pan' => 'required',
                    //'kcal_pan' => 'required',
                    //'sopa' => 'required',
                    //'kcal_sopa' => 'required',
                    'fondo' => 'required',
                    'kcal_fondo' => 'required',
                    //'guarnicion1' => 'required',
                    //'kcal_guarnicion1' => 'required',
                    //'guarnicion2' => 'required',
                    //'kcal_guarnicion2' => 'required',
                    //'infusion' => 'required',
                    //'kcal_infusion' => 'required',
                    //'opcional' => 'required',
                    //'kcal_opcional' => 'required',
                    'dieta' => 'required',
                    )
                );
        }
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $obj       = Cena::find($id);
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
                $obj->salad = '';
                $obj->kcal_salad = 0;
                $obj->pan = '';
                $obj->kcal_pan = 0;
                $obj->infusion = '';
                $obj->kcal_infusion = 0;
                $obj->sopa = '';
                $obj->kcal_sopa = 0;
                $obj->opcional = '';
                $obj->kcal_opcional = 0;
            }else{
                $obj->salad = $request->input('salad');
                $obj->kcal_salad = $request->input('kcal_salad');
                $obj->pan = $request->input('pan');
                $obj->kcal_pan = $request->input('kcal_pan');
                $obj->infusion = $request->input('infusion');
                $obj->kcal_infusion = $request->input('kcal_infusion');
                $obj->sopa = $request->input('sopa');
                $obj->kcal_sopa = $request->input('kcal_sopa');
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
        $existe = Libreria::verificarExistencia($id, 'cena');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Cena::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'cena');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Cena::find($id);
        $entidad          = $this->entidad;
        $formData = array('route' => array('cena.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
