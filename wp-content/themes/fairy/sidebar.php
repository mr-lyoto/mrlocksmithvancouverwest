<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fairy
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside class="widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<div class="taggbox" style="width:100%;height:100%" data-widget-id="149903" data-tags="false" ></div><script src="https://widget.taggbox.com/embed-lite.min.js" type="text/javascript"></script>
</aside><!-- #secondary -->
