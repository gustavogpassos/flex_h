

function gerasenha(key) {


    var dados = {
        'cd_filial': $('#cd_filial').val(),
        'nr_pedido': $('#nr_pedido').val(),
        'cd_cliente': $('#cd_cliente').val(),
        'cd_tipocobr': $('#cd_tipocobr').val(),
        'cd_condpgto': $('#cd_condpgto').val(),
        'cd_produto': $('#cd_produto' + key).val(),
        'nr_tabpreco': $('#nr_tabpreco' + key).val(),
        'vl_desc': $('#vl_desc' + key).val()
    };

    var stringData = JSON.stringify(dados);

    $.ajax({
        type: 'get',
        url: 'ws/comunicaws.php',
        data: dados,
        success: function (response, textStatus, jqXHR) {
            var resposta = JSON.parse(response);

            console.log(parseFloat(resposta.senha_liberacao).toFixed(2));

            $('#nr_senha' + key).val(parseFloat(resposta.senha_liberacao).toFixed(2));

        },
        error: function (response) {
            console.log('Erro: ' + response);
        }
    });
    //console.log(data);

}

function negasenha(key){
    $('#nr_senha' + key).val("Negado");
}