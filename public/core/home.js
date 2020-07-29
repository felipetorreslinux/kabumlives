$(function(){
    $("#modal-comprar select[name=valor]").on('change', function(){
        var value = $(this).val();
        if(value != ""){
            $("#modal-comprar .btn-comprar").html("Comprar R$ "+parseFloat(value).toFixed(2));
        }else{
            $("#modal-comprar .btn-comprar").html("Comprar");
        }
        $("#modal-comprar .btn-comprar").on('click', function(){});
    });

    $(".btn-credito").on('click', function(){
        $("#lista").val(null);
        $("#modal-checker").modal("show");
    });

    $(".btn-geradas").on('click', function(){
        $("#lista").val(null);
        $("#modal-geradas").modal("show");
    });

    $(".btn-copy").on('click', function(){ 
        var range = document.createRange();
        range.selectNode(document.getElementById("aprovadas"));
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand("copy");
        window.getSelection().removeAllRanges();
        $(this).html("Copiando...");
        setTimeout(() => {
            $(this).html("Copiado!!!");
        }, 1000);
        setTimeout(() => {
            $(this).html("Copiar");
        }, 2000);
    });

    $.notifyDefaults({
        delay: 1000,
        timer: 2000,
        allow_dismiss: false
    });

});


function enviarLista(){
    var linha = $("#lista").val().trim();
    var linhaenviar = linha.split("\n");
    var total = linhaenviar.length;
    var testadas = 0;

    $("#modal-checker #lista").attr('readonly', true);
    $("#modal-checker #aprovadas").html(null);
    $("#modal-checker .btn-enviar-cartao").html("Processando. Aguarde...");
    $("#modal-checker .btn-fechar-cartao").addClass('d-none');

    if(linha.length > 0){
        linhaenviar.forEach(function(value, index){
            setTimeout(() => {
                $.ajax({
                    url: '/cartao/consulta?lista='+value,
                    type: 'GET',
                    async: true,
                    success: function(rep) {
                        $("#modal-checker #aprovadas").append(rep.message);
                    },
                    error:function(){console.clear()},
                    complete:function(){
                        removelinha()
                        testadas++;
                        if(testadas == total){
                            testadas = 0;
                            $("#modal-checker .btn-enviar-cartao").html("Iniciar");
                            $("#modal-checker .btn-fechar-cartao").removeClass('d-none');
                            $("#modal-checker #lista").attr('readonly', false);
                        }
                    }
                });
            }, index * 500);
        });
    }else{
        $.notifyClose();
        $.notify({message: "<span class='white'>Informe sua DB</span>"},{type: 'danger'});
    }
}

function removelinha() {
    var lines = $("#lista").val().split('\n');
    lines.splice(0, 1);
    $("#lista").val(lines.join("\n"));
}