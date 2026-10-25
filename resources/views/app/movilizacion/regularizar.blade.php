@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="dist/css/auto-complete.css">
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="dist/css/toastr.min.css">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 col-xs-12" style="padding-right: 20px !important;">
            <div class="card">
                <div class="card-header">Registro de Movilizacion de Terceros</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('webservices.guardarMovilizacion') }}" id="frmPedido">
                        {!! Form::hidden('validar', 'N', array('id' => 'validar')) !!}
                        @csrf
                        <div class="form-group row">
                            <label for="persona" class="col-md-4 col-form-label text-md-right">Beneficiario</label>
                            <div class="col-md-8">
                                {!! Form::text('persona', null, array('class' => 'form-control input-xs js-persona', 'id' => 'persona', 'autocomplete'=>'off')) !!}
                                <small id="autoComplete_listSolicitante" class="text-danger"></small>
                                {!! Form::hidden('persona_id', 0, array('id' => 'persona_id')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="empresa" class="col-md-4 col-form-label text-md-right">Empresa</label>
                            <div class="col-md-8">
                                {!! Form::text('empresa', null, array('class' => 'form-control input-xs js-empresa', 'id' => 'empresa', 'autocomplete'=>'off')) !!}
                                <small id="autoComplete_listEmpresa" class="text-danger"></small>
                                {!! Form::hidden('empresa_id', 0, array('id' => 'empresa_id')) !!}
                                {!! Form::hidden('contratista_id', 0, array('id' => 'contratista_id')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="campamento_id" class="col-md-4 col-form-label text-md-right">Campamento</label>
                            <div class="col-md-8">
                                {!! Form::select('campamento_id',$cboCampamento, null, array('class' => 'form-control input-xs', 'id' => 'campamento_id')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="origen_id" class="col-md-4 col-form-label text-md-right">Origen</label>
                            <div class="col-md-8">
                                {!! Form::select('origen_id',$cboOrigen, null, array('class' => 'form-control input-xs', 'id' => 'origen_id', 'onchange' => 'calcularTarifa();')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="origen_id" class="col-md-4 col-form-label text-md-right">Destino</label>
                            <div class="col-md-8">
                                {!! Form::select('destino_id',$cboDestino, null, array('class' => 'form-control input-xs', 'id' => 'destino_id', 'onchange' => 'calcularTarifa();')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="monto" class="col-md-4 col-form-label text-md-right">Monto</label>
                            <div class="col-md-8">
                                {!! Form::text('monto', null, array('class' => 'form-control input-xs', 'id' => 'monto','readonly'=>'')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="motivo" class="col-md-4 col-form-label text-md-right">Motivo</label>
                            <div class="col-md-8">
                                <?php /* {!! Form::textarea('motivo', null, array('class' => 'form-control input-xs', 'id' => 'motivo','rows'=>'5')) !!} */ ?>
                                {!! Form::select('motivo',$cboMotivo, null, array('class' => 'form-control input-xs', 'id' => 'motivo')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fecha" class="col-md-4 col-form-label text-md-right">Fecha Hora</label>
                            <div class="col-md-8">
                                <input id="fecha" type="datetime-local" name="fecha" value="" class="form-control input-xs" />
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-primary" onclick='guardar();' id="btnGuardar">
                                    Guardar
                                </button>
                            </div>
                        </div>
                        <br />
                        <div class="form-group">
                            <div class="col-md-12 text-left">
                                <label style="font-weight: bold;">RECUERDA : </label>
                            </div>
                            <div class="col-md-12 text-left">
                                <label style="font-weight: bold;">(*) Generar traslado sólo si se confirma disponibilidad.</label>
                            </div>
                            <div class="col-md-12 text-left">
                                <label style="font-weight: bold;">(*) Sólo podrá registrar con una hora de anticipación.</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="dist/js/auto-complete.js"></script>
<script src="dist/js/funciones.js"></script>
<script src="dist/js/toastr.min.js"></script>

<script type="text/javascript">
    var a = <?php echo 1;//$contador;?> -1;
    $(document).ready(function() {
        const doSearchPersona = () => {

            var autoCompletejsPersona = new autoComplete({
                data: {
                    src: async () => {
                        // Loading placeholder text
                        document
                            .querySelector(".js-persona")
                            .setAttribute("placeholder", "Loading...");
               
                        // Fetch External Data Source
                        const headers = new Headers();
                        headers.append('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                        const config = {
                            headers,
                            method:'GET'
                        };
                          //Query
                        const query = document.querySelector('.js-persona').value;
                        const source = await fetch(
                            `searchAutocompletePersonaMovilizacion/${query}` , config
                        );
                        let data = await source.json();
                        // Post loading placeholder text
                        // Returns Fetched data
                        return data;
                    },
                    key: ["descripcion"],
                    cache: false
                },
                sort: (a, b) => {
                    if (a.match < b.match) return -1;
                    if (a.match > b.match) return 1;
                    return 0;
                },
                selector: ".js-persona",
                threshold: 1,
                debounce: 0,
                searchEngine: "strict",
                highlight: true,
                maxResults: 10,
                resultsList: {
                    render: true,
                    container: source => {
                        source.setAttribute("id", "autoComplete_list");
                        source.setAttribute("class", "u-search-ua__result");
                    },
                    destination: document.querySelector(".js-persona"),
                    position: "afterend",
                    element: "ul"
                },
                resultItem: {
                    content: (data, source) => {
                        source.innerHTML = data.match;
                    },
                    element: "li"
                },
                noResults: () => {
                    document.querySelector("#autoComplete_list").innerText = 'Sin resultados';
                    $('#persona_id').val("0");
                },
                onSelection: feedback => { //VA NUESTRA LOGICA
                    document.querySelector("#autoComplete_list").innerText = '';
                    const selection = feedback.selection.value;
                    document.querySelector(".js-persona").value = selection.persona;
                    document.querySelector("#persona_id").value = selection.id;
                    document.querySelector("#empresa_id").value = selection.empresa_id;
                    document.querySelector("#empresa").value = selection.empresa;
                    document.querySelector("#contratista_id").value = selection.contratista_id;
                    // console.log(feedback);
                }
            });
            
            // Toggle event for search input
            // showing & hidding results list onfocus / blur
            ["focus", "blur"].forEach(function(eventType) {
              const resultsList = document.querySelector("#autoComplete_list");
            
              document.querySelector(".js-persona").addEventListener(eventType, function() {
                // Hide results list & show other elemennts
                if (eventType === "blur") {
                  resultsList.style.display = "none";
                } else if (eventType === "focus") {
                  // Show results list & hide other elemennts
                  resultsList.style.display = "block";
                }
              });
            });
        };

        const doSearchEmpresa = () => {

            var autoCompletejsEmpresa = new autoComplete({
                data: {
                    src: async () => {
                        // Loading placeholder text
                        document
                            .querySelector(".js-empresa")
                            .setAttribute("placeholder", "Loading...");
               
                        // Fetch External Data Source
                        const headers = new Headers();
                        headers.append('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                        const config = {
                            headers,
                            method:'GET'
                        };
                          //Query
                        const query = document.querySelector('.js-empresa').value;
                        const source = await fetch(
                            `searchAutocompleteEmpresa/${query}` , config
                        );
                        let data = await source.json();
                        // Post loading placeholder text
                        // Returns Fetched data
                        return data;
                    },
                    key: ["descripcion"],
                    cache: false
                },
                sort: (a, b) => {
                    if (a.match < b.match) return -1;
                    if (a.match > b.match) return 1;
                    return 0;
                },
                selector: ".js-empresa",
                threshold: 1,
                debounce: 0,
                searchEngine: "strict",
                highlight: true,
                maxResults: 10,
                resultsList: {
                    render: true,
                    container: source => {
                        source.setAttribute("id", "autoComplete_list");
                        source.setAttribute("class", "u-search-ua__result");
                    },
                    destination: document.querySelector(".js-empresa"),
                    position: "afterend",
                    element: "ul"
                },
                resultItem: {
                    content: (data, source) => {
                        source.innerHTML = data.match;
                    },
                    element: "li"
                },
                noResults: () => {
                    document.querySelector("#autoComplete_listEmpresa").innerText = 'Sin resultados';
                },
                onSelection: feedback => { //VA NUESTRA LOGICA
                    document.querySelector("#autoComplete_listEmpresa").innerText = '';
                    const selection = feedback.selection.value;
                    document.querySelector(".js-empresa").value = selection.razonsocial;
                    $("#empresa_id").val(selection.empresa_id);
                    $("#contratista_id").val(selection.contratista_id);
                    // console.log(feedback);
                }
            });
            
            // Toggle event for search input
            // showing & hidding results list onfocus / blur
            ["focus", "blur"].forEach(function(eventType) {
              const resultsList = document.querySelector("#autoComplete_listEmpresa");
            
              document.querySelector(".js-empresa").addEventListener(eventType, function() {
                // Hide results list & show other elemennts
                if (eventType === "blur") {
                  resultsList.style.display = "none";
                } else if (eventType === "focus") {
                  // Show results list & hide other elemennts
                  resultsList.style.display = "block";
                }
              });
            });
        };

        doSearchPersona();
        doSearchEmpresa();
    });

var contador=0;
function guardar() {
    var band=true;
    var msg="";
    var btn = $("#btnGuardar");
    btn.attr('disabled', true);

    if($("#persona").val()==""){
        band = false;
        msg += " *No se selecciono un beneficiario \n";
    }
    if($("#origen_id").val()==$("#destino_id").val()){
        alert("Origen y destino no pueden ser iguales");
        btn.attr('disabled', false);
        return false;
    }
    if($("#campamento_id").val()==""){
        band = false;
        msg += " *No se selecciono un campamento \n";
    }
    if($("#persona_id").val()=="0"){
        band = false;
        msg += " *No esta registrada la persona \n";
    }
    if($("#empresa").val()==""){
        band = false;
        msg += " *No se selecciono una empresa \n";
    }
    if($("#empresa_id").val()=="0" && $("#contratista_id").val()=="0"){
        band = false;
        msg += " *No se selecciono una empresa registrada \n";
    }
    if($("#monto").val()=="" || $("#monto").val()=="0"){
        band = false;
        msg += " *No existe monto para el origen y destino \n";
    }
    if($("#fecha").val()==""){
        band = false;
        msg += " *No se selecciono una fecha \n";
    }
    if($("#motivo").val()==""){
        band = false;
        msg += " *No se selecciono un motivo \n";
    }
    var vmsg2 = "Desea guardar la siguiente información:\n";
    vmsg2+="*Beneficiario: "+$("#persona").val()+"\n";
    vmsg2+="*Empresa: "+$("#empresa").val()+"\n";
    vmsg2+="*Campamento: "+$("#campamento_id option:selected").text()+"\n";
    vmsg2+="*Origen: "+$("#origen_id option:selected").text()+"\n";
    vmsg2+="*Destino: "+$("#destino_id option:selected").text()+"\n";
    vmsg2+="*Monto: "+$("#monto").val()+"\n";
    vmsg2+="*Motivo: "+$("#motivo").val()+"\n";
    vmsg2+="*Fecha y Hora: "+$("#fecha").val()+"\n";
    if(confirm(vmsg2)){
        if(band && contador==0){
            contador=1;
            var idformulario = "#frmPedido";
            var data         = submitForm(idformulario);
            var respuesta    = '';
            var error = '';
            data.done(function(msg) {
                respuesta = msg;
            }).fail(function(xhr, textStatus, errorThrown) {
                respuesta = 'ERROR';
                contador=0;
                btn.attr('disabled', false);
            }).always(function() {

                if(respuesta === 'ERROR'){
                    console.log(error);
                    btn.attr('disabled', false);
                    contador=0;
                }else{
                    resp = respuesta
                    
                    if (resp === 'OK') {
                        mostrarMensaje('Guardado correctamente', 'OK');
                        window.open('https://h2olmos.pe/comedor/registromovilizacion','_self');
                    } else if(resp === 'ERROR') {
                        toastr.error(dat[0].msg , 'Error');
                        btn.attr('disabled', false);
                        contador=0;
                    } else {
                        alert(resp);
                        //mostrarMensaje(resp, 'ERROR');
                        btn.attr('disabled', false);
                        contador=0;
                    }
                }
            });
        }else{
            toastr.error(msg , "Corrige los siguientes errores");
            btn.attr('disabled', false);
        } 
    }else{
        btn.attr('disabled', false);
    }  
}

function calcularTarifa(){
    if($("#origen_id").val()!="" && $("#destino_id").val()!=""){
        if($("#origen_id").val()==$("#destino_id").val()){
            alert("Origen y destino no pueden ser iguales");
            return false;
        }
        $.ajax({
            type: "POST",
            url: "consultarTarifa",
            data: "origen_id="+$("#origen_id").val()+"&destino_id="+$("#destino_id").val()+"&_token="+$(':input[name="_token"]').val(),
            success: function(a) {
                $("#monto").val(a);
            }
        });
    }
}
</script>
@endsection
