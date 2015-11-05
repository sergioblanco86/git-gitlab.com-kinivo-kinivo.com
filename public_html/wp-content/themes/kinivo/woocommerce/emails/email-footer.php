<?php
/**
 * Email Footer
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$base = get_option( 'woocommerce_email_base_color' );

$base_lighter_40 = wc_hex_lighter( $base, 40 );

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline.
$template_footer = "
	border-top:0;
	-webkit-border-radius:6px;
";

$credit = "
	border:0;
	color: $base_lighter_40;
	font-family: Arial;
	font-size:12px;
	line-height:125%;
	text-align:center;
";
?>
															</div>
														</td>
                                                    </tr>
                                                </table>
                                                <!-- End Content -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Body -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Footer -->
                                	<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer" style="<?php echo $template_footer; ?>">
                                    	<tr>
                                        	<td valign="top">
                                                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td style="width:70%;border-right:1px solid #EEEEEE;padding-left:15px;line-height:25px;font-size:12px; color:#939393;" valign="top">
                                                            If you have questions, please call 855 454 6486 (Toll Free) or email <a href="mailto:support@kinivo.com">support@kinivo.com</a>
                                                            <br />
                                                            <a target="blank" href="https://www.facebook.com/Kinivo"><img src="<?php echo get_template_directory_uri(); ?>/img/icn/fb-icon.png"></a>&nbsp;
                                                            &nbsp;<a target="blank" href="https://twitter.com/kinivo"><img src="<?php echo get_template_directory_uri(); ?>/img/icn/tw-icon.png"></a>&nbsp;
                                                            &nbsp;<a target="blank" href="https://instagram.com/kinivoelectronics/"><img src="<?php echo get_template_directory_uri(); ?>/img/icn/ig-icon.png"></a>&nbsp;
                                                            &nbsp;<a target="blank" href="https://www.youtube.com/user/myKinivo"><img src="<?php echo get_template_directory_uri(); ?>/img/icn/yt-icon.png"></a>
                                                            
                                                        </td>
                                                        <td style="line-height:25px; font-size:12px; color:#939393;" valign="top">
                                                            <a href="https://support.kinivo.com" style="text-decoration:underline;color:#939393;">Support</a><br />
                                                            <a href="<?php bloginfo('url'); ?>/warranty" style="text-decoration:underline;color:#939393;">Warranty</a><br />
                                                            <a href="<?php bloginfo('url'); ?>/privacy-policy" style="text-decoration:underline;color:#939393;">Privacy Policy</a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" valign="middle" id="credit" style="<?php echo $credit; ?>">
                                                            <?php echo wpautop( wp_kses_post( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Footer -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>