<?php
use App\DetallePedidoCarnico;
use App\Carnico;
use Illuminate\Support\Facades\DB;
?>
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<table id="example1" class="table table-sm text-center table-striped  table-hover" border="1">

	<thead>
		<tr>
			<th class="text-center" rowspan="2">{!! date("d/m/Y",strtotime($fechainicio))." - ".date("d/m/Y",strtotime($fechafin)) !!}</th>
			@foreach ($listaServicio as $key => $value)
			@php
				$tot = Carnico::where(DB::raw('concat(\',\',listaServicio2,\',\')'),'like','%,'.$value->id.',%')->get();
			@endphp
				<th colspan="{{ count($tot) }}">{{ $value->nombre }}</th>
			@endforeach
			<th class="text-center" rowspan="2">Total</th>
			<th class="text-center" rowspan="2">Observacion</th>
		</tr>
		<tr>
			@foreach ($listaServicio as $key => $value)
			@php
			$listaCarnico = Carnico::where(DB::raw('concat(\',\',listaServicio2,\',\')'),'like','%,'.$value->id.',%')->get();
			@endphp
				@foreach ($listaCarnico as $k => $v)
					<th class="text-center">{{$v->nombre}}</th>
				@endforeach
			@endforeach
		</tr>
	</thead>
	<tbody>
		@php $idanterior=0;$dataCarnico = array();$totalgeneral=0;
		@endphp
		@foreach ($lista as $key => $value)
		<tr>
			<td>{{ $value->persona }}</td>
			@foreach ($listaServicio as $k => $v)
				@php
				$listaCarnico = Carnico::where(DB::raw('concat(\',\',listaServicio2,\',\')'),'like','%,'.$v->id.',%')->get();
				foreach($listaCarnico as $k2=>$v2){
					$carnico = DetallePedidoCarnico::join('pedidocarnico','pedidocarnico.id','=','detallepedidocarnico.pedidocarnico_id')
							->where('detallepedidocarnico.persona','like',$value->persona)
							->where('detallepedidocarnico.carnico_id','=',$v2->id)
							->where('detallepedidocarnico.servicio_id','=',$v->id)
							->whereNull('pedidocarnico.deleted_at')
							->where('pedidocarnico.id','=',$value->id)
							->where(DB::raw('date(pedidocarnico.fecha)'),'>=',DB::raw("'".$fechainicio."'"))
							->where(DB::raw('date(pedidocarnico.fecha)'),'<=',DB::raw("'".$fechafin."'"))
							->get();
					if(isset($dataCarnico[$v->id."-".$v2->id])){
						$dataCarnico[$v->id."-".$v2->id] = $dataCarnico[$v->id."-".$v2->id] + count($carnico);
					}else{
						$dataCarnico[$v->id."-".$v2->id] = count($carnico);
					}
					$totalgeneral = $totalgeneral + count($carnico);
				@endphp
						<td>{!! count($carnico) !!}</td>
				@php
				}
				@endphp
			@endforeach
			<td>{{ $value->cantidad }}</td>
			<td>{{ $value->observacion }}</td>
		</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th class="text-center">TOTAL</th>
			@foreach ($listaServicio as $k => $v)
				@php
				$listaCarnico = Carnico::where(DB::raw('concat(\',\',listaServicio2,\',\')'),'like','%,'.$v->id.',%')->get();
				@endphp
				@foreach ($listaCarnico as $k2 => $v2)
				<th>{{$dataCarnico[$v->id."-".$v2->id]}}</th>
				@endforeach
			@endforeach
			<th>{{$totalgeneral}}</th>	
		</tr>
	</tfoot>
</table>
@endif