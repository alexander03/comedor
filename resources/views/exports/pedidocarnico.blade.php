<?php
use App\DetallePedidoCarnico;
?>
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<table id="example1" class="table table-sm text-center table-striped  table-hover">

    <thead>
        <tr>
            <th style="width: 15px"><b>FECHA</b></th>
            <th style="width: 40px"><b>SOLICITANTE</b></th>
            <!--th style="width: 20px"><b>LUGAR</b></th-->
            <th style="width: 20px"><b>OBSERVACION</b></th>
            <th style="width: 40px"><b>PERSONA</b></th>
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
        <tr>
            @if($id!=$value->id)
            <td>{{ date("d/m/Y H:i",strtotime($value->fecha)) }}</td>
            <td>{{ $value->solicitante }}</td>
            <!--td>{{ $value->lider }}</td-->
            <!--td>{{ $value->lugar }}</td-->
            <td>{{ $value->observacion }}</td>
            @php $id=$value->id;$contador++; @endphp
            @else
            <td colspan="4"></td>
            @endif
            <td>{{ $value->persona }}</td>
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