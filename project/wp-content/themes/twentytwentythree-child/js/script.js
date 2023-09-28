(function($){
    $(document).ready(function() {
        let make_alert_message = function (msg='', type='success') {
            return '<div class="alert alert-'+type+' alert-dismissible fade show" role="alert">'+msg+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
        }
        $('#customer_order_form').on('submit', (e) => {
            e.preventDefault();

            let form = $(e.currentTarget);
            let email = form.find('#sheepfish_email');
            let details = form.find('#sheepfish_details');

            $.ajax({
                url: myajax.url,
                method: form.attr('method'),
                data: {
                    action: form.data('action'),
                    customer_form_wpnonce: form.find('[name="customer_form_wpnonce"]').val(),
                    email: email.val(),
                    details: details.val()
                },
                beforeSend: () => {
                    $('#alerts_section').empty();
                },
                success: (resp) => {
                    let type = resp.success ? 'success' : 'danger';
                    let msg = "undefined" !== typeof resp.message ? resp.message : '';

                    $('#alerts_section').html(make_alert_message(msg, type));
                }
            });
        });
    });
})(jQuery);
