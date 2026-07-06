<?php
/**
 * Uninstall ContinueWith plugin options.
 *
 * @package ContinueWith
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

delete_option('continuewith_site_key');
delete_option('continuewith_enabled');
