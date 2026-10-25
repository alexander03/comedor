<?php

namespace App\Exports;

use App\Programa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProgramaExport implements FromView
{
	protected $nombre;
	protected $anio;

    public function __construct($anio, $nombre)
    {
        $this->anio = $anio;
        $this->nombre = $nombre;
    }
    
    public function view(): View
    {
    	$resultado        = Programa::join('area','area.id','=','programa.area_id')
                                ->where('programa.nombre','like','%'.$this->nombre.'%');
        if($this->anio!=""){
            $resultado = $resultado->where('programa.anio','=',$this->anio);
        }
        $lista            = $resultado->select('programa.*')->orderBy('area.orden','asc')->orderBy('programa.orden','asc')->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'Item', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Detalle', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Descripcion', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Responsable', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Frecuencia', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Año: '.$this->anio, 'numero' => '12','rows'=>'1');
        $cabecera[]       = array('valor' => 'Observaciones', 'numero' => '1','rows'=>'2');
        
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
        return view('exports.programa')->with(compact('lista','anio','cabecera','cabecera2'));
    }

   
}
