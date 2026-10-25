<?php

namespace App\Exports;

use App\Movilizacion;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class MovilizacionExport implements FromView
{
	protected $fechainicio;
	protected $fechafin;

    public function __construct($fechainicio, $fechafin)
    {
        $this->fechainicio = $fechainicio;
        $this->fechafin = $fechafin;
    }
    
    public function view(): View
    {
    	$resultado        = Movilizacion::join('lugar as origen','origen.id','=','movilizacion.origen_id')
                            ->join('lugar as destino','destino.id','=','movilizacion.destino_id')
                            ->orderBy('movilizacion.fecha','desc')
                            ->select('movilizacion.*','origen.nombre as origen','destino.nombre as destino');
        if($this->fechainicio!=""){
            $resultado = $resultado->where(DB::raw('date(movilizacion.fecha)'),'>=',DB::raw("'".$this->fechainicio."'"));
        }
        if($this->fechafin!=""){
            $resultado = $resultado->where(DB::raw('date(movilizacion.fecha)'),'<=',DB::raw("'".$this->fechafin."'"));
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Beneficiario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Empresa', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Campamento', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Origen', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Destino', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tarifa', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Motivo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha Registro', 'numero' => '1');
        return view('exports.movilizacion')->with(compact('lista','cabecera'));
    }

   
}
