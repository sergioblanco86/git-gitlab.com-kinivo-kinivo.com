<?php
/**
 * Woocommerce Multiple Addresses plugin.
 *
 * @package   WC_Multiple_addresses
 * @author    Alexander Tinyaev <alexander.tinyaev@n3wnormal.com>
 * @license   GPL-2.0+
 * @link      http://n3wnormal.com
 * @copyright 2014 N3wNormal
 */

/**
 * Plugin class.
 *
 * @package WC_Multiple_addresses
 * @author  Alexander Tinyaev <alexander.tinyaev@n3wnormal.com>
 */
class WC_Multiple_addresses {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.5
	 *
	 * @var     string
	 */
	const VERSION = '1.0.5';

	/**
	 * Unique identifier for the plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'woocommerce-multiple-addresses';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting filters and administration functions.
	 *
	 * @since     1.0.4
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin for newly added blog on multisite
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Change 'edit' link on My Account page to lead on our 'edit address' page
		add_action( 'woocommerce_before_my_account', array( $this, 'rewrite_edit_url_on_my_account' ), 25 );

		// Create a shortcode to show content on 'Manage addresses' page
		add_shortcode( 'woocommerce_multiple_shipping_addresses', array( $this, 'multiple_shipping_addresses' ) );

		// Process saving on 'Manage addresses' page
		add_action( 'template_redirect', array( $this, 'save_multiple_shipping_addresses' ) );

		// Show a 'configure addresses' button on checkout
		add_action( 'woocommerce_before_checkout_form', array( $this, 'before_checkout_form' ) );

		// Save shipping address as default when creating a new customer
		add_action( 'woocommerce_created_customer', array( $this, 'created_customer_save_shipping_as_default' ) );

		// Add a dropdown to choose an address
		add_filter( 'woocommerce_checkout_fields', array( $this, 'add_dd_to_checkout_fields' ) );

		// Add ajax handler for choosing shipping address on checkout
		add_action( 'wp_ajax_alt_change', array( $this, 'ajax_checkout_change_shipping_address' ) );
		add_action( 'wp_ajax_nopriv_alt_change', array( $this, 'ajax_checkout_change_shipping_address' ) );

	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.4
	 *
	 * @param    boolean $network_wide       True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.4
	 *
	 * @param    boolean $network_wide       True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.4
	 *
	 * @param    int $blog_id ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.4
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.4
	 */
	private static function single_activate() {
		global $woocommerce;

		$page_id = woocommerce_get_page_id( 'multiple_shipping_addresses' );

		if ( $page_id == - 1 ) {
			// get the checkout page
			$account_id = woocommerce_get_page_id( 'myaccount' );

			// add page and assign
			$page = array(
				'menu_order'     => 0,
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_author'    => 1,
				'post_content'   => '[woocommerce_multiple_shipping_addresses]',
				'post_name'      => 'multiple-shipping-addresses',
				'post_parent'    => $account_id,
				// TODO: add textdomain as plugin slug
				'post_title'     => __( 'Manage Your Addresses', 'woocommerce-multiple-addresses' ),
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_category'  => array( 1 )
			);

			$page_id = wp_insert_post( $page );

			update_option( 'woocommerce_multiple_shipping_addresses_page_id', $page_id );
		}
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.4
	 */
	private static function single_deactivate() {
		// Nothing here for now...
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.4
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, basename( plugin_dir_path( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.4
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.4
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		wp_localize_script( $this->plugin_slug . '-plugin-script', 'WCMA_Ajax', array(
				'ajaxurl'               => admin_url( 'admin-ajax.php' ),
				'id'                    => 0,
				'wc_multiple_addresses' => wp_create_nonce( 'wc-multiple-addresses-ajax-nonce' )
			)
		);
	}


	/**
	 * Point edit address button on my account to edit multiple shipping addresses
	 *
	 * @since    1.0.2
	 */
	public function rewrite_edit_url_on_my_account() {
		$page_id  = woocommerce_get_page_id( 'multiple_shipping_addresses' );
		$site_url = home_url( '/' );
		?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery('.woocommerce .col2-set.addresses .col-2 .title a').attr('href', '<?php echo $site_url . "?page_id=" . $page_id; ?>');
			});
		</script>
	<?php
	}

	/**
	 * Multiple shipping addresses page
	 *
	 * @since    1.0.4
	 */
	public function multiple_shipping_addresses() {
		global $woocommerce;

		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
			require_once $woocommerce->plugin_path() .'/classes/class-wc-checkout.php';
		} else {
			require_once $woocommerce->plugin_path() . '/includes/class-wc-checkout.php';
		}

		$checkout = new WC_Order('');
		$user     = wp_get_current_user();

		$shipFields                           = $woocommerce->countries->get_address_fields( $woocommerce->countries->get_base_country(), 'shipping_' );
		$shipFields['shipping_city']['label'] = $shipFields['shipping_city']['placeholder'] = "City";

		if ( $user->ID == 0 ) {
			return;
		}

		$otherAddr = get_user_meta( $user->ID, 'wc_multiple_shipping_addresses', true );
		echo '<div class="woocommerce">';
		echo '<form action="" method="post" id="address_form">';
		if ( ! empty( $otherAddr ) ) {
			echo '<div id="addresses">';

			foreach ( $otherAddr as $idx => $address ) {
				echo '<div class="shipping_address address_block" id="shipping_address_' . $idx . '">';
				echo '<p align="right" style="margin:0;"><a href="#" class="delete">' . __( '', $this->plugin_slug ) . '</a></p>';
				do_action( 'woocommerce_before_checkout_shipping_form', $checkout );

				foreach ( $shipFields as $key => $field ) {
					$val = '';

					if ( isset( $address[ $key ] ) ) {
						$val = $address[ $key ];
					}
					$key .= '[]';
					woocommerce_form_field( $key, $field, $val );
				}

				$is_checked = $address['shipping_address_is_default'] == 'true' ? "checked" : "";
				echo '<input type="checkbox" class="default_shipping_address" ' . $is_checked . ' value="' . $address['shipping_address_is_default'] . '"> <span>' . __( 'Mark this shipping address as default', $this->plugin_slug ).'</span>';
				echo '<input type="hidden" class="hidden_default_shipping_address" name="shipping_address_is_default[]" value="' . $address['shipping_address_is_default'] . '" />';

				do_action( 'woocommerce_after_checkout_shipping_form', $checkout );
				echo '</div>';
			}
			echo '</div>';
		} else {

			$shipping_address = array(
				'shipping_first_name' => get_user_meta( $user->ID, 'shipping_first_name', true ),
				'shipping_last_name'  => get_user_meta( $user->ID, 'shipping_last_name', true ),
				'shipping_company'    => get_user_meta( $user->ID, 'shipping_company', true ),
				'shipping_address_1'  => get_user_meta( $user->ID, 'shipping_address_1', true ),
				'shipping_address_2'  => get_user_meta( $user->ID, 'shipping_address_2', true ),
				'shipping_city'       => get_user_meta( $user->ID, 'shipping_city', true ),
				'shipping_state'      => get_user_meta( $user->ID, 'shipping_state', true ),
				'shipping_postcode'   => get_user_meta( $user->ID, 'shipping_postcode', true ),
				'shipping_country'    => get_user_meta( $user->ID, 'shipping_country', true )
			);

			echo '<div id="addresses">';
			foreach ( $shipFields as $key => $field ) :
				$val = $shipping_address[ $key ];
				$key .= '[]';

				woocommerce_form_field( $key, $field, $val );
			endforeach;

			echo '<input type="checkbox" class="default_shipping_address" checked value="true"> <span>' . __( 'Mark this shipping address as default', $this->plugin_slug ).'</span>';
			echo '<input type="hidden" class="hidden_default_shipping_address" name="shipping_address_is_default[]" value="true" />';

			echo '</div>';
		}
		echo '<div class="form-row">
                <input type="hidden" name="shipping_account_address_action" value="save" />
                <input type="submit" name="set_addresses" value="' . __( 'Save Addresses', $this->plugin_slug ) . '" class="button alt submit-button green standar-nowidth" />
                <a class="add_address" href="#">' . __( 'Add another', $this->plugin_slug ) . '</a>
            </div>';
		echo '</form>';
		echo '</div>';
		?>
		<link rel="stylesheet" id="woocommerce_chosen_styles-css" href="<?php bloginfo('url'); ?>/wp-content/plugins/woocommerce/assets/css/chosen.css?ver=4.0" type="text/css" media="all">
		<script type="text/javascript" src="<?php bloginfo('url'); ?>/wp-content/plugins/woocommerce/assets/js/chosen/chosen.jquery.min.js?ver=1.0.0"></script>
		<script type="text/javascript" src="<?php bloginfo('url'); ?>/wp-content/plugins/woocommerce/assets/js/frontend/chosen-frontend.min.js?ver=2.2.4"></script>
		<script type="text/javascript">
			var tmpl = '<div class="shipping_address address_block"><p align="right"><a href="#" class="delete"></a></p>';

			tmpl += '<?php foreach ($shipFields as $key => $field) :
				$key .= '[]';
				$val = '';
				$field['return'] = true;
				$row = woocommerce_form_field( $key, $field, $val );
				echo str_replace("\n", "\\\n", str_replace("'", "\'", $row));
			endforeach; ?>';

			tmpl += '<input type="checkbox" class="default_shipping_address" value="false"> <span><?php _e( "Mark this shipping address as default", $this->plugin_slug ); ?></span>';
			tmpl += '<input type="hidden" class="hidden_default_shipping_address" name="shipping_address_is_default[]" value="false" />';
			tmpl += '</div>';
			jQuery(".add_address").click(function (e) {
				e.preventDefault();

				jQuery("#addresses").append(tmpl);

				jQuery('html,body').animate({
						scrollTop: jQuery('#addresses .shipping_address:last').offset().top},
					'slow');
				jQuery( 'select.country_select' ).chosen().trigger( 'chosen:updated' );
			});

			jQuery(".delete").live("click", function (e) {
				e.preventDefault();
				jQuery(this).parents("div.address_block").remove();
			});

			jQuery(document).ready(function () {

				jQuery(document).on("click", ".default_shipping_address", function () {
					if (this.checked) {
						jQuery("input.default_shipping_address").not(this).removeAttr("checked");
						jQuery("input.default_shipping_address").not(this).val("false");
						jQuery("input.hidden_default_shipping_address").val("false");
						jQuery(this).next().val('true');
						jQuery(this).val('true');
					}
					else {
						jQuery("input.default_shipping_address").val("false");
						jQuery("input.hidden_default_shipping_address").val("false");
					}
				});

				jQuery("#address_form").submit(function () {
					var valid = true;
					jQuery("input[type=text],select").each(function () {
						if (jQuery(this).prev("label").children("abbr").length == 1 && jQuery(this).val() == "") {
							jQuery(this).focus();
							valid = false;
							return false;
						}
					});
					return valid;
				});

			});
		</script>
	<?php
	}

