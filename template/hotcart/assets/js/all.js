/*
jQuery(document).ready(function($) {
    $(document.body).on('added_to_cart', function() {
        alert('Produto adicionado ao carrinho!');
        $.ajax({
            url: cwmp.ajaxUrl,
            type: 'POST',
            data: {
                action: 'cwmpAddToCart'
            },
            success: function(response) {
                $('body').append(response);
            },
            error: function(xhr, status, error) {
                console.log("Erro ao chamar a função AJAX: " + error);
            }
        });
    });
});
*/