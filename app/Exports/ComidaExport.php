<?php

namespace App\Exports;

use App\Comida;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class ComidaExport implements FromView
{
	protected $fechainicio;
	protected $fechafin;
    protected $persona;
    protected $comedor;
    protected $servicio;

    public function __construct($fechainicio, $fechafin, $persona, $comedor, $servicio)
    {
        $this->fechainicio = $fechainicio;
        $this->fechafin = $fechafin;
        $this->persona = $persona;
        $this->comedor = $comedor;
        $this->servicio = $servicio;
    }
    
    public function view(): View
    {
    	$resultado        = Comida::join('comedor','comedor.id','=','comida.comedor_id')
                            ->join('servicio','servicio.id','=','comida.servicio_id')
                            ->orderBy('comida.fecha','desc')
                            ->select('comida.*','servicio.nombre as servicio2','comedor.descripcion as comedor2');
        if($this->fechainicio!=""){
            $resultado = $resultado->where(DB::raw('date(comida.fecha)'),'>=',DB::raw("'".$this->fechainicio."'"));
        }
        if($this->fechafin!=""){
            $resultado = $resultado->where(DB::raw('date(comida.fecha)'),'<=',DB::raw("'".$this->fechafin."'"));
        }
        if($this->persona!=""){
            $resultado = $resultado->where('comida.persona','like',DB::raw("'%".$this->persona."%'"));
        }
        if($this->comedor!="0"){
            $resultado = $resultado->where('comida.comedor_id','=',$this->comedor);
        }
        if($this->servicio!="0"){
            $resultado = $resultado->where('comida.servicio_id','=',$this->servicio);
        }
        $lista1            = $resultado->orderBy('comida.servicio_id','asc')->orderBy('comida.comedor_id','asc')->get();
        $fechainicio = $this->fechainicio;
        $fechafin = $this->fechafin;
        return view('exports.comida')->with(compact('lista1','fechainicio','fechafin'));
    }

   
}