	/**
	 * Save multiple shipping addresses
	 *
	 * @since    1.0.3
	 */
	public function save_multiple_shipping_addresses() {

		if ( isset( $_POST['shipping_account_address_action'] ) && $_POST['shipping_account_address_action'] == 'save' ) {
			unset( $_POST['shipping_account_address_action'] );

			$addresses  = array();
			$is_default = false;
			foreach ( $_POST as $key => $values ) {
				if ( $key == 'shipping_address_is_default' ) {
					foreach ( $values as $idx => $val ) {
						if ( $val == 'true' ) {
							$is_default = $idx;
						}
					}
				}
				if ( ! is_array( $values ) ) {
					continue;
				}

				foreach ( $values as $idx => $val ) {
					$addresses[ $idx ][ $key ] = $val;
				}
			}

			$user = wp_get_current_user();

			if ( $is_default !== false ) {
				$default_address = $addresses[ $is_default ];
				foreach ( $default_address as $key => $field ) :
					if ( $key == 'shipping_address_is_default' ) {
						continue;
					}
					update_user_meta( $user->ID, $key, $field );
				endforeach;
			}

			update_user_meta( $user->ID, 'wc_multiple_shipping_addresses', $addresses );

			if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
				global $woocommerce;
				$woocommerce->add_message( __( 'Addresses have been saved', $this->plugin_slug ) );
			} else {
				wc_add_notice( __( 'Addresses have been saved', $this->plugin_slug ), $notice_type = 'success' );
			}

			$page_id = woocommerce_get_page_id( 'myaccount' );
			wp_redirect( get_permalink( $page_id ).'?address_saved=true' );
			exit;
		}
	}

	/**
	 * Add possibility to configure addresses on checkout page
	 *
	 * @since    1.0.4
	 */
	public function before_checkout_form() {
		global $woocommerce;

		$page_id = woocommerce_get_page_id( 'multiple_shipping_addresses' );
		if ( is_user_logged_in() ) {
			echo '<div class="woocommerce-info woocommerce_message">
	                ' . __( 'If you have more than one shipping address, then you may choose a default one here.', $this->plugin_slug ) . '
	                <a href="' . get_permalink( $page_id ) . '">' . __( 'Click here to configure your Address', $this->plugin_slug ) . '</a>
	              </div>';
		}
	}

	/**
	 * Helper function to prepend value to an array with custom key
	 *
	 * @param $arr
	 * @param $key
	 * @param $val
	 *
	 * @since    1.0.4
	 *
	 * @return array
	 */
	public function array_unshift_assoc( &$arr, $key, $val ) {
		$arr         = array_reverse( $arr, true );
		$arr[ $key ] = $val;

		return array_reverse( $arr, true );
	}

	/**
	 * Creating the same default shipping for newly created customer
	 *
	 * @since    1.0.0
	 *
	 * @param    integer $current_user_id
	 */
	public function created_customer_save_shipping_as_default( $current_user_id ) {
		global $woocommerce;
		if ( $current_user_id == 0 ) {
			return;
		}

		$checkout        = $woocommerce->checkout->posted;
		$default_address = array();
		if ( $checkout['shiptobilling'] == 0 ) {
			$default_address[0]['shipping_country']    = $checkout['shipping_country'];
			$default_address[0]['shipping_first_name'] = $checkout['shipping_first_name'];
			$default_address[0]['shipping_last_name']  = $checkout['shipping_last_name'];
			$default_address[0]['shipping_company']    = $checkout['shipping_company'];
			$default_address[0]['shipping_address_1']  = $checkout['shipping_address_1'];
			$default_address[0]['shipping_address_2']  = $checkout['shipping_address_2'];
			$default_address[0]['shipping_city']       = $checkout['shipping_city'];
			$default_address[0]['shipping_state']      = $checkout['shipping_state'];
			$default_address[0]['shipping_postcode']   = $checkout['shipping_postcode'];
		} elseif ( $checkout['shiptobilling'] == 1 ) {
			$default_address[0]['shipping_country']    = $checkout['billing_country'];
			$default_address[0]['shipping_first_name'] = $checkout['billing_first_name'];
			$default_address[0]['shipping_last_name']  = $checkout['billing_last_name'];
			$default_address[0]['shipping_company']    = $checkout['billing_company'];
			$default_address[0]['shipping_address_1']  = $checkout['billing_address_1'];
			$default_address[0]['shipping_address_2']  = $checkout['billing_address_2'];
			$default_address[0]['shipping_city']       = $checkout['billing_city'];
			$default_address[0]['shipping_state']      = $checkout['billing_state'];
			$default_address[0]['shipping_postcode']   = $checkout['billing_postcode'];
		}
		$default_address[0]['shipping_address_is_default'] = 'true';
		update_user_meta( $current_user_id, 'wc_multiple_shipping_addresses', $default_address );
	}

	/**
	 * Add dropdown above shipping address at checkout
	 *
	 * @param    $fields
	 *
	 * @since    1.0.4
	 *
	 * @return   mixed
	 */
	public function add_dd_to_checkout_fields( $fields ) {
		global $current_user;

		$otherAddrs = get_user_meta( $current_user->ID, 'wc_multiple_shipping_addresses', true );
		if ( ! $otherAddrs ) {
			return $fields;
		}

		$addresses    = array();
		$addresses[0] = __( 'Choose an address...', $this->plugin_slug );
		for ( $i = 1; $i <= count( $otherAddrs ); ++$i ) {
			$addresses[ $i ] = $otherAddrs[ $i - 1 ]['shipping_first_name'] . ' ' . $otherAddrs[ $i - 1 ]['shipping_last_name'] . ', ' . $otherAddrs[ $i - 1 ]['shipping_postcode'] . ' ' . $otherAddrs[ $i - 1 ]['shipping_city'];
		}

		$alt_field = array(
			'label'    => __( 'Predefined addresses', $this->plugin_slug ),
			'required' => false,
			'class'    => array( 'form-row' ),
			'clear'    => true,
			'type'     => 'select',
			'options'  => $addresses
		);

		$fields['shipping'] = $this->array_unshift_assoc( $fields['shipping'], 'shipping_alt', $alt_field );
		$fields['billing'] = $this->array_unshift_assoc( $fields['billing'], 'billing_alt', $alt_field );

		return $fields;
	}

	/**
	 * Handles ajax action call on choosing shipping address on checkout
	 *
	 * @since    1.0.4
	 */
	public function ajax_checkout_change_shipping_address() {

		// check nonce
		$nonce = $_POST['wc_multiple_addresses'];
		if ( ! wp_verify_nonce( $nonce, 'wc-multiple-addresses-ajax-nonce' ) ) {
			die ( 'Busted!' );
		}

		$address_id = $_POST['id'] - 1;
		if ( $address_id < 0 ) {
			return;
		}

		// get address
		global $current_user;
		$otherAddr = get_user_meta( $current_user->ID, 'wc_multiple_shipping_addresses', true );

		global $woocommerce;
		$addr                          = $otherAddr[ $address_id ];
		$addr['shipping_country_text'] = $woocommerce->countries->countries[ $addr['shipping_country'] ];
		$response                      = json_encode( $addr );

		// response output
		header( "Content-Type: application/json" );
		echo $response;

		exit;
	}
}