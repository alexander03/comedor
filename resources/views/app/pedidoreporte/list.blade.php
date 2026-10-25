<?php
use App\DetallePedido;
use App\DetallePedidoGrupal;
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
				$tot = Carnico::where(DB::raw('concat(\',\',listaServicio,\',\')'),'like','%,'.$value->id.',%')->get();
			@endphp
				<th colspan="{{ count($tot) }}">{{ $value->nombre }}</th>
			@endforeach
			<th class="text-center" rowspan="2">Lugar</th>
			<th class="text-center" rowspan="2">Total</th>
			<th class="text-center" rowspan="2">Observacion</th>
		</tr>
		<tr>
			@foreach ($listaServicio as $key => $value)
			@php
			$listaCarnico = Carnico::where(DB::raw('concat(\',\',listaServicio,\',\')'),'like','%,'.$value->id.',%')->get();
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
			<td>{{ $value->solicitante }}</td>
			@foreach ($listaServicio as $k => $v)
				@php
				$listaCarnico = Carnico::where(DB::raw('concat(\',\',listaServicio,\',\')'),'like','%,'.$v->id.',%')->get();
				foreach($listaCarnico as $k2=>$v2){
					if($value->tipo=="grupal"){
						$carnico = DetallePedidoGrupal::join('pedido','pedido.id','=','detallepedidogrupal.pedido_id')
								->where('pedido.solicitante','like',$value->solicitante)
								->where('detallepedidogrupal.carnico_id','=',$v2->id)
								->whereNull('pedido.deleted_at')
								->where('pedido.id','=',$value->id)
								->where(DB::raw('date(pedido.fecha)'),'>=',DB::raw("'".$fechainicio."'"))
								->where(DB::raw('date(pedido.fecha)'),'<=',DB::raw("'".$fechafin."'"))
								->where('detallepedidogrupal.servicio_id','=',$v->id)
								->select(DB::raw('sum(detallepedidogrupal.cantidad) as cantidad'))
								->first();
						if(isset($dataCarnico[$v->id."-".$v2->id])){
							$dataCarnico[$v->id."-".$v2->id] = $dataCarnico[$v->id."-".$v2->id] + $carnico->cantidad;
						}else{
							$dataCarnico[$v->id."-".$v2->id] = $carnico->cantidad;
						}
						$totalgeneral = $totalgeneral + $carnico->cantidad;
				@endphp
				<td>{!! ($carnico->cantidad+0) !!}</td>
				@php
					}else{
						$carnico = DetallePedido::join('pedido','pedido.id','=','detallepedido.pedido_id')
								->where('pedido.solicitante','like',$value->solicitante)
								->where('detallepedido.carnico_id','=',$v2->id)
								->where('detallepedido.servicio_id','=',$v->id)
								->whereNull('pedido.deleted_at')
								->where('pedido.id','=',$value->id)
								->where(DB::raw('date(pedido.fecha)'),'>=',DB::raw("'".$fechainicio."'"))
								->where(DB::raw('date(pedido.fecha)'),'<=',DB::raw("'".$fechafin."'"))
								
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
				}
				@endphp
			@endforeach
			<td>{{ $value->lugar }}</td>
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
				$listaCarnico = Carnico::where(DB::raw('concat(\',\',listaServicio,\',\')'),'like','%,'.$v->id.',%')->get();
				@endphp
				@foreach ($listaCarnico as $k2 => $v2)
				<th>{{$dataCarnico[$v->id."-".$v2->id]}}</th>
				@endforeach
			@endforeach
			<th></th>
			<th>{{$totalgeneral}}</th>	
		</tr>
	</tfoot>
</table>
@endif