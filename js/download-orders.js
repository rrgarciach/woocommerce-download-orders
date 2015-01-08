/*
Plugin Name: DISTRIBUIDORA GC - Download Orders
Plugin URI: http://developercats.com
Description: Plugin for WooCommerce to download the store orders as CSV, XLS or custom TXT format.
Author: Ruy R. Garcia
Version: 0.0.1
Author URI: http://developercats.com
*/
jQuery(document).ready(function() {
	jQuery('#date_from').datepicker({
		dateFormat : 'yy-mm-dd'
	});

	jQuery('#date_to').datepicker({
		dateFormat : 'yy-mm-dd'
	});

	jQuery('#customer_id_from').keyup(function() {
		var text = this.value;
		jQuery('#customer_id_to').val( this.value );
	});

	jQuery('#reset_form').click(function() {
		jQuery('.search_form_class').val('');
	});
});
