<?php
/*
Collapsing Categories version: 1.0.alpha
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

if (isset($_POST['infoUpdate'])) {
  $style=$_POST['collapsCatStyle'];
  update_option('collapsCatStyle', $style);
  if ($widgetOn==0) {
		include('updateOptions.php');
  }
}
//include('processOptions.php');
?>
<div class=wrap>
 <form method="post">
  <h2><?php _e('Collapsing Categories Options', 'collapsing-categories') ?></h2>
  <fieldset name="Collapsing Categories Options">
   <legend><?php _e('Display Options:', 'collapsing-categories'); ?></legend>
   <ul style="list-style-type: none;">
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
   <input type="submit" name="infoUpdate" value="<?php _e('Update options', 
   'collapsing-categories'); ?> &raquo;" />
  </div>
 </form>
</div>
