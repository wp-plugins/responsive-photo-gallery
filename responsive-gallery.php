<?php
/**
 * Plugin Name: Responsive Photo Gallery
 * Version: 0.3
 * Description: Create and display various animated image gallery on WordPress blog.
 * Author: Weblizar
 * Author URI: http://www.weblizar.com
 * Plugin URI: http://weblizar.com/plugins/responsive-photo-gallery-pro/
 */

/**
 * Constant Variable
 */
define("WEBLIZAR_RPG_TEXT_DOMAIN","weblizar_rpg" );
define("WEBLIZAR_RG_PLUGIN_URL", plugin_dir_url(__FILE__));

// Run 'Install' script on plugin activation
register_activation_hook( __FILE__, 'DefaultSettings' );
function DefaultSettings(){
    $DefaultSettingsArray = serialize( array(
        'WL_Hover_Animation' => "fade",
        'WL_Gallery_Layout' => "col-md-6",
        'WL_Hover_Color' => "#74C9BE",
        'WL_Hover_Color_Opacity' => 1,
        'WL_Font_Style' => "Arial",
        'WL_Image_View_Icon' => "fa-picture-o"
    ) );
    add_option("WL_IGP_Settings", $DefaultSettingsArray);
}

//Get Ready Plugin Translation
add_action('plugins_loaded', 'GetReadyTranslation');
function GetReadyTranslation() {
	load_plugin_textdomain(WEBLIZAR_RPG_TEXT_DOMAIN, FALSE, dirname( plugin_basename(__FILE__)).'/languages/' );
}

// Register Custom Post Type
function ResponsiveGallery() {

    $labels = array(
        'name'                => _x( 'Responsive Photo Gallery', 'Responsive Photo Gallery', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'singular_name'       => _x( 'Responsive Photo Gallery', 'Responsive Photo Gallery', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'menu_name'           => __( 'Responsive Photo Gallery', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'parent_item_colon'   => __( 'Parent Item:', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'all_items'           => __( 'All Gallery', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'view_item'           => __( 'View Gallery', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'add_new_item'        => __( 'Add New Gallery', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'add_new'             => __( 'Add New Gallery', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'edit_item'           => __( 'Edit Gallery', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'update_item'         => __( 'Update Gallery', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'search_items'        => __( 'Search Gallery', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'not_found'           => __( 'No Gallery Found', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'not_found_in_trash'  => __( 'No Gallery found in Trash', WEBLIZAR_RPG_TEXT_DOMAIN ),
    );
    $args = array(
        'label'               => __( 'Responsive Photo Gallery', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'description'         => __( 'Responsive Photo Gallery', WEBLIZAR_RPG_TEXT_DOMAIN ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', '', '', '', '', '', 'revisions', '', '', '', ),
        //'taxonomies'          => array( 'category', 'post_tag' ),
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-format-gallery',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    );
    register_post_type( 'responsive-gallery', $args );

}

// Hook into the 'init' action
add_action( 'init', 'ResponsiveGallery', 0 );

/**
 * Weblizar Responsive Gallery Short Code Detect Function
 */
function WeblizarResponsiveGalleryShortCodeDetect() {
    global $wp_query;
    $Posts = $wp_query->posts;
    $Pattern = get_shortcode_regex();

    foreach ($Posts as $Post) {
        if (   preg_match_all( '/'. $Pattern .'/s', $Post->post_content, $Matches ) && array_key_exists( 2, $Matches ) && in_array( 'WRG', $Matches[2] ) ) {
            /**
             * js scripts
             */
            wp_enqueue_script('jquery');
            wp_enqueue_script('wl-hover-pack-js',WEBLIZAR_RG_PLUGIN_URL.'js/hover-pack.js', array('jquery'));
            wp_enqueue_script('wl-bootstrap-js',WEBLIZAR_RG_PLUGIN_URL.'js/bootstrap.min.js', array('jquery'));

            /**
             * css scripts
             */
            wp_enqueue_style('wl-hover-pack-css', WEBLIZAR_RG_PLUGIN_URL.'css/hover-pack.css');
            wp_enqueue_style('wl-reset-css', WEBLIZAR_RG_PLUGIN_URL.'css/reset.css');
            wp_enqueue_style('wl-boot-strap-css', WEBLIZAR_RG_PLUGIN_URL.'css/bootstrap.css');
            wp_enqueue_style('wl-img-gallery-css', WEBLIZAR_RG_PLUGIN_URL.'css/img-gallery.css');

            wp_enqueue_style('wl-font-awesome-4', WEBLIZAR_RG_PLUGIN_URL.'css/font-awesome-4.0.3/css/font-awesome.min.css');

            break;
        } //end of if
    } //end of foreach
}
add_action( 'wp', 'WeblizarResponsiveGalleryShortCodeDetect' );

/**
 * Settings Page for Responsive Gallery
 */
add_action('admin_menu' , 'WRG_SettingsPage');

function WRG_SettingsPage() {
    add_submenu_page('edit.php?post_type=responsive-gallery', __('Settings', WEBLIZAR_RPG_TEXT_DOMAIN), __('Settings', WEBLIZAR_RPG_TEXT_DOMAIN), 'administrator', 'image-gallery-settings', 'image_gallery_settings_page_function');
    add_submenu_page('edit.php?post_type=responsive-gallery', 'Pro Features', 'Pro Features', 'administrator', 'get-image-gallery-pro-plugin', 'get_image_gallery_pro_page_function');
}

/**
 * Photo Gallery Settings Page
 */
function image_gallery_settings_page_function() {
    //css
    wp_enqueue_style('wl-font-awesome-4', WEBLIZAR_RG_PLUGIN_URL.'css/font-awesome-4.0.3/css/font-awesome.min.css');
    require_once("responsive-gallery-settings.php");
}

/**
 * Get Responsive Photo Gallery Pro Plugin Page
 */
function get_image_gallery_pro_page_function() {
    //css
    wp_enqueue_style('wl-font-awesome-4', WEBLIZAR_RG_PLUGIN_URL.'css/font-awesome-4.0.3/css/font-awesome.min.css');
    wp_enqueue_style('wl-pricing-table-css', WEBLIZAR_RG_PLUGIN_URL.'css/pricing-table.css');
    wp_enqueue_style('wl-pricing-table-responsive-css', WEBLIZAR_RG_PLUGIN_URL.'css/pricing-table-responsive.css');
    wp_enqueue_style('wl-boot-strap-responsive-min-2-3-css', WEBLIZAR_RG_PLUGIN_URL.'css/bootstrap-responsive.min.2.3.css');
    require_once("get-responsive-gallery-pro.php");
}


/**
 * Responsive Gallery Short Code [WRG]
 */
require_once("responsive-gallery-short-code.php");