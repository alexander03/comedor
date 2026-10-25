<head>
    <link rel="stylesheet" href="http://www.h2olmos.pe/sgisoft/plugins/fontawesome-free/css/all.min.css">
</head>
<table id="example1" class="table table-sm text-center table-striped  table-hover" border="1">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th style='background:#3bc0ff;width: 30px;text-align: center' @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$area="";
		?>
		@foreach ($lista as $key => $value)
		<tr>
			<td>{{ date("d/m/Y H:i",strtotime($value->fecha)) }}</td>
			<td>{{ $value->persona }}</td>
			<td>{{ $value->empresa }}</td>
			<td>{{ $value->campamento->nombre }}</td>
			<td>{{ $value->origen }}</td>
			<td>{{ $value->destino }}</td>
			<td>{{ $value->tarifa }}</td>
			<td>{{ $value->motivo }}</td>
			<td>{{ date("d/m/Y H:i",strtotime($value->created_at)) }}</td>
		</tr>
		@endforeach
	</tbody>
</table>