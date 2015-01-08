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
});