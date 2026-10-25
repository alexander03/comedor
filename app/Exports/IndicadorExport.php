<?php

namespace App\Exports;

use App\Indicador;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IndicadorExport implements FromView
{
	protected $anio;
	protected $nombre;

    public function __construct($anio, $nombre)
    {
        $this->anio = $anio;
        $this->nombre = $nombre;
    }
    
    public function view(): View
    {
    	$resultado        = Indicador::join('proceso','proceso.id','=','indicador.proceso_id')
                                ->where('indicador.indicador','like','%'.$this->nombre.'%');
        if($this->anio!=""){
            $resultado = $resultado->where('indicador.anio','=',$this->anio);
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
        $cabecera[]       = array('valor' => 'Año: '.$this->anio, 'numero' => '12','rows'=>'1');
        $cabecera[]       = array('valor' => 'Acumulado', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Observaciones/Acciones', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Responsable', 'numero' => '1','rows'=>'2');
        
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
        $anio = $this->anio;
        return view('exports.indicador')->with(compact('lista','anio','cabecera','cabecera2'));
    }

   
}
