<?php
use Illuminate\Support\Facades\DB;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>{{ $namefile }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
    <style type="text/css">
        @page {
            margin-left: 29px;
            margin-top: 15px;
            margin-right: 29px;
        }
        body{
            font-size: 12px;
            font-family: "Arial";
            padding: 0px;
        }
        table{
            border-collapse: collapse;
        }
        tr{
            border-collapse: collapse;
        }
        td{
            border-collapse: collapse;
            /*border: 1.2px solid;
            height: 16.5px;*/
        }
        span{
            font-weight: normal;
        }
        .table-bordered{
            border-collapse: collapse;
            border: 1.2px solid; 
        }
        .table-borderless{
            border-collapse: collapse;
            border: none;
        }
        .table-borderlight{
            border-collapse: collapse;
            border: 0.5px;
        }
        .bg-blue{
            background-color: blue;
        }
        .h2{
            font-size: 12px;
        }
        .text-top{
            vertical-align: text-top;
        }
        .text-center{
            text-align: center;
        }
        .text-right{
            text-align: right;
        }
        .text-white{
            color: white;
        }
        .text-bold{
            font-weight: bold;
            font-size:9.5px;
        }
        .fsize{
            font-size: 8px;
        }
        .text-normal{
            font-weight: normal;
        }
        .pequeno{
            height: 12px;
        }
        .pequeno2{
            height: 15px;
        }
        .items{
            height: 15.5px;
            font-size: 8px;
        }
        .letratitulopequeno{
            font-weight: bold;
            font-size: 8px;
        }
        .izq{
            width: 30% !important;
        }
        .der{
            width: 70% !important;
        }
    </style>
</head>
<body>
    @php
    if($comida->persona_id>0){
        $persona = DB::connection('mysql3')->select("select p.*,concat(ua.codigo,' ',ua.descripcion) as ua from persona p left join ua on ua.id=p.ua_id where p.id=".$comida->persona_id)[0];
        $empresa = $persona->contratista;
        $dni = $persona->dni;
        if(trim($empresa)=="CONCESIONARIA TRASVASE OLMOS S.A."){
            $empresa = "CTO";
        }
        if(trim($empresa)=="H2OLMOS S.A."){
            $empresa = "H2O";
        }
    }else{
        $persona = DB::connection('mysql2')->select("select p.docid,tr.contratista_id from personal p inner join trabajador tr on tr.personal_id=p.id where p.id=".$comida->personawp_id)[0];
        $empresa = $persona->contratista_id==27?"CTO":"H2O";
        $dni = $persona->docid;
    }
    @endphp
    <table width="100%" class="">
        <tr class="table-borderless">
            <th class="text-center" style="font-size: 20px;" colspan="10">COMEDOR {{$comida->comedor_id=="2"?"CTO":"H2O"}}</th>
        </tr>
        <tr>
            <td colspan="10" style="height: 10px !important">&nbsp;</td>
        </tr>
        <tr class="table-borderless">
            <td class="text-left izq" colspan="2">Nro Ticket</td>
            <td class="text-left der" colspan="8">: {{$comida->id}}</td>
        </tr>
        <tr class="table-borderless">
            <td class="text-left izq" colspan="2">Fecha</td>
            <td class="text-left der" colspan="8">: {{date("d/m/Y H:i:s",strtotime($comida->fecha))}}</td>
        </tr>
        <tr class="table-borderless">
            <td class="text-left izq" colspan="2">Servicio</td>
            <td class="text-left der" colspan="8">: {{$comida->servicio->nombre}}</td>
        </tr>
        <tr class="table-borderless">
            <td class="text-left izq" colspan="2">Comensal</td>
            <td class="text-left der" colspan="8">: {{$comida->persona}}</td>
        </tr>
        <tr class="table-borderless">
            <td class="text-left izq" colspan="2">DNI</td>
            <td class="text-left der" colspan="8">: {{$dni}}</td>
        </tr>
        <tr class="table-borderless">
            <td class="text-left izq" colspan="2">Empresa</td>
            <td class="text-left der" colspan="8">: {{$empresa}}</td>
        </tr>
        <tr class="table-borderless">
            <td colspan="10" class="text-left">-------------------------------------------------------------------------------</td>
        </tr>
        <tr class="table-borderless">
            <td colspan="10" class="text-left">Este es un ticket virtual generado por una regularización</td>
        </tr>
    </table>
</body>
</html>