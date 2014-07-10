<?php
/**
 * Plugin Name: Responsive Photo Gallery
 * Version: 0.6
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
        'supports'            => array( 'title', '', '', '', '', '', '', '', '', '', '', ),
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
 * Add Meta Box & load required CSS and JS for interface
 */
add_action('admin_init','ResponsivePhotoGallery_init');
function ResponsivePhotoGallery_init() {
    add_meta_box('ResponsivePhotoGallery_meta', __('Add New Images', WEBLIZAR_RPG_TEXT_DOMAIN), 'responsive_photo_gallery_function', 'responsive-gallery', 'normal', 'high');
    add_action('save_post','responsive_photo_gallery_meta_save');
    wp_enqueue_script('theme-preview');
    wp_enqueue_script('rpg-media-uploads',WEBLIZAR_RG_PLUGIN_URL.'js/rpg-media-upload-script.js',array('media-upload','thickbox','jquery'));
    wp_enqueue_style('dashboard');
    wp_enqueue_style('rpg-meta-css', WEBLIZAR_RG_PLUGIN_URL.'css/rpg-meta.css');
    wp_enqueue_style('thickbox');
}

/**
 * Meta box interface design
 */
function responsive_photo_gallery_function() {
    $rpg_all_photos_details = unserialize(get_post_meta( get_the_ID(), 'rpg_all_photos_details', true));
    $TotalImages =  get_post_meta( get_the_ID(), 'rpg_total_images_count', true );
    $i = 1;
    ?>
    <input type="hidden" id="count_total" name="count_total" value="<?php if($TotalImages==0){ echo 0; } else { echo $TotalImages; } ?>"/>
    <div style="clear:left;"></div>

    <?php
    /* load saved photos into gallery */
    if($TotalImages) {
        foreach($rpg_all_photos_details as $rpg_single_photos_detail) {
            $name = $rpg_single_photos_detail['rpg_image_label'];
            $url = $rpg_single_photos_detail['rpg_image_url'];
            ?>
                <div class="rpg-image-entry" id="rpg_img<?php echo $i; ?>">
                        <a class="gallery_remove" href="#gallery_remove" id="rpg_remove_bt<?php echo $i; ?>"onclick="remove_meta_img(<?php echo $i; ?>)"><img src="<?php echo  WEBLIZAR_RG_PLUGIN_URL.'images/Close-icon.png'; ?>" /></a>
                        <img src="<?php echo  $url; ?>" class="rpg-meta-image" alt=""  style="">
                        <input type="button" id="upload-background-<?php echo $i; ?>" name="upload-background-<?php echo $i; ?>" value="Upload Image" class="button-primary" onClick="weblizar_image('<?php echo $i; ?>')" />
                        <input type="text" id="rpg_img_url<?php echo $i; ?>" name="rpg_img_url<?php echo $i; ?>" class="rpg_label_text"  value="<?php echo  $url; ?>"  readonly="readonly" style="display:none;" />
                        <input type="text" id="image_label<?php echo $i; ?>" name="image_label<?php echo $i; ?>" placeholder="Enter Image Label" class="rpg_label_text" value="<?php echo $name; ?>">
                </div>
            <?php
            $i++;
        } // end of foreach
    } else {
        $TotalImages = 0;
    }
    ?>


    <div id="append_rpg_img">
    </div>
    <div class="rpg-image-entry add_rpg_new_image" onclick="add_rpg_meta_img()">
            <div class="dashicons dashicons-plus"></div>
            <p><?php _e('Add New Image', WEBLIZAR_RPG_TEXT_DOMAIN); ?></p>
    </div>
    <div style="clear:left;"></div>
    <script>
    var rpg_i = parseInt(jQuery("#count_total").val());
    function add_rpg_meta_img() {
        rpg_i = rpg_i + 1;

        var rpg_output = '<div class="rpg-image-entry" id="rpg_img'+ rpg_i +'">'+
                            '<a class="gallery_remove" href="#gallery_remove" id="rpg_remove_bt' + rpg_i + '"onclick="remove_meta_img(' + rpg_i + ')"><img src="<?php echo  WEBLIZAR_RG_PLUGIN_URL.'images/Close-icon.png'; ?>" /></a>'+
                            '<img src="<?php echo  WEBLIZAR_RG_PLUGIN_URL.'images/rpg-default.jpg'; ?>" class="rpg-meta-image" alt=""  style="">'+
                            '<input type="button" id="upload-background-' + rpg_i + '" name="upload-background-' + rpg_i + '" value="Upload Image" class="button-primary" onClick="weblizar_image(' + rpg_i + ')" />'+
                            '<input type="text" id="rpg_img_url'+ rpg_i +'" name="rpg_img_url'+ rpg_i +'" class="rpg_label_text"  value="<?php echo  WEBLIZAR_RG_PLUGIN_URL.'images/rpg-default.jpg'; ?>"  readonly="readonly" style="display:none;" />'+
                            '<input type="text" id="image_label'+ rpg_i +'" name="image_label'+ rpg_i +'" placeholder="Enter Image Label" class="rpg_label_text"   >'+
                        '</div>';
        jQuery(rpg_output).hide().appendTo("#append_rpg_img").slideDown(500);
        jQuery("#count_total").val(rpg_i);
    }

    function remove_meta_img(id){
        jQuery("#rpg_img"+id).slideUp(600, function(){
            jQuery(this).remove();
        });

        count_total = jQuery("#count_total").val();
        count_total = count_total - 1;
        var id_i= id + 1;

        for(var i=id_i;i<=rpg_i;i++){
            var j = i-1;
            jQuery("#rpg_remove_bt"+i).attr('onclick','remove_meta_img('+j+')');
            jQuery("#rpg_remove_bt"+i).attr('id','rpg_remove_bt'+j);
            jQuery("#rpg_img_url"+i).attr('name','rpg_img_url'+j);
            jQuery("#image_label"+i).attr('name','image_label'+j);
            jQuery("#rpg_img_url"+i).attr('id','rpg_img_url'+j);
            jQuery("#image_label"+i).attr('id','image_label'+j);

            jQuery("#rpg_img"+i).attr('id','rpg_img'+j);
        }
        jQuery("#count_total").val(count_total);
        rpg_i = rpg_i - 1;
    }
    </script>
    <?php
}

