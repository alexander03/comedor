<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Equipo;
use App\Person;
use App\Categoria;
use App\Subcategoria;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EquipoController extends Controller
{
    protected $folderview      = 'app.equipo';
    protected $tituloAdmin     = 'Equipo';
    protected $tituloRegistrar = 'Registrar equipo';
    protected $tituloModificar = 'Modificar equipo';
    protected $tituloEliminar  = 'Eliminar equipo';
    protected $rutas           = array('create' => 'equipo.create', 
            'edit'     => 'equipo.edit', 
            'delete'   => 'equipo.eliminar',
            'search'   => 'equipo.buscar',
            'index'    => 'equipo.index',
            'viewCalibracion'  => 'equipo.viewCalibracion',
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
        $entidad          = 'Equipo';
        $name             = Libreria::getParam($request->input('name'));
        $serie             = Libreria::getParam($request->input('serie'));
        $resultado        = Equipo::where('descripcion','like','%'.strtoupper($name).'%')->where('serie','like','%'.$serie.'%');
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Categoria', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Subcategoria', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Codigo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Descripcion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Marca', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Modelo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Serie', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha Adq.', 'numero' => '1');
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
        $entidad          = 'Equipo';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Equipo';
        $equipo  = null;
        $formData     = array('equipo.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        $cboCategoria = array();
        $categoria = Categoria::orderBy('descripcion', 'asc')->get();
        foreach ($categoria as $k => $v) {
            $cboCategoria = $cboCategoria + array($v->id => $v->descripcion);
        }
        $cboSubcategoria = array();
        return view($this->folderview.'.mant')->with(compact('equipo', 'formData', 'entidad', 'boton', 'listar','cboCategoria','cboSubcategoria'));
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
                'descripcion' => 'required|max:100'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $equipo       = new Equipo();
            $equipo->codigo = $request->input('codigo');
            $equipo->descripcion = $request->input('descripcion');
            $equipo->marca = $request->input('marca');
            $equipo->modelo = $request->input('modelo');
            $equipo->serie = $request->input('serie');
            $equipo->fechaadquisicion = $request->input('fechaadquisicion');
            $equipo->subcategoria_id = $request->input('subcategoria_id');
            $equipo->save();
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
        $existe = Libreria::verificarExistencia($id, 'equipo');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $equipo  = Equipo::find($id);
        $entidad      = 'Equipo';
        $formData     = array('equipo.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        $cboCategoria = array();
        $categoria = Categoria::orderBy('descripcion', 'asc')->get();
        foreach ($categoria as $k => $v) {
            $cboCategoria = $cboCategoria + array($v->id => $v->descripcion);
        }
        $cboSubcategoria = array();
        $subcategoria = Subcategoria::orderBy('descripcion', 'asc')->get();
        foreach ($subcategoria as $k => $v) {
            $cboSubcategoria = $cboSubcategoria + array($v->id => $v->descripcion);
        }
        return view($this->folderview.'.mant')->with(compact('equipo', 'formData', 'entidad', 'boton', 'listar','cboCategoria','cboSubcategoria'));
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
        $existe = Libreria::verificarExistencia($id, 'equipo');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'descripcion' => 'required|max:100'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $equipo       = Equipo::find($id);
            $equipo->codigo = $request->input('codigo');
            $equipo->descripcion = $request->input('descripcion');
            $equipo->marca = $request->input('marca');
            $equipo->modelo = $request->input('modelo');
            $equipo->serie = $request->input('serie');
            $equipo->fechaadquisicion = $request->input('fechaadquisicion');
            $equipo->subcategoria_id = $request->input('subcategoria_id');
            $equipo->save();

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
        $existe = Libreria::verificarExistencia($id, 'equipo');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = Equipo::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'equipo');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Equipo::find($id);
        $entidad  = 'Equipo';
        $formData = array('route' => array('equipo.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }
    
    public function viewCalibracion($id, $listarLuego)
    {
        $equipo = Equipo::find($id);
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $calibracion  = null;
        $entidad      = 'Calibracion';
        $formData     = array('calibracion.store');
        $cboAnio = array("2022"=>"2022","2023"=>"2023","2024"=>"2024","2025"=>"2025","2026"=>"2026","2027"=>"2027");
        $cboCategoria = array();
        $categoria = Categoria::orderBy('descripcion', 'asc')->get();
        foreach ($categoria as $k => $v) {
            $cboCategoria = $cboCategoria + array($v->id => $v->descripcion);
        }
        $cboSubcategoria = array();
        $subcategoria = Subcategoria::where('categoria_id','=',$equipo->subcategoria->categoria_id)->orderBy('descripcion', 'asc')->get();
        foreach ($subcategoria as $k => $v) {
            $cboSubcategoria = $cboSubcategoria + array($v->id => $v->descripcion);
        }
        $cboPersona = array();
        $persona = Person::where('id','>',1)->orderBy('apellidopaterno', 'asc')->get();
        foreach ($persona as $k => $v) {
            $cboPersona = $cboPersona + array($v->id => $v->apellidopaterno.' '.$v->apellidomaterno.' '.$v->nombres);
        }
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Modificar';
        return view('app.calibracion.mant')->with(compact('calibracion', 'formData', 'entidad', 'boton', 'listar','cboAnio','cboCategoria','cboSubcategoria','cboPersona','equipo'));
    }

}
