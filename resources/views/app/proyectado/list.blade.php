<?php
use App\Comida;
use App\Pedido;
?>
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<div class="row">
	<div class="col-9">
		{!! $paginacion  !!}
	</div>
</div>
<table id="example1" class="table table-sm text-center table-striped  table-hover">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th class="text-center" @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		date_default_timezone_set("America/Caracas");
		setlocale(LC_ALL, 'es_VE.UTF-8','esp');
		?>
		@foreach ($lista as $key => $value)
			@php
			$resultado2        = Comida::orderBy('comida.fecha','desc')
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
			<td>{{ strftime("%A %d de %B del %Y",strtotime($value->fecha)) }}</td>
			<td>{{ $value->desayuno }}</td>
			<td>{{ $desayuno }}</td>
			<td>{{ $value->almuerzo }}</td>
			<td>{{ $almuerzo }}</td>
			<td>{{ $value->cena }}</td>
			<td>{{ $cena }}</td>
			<td>{{ $value->comedor->descripcion }}</td>
			<td>{{ $value->user->person->nombres }}</td>
			<!--td>{!! Form::button('<div class="fas fa-edit"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-sm btn-outline-success')) !!}</td>
            <td>{!! Form::button('<div class="fas fa-trash-alt"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-outline-danger')) !!}</td-->
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			@foreach($cabecera as $key => $value)
				<th @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</tfoot>
</table>
{!! $paginacion !!}
@endif