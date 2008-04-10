<?php
  if( isset($_POST['showPostCount']) ) {
    update_option( 'collapsCatShowPostCount', 'yes' );
  }
  else {
    update_option( 'collapsCatShowPostCount', 'no' );
  }
  if( isset($_POST['showPages']) ) {
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
  if($_POST['sort'] == 'catName') {
    update_option( 'collapsCatSort', 'catName' );
  } elseif ($_POST['sort'] == 'catId') {
    update_option( 'collapsCatSort', 'catId' );
  } elseif ($_POST['sort'] == '') {
    update_option( 'collapsCatSort', '' );
    update_option( 'collapsCatSortOrder', '' );
  }
  if($_POST['showPosts'] == 'yes') {
    update_option( 'collapsCatShowPosts', 'yes' );
  } elseif ($_POST['showPosts'] == 'no') {
    update_option( 'collapsCatShowPosts', 'no' );
  }
?>
