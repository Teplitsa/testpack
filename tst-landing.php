<?php
/*
Plugin Name: TST Landing - Landing page for WP
Description: Simple landing page for homepage
Version: 1.1
Author: Teplitsa
Author URI: https://te-st.ru/
Text Domain: tstl
Domain Path: /lang
Contributors:
	Gleb Suvorov aka gsuvorov (suvorov.gleb@gmail.com) - Idea, UX
	Anna Ladoshkina aka foralien (webdev@foralien.com) - Development


License URI: http://www.gnu.org/licenses/gpl-2.0.txt
License: GPLv2 or later
	Copyright (C) 2012-2014 by Teplitsa of Social Technologies (https://te-st.ru).

	GNU General Public License, Free Software Foundation <http://www.gnu.org/licenses/gpl-2.0.html>

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

if(!defined('ABSPATH')) die; // Die if accessed directly

// Plugin version:
if( !defined('TSTL_VERSION') )
    define('TSTL_VERSION', '1.1');

// Plugin DIR, with trailing slash:
if( !defined('TSTL_PLUGIN_DIR') )
    define('TSTL_PLUGIN_DIR', plugin_dir_path( __FILE__ ));

// Plugin URL:
if( !defined('TSTL_PLUGIN_BASE_URL') )
    define('TSTL_PLUGIN_BASE_URL', plugin_dir_url(__FILE__));

// Plugin ID:
if( !defined('TSTL_PLUGIN_BASE_NAME') )
    define('TSTL_PLUGIN_BASE_NAME', plugin_basename(__FILE__));



//load_plugin_textdomain('tstl', false, '/'.basename(TSTL_PLUGIN_DIR).'/lang');



/** Init **/
require_once(plugin_dir_path(__FILE__).'inc/core.php');
$tstl = TSTL_Core::get_instance();