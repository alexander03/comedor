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
			<td>{{ date("d/m/Y",strtotime($value->fecha)) }}</td>
			@if($value->dieta=='N')
			<!--td>{{ $value->sopa.' ('.$value->kcal_sopa.' KCAL)' }}</td-->
			@else
			<!--td> - </td-->
			@endif
			<td>{{ $value->fondo.' ('.$value->kcal_fondo.' KCAL)' }}</td>
			<!--td>{{ $value->guarnicion1!=""?($value->guarnicion1.' ('.$value->kcal_guarnicion1.' KCAL)'):'-' }}</td-->
			@if($value->dieta=='N')
			<!--td>{{ $value->guarnicion2.' ('.$value->kcal_guarnicion2.' KCAL)' }}</td>
			<td>{{ $value->infusion.' ('.$value->kcal_infusion.' KCAL)' }}</td>
			<td>{{ $value->pan.' ('.$value->kcal_pan.' KCAL)' }}</td>
			<td>{{ $value->salad.' ('.$value->kcal_salad.' KCAL)' }}</td-->
			@else
			<!--td> - </td>
			<td> - </td>
			<td> - </td>
			<td> - </td-->
			@endif
			<!--td>{{ $value->fruta.' ('.$value->kcal_fruta.' KCAL)' }}</td-->
			@if($value->dieta=='N')
			<!--td>{{ $value->opcional.' ('.$value->kcal_opcional.' KCAL)' }}</td-->
			@else
			<!--td> - </td-->
			@endif
			<td>{{ $value->dieta }}</td>
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