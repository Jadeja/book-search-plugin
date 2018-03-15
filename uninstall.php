<?php

/**
 * Trigger this file on Plugin uninstall
 *
 * @package  AlecadddPlugin
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Clear Database stored data
$books = get_posts( array( 'post_type' => 'book', 'numberposts' => -1 ) );

foreach( $books as $book ) {
	wp_delete_post( $book->ID, true );
}

// Access the database via SQL
global $wpdb;
/*$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'book'" );
$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );
$wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)" );
*/
$wpdb->delete( 'wp_books', array( 'ID' => 1 ), array( ‘%d’ ); 
//$wpdb::delete( 'wp_books', array( 'id' => 1 ));

//$values[] = 0;  
//$conditions[] = "`$field` IS NULL";
//$sql = "DELETE FROM `$table` WHERE $conditions";
//$this->query( $this->prepare( $sql, $values ) );

$wpdb->query( "DELETE FROM wp_books WHERE id IS NOT NULL" );
$wpdb->query( "DROP TABLE IF EXISTS wp_books" );
delete_option("my_plugin_db_version");