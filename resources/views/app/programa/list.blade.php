@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<?php /*{!! $paginacion  !!}*/ ?>
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
		$contador = $inicio + 1;$area="";
		?>
		@foreach ($lista as $key => $value)
		<?php
		if(!is_null($value->area) && $area!=$value->area->descripcion){
		    echo "<tr><td  style='background:#3bc0ff'>".$value->area->orden."</td><td style='background:#3bc0ff' colspan='25'>".$value->area->descripcion."</td></tr>";
		    $area = $value->area->descripcion;
		}
		?>
		<tr>
			<td>{{ $value->orden }}</td>
			<td>{{ $value->nombre }}</td>
			<td>{{ $value->descripcion }}</td>
			<td>{{ $value->responsable }}</td>
			<td>{{ $value->frecuencia }}</td>
			<?php
			if($value->frecuencia=="Mensual"){
			    $d=1;
			}elseif($value->frecuencia=="Bimestral"){
			    $d=2;
			}elseif($value->frecuencia=="Trimestral"){
			    $d=3;
			}elseif($value->frecuencia=="Semestral"){
			    $d=6;
			}elseif($value->frecuencia=="Anual"){
			    $d=12;
			}
			for($c=1;$c<=12;$c++){
			    $x="";$color="";
			    if($d==1){
			    	if($value->mes<=$c){
			        	$x="X";
			        	$color="background:#3bc0ff";
			        }
			    }elseif($value->mes==$c){
		            $x="X";
		            $color="background:#3bc0ff";
			    }elseif(($c - $value->mes)%($d)==0){
			        $x="X";
			        $color="background:#3bc0ff";
			    }
			    echo "<td style='".$color."'>$x</td>";
			}
			?>
			<td>{{ $value->observacion }}</td>
			<td>{!! Form::button('<div class="fas fa-file"></div> Archivo', array('class'=>'btn btn-outline-info btn-sm', 'onclick' => 'window.open(\'image-programa/'.$value->id.'-'.$value->archivo.'\',\'_blank\')')) !!}</td>
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