/**
 * Save All Photo Gallery Images
 */
function responsive_photo_gallery_meta_save() {
    if(isset($_POST['post_ID'])) {
        $post_ID = $_POST['post_ID'];
        $post_type = get_post_type($post_ID);
        if($post_type == 'responsive-gallery') {
            $TotalImages = $_POST['count_total'];
            $ImagesArray = array();
            if($TotalImages) {
                for($i=1; $i <= $TotalImages; $i++) {
                    $image_label = "image_label".$i;
                    $name = $_POST['image_label'.$i];
                    $url = $_POST['rpg_img_url'.$i];
                    $ImagesArray[] = array(
                        'rpg_image_label' => $name,
                        'rpg_image_url' => $url
                    );
                }
                update_post_meta($post_ID, 'rpg_all_photos_details', serialize($ImagesArray));
                update_post_meta($post_ID, 'rpg_total_images_count', $TotalImages);
            } else {
                $TotalImages = 0;
                update_post_meta($post_ID, 'rpg_total_images_count', $TotalImages);
                $ImagesArray = array();
                update_post_meta($post_ID, 'rpg_all_photos_details', serialize($ImagesArray));
            }
        }
    }
}


/**
 * Weblizar Responsive Gallery Short Code Detect Function
 */
function WeblizarResponsiveGalleryShortCodeDetect() {
    global $wp_query;
    $Posts = $wp_query->posts;
    $Pattern = get_shortcode_regex();

    foreach ($Posts as $Post) {
        if( preg_match_all( '/'. $Pattern .'/s', $Post->post_content, $Matches ) && array_key_exists( 2, $Matches ) && in_array( 'WRG', $Matches[2] ) ) {
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

            /**
             * envira & isotope js
             */
            wp_enqueue_script( 'envira-js', WEBLIZAR_RG_PLUGIN_URL.'js/envira.js', array(), '1.5.26', true );
            wp_enqueue_script( 'isotope-js', WEBLIZAR_RG_PLUGIN_URL.'js/gl_isotope.js', array(), '', true );

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