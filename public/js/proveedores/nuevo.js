/*function getDatosBanco(){
    var txtCodigo = $('#cod');
    var ruta = txtCodigo.attr('url');
    $('#codtxt').val('');
    $('#banco').val('');
    $('#bcv').val('');
    $('#numcuenta').val('');

    $.ajax({
        type: 'GET',
        url: ruta + '/bancos/' + txtCodigo.val(),
        dataType: 'json',
        async: true,
        success: function (bancos) {
            if(bancos){               
                $('#codtxt').val(bancos.codigo);
                $('#banco').val(bancos.nombre_banco);
                $('#bcv').val(bancos.bcv);
                $('#numcuenta').val(bancos.bcv);
            }
        }
    });
}

*/

