<?php
use App\DetallePedido;
use Illuminate\Support\Facades\DB;
?>
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<div class="row">
	<div class="col-8">
		{!! $paginacion  !!}
	</div>
	@if($fechainicio==$fechafin)
	<div class="col-4">
		<label class="form-label"><b>Comedor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>></b> Desayunos: <b style="color:blue" id="bDesayuno">{{$desayuno}}</b>  | Almuerzo: <b style="color:blue" id="bAlmuerzo">{{$almuerzo}}</b>  | Cena: <b style="color:blue" id="bCena">{{$cena}}</b></label><br />
		<label class="form-label"><b>Transportados&nbsp;&nbsp;>></b> Desayunos: <b style="color:blue" id="bDesayuno">{{$desayuno1}}</b>  | Almuerzo: <b style="color:blue" id="bAlmuerzo">{{$almuerzo1}}</b>  | Cena: <b style="color:blue" id="bCena">{{$cena1}}</b></label>
	</div>
	@endif
</div>
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
			@php
			if($value->persona_id>0){
                $persona = DB::connection('mysql3')->select("select p.*,concat(ua.codigo,' ',ua.descripcion) as ua from persona p left join ua on ua.id=p.ua_id where p.id=".$value->persona_id)[0];
                $empresa = $persona->contratista;
                if(trim($empresa)=="CONCESIONARIA TRASVASE OLMOS S.A."){
                    $empresa = "CTO";
                }
                if(trim($empresa)=="H2OLMOS S.A."){
                    $empresa = "H2O";
                }
            }else{
                $persona = DB::connection('mysql2')->select("select p.docid,tr.contratista_id from personal p inner join trabajador tr on tr.personal_id=p.id where p.id=".$value->personawp_id)[0];
                $empresa = $persona->contratista_id==27?"CTO":"H2O";
            }
			@endphp
		<tr>
			<td>{{ $contador }}</td>
			<td>{{ date("d/m/Y H:i",strtotime($value->fecha)) }}</td>
			<td>{{ $value->persona }}</td>
			<td>{{ $empresa }}</td>
			<td>{{ $value->servicio2 }}</td>
			<!--td>{{ $value->costo }}</td-->
			<td>{{ $value->comedor2 }}</td>
			<td><img src='image-firma-comedor/{{ $value->id }}.png' width="150px" height="50px" /></td>
			@if(!is_null($value->user))
				<td>{{ $value->user->login }}</td>
				<td>{!! Form::button('<div class="fas fa-file-pdf"></div> PDF', array('onclick' => 'window.open(\'/comedor/comidareporte/generatePDFTicket/'.$value->id.'\')', 'class' => 'btn btn-sm btn-outline-info')) !!}</td>
			@else
				<td> - </td>
				<td> - </td>
			@endif
			@if($user->usertype_id=="1" || $user->usertype_id=="35")
			<td>{!! Form::button('<div class="fas fa-trash-alt"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-outline-danger')) !!}</td>
			@else
			<td> - </td>
			@endif
			@php $contador++; @endphp
		</tr>
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