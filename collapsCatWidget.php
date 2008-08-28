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
        wp_list_cats('sort_column=name&optioncount=1&hierarchical=0');
        echo "</ul>\n";
       }

    echo $after_widget;
  }


function collapsCatWidgetInit() {
if ( !$options = get_option('collapsCatOptions') )
    $options = array();
  $control_ops = array('width' => 400, 'height' => 350, 'id_base' => 'collapsCat');
	$widget_ops = array('classname' => 'collapsCat', 'description' =>
  __('Categories expand and collapse to show subcategories and/or posts'));
  $name = __('Collapsing Categories');

  $id = false;
  foreach ( array_keys($options) as $o ) {
    // Old widgets can have null values for some reason
    if ( !isset($options[$o]['title']) || !isset($options[$o]['title']) )
      continue;
    $id = "collapsCat-$o"; // Never never never translate an id
    wp_register_sidebar_widget($id, $name, 'collapsCatWidget', $widget_ops, array( 'number' => $o ));
    wp_register_widget_control($id, $name, 'collapsCatWidgetControl', $control_ops, array( 'number' => $o ));
  }

  // If there are none, we register the widget's existance with a generic template
  if ( !$id ) {
    wp_register_sidebar_widget( 'collapsCat-1', $name, 'collapsCatWidget', $widget_ops, array( 'number' => -1 ) );
    wp_register_widget_control( 'collapsCat-1', $name, 'collapsCatWidgetControl', $control_ops, array( 'number' => -1 ) );
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

    foreach ( (array) $_POST['collapsCat'] as $widget_number => $widget_collapsCat ) {
      if ( !isset($widget_collapsCat['title']) && isset($options[$widget_number]) ) // user clicked cancel
        continue;
      $title = strip_tags(stripslashes($widget_collapsCat['title']));
      $showLinkCount= 'no' ;
      if( isset($widget_collapsCat['showLinkCount']) ) {
        $showLinkCount= 'yes' ;
      }  
      $catSortOrder= 'ASC' ;
      if($widget_collapsCat['catSortOrder'] == 'DESC') {
        $catSortOrder= 'DESC' ;
      }
      $showPosts= 'yes' ;
      if($widget_collapsCat['showPosts'] == 'no') {
        $showPosts= 'no' ;
      }
      $linkToCat= 'yes' ;
      if($widget_collapsCat['linkToCat'] == 'no') {
        $linkToCat= 'no' ;
      }
      $showPostCount= 'yes' ;
      if($widget_collapsCat['showPostCount'] == 'no') {
        $showPostCount= 'no' ;
      }
      $showPages= 'no' ;
      if($widget_collapsCat['showPages'] == 'yes') {
        $showPages= 'yes' ;
      }
      if($widget_collapsCat['catSort'] == 'catName') {
        $catSort= 'catName' ;
      } elseif ($widget_collapsCat['catSort'] == 'catId') {
        $catSort= 'catId' ;
      } elseif ($widget_collapsCat['catSort'] == 'catSlug') {
        $catSort= 'catSlug' ;
      } elseif ($widget_collapsCat['catSort'] == 'catOrder') {
        $catSort= 'catOrder' ;
      } elseif ($widget_collapsCat['catSort'] == 'catCount') {
        $catSort= 'catCount' ;
      } elseif ($widget_collapsCat['catSort'] == '') {
        $catSort= '' ;
        $catSortOrder= '' ;
      }
      $postSortOrder= 'ASC' ;
      if($widget_collapsCat['postSortOrder'] == 'DESC') {
        $postSortOrder= 'DESC' ;
      }
      if($widget_collapsCat['postSort'] == 'postTitle') {
        $postSort= 'postTitle' ;
      } elseif ($widget_collapsCat['postSort'] == 'postId') {
        $postSort= 'postId' ;
      } elseif ($widget_collapsCat['postSort'] == 'postComment') {
        $postSort= 'postComment' ;
      } elseif ($widget_collapsCat['postSort'] == 'postDate') {
        $postSort= 'postDate' ;
      } elseif ($widget_collapsCat['postSort'] == '') {
        $postSort= '' ;
        $postSortOrder= '' ;
      }
      if($widget_collapsCat['expand'] == '0') {
        $expand= 0 ;
      } elseif ($widget_collapsCat['expand'] == '1') {
        $expand= 1 ;
      } elseif ($widget_collapsCat['expand'] == '2') {
        $expand= 2 ;
      }
      $inExclude= 'include' ;
      if($widget_collapsCat['inExclude'] == 'exclude') {
        $inExclude= 'exclude' ;
      }
      $inExcludeCats=addslashes($widget_collapsCat['inExcludeCats']);
      $defaultExpand=addslashes($widget_collapsCat['defaultExpand']);
      $options[$widget_number] = compact( 'title','showPostCount','catSort',
          'catSortOrder','defaultExpand','expand','inExclude', 'showPosts',
          'inExcludeCats','postSort','postSortOrder','showPages', 'linkToCat' );
    }

    update_option('collapsCatOptions', $options);
    $updated = true;
  }

 if ( -1 == $number ) {
    /* default options go here */
    $title = 'Categories';
    $text = '';
    $showPostCount = 'no';
    $catSort = 'catName';
    $catSortOrder = 'ASC';
    $postSort = 'postTitle';
    $postSortOrder = 'ASC';
    $defaultExpand='';
    $number = '%i%';
    $expand='0';
    $inExclude='include';
    $inExcludeCats='';
    $showPosts='yes';
    $linkToCat='yes';
    $showPages='yes';
  } else {
    $title = attribute_escape($options[$number]['title']);
    $showPostCount = $options[$number]['showPostCount'];
    $expand = $options[$number]['expand'];
    $inExcludeCats = $options[$number]['inExcludeCats'];
    $inExclude = $options[$number]['inExclude'];
    $catSort = $options[$number]['catSort'];
    $catSortOrder = $options[$number]['catSortOrder'];
    $postSort = $options[$number]['postSort'];
    $postSortOrder = $options[$number]['postSortOrder'];
    $defaultExpand = $options[$number]['defaultExpand'];
    $showPosts = $options[$number]['showPosts'];
    $showPages = $options[$number]['showPages'];
    $linkToCat = $options[$number]['linkToCat'];
  }

		//$title		= wp_specialchars($options['title']);
    // Here is our little form segment. Notice that we don't need a
    // complete form. This will be embedded into the existing form.
    echo '<p style="text-align:right;"><label for="collapsCat-title-'.$number.'">' . __('Title:') . '<input class="widefat" style="width: 200px;" id="collapsCat-title-'.$number.'" name="collapsCat['.$number.'][title]" type="text" value="'.$title.'" /></label></p>';
  ?>
    <p>
     <input type="checkbox" name="collapsCat[<?php echo $number ?>][showPostCount]" <?php if ($showPostCount=='yes')  echo 'checked'; ?> id="collapsCat-showPostCount-<?php echo $number ?>"></input> <label for="collapsCatShowPostCount">Show Post Count </label>
     <input type="checkbox" name="collapsCat[<?php echo $number
     ?>][showPages]" <?php if ($showPages=='yes')  echo 'checked'; ?>
     id="collapsCat-showPages-<?php echo $number ?>"></input> <label
     for="collapsCatShowPages">Show Pages as well as posts </label>
    </p>
    <p>Sort Categories by:<br />
     <select name="collapsCat[<?php echo $number ?>][catSort]">
     <option <?php if($catSort=='catName') echo 'selected'; ?> id="sortName" value='catName'> category name</option>
     <option <?php if($catSort=='catId') echo 'selected'; ?> id="sortId" value='catId'> category id</option>
     <option <?php if($catSort=='catSlug') echo 'selected'; ?> id="sortSlug" value='catSlug'> category Slug</option>
     <option <?php if($catSort=='catOrder') echo 'selected'; ?> id="sortOrder" value='catOrder'> category (term) Order</option>
     <option <?php if($catSort=='catCount') echo 'selected'; ?> id="sortCount" value='catCount'> category Count</option>
    </select>
     <input type="radio" name="collapsCat[<?php echo $number ?>][catSortOrder]" <?php if($catSortOrder=='ASC') echo 'checked'; ?> id="collapsCat-catSortASC-<?php echo $number ?>" value='ASC'></input> <label for="collapsCatCatSortASC">Ascending</label>
     <input type="radio" name="collapsCat[<?php echo $number ?>][catSortOrder]" <?php if($catSortOrder=='DESC') echo 'checked'; ?> id="collapsCat-catSortDESC-<?php echo $number ?>" value='DESC'></input> <label for="collapsCatCatSortDESC">Descending</label>
    </p>
    <p>Sort Posts by:<br />
     <select name="collapsCat[<?php echo $number ?>][postSort]">
     <option <?php if($postSort=='postTitle') echo 'selected'; ?>
     id="sortPostTitle-<?php echo $number ?>" value='postTitle'>Post Title</option>
     <option <?php if($postSort=='postId') echo 'selected'; ?> id="sortPostId-<?php echo $number ?>" value='postId'>Post id</option>
     <option <?php if($postSort=='postDate') echo 'selected'; ?>
     id="sortPostDate-<?php echo $number ?>" value='postDate'>Post Date</option>
     <option <?php if($postSort=='postComment') echo 'selected'; ?>
     id="sortComment-<?php echo $number ?>" value='postComment'>Post Comment
     Count</option>
    </select>
     <input type="radio" name="collapsCat[<?php echo $number ?>][postSortOrder]" <?php if($postSortOrder=='ASC') echo 'checked'; ?> id="postSortASC" value='ASC'></input> <label for="postPostASC">Ascending</label>
     <input type="radio" name="collapsCat[<?php echo $number ?>][postSortOrder]" <?php if($postSortOrder=='DESC') echo 'checked'; ?> id="postPostDESC" value='DESC'></input> <label for="postPostDESC">Descending</label>
    </p>
    <p>Expanding shows:<br />
     <input type="radio" name="collapsCat[<?php echo $number ?>][showPosts]" <?php if($showPosts=='yes') echo 'checked'; ?> id="showPostsYes" value='yes'></input> <label for="showPostsYes">Sub-categories and Posts</label>
     <input type="radio" name="collapsCat[<?php echo $number ?>][showPosts]"
     <?php if($showPosts=='no') echo 'checked'; ?> id="showPostsNo" value='no'></input> <label for="showPostsNO">Just Sub-categories</label>
    </p>
    <p>Clicking on category name:<br />
     <input type="radio" name="collapsCat[<?php echo $number ?>][linkToCat]"
     <?php if($linkToCat=='yes') echo 'checked'; ?>
     id="collapsCat-linkToCatYes-<?php echo $number ?>"
     value='yes'></input> <label for="collapsCat-linkToCatYes">Links to category archive</label>
     <input type="radio" name="collapsCat[<?php echo $number ?>][linkToCat]"
     <?php if($linkToCat=='no') echo 'checked'; ?>
     id="collapsCat-linkToCatNo-<?php echo $number ?>"
     value='no'></input> <label for="linkToCatNo">Expands to show
     sub-categories and/or Posts</label>
    </p>
    <p>Expanding and collapse characters:<br />
     <input type="radio" name="collapsCat[<?php echo $number ?>][expand]" <?php if($expand==0) echo 'checked'; ?> id="expand0" value='0'></input> <label for="expand0">&#9658;&nbsp;&#9660;</label>
     <input type="radio" name="collapsCat[<?php echo $number ?>][expand]" <?php if($expand==1) echo 'checked'; ?> id="expand1" value='1'></input> <label for="expand1">+&nbsp;&mdash;</label>
     <input type="radio" name="collapsCat[<?php echo $number ?>][expand]" <?php if($expand==2) echo 'checked'; ?> id="expand2" value='2'></input> <label for="expand1">[+]&nbsp;[&mdash;]</label>
    </p>
    <p>Auto-expand these link categories (separated by commas):<br />
     <input type="text" name="collapsCat[<?php echo $number ?>][defaultExpand]" value="<?php echo $defaultExpand ?>" id="collapsCat-defaultExpand-<?php echo $number ?>"</input> 
    </p>
    <p> 
     <select name="collapsCat[<?php echo $number ?>][inExclude]">
     <option  <?php if($inExclude=='include') echo 'selected'; ?> id="inExcludeInclude-<?php echo $number ?>" value='include'>Include</option>
     <option  <?php if($inExclude=='exclude') echo 'selected'; ?> id="inExcludeExclude-<?php echo $number ?>" value='exclude'>Exclude</option>
     </select>
     these link categories (separated by commas):<br />
    <input type="text" name="collapsCat[<?php echo $number ?>][inExcludeCats]" value="<?php echo $inExcludeCats ?>" id="collapsCat-inExcludeCats-<?php echo $number ?>"</input> 
    </p>
   <?php
    echo '<input type="hidden" id="collapsCat-submit-'.$number.'" name="collapsCat['.$number.'][submit]" value="1" />';

	}
?>
