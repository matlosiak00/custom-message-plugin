jQuery(document).ready(function($) {
    $('#cmp-form').on('submit', function(e) {
        e.preventDefault(); // Zatrzymanie domyślnego wysyłania formularza

        var message = $('#cmp-message').val();
        var responseArea = $('#cmp-response');

        $.ajax({
            url: cmp_ajax.ajax_url, // Adres AJAX z WordPressa
            type: 'POST',
            data: {
                action: 'cmp_save_message',
                nonce: cmp_ajax.nonce,
                message: message
            },
            success: function(response) {
                if (response.success) {
                    responseArea.text(response.data).css('color', 'green');
                } else {
                    responseArea.text(response.data).css('color', 'red');
                }
            },
            error: function() {
                responseArea.text('Błąd połączenia!').css('color', 'red');
            }
        });
    });
});
