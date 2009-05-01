<?php

function collapsCatWidget($args, $widget_args=1) {
  extract($args, EXTR_SKIP);
  if ( is_numeric($widget_args) )
    $widget_args = array( 'number' => $widget_args );
  $widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
  extract($widget_args, EXTR_SKIP);

  $options = get_option('collapsCatOptions');
  if ( !isset($options[$number]) )
    return;

  $title = ($options[$number]['title'] != "") ? $options[$number]['title'] : ""; 

    echo $before_widget . $before_title . $title . $after_title;
       if( function_exists('collapsCat') ) {
        collapsCat($number);
       } else {
        echo "<ul>\n";
        wp_list_links('sort_column=name&optioncount=1&hierarchical=0');
        echo "</ul>\n";
       }

    echo $after_widget;
  }


function collapsCatWidgetInit() {
if ( !$options = get_option('collapsCatOptions') )
    $options = array();
  $control_ops = array('width' => 450, 'height' => 350, 'id_base' => 'collapscat');
	$widget_ops = array('classname' => 'collapsCat', 'description' => __('Links expand and collapse to show sublinks and/or posts'));
  $name = __('Collapsing Links');

  $id = false;
  foreach ( array_keys($options) as $o ) {
    // Old widgets can have null values for some reason
    if ( !isset($options[$o]['title']) || !isset($options[$o]['title']) )
      continue;
    $id = "collapscat-$o"; // Never never never translate an id
    wp_register_sidebar_widget($id, $name, 'collapsCatWidget', $widget_ops, array( 'number' => $o ));
    wp_register_widget_control($id, $name, 'collapsCatWidgetControl', $control_ops, array( 'number' => $o ));
  }

  // If there are none, we register the widget's existance with a generic template
  if ( !$id ) {
    wp_register_sidebar_widget( 'collapscat-1', $name, 'collapsCatWidget', $widget_ops, array( 'number' => -1 ) );
    wp_register_widget_control( 'collapscat-1', $name, 'collapsCatWidgetControl', $control_ops, array( 'number' => -1 ) );
  }

}

// Run our code later in case this loads prior to any required plugins.
if (function_exists('collapsCat')) {
	add_action('widgets_init', 'collapsCatWidgetInit');
} else {
	$fname = basename(__FILE__);
	$current = get_settings('active_plugins');
	array_splice($current, array_search($fname, $current), 1 ); // Array-fu!
	update_option('active_plugins', $current);
	do_action('deactivate_' . trim($fname));
	header('Lolinkion: ' . get_settings('siteurl') . '/wp-admin/plugins.php?deactivate=true');
	exit;
}

	function collapsCatWidgetControl($widget_args) {
  global $wp_registered_widgets;
  static $updated = false;

  if ( is_numeric($widget_args) )
    $widget_args = array( 'number' => $widget_args );
  $widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
  extract( $widget_args, EXTR_SKIP );

  $options = get_option('collapsCatOptions');
  if ( !is_array($options) )
    $options = array();

  if ( !$updated && !empty($_POST['sidebar']) ) {
    $sidebar = (string) $_POST['sidebar'];

    $sidebars_widgets = wp_get_sidebars_widgets();
    if ( isset($sidebars_widgets[$sidebar]) )
      $this_sidebar =& $sidebars_widgets[$sidebar];
    else
      $this_sidebar = array();

    foreach ( $this_sidebar as $_widget_id ) {
      if ( 'collapsCatWidget' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
        $widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
        if ( !in_array( "collapsCat-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed.
          unset($options[$widget_number]);
      }
    }
    include('updateOptions.php');
  }
  include('processOptions.php');


    echo '<p style="text-align:right;"><label for="collapsCat-title-'.$number.'">' . __('Title:') . '<input class="widefat" style="width: 200px;" id="collapsCat-title-'.$number.'" name="collapsCat['.$number.'][title]" type="text" value="'.$title.'" /></label></p>';
  include('options.txt');
  ?>
  <p>Style can be set from the <a
  href='options-general.php?page=collapsCat.php'>options page</a></p>
   <?php
    echo '<input type="hidden" id="collapsCat-submit-'.$number.'" name="collapsCat['.$number.'][submit]" value="1" />';

	}
?>
