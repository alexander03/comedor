@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion  !!}
<table id="example1" class="table table-sm text-center table-striped  table-hover" border="1">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th style='background:#3bc0ff' class="text-center" @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif @if((int)$value['rows'] > 1) rowspan="{{ $value['rows'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;$categoria="";$subcategoria="";
		?>
		@foreach ($lista as $key => $value)
		    <?php 
		    if($value->situacion=='P'){
		        $color="color:red;";
		    }elseif($value->situacion=='D'){
		        $color="color:blue;";
		    }
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
			@if($user->usertype_id==5 && $value->situacion=='P')
			    <td> - </td>
			@else
			    <td>{!! Form::button('<div class="fas fa-file"></div> Descargar', array('class'=>'btn btn-outline-info btn-sm', 'onclick' => 'window.open(\'image-documento/'.$value->id.'-'.$value->archivo.'\',\'_blank\')')) !!}</td>
			@endif
			<td>{!! Form::button('<div class="fas fa-file"></div> Subir', array('class'=>'btn btn-outline-danger btn-sm', 'onclick' => 'modal (\''.URL::route($ruta["subir"], array($value->id, 'SI','P')).'\', \'Subir Archivo\', this);')) !!}</td>
			@if($user->usertype_id!=5)
			    <td>{!! Form::button('<div class="fas fa-file"></div> Subir Revisado', array('class'=>'btn btn-outline-warning btn-sm', 'onclick' => 'modal (\''.URL::route($ruta["subir"], array($value->id, 'SI','D')).'\', \'Subir Archivo\', this);')) !!}</td>
			@endif
			@if($user->usertype_id!=5)
			    <td>{!! Form::button('<div class="fas fa-edit"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-sm btn-outline-success')) !!}</td>
                <td>{!! Form::button('<div class="fas fa-trash-alt"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-outline-danger')) !!}</td>
            @elseif($value->situacion=='P')
                <td>{!! Form::button('<div class="fas fa-trash-alt"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-outline-danger')) !!}</td>
            @endif
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