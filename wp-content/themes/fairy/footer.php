<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fairy
 */

?>

<div class="container">
	<h2>
		Follow us on Youtube
	</h2>
	
		<div class="taggbox" style="width:100%;height:100%" data-widget-id="149509" data-tags="false" ></div><script src="https://widget.taggbox.com/embed-lite.min.js" type="text/javascript"></script>
	
</div>
<p>&nbsp;</p>

<!--div class="container">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8053.251927894334!2d-123.13216524587537!3d49.206645434063205!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x548674eddb488b03%3A0x2bf60ffaec35c90e!2s8685%20Shaughnessy%20St%2C%20Vancouver%2C%20BC%20V6P%203Y3!5e0!3m2!1sen!2sca!4v1707975487092!5m2!1sen!2sca" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe-->
</div>

<p>&nbsp;</p>

</div> <!-- #content -->
<?php
/**
 * fairy_newsletter hook.
 *
 * @since 1.0.0
 *
 * @hooked fairy_construct_newsletter - 10
 *
 */
do_action('fairy_newsletter');
?>

	<footer id="colophon" class="site-footer">
        <?php
        if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) {
            ?>
            <section class="site-footer-top">
                <div class="container">
                    <div class="row">
                        <?php
                        if (is_active_sidebar('footer-1')) {
                            ?>
                            <div class="col-12 col-sm-1-1 col-md-1-3">
                                <?php dynamic_sidebar('footer-1'); ?>
                            </div>
                            <?php
                        }
                        if (is_active_sidebar('footer-2')) {
                            ?>

                            <div class="col-12 col-sm-1-1 col-md-1-3">
                                <?php dynamic_sidebar('footer-2'); ?>
                            </div>
                            <?php
                        }
                        if (is_active_sidebar('footer-3')) {
                            ?>
                            <div class="col-12 col-sm-1-1 col-md-1-3">
                                <?php dynamic_sidebar('footer-3'); ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </section>
            <?php
        }
        ?>
		<section class="site-footer-bottom">
			<div class="container">
                <div class="fairy-menu-social">
                    <?php
                    /**
                     * fairy_footer_social hook.
                     *
                     * @since 1.0.0
                     *
                     * @hooked fairy_construct_footer_social - 10
                     *
                     */
                    do_action('fairy_footer_social');
                    ?>
                </div>
                <?php
                /**
                 * fairy_footer_info_texts hook.
                 *
                 * @since 1.0.0
                 *
                 * @hooked fairy_footer_copyright - 10
                 * @hooked fairy_footer_theme_info - 20
                 *
                 */
//                do_action('fairy_footer_info_texts');
                ?>
				<p>© 2024 Mr. Locksmith <a href="https://www.terrywhinyates.com">Terry Whin-Yates</a> All rights reserved.</p>
			</div>
		</section>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php
/**
 * fairy_construct_gototop hook
 *
 * @since 1.0.0
 *
 */
do_action('fairy_gototop');

 wp_footer(); ?>

</body>
</html>
