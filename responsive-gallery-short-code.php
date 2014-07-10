<?php
add_shortcode( 'WRG', 'image_gallery_premium_short_code' );
function image_gallery_premium_short_code() {
	ob_start();

    /**
     * Load Responsive Gallery Settings
     */
    $WL_RG_Settings  = unserialize( get_option("WL_IGP_Settings") );
    if(count($WL_RG_Settings)) {
        $WL_Hover_Animation     = $WL_RG_Settings['WL_Hover_Animation'];
        $WL_Gallery_Layout      = $WL_RG_Settings['WL_Gallery_Layout'];
        $WL_Hover_Color         = $WL_RG_Settings['WL_Hover_Color'];
        $WL_Hover_Color_Opacity = 1;
        $WL_Font_Style          = $WL_RG_Settings['WL_Font_Style'];
        $WL_Image_View_Icon     = $WL_RG_Settings['WL_Image_View_Icon'];
    } else {
		$WL_Hover_Color_Opacity = 1;
		$WL_Hover_Animation     = "fade";
        $WL_Gallery_Layout      = "col-md-6";
        $WL_Hover_Color         = "#74C9BE";
        $WL_Font_Style          = "Arial";
        $WL_Image_View_Icon     = "fa-picture-o";
    }
	if(!function_exists('hex2rgb')) {
		function hex2rgb($hex) {
		   $hex = str_replace("#", "", $hex);

		   if(strlen($hex) == 3) {
			  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
			  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
			  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
		   } else {
			  $r = hexdec(substr($hex,0,2));
			  $g = hexdec(substr($hex,2,2));
			  $b = hexdec(substr($hex,4,2));
		   }
		   $rgb = array($r, $g, $b);

		   return $rgb; // returns an array with the rgb values
		}
	}
    $RGB = hex2rgb($WL_Hover_Color);
    $HoverColorRGB = implode(", ", $RGB);
    ?>

    <script>
        jQuery.browser = {};
        (function () {
            jQuery.browser.msie = false;
            jQuery.browser.version = 0;
            if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                jQuery.browser.msie = true;
                jQuery.browser.version = RegExp.$1;
            }
        })();
    </script>

    <style>
	.modal-backdrop.in {
		display:none !important;
	}
    .b-link-fade .b-wrapper, .b-link-fade .b-top-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);
    }
    .b-link-flow .b-wrapper, .b-link-flow .b-top-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);
    }
    .b-link-stroke .b-top-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);
    }
    .b-link-stroke .b-bottom-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);
    }

    .b-link-box .b-top-line{

        border: 16px solid rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);
    }
    .b-link-box .b-bottom-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);
    }
    .b-link-stripe .b-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);
    }
    .b-link-apart-horisontal .b-top-line, .b-link-apart-horisontal .b-top-line-up{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);

    }
    .b-link-apart-horisontal .b-bottom-line, .b-link-apart-horisontal .b-bottom-line-up{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);
    }
    .b-link-apart-vertical .b-top-line, .b-link-apart-vertical .b-top-line-up{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);
    }
    .b-link-apart-vertical .b-bottom-line, .b-link-apart-vertical .b-bottom-line-up{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);
    }
    .b-link-diagonal .b-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);
    }

    .b-wrapper{
        font-family:<?php echo str_ireplace("+", " ", $WL_Font_Style); ?>; // real name pass here
    }
    </style>

    <?php
    /**
     * Load All Image Gallery Custom Post Type
     */
    $IG_CPT_Name = "responsive-gallery";
    $IG_Taxonomy_Name = "category";
    $AllGalleries = array( 'post_type' => $IG_CPT_Name, 'orderby' => 'ASC');
    $loop = new WP_Query( $AllGalleries );
    ?>
    <div id="gallery1" class="gal-container">
    <?php while ( $loop->have_posts() ) : $loop->the_post();?>
        <!--get the post id-->
        <?php $post_id = get_the_ID(); ?>

            <div style="font-weight: bolder;padding-bottom:20px;border-bottom:2px solid #cccccc;margin-bottom: 20px "><?php echo ucwords(get_the_title($post_id)); ?></div>
    <div class="gallery1">
        <?php

            /**
             * Get All Photos from Gallery Post Meta
             */
            $rpg_all_photos_details = unserialize(get_post_meta( get_the_ID(), 'rpg_all_photos_details', true));
            //print_r(($rpg_all_photos_details)); echo "<br><br>";
            $TotalImages =  get_post_meta( get_the_ID(), 'rpg_total_images_count', true );
            $i = 1;

            foreach($rpg_all_photos_details as $rpg_single_photos_detail) {
                $name = $rpg_single_photos_detail['rpg_image_label'];
                $url = $rpg_single_photos_detail['rpg_image_url'];
				?>
                <div class="<?php echo $WL_Gallery_Layout; ?> col-sm-6 wl-gallery" >
                    <div class="b-link-<?php echo $WL_Hover_Animation; ?> b-animate-go">

                        <img src="<?php echo $url; ?>" class="gall-img-responsive">

                        <div class="b-wrapper">
                            <h2 class="b-from-left b-animate b-delay03"><?php echo ucwords($name); ?></h2>
                            <p class="b-from-right b-animate b-delay03">
                                <a href="#" data-toggle="modal" data-target="#<?php echo $post_id."-".$i; ?>">
                                    <i class="fa <?php echo $WL_Image_View_Icon; ?> fa-2x"></i>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="<?php echo $post_id."-".$i; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel"><?php echo ucwords($name); ?></h4>
                            </div>
                            <div class="modal-body">
                                <img src="<?php echo $url; ?>" class="gall-img-responsive" alt="<?php echo ucwords($name); ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e("Close", "weblizar_rpg"); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
				$i++;
            }
        ?>
        </div>
    <?php endwhile; ?>
    </div>
    <hr>
    <?php wp_reset_query(); ?>
    <?php
	return ob_get_clean();
}
?>