<table border="1">
	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th style='background:#3bc0ff;text-align: center' @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif @if((int)$value['rows'] > 1) rowspan="{{ $value['rows'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
		<tr>
			@foreach($cabecera2 as $key => $value)
				<th style='background:#3bc0ff;text-align: center' @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$categoria="";$subcategoria="";
		?>
		@foreach ($lista as $key => $value)
		<?php
		if($categoria!=$value->subcategoria->categoria->descripcion){
		    echo "<tr><td style='background:#3bc0ff;text-align: center' colspan='27'>".$value->subcategoria->categoria->descripcion."</td></tr>";
		    $categoria = $value->subcategoria->categoria->descripcion;
		}
		if($subcategoria!=$value->subcategoria->descripcion){
		    echo "<tr><td style='background:#3bc0ff;text-align: center' colspan='27'>".$value->subcategoria->descripcion."</td></tr>";
		    $subcategoria = $value->subcategoria->descripcion;
		}
		?>
		<tr>
			<td>{{ $value->orden }}</td>
			<td>{{ $value->equipo->codigo }}</td>
			<td>{{ $value->equipo->descripcion }}</td>
			<td>{{ $value->equipo->marca }}</td>
			<td>{{ $value->equipo->modelo }}</td>
			<td>{{ $value->equipo->serie }}</td>
			<td>{{ $value->equipo->fechaadquisicion }}</td>
			<td>{{ $value->frente }}</td>
			<td>{{ $value->area }}</td>
			<td>{{ $value->frecuenciacalibracion }}</td>
			<td>{{ $value->frecuenciavalidacion }}</td>
			<td>{{ $value->codigocertificado }}</td>
			<td>{{ date("d/m/Y",strtotime($value->fechacertificado)) }}</td>
			<td>{{ $value->proximacalibracion!=""?date("d/m/Y",strtotime($value->proximacalibracion)):"" }}</td>
			<?php
			for($c=1;$c<=12;$c++){
			    $x="";$color="";
			    if($value->proximacalibracion!=""){
			        if((date("m",strtotime($value->proximacalibracion))+0)==$c && $value->anio==(date("Y",strtotime($value->proximacalibracion))+0)){
			            $x="X";
			            $color="background:#3bc0ff";
			        }
			    }
			    echo "<td style='".$color.";text-align: center'>$x</td>";
			}
			?>
			<td>{{ $value->comentario }}</td>
		</tr>
		@endforeach
	</tbody>
</table>