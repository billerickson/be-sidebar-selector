# BE Sidebar Selector #
**Contributors:** billerickson  
**Tags:** sidebar  
**Requires at least:** 4.1  
**Tested up to:** 4.5.2  
**Stable tag:** 1.0.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

Create new sidebars, and select which sidebar is used when editing pages

## Installation ##

[Download the plugin here.](https://github.com/billerickson/be-sidebar-selector/archive/master.zip) Once installed, go to Appearance > Edit Widget Areas create new widget areas. Go to Appearance > Widgets to populate those widget areas with widgets. When editing a page, use the Sidebar Selector metabox to select which sidebar to use for that page.

Place the following code in your theme wherever you'd like the sidebar to appear (typically in sidebar.php):  
`do_action( 'be_sidebar_selector' );`

## Customization ##

The following filters are available to customize the default settings.

* `be_sidebar_selector_post_types` - What post types this can be used on. Default: array( 'page' )
* `be_sidebar_selector_default_sidebar` - The name and id of the default sidebar. Default: array( 'name' => 'Default Sidebar', 'id' => 'default-sidebar' )
* `be_sidebar_selector_widget_area_args` - Customize the $args passed to [register_sidebar()](https://codex.wordpress.org/Function_Reference/register_sidebar). Useful for setting things like before/after widget, before/after title, etc. [Example](http://www.billerickson.net/code/default-widget-area-arguments/). Default: empty array

## Screenshots ##

### Edit Widget Areas ###
![edit widget areas](https://s3.amazonaws.com/f.cl.ly/items/193b2p0O0w2C3T3U2G0S/Screen%20Shot%202016-06-07%20at%206.51.24%20PM.png?v=565e2328)

### Widgets ###
![widgets](https://s3.amazonaws.com/f.cl.ly/items/1h2N1q1s403p1p024008/Screen%20Shot%202016-06-07%20at%206.52.21%20PM.png?v=150cf283)

### Sidebar Selector ###
![sidebar selector](https://s3.amazonaws.com/f.cl.ly/items/1s3T1j213r1d2T2g1934/Screen%20Shot%202016-06-07%20at%206.53.02%20PM.png?v=43b5e7de)