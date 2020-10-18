<?php
/**
 * Plugin Name: Void Contact Form 7 Widget For Elementor Page Builder
 * Description: Adds Contact Form 7 widget element to Elementor page builder for easy drag & drop the created contact forms with CF7 (contact form 7).
 * Version:     1.1.3
 * Author:      voidCoders
 * Plugin URI:  https://demo.voidcoders.com/plugins/contact-form7-widget-for-elementor/
 * Author URI:  https://voidcoders.com
 * Text Domain: void
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('CF7_WIDGET_E_VERSION', '1.1.3');
define('CF7_WIDGET_E_PLUGIN_URL', trailingslashit(plugin_dir_url( __FILE__ )));
define('CF7_WIDGET_E_PLUGIN_DIR', trailingslashit(plugin_dir_path( __FILE__ )));

function void_cf7_widget() {
	// Load localization file
	load_plugin_textdomain( 'void' );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	// Check version required
	$elementor_version_required = '2.8.5';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		return;
	}

	// Require the main plugin file
    require( __DIR__ . '/plugin.php' );   //loading the main plugin
    include CF7_WIDGET_E_PLUGIN_DIR.'custom-editor/init.php';
    // helper file for this plugin. currently used for gettings all contact form of cf7. also used for ajax request handle
    require __DIR__ . '/helper/helper.php';

}
add_action( 'plugins_loaded', 'void_cf7_widget' ); 

// display activation notice for depended plugin
function void_cf7_widget_notice() { ?>

    <?php if ( !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) || ! did_action( 'elementor/loaded' ) ) : ?>
        <div class="notice notice-warning is-dismissible">

            <?php if ( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) && ! did_action( 'elementor/loaded' ) ) : ?>
                    <p><?php echo sprintf( __( '<a href="%s" class="button button-primary">Active</a> <b>Elementor Page Builder</b> now for working with <b>"Void Contact Form 7 Widget"</b>' ),  wp_nonce_url( 'plugins.php?action=activate&plugin=elementor/elementor.php&plugin_status=all&paged=1', 'activate-plugin_elementor/elementor.php') ); ?></p>
            <?php elseif ( !file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) : ?>
                    <p><?php echo sprintf( __( '<b>Elementor Page Builder</b> must be installed for <b>"Void Contact Form 7 Widget"</b> to work. <a href="%s" class="button button-primary">Install Now</a>' ),  wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' )); ?></p>
            <?php endif; ?>

            <?php if ( file_exists( WP_PLUGIN_DIR . '/contact-form-7/wp-contact-form-7.php' ) && !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) : ?>
                    <p><?php echo sprintf( __( '<a href="%s" class="button button-primary">Active</a> <b>Contact Form 7</b> now to start working with <b>"Void Contact Form 7 Widget"</b>' ), wp_nonce_url( 'plugins.php?action=activate&plugin=contact-form-7/wp-contact-form-7.php&plugin_status=all&paged=1', 'activate-plugin_contact-form-7/wp-contact-form-7.php' )); ?></p>
            <?php elseif ( !file_exists( WP_PLUGIN_DIR . '/contact-form-7/wp-contact-form-7.php' ) ) : ?>
                    <p><?php echo sprintf( __( '<b>Contact Form 7</b>  must be installed and activated for <b>"Void Contact Form 7 Widget"</b> to work. <a href="%s" class="button button-primary">Install Now</a>' ), wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=contact-form-7' ), 'install-plugin_contact-form-7' )); ?></p>
            <?php endif; ?>

        </div>
    <?php endif; ?>

<?php }
add_action('admin_notices', 'void_cf7_widget_notice');

function void_cf7_widget_promotional_notice(){
    // notice dismiss date form database
    $db_dismiss_date = get_option('dismissed-void-cf7-promotion-notice-ele-query-at');
    // create a date object from database date
    $dismiss_date = date_create($db_dismiss_date);
    // create a current date object
    $current_date = date_create(date('Y-m-d'));
    // get difference of both date
    $diff = date_diff($dismiss_date, $current_date);
    // make conditional days. if date found in database, it will be 30.
    // otherwise it will be 0. Becase difference return 0 if there was no data on database
    $conditional_days = ($db_dismiss_date) ? 15 : 0;
    // elementor pro install check
    if ( file_exists( WP_PLUGIN_DIR . '/elementor-pro/elementor-pro.php' ) || did_action( 'elementor_pro/init' ) ) : 
        $url = 'https://elequerybuilder.com?click=cf7-promo';
            // different day condition. notice will again show if dismiss interval is more than equal 30 days
            if( !get_option('dismissed-void-cf7-promotion-notice-ele-query-never', FALSE )):

                // different day condition. notice will again show if dismiss interval is more than equal 30 days
                if($diff->days >= $conditional_days ):
                    $url .= (($conditional_days == 15) ? '&discount=INSIDE10E' : '');
        ?>
                <div class="cf7-widget-promotion-notice notice is-dismissible" data-notice="void-cf7-promotion-notice-ele-query" data-nonce="<?php echo wp_create_nonce('wp_rest'); ?>">
                    <div class="cf7-widget-message-inner">
                        <div class="cf7-widget-message-icon">
                            <img class="cf7-widget-notice-icon" src="https://elequerybuilder.com/wp-content/uploads/2020/05/EQ-Banner.png" alt="voidCoders promotional banner">
                        </div>
                        <div class="cf7-widget-message-content">
                            <?php if ($conditional_days == 15) :?>
                                <p>Here is a Little gift for you!</p>
                                <p>Get <strong>Ele Query Builder</strong> to build custom query without code with <strong>10% Discount</strong>. <strong>Use Coupon - INSIDE10E</strong></p>
                            <?php else: ?>
                                <p>We noticed you have <strong>Elementor Pro</strong> on your site.</p>
                                <p>Get our <strong>Ele Query Builder</strong> to use custom query by using postmeta, ACF/PODS</p>
                                <p>Woocommerce meta and events calendar with no CODE</p>
                            <?php endif; ?>
                        </div>
                        <div class="cf7-widget-message-action">
                            <a class="cf7-widget-button" target="__blank" href="<?php echo esc_url($url); ?>">Check Now</a>
                            <!-- <a class="cf7-widget-remind-later" href="#">Remind me later -> </a> -->
                            <a class="cf7-widget-never-show" href="#">Never show again -> </a>
                        </div>
                    </div>
                </div>
            <?php endif;    
        endif;
    endif;
}
add_action('admin_notices', 'void_cf7_widget_promotional_notice');


// add plugin activation time

function void_cf7_activation_time(){
    $get_installation_time = strtotime("now");
    add_option('void_cf7_elementor_activation_time', $get_installation_time ); 
}
register_activation_hook( __FILE__, 'void_cf7_activation_time' );

//check if review notice should be shown or not

function void_cf7_check_installation_time() {

    $spare_me = get_option('void_cf7_spare_me');
    if( !$spare_me ){
        $install_date = get_option( 'void_cf7_elementor_activation_time' );
        $past_date = strtotime( '-7 days' );
     
        if ( $past_date >= $install_date ) {
     
            add_action( 'admin_notices', 'void_cf7_display_admin_notice' );
     
        }
    }
}
add_action( 'admin_init', 'void_cf7_check_installation_time' );
 
/**
* Display Admin Notice, asking for a review
**/
function void_cf7_display_admin_notice() {
    // wordpress global variable 
    global $pagenow;
    if( $pagenow == 'index.php' ){
 
        $dont_disturb = esc_url( get_admin_url() . '?spare_me2=1' );
        $plugin_info = get_plugin_data( __FILE__ , true, true );       
        $reviewurl = esc_url( 'https://wordpress.org/support/plugin/cf7-widget-elementor/reviews/#new-post' );
        $void_url = esc_url( 'https://voidcoders.com/shop/' );
     
        printf(__('<div class="void-cf7-review wrap">You have been using <b> %s </b> for a while. We hope you liked it ! Please give us a quick rating, it works as a boost for us to keep working on the plugin ! Also you can visit our <a href="%s" target="_blank">site</a> to get more themes & Plugins<div class="void-cf7-review-btn"><a href="%s" class="button button-primary" target=
            "_blank">Rate Now!</a><a href="%s" class="void-cf7-review-done"> Already Done !</a></div></div>', $plugin_info['TextDomain']), $plugin_info['Name'], $void_url, $reviewurl, $dont_disturb );
    }
}
// remove the notice for the user if review already done or if the user does not want to
function void_cf7_spare_me(){    
    if( isset( $_GET['spare_me2'] ) && !empty( $_GET['spare_me2'] ) ){
        $spare_me = $_GET['spare_me2'];
        if( $spare_me == 1 ){
            add_option( 'void_cf7_spare_me' , TRUE );
        }
    }
}
add_action( 'admin_init', 'void_cf7_spare_me', 5 );

//add admin css
function void_cf7_admin_css(){

    global $pagenow;
    if( $pagenow == 'index.php' || file_exists( WP_PLUGIN_DIR . '/elementor-pro/elementor-pro.php' ) || did_action( 'elementor_pro/init' ) || !get_option('dismissed-void-cf7-promotion-notice-ele-query-never', FALSE ) ){
        wp_enqueue_style( 'void-cf7-admin', CF7_WIDGET_E_PLUGIN_URL . 'assets/css/void-cf7-admin.css', [], CF7_WIDGET_E_VERSION, 'all' );
        wp_enqueue_script('void-cf7-admin', CF7_WIDGET_E_PLUGIN_URL . 'assets/js/void-cf7-admin.js', ['jquery'], CF7_WIDGET_E_VERSION, true);
    }
}
add_action( 'admin_enqueue_scripts', 'void_cf7_admin_css' );
