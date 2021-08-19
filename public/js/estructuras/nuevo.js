/*function getDatosRif(){
    var txtRif = $('#rif');
    var ruta = txtRif.attr('url');
    $('#nacionalidad').val('');
    $('#razon').val('');
    $('#representante').val('');
    $('#numcuenta').val('');
    $('#tipocuenta').val('');
    $('#banco').val('');
    
    $.ajax({
        type: 'GET',
        url: ruta + '/proveedores/' + txtRif.val(),
        dataType: 'json',
        async: true,
        success: function (proveedores) {
            if(proveedores){               
                $('#nacionalidad').val(proveedores.nacionalidad);
                $('#razon').val(proveedores.razon);
                $('#representante').val(proveedores.representante);
                $('#numcuenta').val(proveedores.numcuenta);
                $('#tipocuenta').val(proveedores.tipocuenta);
                $('#bancotxt').val(proveedores.banco);                
            }
        }
    });
}


function getDatosCuenta(){
    var txtRif = $('#rif');
    var ruta = txtRif.attr('url');
    $('#numcuenta').val('');
    $('#tipocuenta').val('');
    $('#bancotxt').val('');
    //$('#numcuenta').val('');
   // $('#tipocuenta').val('');
   // $('#banco').val('');

    $.ajax({
        type: 'GET',
        url: ruta + '/cuentas_proveedores/' + txtRif.val(),
        dataType: 'json',
        async: true,
        success: function (cuentas_proveedores) {
            if(cuentas_proveedores){
                $('#numcuenta').val(cuentas_proveedores.numcuenta);
                $('#tipocuenta').val(cuentas_proveedores.tipocuenta);
                $('#bancotxt').val(cuentas_proveedores.bancotxt);
                //$('#numcuenta').val(proveedores.numcuenta);
                //$('#tipocuenta').val(proveedores.tipocuenta);
                //$('#bancotxt').val(proveedores.banco); 
            }
        }
    });
}

function getDatosFicha(){
    var txtFicha = $("#ficha");
    var ruta = txtFicha.attr("url");
    $("#cedula1").val("");
    $("#nombre1").val("");
    $("#apellido1").val("");
    $("#nacional1").val("");
    $("#numerocuenta1").val("");
    $("#tcuenta1").val("");
    $("#banco1").val("");
    
    $.ajax({
        type: "GET",
        url: ruta + "/ficha/" + txtFicha.val(),
        dataType: "json",
        async: true,
        success: function (ficha) {
            if(ficha){
                $("#cedula1").val(ficha.cedula);
                $("#nombre1").val(ficha.nombre);
                $("#apellido1").val(ficha.apellido);
                $("#nacional1").val(ficha.nacionalidad);
                $("#numerocuenta1").val(ficha.numcuenta);
                $("#banco1").val(ficha.banco);
                $("#tcuenta1").val(ficha.tcuenta);
            }
        }
    });
}
/*
function getDatosCedula(){
    var txtCedula = $('#cedula');
    var ruta = txtCedula.attr('url');
    $('#numerocuenta').val('');
    $.ajax({
        type: 'GET',
        url: ruta + '/cedula/' + txtCedula.val(),
        dataType: 'json',
        async: true,
        success: function (cedula) {
            if(cedula){               
                $('#numerocuenta').val(cedula.forp_ccc);

            }
        }
    });
}*/

