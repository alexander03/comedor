<?php

namespace App\Exports;

use App\Documento;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DocumentoExport implements FromView
{
	protected $descripcion;
	protected $tipodocumento_id;
    protected $proceso_id;

    public function __construct($descripcion, $tipodocumento_id, $proceso_id)
    {
        $this->descripcion = $descripcion;
        $this->tipodocumento_id = $tipodocumento_id;
        $this->proceso_id = $proceso_id;
    }
    
    public function view(): View
    {
    	$descripcion      = $this->descripcion;
        $tipodocumento    = $this->tipodocumento_id;
        $proceso          = $this->proceso_id;
        $resultado        = Documento::join('tipodocumento','tipodocumento.id','=','documento.tipodocumento_id')
                                ->where('documento.descripcion','like','%'.$descripcion.'%');
        if($tipodocumento!="0"){
            $resultado = $resultado->where('documento.tipodocumento_id','=',$tipodocumento);
        }
        if($proceso!="0"){
            $resultado = $resultado->where('documento.proceso_id','=',$proceso);
        }
        
        $lista            = $resultado->select('documento.*')->orderBy('tipodocumento.descripcion','desc')->orderBy('documento.descripcion','desc')->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'N°', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Tipo Doc.', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Codigo', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Revision', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Fecha Aprobacion', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Proceso Involucrado', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Observacion', 'numero' => '1','rows'=>'1');
        $cabecera[]       = array('valor' => 'Situacion', 'numero' => '1','rows'=>'1');
        return view('exports.documento')->with(compact('lista','cabecera'));
    }

   
}
