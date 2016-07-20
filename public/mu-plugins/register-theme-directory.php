<?php
/*
Plugin Name:    Register Theme Directory
Plugin URI:     https://github.com/tagmeo/tagmeo
Description:    Register default theme directory
Version:        1.0.0
Author:         Kyle Anderson
Author URI:     https://divspace.com/
License:        MIT License
License URI:    http://opensource.org/licenses/MIT
*/

if (!defined('WP_DEFAULT_THEME')) {
    register_theme_directory(ABSPATH.'wp-content/themes');
}
