@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<?php /* {!! $paginacion  !!} */?>
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
		    $parametro="";
		    $val = explode("|",$value->parametro);
		    for($z=0;$z<count($val);$z++){
		        $dat = explode("@",$val[$z]);
		        $parametro.="<i style='color:".$dat[2]."' class='fa fa-circle'></i> Min: ".$dat[0]." - Max: ".$dat[1]."<br />";
		    }
		    ?>
		<tr>
			<td>{{ $value->orden }}</td>
			<td>{{ $value->proceso->descripcion }}</td>
			<td>{{ $value->indicador }}</td>
			<td>{{ $value->objetivo }}</td>
			<td>{{ $value->formula }}</td>
			<td><?php echo $parametro; ?></td>
			<td>{{ $value->frecuencia }}</td>
			<td>{{ $value->responsable1 }}</td>
			<td>{{ $value->responsable2 }}</td>
			<td>{{ $value->responsable3 }}</td>
			<?php
			if($value->frecuencia=="Mensual"){
			    $d=12;
			}elseif($value->frecuencia=="Bimestral"){
			    $d=6;
			}elseif($value->frecuencia=="Trimestral"){
			    $d=4;
			}elseif($value->frecuencia=="Semestral"){
			    $d=2;
			}elseif($value->frecuencia=="Anual"){
			    $d=1;
			}
			$acumulado = 0;$dd=0;
			$valores= explode("|",$value->valores);
			
			$param = explode("|",$value->parametro);
			for($c=1;$c<=$d;$c++){
			    $color="";
		        $x=$valores[$c-1];
		        if($x!=""){
		            $dd++;
		        }
		        for($z=0;$z<count($param);$z++){
		            $dat = explode("@",$param[$z]);
		            if($dat[0]<=$x && $dat[1]>=$x){
		                $color = $dat[2];
		                break;
		            }
		        }
		        $acumulado+= $x;
		        $color="color:".$color;
			    echo "<td style='background:#white' colspan='".(12/$d)."'><i style=".$color." class='fa fa-circle'></i>$x</td>";
			}
			?>
			<td>{{ round($acumulado/$dd,2) }}</td>
			<td>{{ $value->observaciones }}</td>
			<td>{{ $value->responsable }}</td>
			<td>{!! Form::button('<div class="fas fa-file"></div> Archivo', array('class'=>'btn btn-outline-info btn-sm', 'onclick' => 'window.open(\'image-indicador/'.$value->id.'-'.$value->archivo.'\',\'_blank\')')) !!}</td>
			<td>{!! Form::button('<div class="fas fa-edit"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-sm btn-outline-success')) !!}</td>
			@if($user->usertype_id!=5)
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