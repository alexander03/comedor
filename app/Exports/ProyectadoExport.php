<?php

namespace App\Exports;

use App\Proyectado;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ProyectadoExport implements FromView
{
	protected $fechainicio;
	protected $fechafin;
    protected $comedor;

    public function __construct($fechainicio,$fechafin,$comedor)
    {
        $this->fechainicio = $fechainicio;
        $this->fechafin = $fechafin;
        $this->comedor = $comedor;
    }
    
    public function view(): View
    {
        $fechainicio = $this->fechainicio;
        $fechafin = $this->fechafin;
        $comedor = $this->comedor;
    	$resultado        = Proyectado::where('fecha','>=',$fechainicio)->where('fecha','<=',$fechafin)->orderBy('fecha','desc');
        if($comedor!="0"){
            $resultado = $resultado->where('comedor_id','=',$comedor);
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Desayuno Proyecto', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Desayuno', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Almuerzo Proyectado', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Almuerzo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cena Proyectado', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cena', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Comedor', 'numero' => '1');
        return view('exports.proyectado')->with(compact('lista','fechainicio','fechafin','cabecera'));
    }

   
}
