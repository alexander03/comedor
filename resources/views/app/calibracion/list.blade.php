@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<?php /*{!! $paginacion  !!} */?>
<table id="example1" class="table table-sm text-center table-striped  table-hover" border="1">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th style='background:#3bc0ff' class="text-center" @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif @if((int)$value['rows'] > 1) rowspan="{{ $value['rows'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
		<tr>
			@foreach($cabecera2 as $key => $value)
				<th style='background:#3bc0ff' class="text-center" @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;$categoria="";$subcategoria="";
		?>
		@foreach ($lista as $key => $value)
		<?php
		if($categoria!=$value->subcategoria->categoria->descripcion){
		    echo "<tr><td style='background:#3bc0ff' colspan='30'>".$value->subcategoria->categoria->descripcion."</td></tr>";
		    $categoria = $value->subcategoria->categoria->descripcion;
		}
		if($subcategoria!=$value->subcategoria->descripcion){
		    echo "<tr><td style='background:#3bc0ff' colspan='30'>".$value->subcategoria->descripcion."</td></tr>";
		    $subcategoria = $value->subcategoria->descripcion;
		}
		?>
		<tr>
			<td>{{ $value->orden }}</td>
			@if(!is_null($value->equipo))
			<td>{{ $value->equipo->codigo }}</td>
			<td>{{ $value->equipo->descripcion }}</td>
			<td>{{ $value->equipo->marca }}</td>
			<td>{{ $value->equipo->modelo }}</td>
			<td>{{ $value->equipo->serie }}</td>
			<td>{{ $value->equipo->fechaadquisicion }}</td>
			@else
			<td> - </td>
			<td> - </td>
			<td> - </td>
			<td> - </td>
			<td> - </td>
			<td> - </td>
			@endif
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
			    echo "<td style='".$color."'>$x</td>";
			}
			?>
			<td>{{ $value->comentario }}</td>
			<td>{!! Form::button('<div class="fas fa-file"></div> Archivo', array('class'=>'btn btn-outline-info btn-sm', 'onclick' => 'window.open(\'image-calibracion/'.$value->id.'-'.$value->archivo.'\',\'_blank\')')) !!}</td>
			<td>{!! Form::button('<div class="fas fa-edit"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-sm btn-outline-success')) !!}</td>
            <td>{!! Form::button('<div class="fas fa-trash-alt"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-outline-danger')) !!}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
	<tfoot>
	    <tr>
			@foreach($cabecera as $key => $value)
				<th style='background:#3bc0ff' @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</tfoot>
</table>
{!! $paginacion !!}
@endif