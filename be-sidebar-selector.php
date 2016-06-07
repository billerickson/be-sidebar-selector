<?php
/**
 * Plugin Name: BE Sidebar Selector
 * Version: 1.0.0
 * Description: Create new sidebars, and select which sidebar is used when editing pages
 * Author: Bill Erickson
 * Author URI: http://www.billerickson.net
 * Plugin URI: https://github.com/billerickson/be-sidebar-selector
 * Text Domain: be-sidebar-selector
 * Domain Path: /languages
 * @package BE_Sidebar_Selector
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main Class
 *
 * @since 1.0.0
 * @package BE_Sidebar_Selector
 */
final class BE_Sidebar_Selector {

	/**
	 * Plugin version
	 * 
	 * @since 1.0.0
	 * @var string
	 */
	private $version = '1.0.0';
	
	/**
	 * Option key, and option page slug
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $key = 'be_sidebar_selector_options';
	
	/**
	 * Options page metabox id
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $metabox_id = 'be_sidebar_selector_option_metabox';
	
	/**
	 * Options Page Title
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $options_page_title;
	
	/**
	 * Options Page Hook
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $options_page = '';
	
	
	/**
	 * Instance of the class
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;
	
	/**
	 * Sidebar Selector Instance
	 *
	 * @since 1.0.0
	 * @return BE_Sidebar_Selector
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof BE_Sidebar_Selector ) ) {
			
			self::$instance = new BE_Sidebar_Selector;
			self::$instance->constants();
			self::$instance->includes();
			self::$instance->hooks();

		}
		return self::$instance;
	}
	
	/**
	 * Constants
	 *
	 * @since 1.0.0
	 */
	public function constants() {

		// Version
		define( 'BE_SIDEBAR_SELECTOR_VERSION', $this->version );

		// Directory path
		define( 'BE_SIDEBAR_SELECTOR_DIR', plugin_dir_path( __FILE__ ) );

		// Directory URL
		define( 'BE_SIDEBAR_SELECTOR_URL', plugin_dir_url( __FILE__ ) );

		// Base name
		define( 'BE_SIDEBAR_SELECTOR_BASE', plugin_basename( __FILE__ ) );
	}
	
	/**
	 * Includes
	 *
	 * @since 1.0.0
	 */
	public function includes() {

		// CMB2
		require_once( BE_SIDEBAR_SELECTOR_DIR . 'cmb2/init.php' );

	}
	
	/**
	 * Initialize hooks
	 *
	 * @since 1.0.0
	 */
	public function hooks() {
		
		add_action( 'init',            array( $this, 'options_page_title' ) );
		add_action( 'admin_init',      array( $this, 'init' ) );
		add_action( 'admin_menu',      array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );

	}
	
	/**
	 * Options Page Title
	 *
	 * @since 1.0.o
	 */
	public function options_page_title() {

		$this->options_page_title = __( 'Edit Widget Areas', 'be-sidebar-selector' );

	}
	
	/**
	 * Register setting for options page
	 *
	 * @since 1.0.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}
	
	/**
	 * Add Options Page
	 *
	 * @since 1.0.0
	 */
	function add_options_page() {

		$this->options_page = add_theme_page( $this->options_page_title, $this->options_page_title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}
	
	/**
	 * Admin Page Display
	 *
	 * @since 1.0.0
	 */
	function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}
	
	/**
	 * Add Options Page Metabox
	 *
	 * @since 1.0.0
	 */
	function add_options_page_metabox() {

		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

		// Create metabox
		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		// Create repeatable field group
		$group_field_id = $cmb->add_field( array( 
			'id'      => 'widget_areas',
			'type'    => 'group',
			'options' => array(
				'group_title'   => __( 'Widget Area {#}', 'be-sidebar-selector' ),
				'add_button'    => __( 'Add Widget Area', 'be-sidebar-selector' ),
				'remove_button' => __( 'Remove Widget Area', 'be-sidebar-selector' ),
				'sortable'      => false,
			)
		) );
		
		// Add fields
		$cmb->add_group_field( $group_field_id, array( 
			'name'        => __( 'Name', 'be-sidebar-selector' ),
			'id'          => 'name',
			'type'        => 'text',
			'description' => __( 'The name is how it appears in Appearance > Widgets.', 'be-sidebar-selector' ),
		) );
		
		$cmb->add_group_field( $group_field_id, array( 
			'name'        => __( 'Description', 'be-sidebar-selector' ),
			'id'          => 'description',
			'type'        => 'textarea',
		) );

	}

	/**
	 * Register settings notices for display
	 *
	 * @since  1.0.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'be-sidebar-selector' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 *
	 * @since  1.0.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}
}

/**
 * The function which returns the BE_Sidebar_Selector instance 
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @since 1.0.0
 * @return object
 */
function be_sidebar_selector() {
	return BE_Sidebar_Selector::instance();
}
be_sidebar_selector();