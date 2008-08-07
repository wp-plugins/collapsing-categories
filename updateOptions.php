<?php
  if( isset($_POST['showPostCount']) ) {
    update_option( 'collapsCatShowPostCount', 'yes' );
  }
  else {
    update_option( 'collapsCatShowPostCount', 'no' );
  }
  if( isset($_POST['collapsCatShowPages']) ) {
    update_option( 'collapsCatShowPages', 'yes' );
  }
  else {
    update_option( 'collapsCatShowPages', 'no' );
  }
  if($_POST['archives'] == 'root') {
    update_option( 'collapsCatLinkToArchives', 'root' );
  } elseif ($_POST['archives'] == 'archives') {
    update_option( 'collapsCatLinkToArchives', 'archives' );
  } elseif ($_POST['archives'] == 'index') {
    update_option( 'collapsCatLinkToArchives', 'index' );
  }
  if($_POST['sortOrder'] == 'ASC') {
    update_option( 'collapsCatSortOrder', 'ASC' );
  } elseif ($_POST['sortOrder'] == 'DESC') {
    update_option( 'collapsCatSortOrder', 'DESC' );
  }
  if($_POST['sortPostOrder'] == 'ASC') {
    update_option( 'collapsCatPostSortOrder', 'ASC' );
  } elseif ($_POST['sortPostOrder'] == 'DESC') {
    update_option( 'collapsCatPostSortOrder', 'DESC' );
  }
  if($_POST['sort'] == 'catName') {
    update_option( 'collapsCatSort', 'catName' );
  } elseif ($_POST['sort'] == 'catId') {
    update_option( 'collapsCatSort', 'catId' );
  } elseif ($_POST['sort'] == 'catSlug') {
    update_option( 'collapsCatSort', 'catSlug' );
  } elseif ($_POST['sort'] == 'catOrder') {
    update_option( 'collapsCatSort', 'catOrder' );
  } elseif ($_POST['sort'] == 'catCount') {
    update_option( 'collapsCatSort', 'catCount' );
  } elseif ($_POST['sort'] == '') {
    update_option( 'collapsCatSort', '' );
    update_option( 'collapsCatSortOrder', '' );
  }
  if($_POST['showPosts'] == 'yes') {
    update_option( 'collapsCatShowPosts', 'yes' );
  } elseif ($_POST['showPosts'] == 'no') {
    update_option( 'collapsCatShowPosts', 'no' );
  }
  if($_POST['collapsCatExpand'] == '0') {
    update_option( 'collapsCatExpand', 0 );
  } elseif ($_POST['collapsCatExpand'] == '1') {
    update_option( 'collapsCatExpand', 1 );
  } elseif ($_POST['collapsCatExpand'] == '2') {
    update_option( 'collapsCatExpand', 2 );
  }
  //if($_POST['collapsCatExclude']) {
    $excludeSafe=addslashes($_POST['collapsCatExclude']);
    //$excludeSafe=wp_texturize($_POST['exclude']);
    update_option( 'collapsCatExclude', $excludeSafe);
  //}
?>
