<?php
/*
Collapsing Categories version: 0.9.4
Copyright 2007 Robert Felty

This work is largely based on the Fancy Categories plugin by Andrew Rader
(http://nymb.us), which was also distributed under the GPLv2. I have tried
contacting him, but his website has been down for quite some time now. See the
CHANGELOG file for more information.

This file is part of Collapsing Categories

    Collapsing Categories is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Collapsing Categories is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Collapsing Categories; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

check_admin_referer();

$options=get_option('collapsCatOptions');
$widgetOn=0;
$number='%i%';

if (empty($options)) {
  $number = '-1';
} elseif (!isset($options['%i%']['title']) || 
    count($options) > 1) {
  $widgetOn=1; 
}

if( isset($_POST['resetOptions']) ) {
  if (isset($_POST['reset'])) {
    delete_option('collapsCatOptions');   
		$widgetOn=0;
  }
} elseif (isset($_POST['infoUpdate'])) {
  $style=$_POST['collapsCatStyle'];
	$defaultStyles=get_option('collapsCatDefaultStyles');
	$selectedStyle=$_POST['collapsCatSelectedStyle'];
	$defaultStyles['selected']=$selectedStyle;
	$defaultStyles['custom']=$_POST['collapsCatStyle'];
  update_option('collapsCatStyle', $style);
  update_option('collapsCatSidebarId', $_POST['collapsCatSidebarId']);
  update_option('collapsCatDefaultStyles', $defaultStyles);
  if ($widgetOn==0) {
    include('updateOptions.php');
  }
  $options=get_option('collapsCatOptions');
}
include('processOptions.php');
?>
<div class=wrap>
 <form method="post">
  <h2>Collapsing Categories Options</h2>
  <fieldset name="Collapsing Categories Options">
   <legend><?php _e('Display Options:'); ?></legend>
   <ul style="list-style-type: none;">
   <?php
   if ($widgetOn==1) {
     echo "
    <div style='width:60em; background:#FFF; color:#444;border: 1px solid
    #444;padding:0 1em'>
    <p>If you wish to use the collapsing categories plugin as a widget, you
    should set the options in the widget page (except for custom styling,
    which is set here). If you would like to use it manually (that is, you
    modify your theme), then click below to delete the current widget options.
    </p>
    <form method='post'>
    <p>
       <input type='hidden' name='reset' value='true' />
       <input type='submit' name='resetOptions' value='reset options' />
       </p>
    </form>
    </div>
    ";
    } else {
      echo '<p style="text-align:left;"><label for="collapsCat-title-'.$number.'">' . __('Title:') . '<input class="widefat" style="width: 200px;" id="collapsCat-title-'.$number.'" name="collapsCat['.$number.'][title]" type="text" value="'.$title.'" /></label></p>';
      include('options.txt'); 
   }
   ?>
	 Id of the sidebar where collapsing pages appears: 
	 <input id='collapsCatSidebarId' name='collapsCatSidebarId' type='text' size='20' value="<?php echo
	 get_option('collapsCatSidebarId')?>" onchange='changeStyle();' />
	 <table>
	   <tr>
		   <td>
  <input type='hidden' id='collapsCatCurrentStyle' value="<?php echo
stripslashes(get_option('collapsCatStyle')) ?>" />
  <input type='hidden' id='collapsCatSelectedStyle'
	name='collapsCatSelectedStyle' />
<label for="collapsCatStyle">Select style: </label>
			 </td>
			 <td>
			 <select name='collapsCatDefaultStyles' id='collapsCatDefaultStyles' 
			   onchange='changeStyle();' >
			 <?php
		$url = get_settings('siteurl') . '/wp-content/plugins/collapsing-pages';
			 $styleOptions=get_option('collapsCatDefaultStyles');
			 //print_r($styleOptions);
			 $selected=$styleOptions['selected'];
			 foreach ($styleOptions as $key=>$value) {
			   if ($key!='selected') {
           if ($key==$selected) {
					   $select=' selected=selected ';
					 } else {
						 $select=' ';
					 }
					 echo '<option' .  $select . 'value="'.
					     stripslashes($value) . '" >'.$key . '</option>';
         }
       }
			 ?>
			 </select>
	     </td>
			 <td>Preview<br />
			 <img style='border:1px solid' id='collapsCatStylePreview' alt='preview' />
			 </td>
		</tr>
		</table>
		You may also customize your style below if you wish<br />
   <input type='button' value='restore current style'
onclick='restoreStyle();' /><br />
   <textarea onfocus='customStyle();' cols='78' rows='10' id="collapsCatStyle" name="collapsCatStyle"><?php echo stripslashes(get_option('collapsCatStyle')) ?></textarea>
    </p>
<script type='text/javascript'>
function changeStyle() {
	var preview = document.getElementById('collapsCatStylePreview');
	var pageStyles = document.getElementById('collapsCatDefaultStyles');
	var selectedStyle;
	var hiddenStyle=document.getElementById('collapsCatSelectedStyle');
	for(i=0; i<pageStyles.options.length; i++) {
		if (pageStyles.options[i].selected == true) {
			selectedStyle=pageStyles.options[i];
		}
	}
	hiddenStyle.value=selectedStyle.innerHTML
	preview.src='<?php echo $url ?>/img/'+selectedStyle.innerHTML+'.png';
  var pageStyle = document.getElementById('collapsCatStyle');
	// add in the name of the sidebar
  var sidebarId=document.getElementById('collapsCatSidebarId').value;
  var theStyle='';
  if (sidebarId!='') {
    theStyle='#' + sidebarId;
  }
  theStyle+=' ul.collapsCatList li:before {content: \'\'}\n' + selectedStyle.value;
  pageStyle.value=theStyle;
}
function restoreStyle() {
  var defaultStyle = document.getElementById('collapsCatCurrentStyle').value;
  var pageStyle = document.getElementById('collapsCatStyle');
  pageStyle.value=defaultStyle;
}
function customStyle() {
	var hiddenStyle=document.getElementById('collapsCatSelectedStyle');
	hiddenStyle.value='custom';
}
	changeStyle();
</script>
  </fieldset>
  <div class="submit">
   <input type="submit" name="infoUpdate" value="<?php _e('Update options', 'Collapsing Categories'); ?> &raquo;" />
  </div>
 </form>
</div>
