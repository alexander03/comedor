<!DOCTYPE html>
<html lang="es">
<head>
    <title>REPORTE TRASLADOS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
    <style type="text/css">
        @page {
            margin-left: 29px;
            margin-top: 35px;
            margin-right: 29px;
        }
        body{
            font-size: 9.5px;
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
            border: 1.2px solid;
            height: 17.5px;
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
            height: 14px;
        }
        .pequeno2{
            height: 18px;
        }
        .items{
            height: 27.5px;
        }
        .letratitulopequeno{
            font-weight: bold;
            font-size: 8px;
        }
        .page-break {
          page-break-after: always;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr class="table-borderless">
            <td class="table-borderless">
                
                <table width="100%" style="margin-bottom: 0px">
                    <tr>

                        <td rowspan="4" width="16%" style="height: 10px" class="text-center pequeno">
                            @if($campamento=="")
                            <img src="https://sistemasolmos.pe/comedor/image/LOGO_CONCESIONARIAS.png" width="130" height="70">
                            @elseif($campamento=="2")
                            <img src="https://sistemasolmos.pe/comedor/image/LOGO-CTO.jpg" width="130" height="70">
                            @elseif($campamento=="1")
                            <img src="https://sistemasolmos.pe/comedor/image/LOGO-H2OLMOS.png" width="130" height="70">
                            @endif
                        </td>
                        <td rowspan="4" width="60%" style="background:#0070C0;font-size:12;height: 14px;border-color: black !important;"  class="text-center text-white text-bold pequeno">
                            MANIFESTO DE TRANSPORTE - SUBCONTRATOS
                        </td>
                        <td class="pequeno">
                            <span class="pequeno letratitulopequeno" style="height: 15px">CÓDIGO DEL FORMATO:</span>
                        </td>
                        <td class="text-center pequeno">
                            <span class="pequeno text-center" style="height: 15px;font-size: 8px;text-align: center;">ADM-013</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="pequeno">
                            <span class="pequeno letratitulopequeno" style="height: 15px;">REVISIÓN DEL FORMATO</span>
                        </td>
                        <td class="text-center pequeno">
                            <span class="pequeno text-center" style="height: 15px;font-size: 8px;">02</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="pequeno">
                            <span class="pequeno letratitulopequeno" style="height: 15px;">FORMATO VIGENTE DESDE:</span>
                        </td>
                        <td class="text-center pequeno">
                            <span class="pequeno" style="height: 15px;font-size: 8px;";>27/02/2022</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="pequeno">
                            <span class="pequeno letratitulopequeno" style="height: 15px;">PÁGINA DEL FORMATO:</span>
                        </td>
                        <td class="pequeno text-center">
                            <span style="height: 15px;font-size: 8px;text-align: center;">1 DE 1</span>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr class="table-borderless">
            <td class="table-borderless">

                <table width="100%" style="margin-top: 6px;">
                    <tr>
                        <td colspan="2" class="text-bold">CONCESIONARIA:&nbsp;<span>H2Olmos S.A. </span></td>
                        <td colspan="2" class="text-bold text-center">AREA: <span>Administración</span></td>
                    </tr>
                    <tr>
                        <td width="70%" class="text-bold">Nombre Encargado Subcontrata:</td>
                        <td class="text-bold text-center">EMPRESA:</td>
                        <td class="text-bold text-center">PERIODO(MES):</td>
                        <td class="text-bold text-center">FECHA:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td width="45%" class="text-bold text-center">{{$empresa}}</td>
                        <td width="30%" class="text-bold text-center">{{$periodo}}</td>
                        <td width="25%" class="text-bold text-center"><span>{{ date("d/m/Y") }}</span></td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr class="table-borderless">
            <td class="table-borderless">
                <table width="100%" style="margin-top: 6px;">
                    <tr>
                        <td colspan="7" class="text-center text-bold" style="background: #bdd7ee">BENEFICIARIOS</td>
                    </tr>
                    <tr style="background: #bdd7ee">
                        <td width="4%" class="text-bold" style="text-align: center;">ITEM</td>
                        <td width="30%" class="text-bold" style="text-align: center;">NOMBRE Y APELLIDO</td>
                        <td width="6%" class="text-bold" style="text-align: center;">ORIGEN</td>
                        <td width="6%" class="text-bold" style="text-align: center;">DESTINO</td>
                        <td width="6%" class="text-bold" style="text-align: center;">FECHA</td>
                        <td width="30%" class="text-bold" style="text-align: center;">MOTIVO*</td>
                        <td width="15%" class="text-bold" style="text-align: center;">FIRMA</td>
                    </tr>
                    <?php $total=0 ?>
                    <?php $contador=0 ?>
                    @foreach($lista as $key => $obs)
                        <?php $contador++; 
                        if($contador==24){
                            echo '</table><div class="page-break"></div>
                            <table width="100%" style="margin-top: 6px;">
                    <tr>
                        <td colspan="7" class="text-center text-bold" style="background: #bdd7ee">BENEFICIARIOS</td>
                    </tr>
                    <tr style="background: #bdd7ee">
                        <td width="4%" class="text-bold" style="text-align: center;">ITEM</td>
                        <td width="30%" class="text-bold" style="text-align: center;">NOMBRE Y APELLIDO</td>
                        <td width="6%" class="text-bold" style="text-align: center;">ORIGEN</td>
                        <td width="6%" class="text-bold" style="text-align: center;">DESTINO</td>
                        <td width="6%" class="text-bold" style="text-align: center;">FECHA</td>
                        <td width="30%" class="text-bold" style="text-align: center;">MOTIVO*</td>
                        <td width="15%" class="text-bold" style="text-align: center;">FIRMA</td>
                    </tr>';
                        }
                        ?>
                    <tr>
                        <td class="text-center items">{{$contador}}</td>
                        <td class="text items text-center">{{$obs->persona}}</td>
                        <td class="text items text-center">{{$obs->origen}}</td>
                        <td class="text items text-center">{{$obs->destino}}</td>
                        <td class="text items text-center">{{date("d/m/Y",strtotime($obs->fecha))}}</td>
                        <td class="text items text-center">{{$obs->motivo}}</td>
                        <td class="text items text-center"> </td>
                    </tr>
                    @endforeach
                    <?php $faltan=20-count($lista); ?>
                    @for($i=0;$i<$faltan;$i++)
                    <?php $contador++; ?>
                    <tr>
                        <td class="text-center items">{{$contador}}</td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                    </tr>
                    @endfor
                </table>

            </td>
        </tr>
        <tr class="table-borderless">
            <td class="table-borderless">

                <table width="100%" style="margin-top: 6px;">
                    <tr style="margin-top: 6px;background: #bdd7ee;">
                        <td width="60%" class="text-bold" align="center" rowspan="3">* "ESPECIFICAR MOTIVO POR EL CUAL USÓ EL SERVICIO DEL TRANSPORTE"</td>
                        <td width="20%" class="text-bold" align="center">SUBCONTRATA</td>
                        <td width="20%" class="text-bold" align="center">ADMINISTRACION</td>
                    </tr>
                    <tr>
                        <td width="20%" class="text-bold" style="padding-top:25px;">FIRMA:</td>
                        <td width="20%" class="text-bold" style="padding-top:25px;">
                        <img src="https://sistemasolmos.pe/comedor/image-persona/{{$personal->id.'-'.$personal->archivo}}" width="130" height="50">
                        <br />FIRMA:</td>
                    </tr>
                    <tr>
                        <td width="20%" class="text-bold">NOMBRE:</td>
                        <td width="20%" class="text-bold">NOMBRE: {{$personal->nombres}}</td>
                    </tr>
                    
                </table>

            </td>
        </tr>
        <tr class="table-borderless">
            <td align="center" style="font-size: 7px;padding-top:10.5px;">“Este documento es propiedad de H2OLMOS S.A. y no podrá ser copiado o transferido a terceros sin autorización de la empresa”</td>
        </tr>
    </table>
</body>
</html>
