<?php

function collapsCatWidget($args) {
  extract($args);
  $options = get_option('collapsCatWidget');
  $title = ($options['title'] != "") ? $options['title'] : ""; 

  //$title = $options['title'];

    echo $before_widget . $before_title . $title . $after_title;
    
       if( function_exists('collapsCat') ) {
        collapsCat();
       } else {
        echo "<ul>\n";
        wp_list_cats('sort_column=name&optioncount=1&hierarchical=0');
        echo "</ul>\n";
       }

    echo $after_widget;
  }


function collapsCatWidgetInit() {
	$widget_ops = array('classname' => 'collapsCatWidget', 'description' => __('Categories expand and collapse to show subcategories and/or posts'));
	if (function_exists('register_sidebar_widget')) {
    register_sidebar_widget('Collapsing Categories', 'collapsCatWidget');
    register_widget_control('Collapsing Categories', 'collapsCatWidgetControl','300px');
	}
}

// Run our code later in case this loads prior to any required plugins.
if (function_exists('collapsCat')) {
	add_action('plugins_loaded', 'collapsCatWidgetInit');
} else {
	$fname = basename(__FILE__);
	$current = get_settings('active_plugins');
	array_splice($current, array_search($fname, $current), 1 ); // Array-fu!
	update_option('active_plugins', $current);
	do_action('deactivate_' . trim($fname));
	header('Location: ' . get_settings('siteurl') . '/wp-admin/plugins.php?deactivate=true');
	exit;
}

	function collapsCatWidgetControl() {
		$options = get_option('collapsCatWidget');
    if ( !is_array($options) ) {
      $options = array('title'=>'Categories'
     //   'cache'=> '',
      //  'lastcheck' => 0,
       // 'apikey' => '9vmtth9uar6ykr8qa5cjx46n',
    //    'url' => '',
     //   'cachetime' => '3600',
      //  'imagesize' => 'large',
       // 'content' => ''
      );
     }

		if ( $_POST['collapsCat-submit'] ) {
			//$newoptions['zip']	= strip_tags(stripslashes($_POST['get_weather-zip']));
			//$newoptions['location']	= strip_tags(stripslashes($_POST['get_weather-location']));
			//$newoptions['options']	= strip_tags(stripslashes($_POST['get_weather-options']));
			$options['title']	= strip_tags(stripslashes($_POST['collapsCat-title']));
		}
    foreach ($_POST as $key=>$value) {
      echo "key = $key<br />\n";
      echo "value = $value<br />\n";
    }
    update_option('collapsCatWidget', $options);
    include('updateOptions.php');
		$title		= wp_specialchars($options['title']);
    // Here is our little form segment. Notice that we don't need a
    // complete form. This will be embedded into the existing form.
    echo '<p style="text-align:right;"><label for="collapsCat-title">' . __('Title:') . '<input class="widefat" style="width: 200px;" id="collapsCat-title" name="collapsCat-title" type="text" value="'.$title.'" /></label></p>';
    include('options.txt');
   ?>
   <?php
    echo '<input type="hidden" id="collapsCat-submit" name="collapsCat-submit" value="1" />';

	}
?>
