<?php
/**
 * @package  LibBookSearch Plugin
 */
/*
Plugin Name: Library Book Search Plugin
Description: This is my first attempt on writing a custom Plugin from amazing tutorial series of Alessandro "Alecaddd" Castellani.
Version: 1.0.0
Author: Kuldeepsinh Jadeja
License: GPLv2 or later
Text Domain: lib-book-search-plugin
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
Copyright 2005-2015 Automattic, Inc.
*/

defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );

if ( !class_exists( 'LibBookSearch' ) ) {
	class LibBookSearch
	{
		public $plugin;		
		function __construct() {
			$this->plugin = plugin_basename( __FILE__ );			
		}

		function register() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
			add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );

			add_action( 'wp_ajax_call_my_ajax_handler', array( $this, 'my_ajax_handler'));
			add_action( 'wp_ajax_nopriv_call_my_ajax_handler', array( $this, 'my_ajax_handler') );

		}

		public function settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=libbooksearch-plugin">Settings</a>';
			array_push( $links, $settings_link );
			return $links;
		}

		public function add_admin_pages() {
			add_menu_page( 'LibBookSearch Plugin', 'BookSearch', 'manage_options', 'libbooksearch_plugin', array( $this, 'admin_index' ), 'dashicons-search', 110 );
		}

		public function admin_index() {
			require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
		}

		protected function create_post_type() {
			add_action( 'init', array( $this, 'custom_post_type' ) );
		}

		function custom_post_type() {
			register_post_type( 'book', ['public' => true, 'label' => 'Books'] );
		}

		function enqueue() {
			// enqueue all our scripts
			wp_enqueue_style( 'mypluginstyle1', plugins_url( '/assets/mystyle.css', __FILE__ ) );
			wp_enqueue_style( 'mypluginstyle2', plugins_url( '/assets/bootstrap.min.css', __FILE__ ) );
			
			wp_enqueue_script( 'mypluginscript2', plugins_url( '/assets/jquery-1.8.1.min.js', __FILE__ ) );
			wp_enqueue_script( 'mypluginscript3', plugins_url( '/assets/bootstrap.min.js', __FILE__ ) );
			wp_enqueue_script( 'mypluginscript1', plugins_url( '/assets/myscript.js', __FILE__ ) );
			
		}

		function activate() {
			require_once plugin_dir_path( __FILE__ ) . 'inc/libbooksearch-plugin-activate.php';
			LibBookSearchPluginActivate::activate();
		}



		function my_ajax_handler()
		{
		    global $wpdb;
		    //if(isset($_POST["code"]))
		    //if($_POST["code"] == $_SESSION["code"])
		    //{
				$where = "1";
				if(isset($_POST["bname"]) && $_POST["bname"] != "")
				{
					$where .= " and book_title ='".$_POST["bname"]."'";
				}

				$where .= (isset($_POST["desc"]) && $_POST["desc"] != '')? " and desc   ='".$_POST["desc"]."'" : '' ;
				$where .= (isset($_POST["pricemin"])  && isset($_POST["pricemax"])  && $_POST["pricemin"] != '' && $_POST["pricemax"] != '')? " and price  >= '".$_POST["pricemin"]."' and price <= '".$_POST["pricemax"]."'" : '';
				$where .= (isset($_POST["author"]) && $_POST["author" ] != '') ? " and author ='".$_POST["author"]."'" : '';
				$where .= (isset($_POST["rating"]) && $_POST["rating"] != '') ? " and rating ='".$_POST["rating"]."'" : '';
				$where .= (isset($_POST["publisher"]) && $_POST["publisher"] != '') ? " and publisher ='".$_POST["publisher"]."'" : '';
								 		
			    $books = $wpdb->get_results("SELECT * FROM wp_books where ".$where);
			    echo json_encode($books);
			    wp_die();
			//}
			/*		else
			{
				echo $_SESSION["code"];
				echo "something is wrong !!";
			}*/
		}

}

	$LibBookSearchPlugin = new LibBookSearch();
	$LibBookSearchPlugin->register();

	// activation
	register_activation_hook( __FILE__, array( $LibBookSearchPlugin, 'activate' ) );

	 $_SESSION["code"] = uniqid();
	 $jal_db_version = '1.0';

	function jal_install() 
	{
		global $wpdb;
		global $jal_db_version;

		$table_name = $wpdb->prefix . 'books';
		
		$charset_collate = $wpdb->get_charset_collate();

		$sql="CREATE TABLE IF NOT EXISTS $table_name (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `book_title` varchar(250) NOT NULL,
		  `desc` varchar(250) NOT NULL,
		  `author` varchar(100) NOT NULL,
		  `publisher` varchar(100) NOT NULL,
		  `price` float NOT NULL,
		  `rating` enum('0','1','2','3','4','5') DEFAULT NULL,
		  `time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `book_title` (`book_title`,`author`,`publisher`,`price`,`rating`)
		) $charset_collate;
		";

	   //var_dump($sql);
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );	
		dbDelta( $sql );

		add_option( 'jal_db_version', $jal_db_version );
	}


	function jal_install_data() 
	{
		global $wpdb;	
		$table_name = $wpdb->prefix . 'books';

		$books = array(
		  array('id' => '1','book_title' => 'bt1','desc' => 'desc1','author' => 'a1','publisher' => 'p1','price' => '1','rating' => '1','time' => '0000-00-00 00:00:00'),
		  array('id' => '2','book_title' => 'bt2','desc' => 'desc2','author' => 'a2','publisher' => 'p2','price' => '2','rating' => '2','time' => '0000-00-00 00:00:00'),
		  array('id' => '3','book_title' => 'bt3','desc' => 'desc3','author' => 'a3','publisher' => 'p3','price' => '3','rating' => '3','time' => '0000-00-00 00:00:00'),
		  array('id' => '4','book_title' => 'bt4','desc' => 'desc4','author' => 'a4','publisher' => 'p4','price' => '4','rating' => '4','time' => '0000-00-00 00:00:00'),
		  array('id' => '5','book_title' => 'bt5','desc' => 'desc5','author' => 'a5','publisher' => 'p5','price' => '5','rating' => '5','time' => '0000-00-00 00:00:00'),
		  array('id' => '6','book_title' => 'bt6','desc' => 'desc6','author' => 'a6','publisher' => 'p6','price' => '6','rating' => '5','time' => '0000-00-00 00:00:00'),
		  array('id' => '7','book_title' => 'bt7','desc' => 'desc7','author' => 'a7','publisher' => 'p7','price' => '7','rating' => '1','time' => '0000-00-00 00:00:00'),
		  array('id' => '8','book_title' => 'bt8','desc' => 'desc8','author' => 'a8','publisher' => 'p8','price' => '8','rating' => '2','time' => '0000-00-00 00:00:00'),
		  array('id' => '9','book_title' => 'bt9','desc' => 'desc9','author' => 'a9','publisher' => 'p9','price' => '9','rating' => '3','time' => '0000-00-00 00:00:00'),
		  array('id' => '10','book_title' => 'bt10','desc' => 'desc10','author' => 'a10','publisher' => 'p10','price' => '10','rating' => '4','time' => '0000-00-00 00:00:00'),
		  array('id' => '11','book_title' => 'bt11','desc' => 'desc11','author' => 'a11','publisher' => 'p11','price' => '11','rating' => '5','time' => '0000-00-00 00:00:00'),
		  array('id' => '12','book_title' => 'bt12','desc' => 'desc12','author' => 'a12','publisher' => 'p12','price' => '12','rating' => '1','time' => '0000-00-00 00:00:00'),
		  array('id' => '13','book_title' => 'bt13','desc' => 'desc13','author' => 'a13','publisher' => 'p13','price' => '13','rating' => '2','time' => '0000-00-00 00:00:00'),
		  array('id' => '14','book_title' => 'bt14','desc' => 'desc14','author' => 'a14','publisher' => 'p14','price' => '14','rating' => '3','time' => '0000-00-00 00:00:00'),
		  array('id' => '15','book_title' => 'bt15','desc' => 'desc15','author' => 'a15','publisher' => 'p15','price' => '15','rating' => '4','time' => '0000-00-00 00:00:00'),
		  array('id' => '16','book_title' => 'bt15','desc' => 'desc16','author' => 'a16','publisher' => 'p16','price' => '16','rating' => '5','time' => '0000-00-00 00:00:00'),
		  array('id' => '17','book_title' => 'bt17','desc' => 'desc17','author' => 'a17','publisher' => 'p17','price' => '17','rating' => '1','time' => '0000-00-00 00:00:00'),
		  array('id' => '18','book_title' => 'bt18','desc' => 'desc18','author' => 'a18','publisher' => 'p18','price' => '18','rating' => '2','time' => '0000-00-00 00:00:00'),
		  array('id' => '19','book_title' => 'bt19','desc' => 'desc19','author' => 'a19','publisher' => 'p19','price' => '19','rating' => '3','time' => '0000-00-00 00:00:00'),
		  array('id' => '20','book_title' => 'bt20','desc' => 'desc20','author' => 'a20','publisher' => 'p20','price' => '20','rating' => '4','time' => '0000-00-00 00:00:00'),
		  array('id' => '21','book_title' => 'bt21','desc' => 'desc21','author' => 'a21','publisher' => 'p21','price' => '21','rating' => '5','time' => '0000-00-00 00:00:00')
		);

		foreach($books as $book)
		{
			$wpdb->insert($table_name,$book);
		}
	}

	register_activation_hook( __FILE__, 'jal_install' );
	register_activation_hook( __FILE__, 'jal_install_data' );
	
	// deactivation
	require_once plugin_dir_path( __FILE__ ) . 'inc/libbooksearch-plugin-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'LibBookSearchPluginDeactivate', 'deactivate' ) );

}