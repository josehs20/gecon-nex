import * as gerais from '@/gerais.js';
var routeGetNcms = $('#dataView').data('routeGetNcms');
gerais.constructSelect2('ncm', routeGetNcms);
$('#form-fiscal-produto').on('submit', function (e) {
    e.preventDefault(); // Impede o envio do formulário

    let form = $(this);
    let url = form.attr('action');
    let formData = form.serialize(); // Serializa os dados do form

    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        success: function (response) {
            console.log(response);

        },
        error: function (xhr) {
            alert("Erro ao cadastrar o produto.");
            console.error(xhr.responseText);
        }
    });
});
