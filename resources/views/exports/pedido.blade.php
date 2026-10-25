<?php
use App\DetallePedido;
use Illuminate\Support\Facades\DB;
?>
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<table id="example1" class="table table-sm text-center table-striped  table-hover">

    <thead>
        <tr>
            <th style="width: 10px"><b>FECHA</b></th>
            <th style="width: 10px"><b>HORA</b></th>
            <th style="width: 40px"><b>SOLICITANTE</b></th>
            <th style="width: 20px"><b>LUGAR</b></th>
            <th style="width: 20px"><b>OBSERVACION</b></th>
            <th style="width: 40px"><b>PERSONA</b></th>
            <th style="width: 40px"><b>UA</b></th>
            <th style="width: 25px"><b>SERVICIO</b></th>
            <th style="width: 35px"><b>OP. CARNICO</b></th>
            <th style="width: 35px"><b>EMPRESA</b></th>
            <th style="width: 15px"><b>FECHA REG.</b></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $contador = 0;$id="";
        ?>
        @foreach ($lista as $key => $value)
            @php
            if($value->persona_id>0){
                $persona = DB::connection('mysql3')->select("select p.*,concat(ua.codigo,' ',ua.descripcion) as ua from persona p left join ua on ua.id=p.ua_id where p.id=".$value->persona_id)[0];
                $dni = trim($persona->dni.' '.$persona->otros);
                $ua = $persona->ua;
                $empresa = $persona->contratista;
                if(trim($empresa)=="CONCESIONARIA TRASVASE OLMOS S.A."){
                    $empresa = "CTO";
                }
                if(trim($empresa)=="H2OLMOS S.A."){
                    $empresa = "H2O";
                }
            }else{
                $persona = DB::connection('mysql2')->select("select p.docid,tr.contratista_id,concat(ua.codigo,' ',ua.descripcion) as ua from personal p left join ua on ua.id=p.ua_id inner join trabajador tr on tr.personal_id=p.id where p.id=".$value->personawp_id)[0];
                $dni = $persona->docid;
                $ua = $persona->ua;
                $empresa = $persona->contratista_id==27?"CTO":"H2O";
            }
            @endphp
        <tr>
            @if($id!=$value->id)
            <td>{{ date("d/m/Y",strtotime($value->fecha)) }}</td>
            <td>{{ date("H:i",strtotime($value->fecha)) }}</td>
            <td>{{ $value->solicitante }}</td>
            <!--td>{{ $value->lider }}</td-->
            <td>{{ $value->lugar }}</td>
            <td>{{ $value->observacion }}</td>
            @php $id=$value->id;$contador++; @endphp
            @else
            <td colspan="5"></td>
            @endif
            <td>{{ $value->persona }}</td>
            <td>{{ $ua }}</td>
            <td>{{ $value->servicio }}</td>
            <td>{{ $value->carnico }}</td>
            <td>{{ $value->empresa }}</td>
            <td>{{ date("d/m/Y H:i",strtotime($value->created_at)) }}</td>
            <!--td>{{ $value->dieta }}</td-->
        </tr>
        @endforeach
    </tbody>
</table>
@endif