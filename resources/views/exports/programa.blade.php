<table id="example1" class="table table-sm text-center table-striped  table-hover" border="1">

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
		$area="";
		?>
		@foreach ($lista as $key => $value)
		<?php
		if(!is_null($value->area) && $area!=$value->area->descripcion){
		    echo "<tr><td  style='background:#3bc0ff;text-align: center'>".$value->area->orden."</td><td style='background:#3bc0ff' colspan='17'>".$value->area->descripcion."</td></tr>";
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
			        $x="X";
			        $color="background:#3bc0ff";
			    }elseif($value->mes==$c){
		            $x="X";
		            $color="background:#3bc0ff";
			    }elseif(($c - $value->mes)%($d)==0){
			        $x="X";
			        $color="background:#3bc0ff";
			    }
			    echo "<td style='".$color.";text-align: center'>$x</td>";
			}
			?>
			<td>{{ $value->observacion }}</td>
		</tr>
		@endforeach
	</tbody>
</table>