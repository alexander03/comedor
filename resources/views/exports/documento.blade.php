<head>
    <link rel="stylesheet" href="http://www.h2olmos.pe/sgisoft/plugins/fontawesome-free/css/all.min.css">
</head>
<table id="example1" class="table table-sm text-center table-striped  table-hover" border="1">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th style='background:#3bc0ff;text-align: center' @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = 0;$categoria="";$subcategoria="";
		?>
		@foreach ($lista as $key => $value)
		    <?php 
		    if($value->situacion=='P'){
		        $color="color:red;";
		    }elseif($value->situacion=='D'){
		        $color="color:blue;";
		    }
		    $contador++;
		    ?>
		<tr>
			<td>{{ $contador }}</td>
			@if(!is_null($value->tipodocumento))
				<td>{{ $value->tipodocumento->descripcion }}</td>
			@else
				<td> - </td>
			@endif
			<td>{{ $value->codigo }}</td>
			<td>{{ $value->descripcion }}</td>
			<td>{{ $value->revision }}</td>
			<td>{{ date("d/m/Y",strtotime($value->fecha)) }}</td>
			@if(!is_null($value->proceso))
				<td>{{ $value->proceso->descripcion }}</td>
			@else
				<td> - </td>
			@endif
			<td>{{ $value->observacion }}</td>
			<td style='font-weight: bold;<?=$color?>'>{{ ($value->situacion=='P'?'Pendiente':'Disponible') }}</td>
		</tr>
		@endforeach
	</tbody>
</table>