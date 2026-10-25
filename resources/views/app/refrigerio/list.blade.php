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
			<td>{{ $value->tipo }}</td>
			<td>{{ date("d/m/Y",strtotime($value->fecha)) }}</td>
			<!--td>{{ $value->bebida.' ('.$value->kcal_bebida.' KCAL)' }}</td-->
			<td>{{ $value->sandwich.' ('.$value->kcal_sandwich.' KCAL)' }}</td>
			<!--td>{{ $value->complemento.' ('.$value->kcal_complemento.' KCAL)' }}</td>
			<td>{{ $value->fruta.' ('.$value->kcal_fruta.' KCAL)' }}</td-->
			@if($value->tipo=='Refrigerio Comun')
			<td>{{ $value->extra.' ('.$value->kcal_extra.' KCAL)' }}</td>
			@else
			<td> - </td>
			@endif
			<td>{!! Form::button('<div class="fas fa-edit"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-sm btn-outline-success')) !!}</td>
            <td>{!! Form::button('<div class="fas fa-trash-alt"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-outline-danger')) !!}</td>
		</tr>
		@php $id=$value->id;$contador++; @endphp
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