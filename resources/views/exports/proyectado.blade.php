<?php
use App\Pedido;
use App\Comida;
?>
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<table id="example1" class="table table-sm text-center table-striped  table-hover">

    <thead>
        <tr>
            <th style="width: 15px"><b>FECHA</b></th>
            <th style="width: 20px"><b>DESAYUNO PROYECTADO</b></th>
            <th style="width: 20px"><b>DESAYUNO</b></th>
            <th style="width: 20px"><b>ALMUERZO PROYECTADO</b></th>
            <th style="width: 20px"><b>ALMUERZO</b></th>
            <th style="width: 20px"><b>CENA PROYECTADO</b></th>
            <th style="width: 20px"><b>CENA</b></th>
            <th style="width: 20px"><b>COMEDOR</b></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $contador = 0;$id="";
        ?>
        @foreach ($lista as $key => $value)
            @php
            $resultado2        = Comida::join('comedor','comedor.id','=','comida.comedor_id')
                                ->orderBy('comida.fecha','desc')
                                ->selectRaw('sum(case when comida.servicio_id=1 then 1 else 0 end) as desayuno, sum(case when comida.servicio_id=2 then 1 else 0 end) as almuerzo, sum(case when comida.servicio_id=3 then 1 else 0 end) as cena')
                                ;
            $resultado2 = $resultado2->where(DB::raw('date(comida.fecha)'),'=',DB::raw("'".$value->fecha."'"));
            $resultado2 = $resultado2->where('comida.comedor_id','=',$value->comedor_id);
            $lista2            = $resultado2->first();
            $desayuno = $lista2->desayuno;
            $almuerzo = $lista2->almuerzo;
            $cena = $lista2->cena;
            $resultado = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                        ->whereNull('detallepedido.deleted_at')
                        ->whereNull('pedido.deleted_at')
                        ->where(DB::raw('date(pedido.fecha)'),'=',DB::raw("'".$value->fecha."'"))
                        ->where('pedido.concesionaria_id','=',$value->comedor_id)
                        ->whereIn('detallepedido.servicio_id',[1,9])
                        ->select(DB::raw('count(detallepedido.id) as cantidad'))
                        ->first();
            if(!is_null($resultado)){
                $desayuno = $desayuno + $resultado->cantidad;
            }

            $resultado = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                        ->orderBy('pedido.solicitante','desc')
                        ->whereNull('detallepedido.deleted_at')
                        ->where(DB::raw('date(pedido.fecha)'),'=',DB::raw("'".$value->fecha."'"))
                        ->where('pedido.concesionaria_id','=',$value->comedor_id)
                        ->whereIn('detallepedido.servicio_id',[2,10])
                        ->select(DB::raw('count(detallepedido.id) as cantidad'))
                        ->first();
            if(!is_null($resultado)){
                $almuerzo = $almuerzo + $resultado->cantidad;
            }

            $resultado = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                        ->orderBy('pedido.solicitante','desc')
                        ->whereNull('detallepedido.deleted_at')
                        ->where(DB::raw('date(pedido.fecha)'),'=',DB::raw("'".$value->fecha."'"))
                        ->where('pedido.concesionaria_id','=',$value->comedor_id)
                        ->whereIn('detallepedido.servicio_id',[3,11])
                        ->select(DB::raw('count(detallepedido.id) as cantidad'))
                        ->first();
            if(!is_null($resultado)){
                $cena = $cena + $resultado->cantidad;
            }
            @endphp
        <tr>
            <td>{{ date("d/m/Y",strtotime($value->fecha)) }}</td>
            <td>{{ $value->desayuno }}</td>
            <td>{{ $desayuno }}</td>
            <td>{{ $value->almuerzo }}</td>
            <td>{{ $almuerzo }}</td>
            <td>{{ $value->cena }}</td>
            <td>{{ $cena }}</td>
            <td>{{ $value->comedor->descripcion }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif