(function($){
    $(document).ready(function() {
        console.log('+++');
        $('#customer_order').on('submit', (e) => {
            e.preventDefault();
            console.log('submit');
        });
    });
})(jQuery);
