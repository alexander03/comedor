<?php

namespace App\Exports;

use App\Calibracion;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CalibracionExport implements FromView
{
	protected $equipo;
	protected $anio;
    protected $serie;

    public function __construct($anio, $equipo,$serie)
    {
        $this->anio = $anio;
        $this->equipo = $equipo;
        $this->serie = $serie;
    }
    
    public function view(): View
    {
    	$resultado        = Calibracion::join('equipo','equipo.id','=','calibracion.equipo_id')
                                ->join('subcategoria','calibracion.subcategoria_id','=','subcategoria.id')
                                ->join('categoria','categoria.id','=','subcategoria.categoria_id')
                                ->where('equipo.descripcion','like','%'.$this->equipo.'%')
                                ->where('equipo.serie','like','%'.$this->serie.'%');
        if($this->anio!=""){
            $resultado = $resultado->where('calibracion.anio','=',$this->anio);
        }
        $lista            = $resultado->select('calibracion.*')->orderBy('categoria.descripcion','desc')->orderBy('subcategoria.descripcion','desc')->get();
        $anio = $this->anio;
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'N°', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Equipo', 'numero' => '5','rows'=>'1');
        $cabecera[]       = array('valor' => 'Fecha Adquisición', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Frente de Ubicación', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Área Responsable', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Frecuencia', 'numero' => '2','rows'=>'1');
        $cabecera[]       = array('valor' => 'Certificado', 'numero' => '2','rows'=>'1');
        $cabecera[]       = array('valor' => 'Próxima Calibración / Verifación', 'numero' => '1','rows'=>'2');
        $cabecera[]       = array('valor' => 'Año: '.$this->anio, 'numero' => '12','rows'=>'1');
        $cabecera[]       = array('valor' => 'Comentario', 'numero' => '1','rows'=>'2');
        
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
        return view('exports.calibracion')->with(compact('lista','anio','cabecera','cabecera2'));
    }

   
}
