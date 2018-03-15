<?php
/**
 * @package  LibBookSearch
 */

class LibBookSearchPluginDeactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	    global $wpdb;		
		$wpdb->query( "DELETE FROM wp_books WHERE id IS NOT NULL" );		
	}
}