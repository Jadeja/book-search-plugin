<?php
/**
 * @package  LibBookSearch
 */

class LibBookSearchPluginActivate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}