<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AmazonFBA_WC_Integration_Settings extends WC_Integration {

	/**
	 * Init and hook in the integration.
	 */
	public function __construct() {
		global $woocommerce;

		$this->id                 = 'amazonfba';
		$this->method_title       = __( 'Amazon FBA Integration', 'amazonfba2woo' );
		$this->method_description = __( 'The following credentials are required to integrate with Amazon MWS. You can obtain MWS credentials <a href="https://sellercentral.amazon.com/gp/mws/registration/register.html" target="_blank">here</a>.
     To receive MWS credentials you first need an Amazon <i>Professional</i> merchant account. You can sign up for an account <a href="https://www.amazon.com/sell" target="_blank">here</a>.', 'amazonfba2woo' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->AmzFBA_Marketplace        = $this->get_option( 'AmzFBA_Marketplace' );
    $this->AmzFBA_MerchantID         = $this->get_option( 'AmzFBA_MerchantID' );
    $this->AmzFBA_AWSAccessKeyID     = $this->get_option( 'AmzFBA_AWSAccessKeyID' );
    $this->AmzFBA_SecretKey          = $this->get_option( 'AmzFBA_SecretKey' );
    $this->AmzFBA_FulfillmentPolicy  = $this->get_option( 'AmzFBA_FulfillmentPolicy' );
    $this->AmzFBA_OrderComment       = $this->get_option( 'AmzFBA_OrderComment' );
		$this->AmzFBA_Schedule_Inv       = $this->get_option( 'AmzFBA_Schedule_Inv' );

		$this->Amz_Debug                 = $this->get_option( 'Amz_Debug' );

		// Actions.
		add_action( 'woocommerce_update_options_integration_' .  $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_integration_' .  $this->id, 'Amz_cron_jobs_clear' );
		add_action( 'woocommerce_update_options_integration_' .  $this->id, 'Amz_cron_jobs' );
		// Filters.
		add_filter( 'woocommerce_settings_api_sanitized_fields_' . $this->id, array( $this, 'sanitize_settings' ) );
		// Add new cron schedules
		add_filter('cron_schedules', 'AmzFBA_cron_tasks');
	}

	/**
	 * Initialize integration settings form fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {
		$this->form_fields = array(
      'AmzFBA_Marketplace'  => array(
        'title'             => __( 'Marketplace', 'amazonfba2woo' ),
        'type'              => 'select',
        'options'           =>  array('US'      => 'US - amazon.com',
                                      'UK'      => 'UK - amazon.co.uk',
                                      'Germany' => 'Germany - amazon.de',
                                      'France'  => 'France - amazon.fr',
                                      'Italy'   => 'Italy - amazon.it',
                                      'Japan'   => 'Japan - amazon.jp',
                                      'China'   => 'China - amazon.com.cn' ),
        'description'       => __( 'Please select to integrate with your marketplace.', 'amazonfba2woo' ),
        'desc_tip'          => false,
        'default'           => 'US'
      ),
      'AmzFBA_MerchantID' => array(
        'title'             => __( 'Merchant ID', 'amazonfba2woo' ),
        'type'              => 'text',
        'description'       => __( 'Merchant ID may be referred to as Seller ID.', 'amazonfba2woo' ),
        'desc_tip'          => false,
        'default'           => ''
      ),
			'AmzFBA_AWSAccessKeyID' => array(
				'title'               => __( 'AWS Access Key ID', 'amazonfba2woo' ),
				'type'                => 'text',
				'default'             => ''
			),
      'AmzFBA_SecretKey' => array(
        'title'          => __( 'Secret Key', 'amazonfba2woo' ),
        'type'           => 'password',
        'default'        => ''
      ),
      'AmzFBA_FulfillmentPolicy' => array(
        'title'                  => __( 'Marketplace', 'amazonfba2woo' ),
        'type'                   => 'select',
        'options'                =>  array('FillOrKill' => 'FillOrKill',
                                      'FillAll'      => 'FillAll',
                                      'FillAllAvailable' => 'FillAllAvailable'),
        'description'       => __( 'We recommend leaving this setting as FillAllAvailable.<br/>
         <b>FillAllAvailable</b> means Amazon ships all fulfillable products and will cancel all unfulfillable products.<br/>
         <b>FillOrKill</b> means if a product in the order is found to be unfulfillable before Amazon starts processing any other products that are fulfillable, the entire order will be cancelled. If Amazon moves any fulfillable products to Pending status before finding unfulfillable products, Amazon will cancel as many products from the order as possible and will still ship all products that were already moved to Pending status.<br/>
         <b>FillAll</b> means Amazon will ship all products that are immediately fulfillable and will keep all the unfulfillable products as Pending until more stock arrives or until you manually cancel the rest of the order.', 'amazonfba2woo' ),
        'desc_tip'          => false,
        'default'           => 'FillAllAvailable'
      ),
      'AmzFBA_OrderComment' => array(
        'title'               => __( 'Order Comment', 'amazonfba2woo' ),
        'type'                => 'text',
        'default'             => '',
        'description'         => __( 'This comment will be added to all orders fulfilled by Amazon.' , 'amazonfba2woo' ),
        'desc_tip'            => false,
      ),
			'AmzFBA_Schedule_Inv'	 	=> array(
				'title'               => __( 'Inventory Sync', 'amazonfba2woo' ),
				'type'                => 'text',
				'default'             => '30',
				'description'         => __( 'How often should the stock levels be synchronised. (Minutes)' , 'amazonfba2woo' ),
				'desc_tip'            => false,
			),
			'AmzFBA_Schedule_Ord'	 	=> array(
				'title'               => __( 'Order Status Sync', 'amazonfba2woo' ),
				'type'                => 'text',
				'default'             => '30',
				'description'         => __( 'How often should the order status be synchronised. (Minutes)' , 'amazonfba2woo' ),
				'desc_tip'            => false,
			),
			'AmzFBA_Schedule_Tra'	 	=> array(
				'title'               => __( 'Tracking Info Sync', 'amazonfba2woo' ),
				'type'                => 'text',
				'default'             => '30',
				'description'         => __( 'How often should the tracking information be synchronised. (Minutes)' , 'amazonfba2woo' ),
				'desc_tip'            => false,
			),
      'AmzFBA_Debug' => array(
				'title'             => __( 'Debug Log', 'amazonfba2woo' ),
				'type'              => 'checkbox',
				'label'             => __( 'Enable logging', 'amazonfba2woo' ),
				'default'           => 'no',
				'description'       => __( 'Log events such as API requests', 'amazonfba2woo' ),
			)
		);
	}


	/**
	 * Generate Button HTML.
	 */
	public function generate_button_html( $key, $data ) {
		$field    = $this->plugin_id . $this->id . '_' . $key;
		$defaults = array(
			'class'             => 'button-secondary',
			'css'               => '',
			'custom_attributes' => array(),
			'desc_tip'          => false,
			'description'       => '',
			'title'             => '',
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<button class="<?php echo esc_attr( $data['class'] ); ?>" type="button" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php echo $this->get_custom_attribute_html( $data ); ?>><?php echo wp_kses_post( $data['title'] ); ?></button>
					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}


	/**
	 * Santize our settings
	 * @see process_admin_options()
	 */
	public function sanitize_settings( $settings ) {
		// We're just going to make the api key all upper case characters since that's how our imaginary API works
		if ( isset( $settings ) &&
		     isset( $settings['api_key'] ) ) {
			$settings['api_key'] = strtoupper( $settings['api_key'] );
		}
		return $settings;
	}


	/**
	 * Validate the API key
	 * @see validate_settings_fields()
	 */
	public function validate_api_key_field( $key ) {
		// get the posted value
		$value = $_POST[ $this->plugin_id . $this->id . '_' . $key ];

		// check if the API key is longer than 20 characters. Our imaginary API doesn't create keys that large so something must be wrong. Throw an error which will prevent the user from saving.
		if ( isset( $value ) &&
			 20 < strlen( $value ) ) {
			$this->errors[] = $key;
		}
		return $value;
	}


	/**
	 * Display errors by overriding the display_errors() method
	 * @see display_errors()
	 */
	public function display_errors( ) {

		// loop through each error and display it
		foreach ( $this->errors as $key => $value ) {
			?>
			<div class="error">
				<p><?php _e( 'Looks like you made a mistake with the ' . $value . ' field. Make sure it isn&apos;t longer than 20 characters', 'amazonfba2woo' ); ?></p>
			</div>
			<?php
		}
	}


}
