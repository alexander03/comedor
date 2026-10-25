<?php
use App\DetallePedido;
?>
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion  !!}
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
		$contador = $inicio + 1;$id="";
		?>
		@foreach ($lista as $key => $value)
		<tr>
			<td>{{ $contador }}</td>
			<td>{{ date("d/m/Y H:i",strtotime($value->fecha)) }}</td>
			<td>{{ $value->solicitante }}</td>
			<!--td>{{ $value->lider }}</td-->
			<td>{{ $value->lugar }}</td>
			<td>{{ $value->observacion }}</td>
			@php $id=$value->id;$contador++; @endphp
			<td>{{ $value->cantidad }}</td>
			<td>{{ $value->servicio }}</td>
			<td>{{ $value->carnico }}</td>
			<td>{{ $value->empresa }}</td>
			<td>{{ date("d/m/Y H:i",strtotime($value->created_at)) }}</td>
			<!--td>{{ $value->dieta }}</td-->
			@if($user->usertype_id!="34")
			<td>{!! Form::button('<div class="fas fa-trash-alt"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->detallepedido_id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-outline-danger')) !!}</td>
			@endif
		</tr>
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