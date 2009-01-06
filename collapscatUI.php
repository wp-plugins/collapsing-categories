<?php
/*
Collapsing Categories version: 0.8.1
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

if( isset($_POST['resetOptions']) ) {
  if (isset($_POST['reset'])) {
    delete_option('collapsCatOptions');   
  }
} else {
  $style=$_POST['collapsCatStyle'];
  update_option('collapsCatStyle', $style);
}
$theOptions=get_option('collapsCatOptions');
/*
echo "<pre>\n";
print_r($theOptions);
echo "</pre>\n";
*/
if (empty($theOptions)) {
  $number = -1;
} elseif (!isset($theOptions['%i%']['title']) || 
    count($theOptions) > 1) {
  $widgetOn=1; 
  //return;
  $numbers=array_keys($theOptions);
  $number= $numbers[0];
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
    <p>
  <input type='hidden' id='collapsCatOrigStyle' value="<?php echo
stripslashes(get_option('collapsCatOrigStyle')) ?>" />
<label for="collapsCatStyle">Style info:</label>
   <input type='button' value='restore original style'
onclick='restoreStyle();' /><br />
   <textarea cols='78' rows='10' id="collapsCatStyle" name="collapsCatStyle">
    <?php echo stripslashes(get_option('collapsCatStyle')) ?>
   </textarea>
    </p>
<script type='text/javascript'>
function restoreStyle() {
  var defaultStyle = document.getElementById('collapsCatOrigStyle').value;
  var catStyle = document.getElementById('collapsCatStyle');
  catStyle.value=defaultStyle;
}
</script>
   </ul>
  </fieldset>
  <div class="submit">
   <input type="submit" name="infoUpdate" value="<?php _e('Update options', 'Collapsing Categories'); ?> &raquo;" />
  </div>
 </form>
</div>
