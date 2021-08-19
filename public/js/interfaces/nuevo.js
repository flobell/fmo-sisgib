/*
 * function getDatosRif(element){
    var fila = element.id;
    var txtRif = $('#'+fila);
    var ruta = txtRif.attr('url');
    $('#nacionalidad'+fila.charAt(fila.length-1)).val('');
    $('#razon'+fila.charAt(fila.length-1)).val('');
    $('#representante'+fila.charAt(fila.length-1)).val('');
    $('#numcuenta'+fila.charAt(fila.length-1)).val('');
    $('#tipocuenta'+fila.charAt(fila.length-1)).val('');
    $('#bancotxt'+fila.charAt(fila.length-1)).val('');
    
    $.ajax({
        type: 'GET',
        url: ruta + '/proveedores/' + txtRif.val(),
        dataType: 'json',
        async: true,
        success: function (proveedores) {
            console.log(proveedores);
            if(proveedores){               
                $('#nacionalidad'+fila.charAt(fila.length-1)).val(proveedores.nacionalidad);
                $('#razon'+fila.charAt(fila.length-1)).val(proveedores.razon);
                $('#representante'+fila.charAt(fila.length-1)).val(proveedores.representante);
                $('#numcuenta'+fila.charAt(fila.length-1)).val(proveedores.numcuenta);
                $('#tipocuenta'+fila.charAt(fila.length-1)).val(proveedores.tipocuenta);
                $('#bancotxt'+fila.charAt(fila.length-1)).val(proveedores.codigo_banco);                
            }
        }
    });
}

function getDatosFicha(element){
    var fila = element.id;
    //console.log(element.id);
    var txtFicha = $('#'+fila);
    var ruta = txtFicha.attr("url");
    $("#cedula"+fila.charAt(fila.length-1)).val("");
    $("#nombre"+fila.charAt(fila.length-1)).val("");
    $("#apellido"+fila.charAt(fila.length-1)).val("");
    $("#nacional"+fila.charAt(fila.length-1)).val("");
    $("#numerocuenta"+fila.charAt(fila.length-1)).val("");
    $("#tcuenta"+fila.charAt(fila.length-1)).val("");
    $("#banco"+fila.charAt(fila.length-1)).val("");
    
    $.ajax({
        type: "GET",
        url: ruta + "/ficha/" + txtFicha.val(),
        dataType: "json",
        async: true,
        success: function (ficha) {
            if(ficha){
                $("#cedula"+fila.charAt(fila.length-1)).val(ficha.cedula);
                $("#nombre"+fila.charAt(fila.length-1)).val(ficha.nombre);
                $("#apellido"+fila.charAt(fila.length-1)).val(ficha.apellido);
                $("#nacional"+fila.charAt(fila.length-1)).val(ficha.nacionalidad);
                $("#numerocuenta"+fila.charAt(fila.length-1)).val(ficha.numcuenta);
                $("#banco"+fila.charAt(fila.length-1)).val(ficha.banco);
                $("#tcuenta"+fila.charAt(fila.length-1)).val(ficha.tcuenta);
            }
        }
    });
}

*/
