<?php
/*
Plugin Name: Collapsing Categories
Plugin URI: http://blog.robfelty.com/plugins
Description: Uses javascript to expand and collapse categories to show the posts that belong to the category 
Author: Robert Felty
Version: 0.9.4
Author URI: http://robfelty.com
Tags: sidebar, widget, categories

Copyright 2007 Robert Felty

This work is largely based on the Fancy Categories plugin by Andrew Rader
(http://nymb.us), which was also distributed under the GPLv2. I have tried
contacting him, but his website has been down for quite some time now. See the
CHANGELOG file for more information.


This file is part of Collapsing Categories

		Collapsing Categories is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License as published by 
    the Free Software Foundation; either version 2 of the License, or (at your
    option) any later version.

    Collapsing Categories is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Collapsing Categories; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ 

$url = get_settings('siteurl');
if (!is_admin()) {
  add_action('wp_head', wp_enqueue_script('scriptaculous-effects'));
  add_action('wp_head', wp_enqueue_script('collapsFunctions',
  "$url/wp-content/plugins/collapsing-categories/collapsFunctions.js",'',
  '1.0'));
  add_action( 'wp_head', array('collapscat','get_head'));
  add_action( 'wp_footer', array('collapsCat','get_foot'));
}
add_action('admin_menu', array('collapscat','setup'));
add_action('activate_collapsing-categories/collapscat.php', array('collapscat','init'));

class collapscat {

	function init() {
	  include('collapsCatStyles.php');
		$defaultStyles=compact('custom','selected','default','block','noArrows');
    if( function_exists('add_option') ) {
      update_option( 'collapsCatOrigStyle', $style);
      update_option( 'collapsCatDefaultStyles', $defaultStyles);
    }
    if (!get_option('collapsCatOptions')) {
      $options=array('%i%' => array('title' => 'Categories',
                   'showPostCount' => 'yes',
                   'inExclude' => 'exclude', 'inExcludeCats' => '',
                   'showPosts' => 'yes', 'showPages' => 'no',
                   'linkToCat' => 'no', 'olderThan' => 0, 'excludeAll' => '0',
                   'catSortOrder' => 'ASC', 'catSort' => 'catName',
                   'postSortOrder' => 'ASC', 'postSort' => 'postTitle',
                   'expand' => '0', 'defaultExpand' => '', 'debug'=>'0',
									 'postTitleLength' => 0,
                   'animate' => '1', 'catfeed' => 'none'));
      update_option('collapsCatOptions', $options);
    }
    if (!get_option('collapsCatStyle')) {
      add_option( 'collapsCatStyle', $style);
		}
    if (!get_option('collapsCatSidebarId')) {
      add_option( 'collapsCatSidebarId', 'sidebar');
		}

	}

	function setup() {
		if( function_exists('add_options_page') ) {
			global $userdata;
			get_currentuserinfo();
			if ($userdata->user_level>9) {
				add_options_page(__('Collapsing Categories'),__('Collapsing
						Categories'),1,basename(__FILE__),array('collapscat','ui'));
			}
		}
	}
	function ui() {
		include_once( 'collapscatUI.php' );
	}

	function get_head() {
    $style=stripslashes(get_option('collapsCatStyle'));
    echo "<style type='text/css'>
    $style
    </style>\n";
	}
  function get_foot() {
    $url = get_settings('siteurl');
		echo "<script type=\"text/javascript\">\n";
		echo "// <![CDATA[\n";
		echo '/* These variables are part of the Collapsing Categories Plugin 
		      *  Version: 0.9.4
		      *  $Id$
					* Copyright 2007 Robert Felty (robfelty.com)
					*/' . "\n";
    $expandSym="<img src='". $url .
         "/wp-content/plugins/collapsing-categories/" . 
         "img/expand.gif' alt='expand' />";
    $collapseSym="<img src='". $url .
         "/wp-content/plugins/collapsing-categories/" . 
         "img/collapse.gif' alt='collapse' />";
    echo "var expandSym=\"$expandSym\";";
    echo "var collapseSym=\"$collapseSym\";";
    echo"
    addLoadEvent(function() {
      autoExpandCollapse('collapsCat');
    });
    ";

		echo "// ]]>\n</script>\n";
  }
}


function collapsCat($number) {
    include_once( 'collapscatlist.php' );
  if (!is_admin()) {
  list_categories($number);
  }
}
include('collapscatwidget.php');
?>
