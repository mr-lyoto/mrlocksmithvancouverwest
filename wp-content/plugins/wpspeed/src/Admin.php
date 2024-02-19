<?php
namespace WPSpeed;

/**
 * WPSpeed - Performs several front-end optimizations for fast downloads
 *
 * @package   WPSpeed
 * @author    JExtensions Store <info@storejextensions.org>
 * @copyright Copyright (c) 2022 JExtensions Store / WPSpeed
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

use WPSFramework\Application\Application;
use WPSFramework\Container\Container;
use WPSpeed\Core\Admin\Ajax\Ajax;
use WPSpeed\Admin\Settings\Html;
use WPSpeed\Admin\Settings\TabSettings;
use WPSpeed\Platform\Plugin;

abstract class Admin
{

	public static function addAdminMenu()
	{
		$hook_suffix = add_options_page( __( 'WPSpeed Settings', 'wpspeed' ), 'WPSpeed Settings', 'manage_options', 'wpspeed', [
			__CLASS__,
			'loadAdminPage'
		] );

		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'loadResourceFiles' ] );
		add_action( 'admin_head-' . $hook_suffix, [ __CLASS__, 'addScriptsToHead' ] );
		add_action( 'load-' . $hook_suffix, [ __CLASS__, 'initializeSettings' ] );

		add_action( 'admin_bar_menu', [ __CLASS__, 'addMenuToAdminBar' ], 100 );
		add_action( 'admin_init', [ __CLASS__, 'checkMessages' ] );
		
		add_filter( 'plugin_action_links_' . plugin_basename(WPSPEED_FILE_PATH), [ static::class, 'add_action_link' ], 10, 2 );
	}

	public static function loadAdminPage()
	{
		try
		{
			$oContainer   = new Container();
			$oApplication = Application::getInstance( 'WPSpeedApplication', $oContainer );
			$oApplication->initialise();
			$oApplication->route();
			$oApplication->dispatch();
			$oApplication->render();
		}
		catch ( \Exception $e )
		{
			$class = get_class( $e );
			echo <<<HTML
<h1>Error initializing the WPSpeed plugin</h1>
<div class="alert alert-danger">  {$class} &mdash;  {$e->getMessage()}  </div>
<pre class="well"> {$e->getTraceAsString()} </pre>
HTML;
		}
	}

	public static function registerOptions()
	{
		register_setting( 'wpspeedOptionsPage', 'wpspeed_settings', [ 'type' => 'array' ] );
	}

	/**
	 * Adds links to Premium Support and FAQ under the plugin in the plugin overview page.
	 *
	 * @param array  $links Array of links for the plugins, adapted when the current plugin is found.
	 * @param string $file  The filename for the current plugin, which the filter loops through.
	 *
	 * @return array
	 */
	public static function add_action_link( $links, $file ) {
		// Add link to docs.
		$faq_link = '<a href="https://storejextensions.org/faq/wpspeed-faq-learn-how-to-make-wordpress-running-fast-for-compliance-with-the-google-pagespeed.html" target="_blank">' . __( 'FAQ', 'wpspeed' ) . '</a>';
		array_push( $links, $faq_link );
		
		$docs_link = '<a href="https://storejextensions.org/wpspeed-documentation.html" target="_blank">' . __( 'PDF docs', 'wpspeed' ) . '</a>';
		array_push( $links, $docs_link );
		
		// Add link to premium landing page.
		$premium_link = '<a style="font-weight: bold;" href="https://storejextensions.org/extensions/wpspeed.html" target="_blank">' . __( 'Upgrade to PRO', 'wpspeed' ) . '</a>';
		array_unshift( $links, $premium_link );
		
		return $links;
	}

	public static function loadResourceFiles( $hook )
	{
		if ( 'settings_page_wpspeed' != $hook )
		{
			return;
		}

		wp_enqueue_style( 'wpspeed-bootstrap-css' );
		wp_enqueue_style( 'wpspeed-verticaltabs-css' );
		wp_enqueue_style( 'wpspeed-admin-css' );
		wp_enqueue_style( 'wpspeed-fonts-css' );
		wp_enqueue_style( 'wpspeed-select2-css' );
		wp_enqueue_style( 'wpspeed-wordpress-css' );

		wp_enqueue_script( 'wpspeed-adminutilities-js' );
		wp_enqueue_script( 'wpspeed-bootstrap-js' );
		wp_enqueue_script( 'wpspeed-adminutility-js' );
		wp_enqueue_script( 'wpspeed-multiselect-js' );

		wp_enqueue_script( 'wpspeed-select2-js' );
		wp_enqueue_script( 'wpspeed-collapsible-js' );
	}

	public static function initializeSettings()
	{
		//Css files
		wp_register_style( 'wpspeed-bootstrap-css', WPSPEED_URL . 'media/bootstrap/css/bootstrap.css', [], WPSPEED_VERSION );

		wp_register_style( 'wpspeed-fonts-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css' );
		wp_register_style( 'wpspeed-select2-css', WPSPEED_URL . 'media/select2/select2.min.css', [], WPSPEED_VERSION );
		wp_register_style( 'wpspeed-wordpress-css', WPSPEED_URL . 'media/css/admin.css', [], WPSPEED_VERSION );

		//Javascript files
		wp_register_script( 'wpspeed-bootstrap-js', WPSPEED_URL . 'media/bootstrap/js/bootstrap.bundle.min.js', [ 'jquery' ], WPSPEED_VERSION, true );
		wp_register_script( 'wpspeed-adminutilities-js', WPSPEED_URL . 'media/js/adminutilities.js', [ 'jquery' ], WPSPEED_VERSION, true );
		wp_register_script( 'wpspeed-adminutility-js', WPSPEED_URL . 'media/core/js/admin-utility.js', [ 'jquery' ], WPSPEED_VERSION, true );
		wp_register_script( 'wpspeed-multiselect-js', WPSPEED_URL . 'media/core/js/multiselect.js', [
			'jquery',
			'wpspeed-adminutility-js',
			'wpspeed-adminutilities-js'
		], WPSPEED_VERSION, true );
		wp_register_script( 'wpspeed-select2-js', WPSPEED_URL . 'media/select2/select2.min.js', [ 'jquery' ], WPSPEED_VERSION, true );

		$aSettingsArray = TabSettings::getSettingsArray();

		foreach ( $aSettingsArray as $section => $aSettings )
		{
			add_settings_section( 'wpspeed_' . $section . '_section', '', [
				'\\WPSpeed\\Admin\\Settings\\Renderer\\Section',
				$section
			], 'wpspeedOptionsPage' );

			/**
			 * $aArgs = [
			 *        0 => title,
			 *        1 => description,
			 *        2 => new
			 * ]
			 */
			foreach ( $aSettings as $setting => $aArgs )
			{
				list( $title, $description, $new ) = array_pad( $aArgs, 3, 0 );

				$id    = 'wpspeed_' . $setting;
				$title = Html::description( $title, $description, $new );
				$args  = [];

				$aClasses = self::getSettingsClassMap();

				if ( isset( $aClasses[$setting] ) )
				{
					$args['class'] = $aClasses[$setting];
				}

				add_settings_field( $id, $title, [
					'\\WPSpeed\\Admin\\Settings\\Renderer\\Setting',
					$setting
				], 'wpspeedOptionsPage', 'wpspeed_' . $section . '_section', $args );
			}
		}
	}

	public static function addScriptsToHead()
	{
		
		echo <<<HTML
		<script type="text/javascript">
		(function($){
			$(document).ready(function () {
					$(".select2-ctrl").select2({
					    tags: true,
					    tokenSeparators: [',']
					})

					$('.hasPopover').popover({
						container: 'body',
						placement: 'bottom',
						trigger: 'hover focus',
						html: true
					})
				});
			})(jQuery);
		 </script>
                
HTML;
	}
	
	private static function getSettingsClassMap()
	{
		return [
			'pro_http2_file_types' => 'wpspeed-wp-checkboxes-grid-wrapper columns-4',
			'staticfiles'          => 'wpspeed-wp-checkboxes-grid-wrapper columns-5',
			'pro_staticfiles_2'    => 'wpspeed-wp-checkboxes-grid-wrapper columns-5',
			'pro_staticfiles_3'    => 'wpspeed-wp-checkboxes-grid-wrapper columns-5',
			'pro_html_sections'    => 'wpspeed-wp-checkboxes-grid-wrapper columns-5 width-400'
		];
	}

	public static function addMenuToAdminBar( $admin_bar )
	{
		if ( ! current_user_can( 'manage_options' ) )
		{
			return;
		}

		$aArgs = [
			'id'     => 'wpspeed-settings',
			'title'  => __( '<span class="ab-icon dashicons-admin-settings"></span>WPSpeed', 'wpspeed' ),
			'href'   => add_query_arg( [
				'page' => 'wpspeed',
			], admin_url( 'options-general.php' ) )
		];

		$admin_bar->add_node( $aArgs );
		
		$aArgs = [
			'id'     => 'wpspeed-clear-cache',
			'title'  => __( '<span class="ab-icon dashicons-trash"></span>WPSpeed cache', 'wpspeed' ),
			'href'   => add_query_arg( [
					'page' => 'wpspeed&task=cleancache',
			], admin_url( 'options-general.php' ) )
		];
		
		$admin_bar->add_node( $aArgs );
	}

	public static function getCurrentAdminUri()
	{
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$uri = preg_replace( '|^.*/wp-admin/|i', '', $uri );

		if ( ! $uri )
		{
			return '';
		}

		return $uri;
	}

	public static function checkMessages()
	{
		if ( get_transient( 'wpspeed_notices' ) )
		{
			add_action( 'admin_notices', [ __CLASS__, 'publishAdminNotices' ] );
		}
	}

	public static function publishAdminNotices()
	{
		$oContainer = new Container();

		try
		{
			/** @var \WPSpeed\Admin\Application $oApplication */
			$oApplication = Application::getInstance( 'WPSpeedApplication', $oContainer );
			$oApplication->publishMessages( $oApplication->getMessageQueue() );
		}
		catch ( WPSFramework\Exception\App $e )
		{
		}
	}

	public static function loadActionLinks( $links, $file )
	{
		static $this_plugin;

		if ( ! $this_plugin )
		{
			$this_plugin = plugin_basename( WPSPEED_FILE_PATH );
		}

		if ( $file == $this_plugin )
		{
			$settings_link = '<a href="' . admin_url( 'options-general.php?page=wpspeed' ) . '">' . __( 'Settings' ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;
	}

	public static function doAjaxMultiSelect()
	{
		echo Ajax::getInstance( 'MultiSelect' )->run();
		die();
	}
}