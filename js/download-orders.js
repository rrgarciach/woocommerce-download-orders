/*
Plugin Name: DISTRIBUIDORA GC - Download Orders
Plugin URI: http://developercats.com
Description: Plugin for WooCommerce to download the store orders as CSV, XLS or custom TXT format.
Author: Ruy R. Garcia
Version: 0.0.1
Author URI: http://developercats.com
*/
jQuery(document).ready(function() {
	// alert('ok');
	jQuery('#datefrom').datepicker({
		dateFormat : 'yy-mm-dd'
	});
	jQuery('#dateto').datepicker({
		dateFormat : 'yy-mm-dd'
	});
});
function popup(){

   alert('hello there this is a test popup');

}