=== Collapsing Categories ===
Contributors: robfelty
Donate link: http://blog.robfelty.com/wordpress-plugins
Tags: categories
Requires at least: 2.0
Tested up to: 2.3
Stable tag: 0.2

This plugin uses Javascript to dynamically expand or collapsable the set of
posts for each category.

== Description ==

This is a very simple plugin that uses Javascript to form a collapsable set of
links in the sidebar for the categories. Every post corresponding to a given
category will be expanded.

It is largely based off of the Fancy Categories Plugin by Andrew Rader

== Installation ==

MANUAL INSTALLATION
(the only option for pre 2.3 wordpress, unless you have the widget plugin installed)

Unpackage contents to wp-content/plugins/ so that the files are in
a collapsCat directory. Now enable the plugin. To use the plugin,
change the following where appropriate	(most likely sidebar.php):

	<ul>
	 `<?php wp_list_cats(...); ?>`
	</ul>

To something of the following:
`
	<?php
	  if( function_exists('collapsCat') ) {
	  collapsCat();
	} else {
	  echo "<ul>\n";
	  wp_list_cats(...);
	  echo "</ul>\n";
	}
	?>
`

The above will fall back to the WP function for categories if you disable
the plugin.

WIDGET INSTALLATION

For those who have widget capabilities, (default in Wordpress 2.3+), installation is easier. 

Unpackage contents to wp-content/plugins/ so that the files are in a collapsCat
directory. There should be 2 new plugins in your Wordpress Admin interface --
Collapsing Categories, and Collapsing Categories Widget. You must enable both
of them, in that order. Then simply go the Presentation > Widgets section and
drag over the Collapsing Categories Widget.

== Frequently Asked Questions ==

None yet.

== Screenshots ==

None yet.

== Demo ==

I use this plugin in my blog at http://blog.robfelty.com


== OPTIONS AND CONFIGURATIONS ==

Options for Collapsing Categories are found under Options -> Collapsing
Categories, the only option so far is the option to display post count.

== CAVEAT ==

Currently this plugin relies on Javascript to expand and collapse the links.
If a user's browser doesn't support javascript they won't see the links to the
posts, but the links to the categories will still work (which is the default
behavior in wordpress anyways)

