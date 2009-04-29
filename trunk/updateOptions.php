<?php
foreach ( (array) $_POST['collapsCat'] as $widget_number => $widget_collapsCat ) {
  if ($widget_number!='%i%' && !isset($widget_collapsCat['title']) && isset($options[$widget_number]) ) // user clicked cancel
    continue;
  $title = strip_tags(stripslashes($widget_collapsCat['title']));
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
  $showPostCount= 'no' ;
  if( isset($widget_collapsCat['showPostCount'])) {
    $showPostCount= 'yes' ;
  }
  $showPages= 'no' ;
  if( isset($widget_collapsCat['showPages'])) {
    $showPages= 'yes' ;
  }
  $addMisc= false ;
  if( isset($widget_collapsCat['addMisc'])) {
    $addMisc= true ;
  }
  $addMiscTitle= $widget_collapsCat['addMiscTitle'];
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
  $expand= $widget_collapsCat['expand'];
  $customExpand= $widget_collapsCat['customExpand'];
  $customCollapse= $widget_collapsCat['customCollapse'];
  $postTitleLength= $widget_collapsCat['postTitleLength'];
  $olderThan= $widget_collapsCat['olderThan'];
  $catfeed= $widget_collapsCat['catfeed'];
  $catTag= $widget_collapsCat['catTag'];
  $inExclude= 'include' ;
  if($widget_collapsCat['inExclude'] == 'exclude') {
    $inExclude= 'exclude' ;
  }
  $animate='1';
  if( !isset($widget_collapsCat['animate'])) {
    $animate= '0' ;
  }
  $debug='0';
  if(isset($widget_collapsCat['debug'])) {
    $debug= '1' ;
  }
  $excludeAll='0';
  if(isset($widget_collapsCat['excludeAll'])) {
    $excludeAll= '1' ;
  }
  $inExcludeCats=addslashes($widget_collapsCat['inExcludeCats']);
  $defaultExpand=addslashes($widget_collapsCat['defaultExpand']);
  $options[$widget_number] = compact( 'title','showPostCount','catSort',
      'catSortOrder','defaultExpand','expand', 'customExpand', 
      'customCollapse', 'inExclude', 'showPosts',
      'inExcludeCats','postSort','postSortOrder','showPages', 'linkToCat',
      'catfeed','animate', 'debug','catTag', 'olderThan', 'excludeAll',
			'postTitleLength', 'addMisc', 'addMiscTitle');
}

update_option('collapsCatOptions', $options);
$updated = true;
?>
