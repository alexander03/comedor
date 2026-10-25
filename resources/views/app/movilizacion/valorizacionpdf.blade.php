<!DOCTYPE html>
<html lang="es">
<head>
    <title>REPORTE VALORIZACION</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
    <style type="text/css">
        @page {
            margin-left: 29px;
            margin-top: 65px;
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
        .letratituloultrapequeno{
            font-weight: bold;
            font-size: 7px;
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
                            VALORIZACION DE SERVICIO DE TRANSPORTE
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
                        <td colspan="2" class="text-bold">CONCESIÓN:&nbsp;<span>Operación y Mantenimiento del Proyecto de Irrigación Olmos </span></td>
                        <td class="text-bold text-center">FECHA DE REGISTRO</td>
                    </tr>
                    <tr>
                        <td class="text-bold">CONCESIONARIA:&nbsp;<span>H2Olmos S.A. </span></td>
                        <td class="text-bold text-center">AREA: <span>Administración</span></td>
                        <td width="25%" class="text-bold text-center"><span>{{ date("d/m/Y",strtotime($fechafin)) }}</span></td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr class="table-borderless">
            <td class="table-borderless">

                <table width="100%" style="margin-top: 6px;">
                    <tr>
                        <td colspan="2" class="text-bold h2">ACREEDOR DEL SERVICIO</td>
                    </tr>
                    <tr>
                        <td class="text-bold text-right">RAZON SOCIAL: </td>
                        <td class="text-bold text-left">{{$empresa->razonsocial}}</td>
                    </tr>
                    <tr>
                        <td class="text-bold text-right">RUC: </td>
                        <td class="text-bold text-left">{{$empresa->ruc}}</td>
                    </tr>
                    <tr>
                        <td class="text-bold text-right">DIRECCION: </td>
                        <td class="text-bold text-left">{{$empresa->direccion}}</td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr class="table-borderless">
            <td class="table-borderless">
                <table width="100%" style="margin-top: 6px;">
                    <tr>
                        <td colspan="8" class="text-left text-bold" style="background: #bdd7ee">DETALLE DEL SERVICIO</td>
                    </tr>
                    <tr style="background: #bdd7ee">
                        <td width="4%" class="text-bold" style="text-align: center;">ITEM</td>
                        <td width="30%" class="text-bold" style="text-align: center;">SERVICIO</td>
                        <td width="6%" class="text-bold" style="text-align: center;">N° PAX</td>
                        <td width="6%" class="text-bold" style="text-align: center;">FECHA</td>
                        <td width="20%" class="text-bold" style="text-align: center;">ORIGEN</td>
                        <td width="20%" class="text-bold" style="text-align: center;">DESTINO</td>
                        <td width="10%" class="text-bold" style="text-align: center;">TARIFA UNITARIA</td>
                        <td width="10%" class="text-bold" style="text-align: center;">SUBTOTAL</td>
                    </tr>
                    <?php $subtotal=0 ?>
                    <?php $contador=0 ?>
                    @foreach($lista as $key => $obs)
                        <?php $contador++;
                        $subtotal = $subtotal + $obs->pasajero*round($obs->tarifa/1.18,2);
                        if($contador==21){
                            echo '</table><div class="page-break"></div>
                            <table width="100%" style="margin-top: 6px;">
                    <tr>
                        <td colspan="8" class="text-left text-bold" style="background: #bdd7ee">DETALLE DEL SERVICIO</td>
                    </tr>
                    <tr style="background: #bdd7ee">
                        <td width="4%" class="text-bold" style="text-align: center;">ITEM</td>
                        <td width="30%" class="text-bold" style="text-align: center;">SERVICIO</td>
                        <td width="6%" class="text-bold" style="text-align: center;">N° PAX</td>
                        <td width="6%" class="text-bold" style="text-align: center;">FECHA</td>
                        <td width="20%" class="text-bold" style="text-align: center;">ORIGEN</td>
                        <td width="20%" class="text-bold" style="text-align: center;">DESTINO</td>
                        <td width="10%" class="text-bold" style="text-align: center;">TARIFA UNITARIA</td>
                        <td width="10%" class="text-bold" style="text-align: center;">SUBTOTAL</td>
                    </tr>';
                        }
                        //number_format(floor(round(floor(($obs->tarifa/1.18*1000))/1000,3)*100)/100,2,'.','')
                        //number_format(floor(round($obs->pasajero*floor(($obs->tarifa/1.18*1000))/1000,3)*100)/100,2,'.','')
                         ?>
                        }
                    <tr>
                        <td class="text-center items">{{$contador}}</td>
                        <td class="text items text-center">TRANSPORTE DIVERSOS</td>
                        <td class="text items text-center">{{$obs->pasajero}}</td>
                        <td class="text items text-center">{{date("d/m/Y",strtotime($obs->fecha))}}</td>
                        <td class="text items text-center">{{$obs->origen}}</td>
                        <td class="text items text-center">{{$obs->destino}}</td>
                        <td class="text items text-right">S/ {{number_format($obs->tarifa/1.18,2,'.','')}}</td>
                        <td class="text items text-right">S/ {{number_format($obs->pasajero*round($obs->tarifa/1.18,2),2,'.','')}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="text-center items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items text-right">Neto:</td>
                        <td class="text items text-right">S/ {{ number_format($subtotal,2,'.','') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items text-right">IGV:</td>
                        <td class="text items text-right">S/ {{ number_format($subtotal*0.18,2,'.','') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items"></td>
                        <td class="text items text-right">Total a pagar:</td>
                        <td class="text items text-bold text-right">S/ {{ number_format($subtotal*1.18,2,'.','') }}</td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr class="table-borderless">
            <td class="table-borderless">

                <table width="100%" style="margin-top: 6px;">
                    <tr style="margin-top: 6px;background: #bdd7ee;">
                        <td  width="30%" style="background:white;" rowspan="4">
                            <img src="https://sistemasolmos.pe/comedor/dist/logo_images/tarifa.png" width="270" height="140">
                            <?php /*<table width="100%" style="margin-top: 2px;" class="table-borderless letratituloultrapequeno">
                                <tr class="table-borderless">
                                    <th></th>
                                    <th>RUTA</th>
                                    <th>PRECIO</th>
                                </tr>
                                <?php $contador=0; ?>
                                @foreach($listaTarifa as $key => $val)
                                <?php $contador++;?>
                                <tr class="table-borderless">
                                    <td style="height:auto !important" class="table-borderless">{{$contador}}|</td>
                                    <td style="height:auto !important"class="table-borderless">{{$val->origen->nombre}} A {{$val->destino->nombre}}</td>
                                    <td style="height:auto !important" class="table-borderless">|S/ {{number_format($val->monto/1.18,2,'.','')}}</td>
                                </tr>
                                @endforeach
                            </table>*/
                            ?>
                        </td>
                        <td width="20%" class="text-bold" align="center" style="height:10px !important;">ENCARGADO</td>
                        <td width="20%" class="text-bold" align="center" style="height:10px !important;">AUTORIZA</td>
                        <td width="20%" class="text-bold" align="center" style="height:10px !important;">APRUEBA</td>
                    </tr>
                    <tr>
                        <td style="border-top: none !important;border-bottom: none !important;"></td>
                        <td style="border-top: none !important;border-bottom: none !important;"></td>
                        <td style="border-top: none !important;border-bottom: none !important;"></td>
                    </tr>
                    <tr>
                        <td width="20%" class="text-bold" style="border-top: none !important;height: 40px !important;">
                        <img src="https://sistemasolmos.pe/comedor/image-persona/{{$personal->id.'-'.$personal->archivo}}" width="130" height="70">
                        <br />FIRMA:</td>
                        <td width="20%" class="text-bold" style="border-top: none !important;height: 40px !important;">
                        <img src="https://sistemasolmos.pe/comedor/image/firmaJGA.png" width="130" height="70">
                        <br />FIRMA:</td>
                        <td width="20%" class="text-bold" style="border-top: none !important;height: 40px !important;">
                        <img src="https://sistemasolmos.pe/comedor/image/firmaCarlos.jpeg" width="130" height="70">
                        <br />FIRMA:</td>
                    </tr>
                    <tr>
                        <td width="20%" class="text-bold" style="padding-top:10px;height: 10px !important;">NOMBRE: {{$personal->nombres}}</td>
                        <td width="20%" class="text-bold" style="padding-top:10px;height: 10px !important;">NOMBRE: JAVIER GONZALES AMPUERO</td>
                        <td width="20%" class="text-bold" style="padding-top:10px;height: 10px !important;">NOMBRE: CARLOS LUNA MARCHENA</td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr class="table-borderless">
            <td class="table-borderless" align="center" style="font-size: 7px;padding-top:10.5px;">“Este documento es propiedad de H2OLMOS S.A. y no podrá ser copiado o transferido a terceros sin autorización de la empresa”</td>
        </tr>
    </table>
</body>
</html>
