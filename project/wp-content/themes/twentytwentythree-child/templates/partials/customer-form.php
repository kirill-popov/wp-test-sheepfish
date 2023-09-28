<h2>Customer Order Form</h2>
<div id="alerts_section"></div>
<form id="customer_order_form" method="POST" data-action="customer_form_submit">
    <div class="mb-3">
        <label for="sheepfish_email" class="form-label">Email:</label>
        <input type="email" name="email" id="sheepfish_email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="sheepfish_details" class="form-label">Details:</label>
        <textarea name="details" id="sheepfish_details" cols="30" rows="4" class="form-control" ></textarea>
    </div>
    <?php wp_nonce_field('customer_form_submit', 'customer_form_wpnonce' ); ?>
    <input type="submit" value="Submit" class="btn btn-success">
</form>