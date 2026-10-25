<?php 
use App\Comida;
use Illuminate\Support\Facades\DB;
$servicio_id = 0;$total=0;
?>
@if (count($lista1)>0)
    <table>
        <thead>
        <tr>
            <th colspan="8" style="text-align: center"><b>REPORTE DE ALIMENTACION DEL {{ date("d/m/Y",strtotime($fechainicio)) }} AL {{ date("d/m/Y",strtotime($fechafin)) }}</b></th>
        </tr>
        <tr>
            <th style="width: 10px"><b>FECHA</b></th>
            <th style="width: 10px"><b>HORA</b></th>
            <th style="width: 15px"><b>DNI</b></th>
            <th style="width: 60px"><b>PERSONA</b></th>
            <th style="width: 30px"><b>EMPRESA</b></th>
            <th style="width: 30px"><b>SERVICIO</b></th>
            <!--th style="width: 30px"><b>COSTO</b></th-->
            <th style="width: 50px"><b>COMEDOR</b></th>
            <th style="width: 50px"><b>UA</b></th>
            <th style="width: 10px"><b>CANTIDAD</b></th>
            <th style="width: 10px"><b>FIRMA</b></th>
        </tr>
        </thead>
        <tbody>
        @foreach($lista1 as $key => $value)
            @if($servicio_id!=$value->servicio_id)
                @if($servicio_id!="0")
                <tr>
                    <th colspan="5">TOTAL</th>
                    <th>{{$total}}</th>
                </tr>    
                @endif
                @php
                    $total=0;
                @endphp
            @endif
            @php
                $servicio_id = $value->servicio_id;
                $total++;
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
                <td>{{ date("d/m/Y",strtotime($value->fecha)) }}</td>
                <td>{{ date("H:i:s",strtotime($value->fecha)) }}</td>
                <td>{{ $dni }}</td>
                <td>{{ $value->persona }}</td>
                <td>{{ $empresa }}</td>
                <td>{{ $value->servicio2 }}</td>
                <!--td>{{ $value->costo }}</td-->
                <td>{{ $value->comedor2 }}</td>
                <td>{{ $ua }}</td>
                <td>1</td>
                <td><img src='image-firma-comedor/{{ $value->id }}.png' width="150px" height="50px" /></td>
            </tr>
        @endforeach
        <tr>
            <th colspan="8">TOTAL</th>
            <th>{{$total}}</th>
        </tr>    
        </tbody>
    </table>
@else
    <table>
        <tr>
            <td>
                SIN RESULTADOS
            </td>
        </tr>
    </table>
@endif


