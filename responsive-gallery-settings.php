<?php
    /**
     * Load Saved Image Gallery settings
     */
    $WL_RG_Settings  = unserialize( get_option("WL_IGP_Settings") );
    if(count($WL_RG_Settings)) {
        $WL_Hover_Animation     = $WL_RG_Settings['WL_Hover_Animation'];
        $WL_Gallery_Layout      = $WL_RG_Settings['WL_Gallery_Layout'];
        $WL_Hover_Color         = $WL_RG_Settings['WL_Hover_Color'];
        $WL_Font_Style          = $WL_RG_Settings['WL_Font_Style'];
        $WL_Image_View_Icon     = $WL_RG_Settings['WL_Image_View_Icon'];
    } else {
        $WL_Hover_Animation     = "fade";
        $WL_Gallery_Layout      = "col-md-6";
        $WL_Hover_Color         = "#74C9BE";
        $WL_Font_Style          = "Arial";
        $WL_Image_View_Icon     = "fa-picture-o";
    }
    //print_r($WL_RG_Settings);
?>

<h2>Responsive Gallery Settings</h2>
<form action="?post_type=responsive-gallery&page=image-gallery-settings" method="post">
    <input type="hidden" id="wl_action" name="wl_action" value="wl-save-settings">
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label>Image Hover Animation</label></th>
                <td>
                    <select name="wl-hover-animation" id="wl-hover-animation">
                        <optgroup label="Select Animation">
                            <option value="fade" <?php if($WL_Hover_Animation == 'fade') echo "selected=selected"; ?>>Fade</option>
                            <!--<option value="stroke" <?php /*if($WL_Hover_Animation == 'stroke') echo "selected=selected"; */?>>Stroke</option>-->
                        </optgroup>
                    </select>
                    <p class="description">Choose an animation effect apply on mouse hover.</p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label>Gallery Layout</label></th>
                <td>
                    <select name="wl-gallery-layout" id="wl-gallery-layout">
                        <optgroup label="Select Gallery Layout">
                            <option value="col-md-6" <?php if($WL_Gallery_Layout == 'col-md-6') echo "selected=selected"; ?>>Two Column</option>
                            <option value="col-md-4" <?php if($WL_Gallery_Layout == 'col-md-4') echo "selected=selected"; ?>>Three Column</option>
                        </optgroup>
                    </select>
                    <p class="description">Choose a column layout for image gallery.</p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label>Hover Color</label></th>
                <td>
                    <input type="radio" name="wl-hover-color" id="wl-hover-color" value="#74C9BE" <?php if($WL_Hover_Color == '#74C9BE' ) { echo "checked"; } ?>> <span style="color: #74C9BE; font-size: large; font-weight: bolder;"">Color 1</span>
                    <input type="radio" name="wl-hover-color" id="wl-hover-color" value="#31A3DD" <?php if($WL_Hover_Color == '#31A3DD' ) { echo "checked"; } ?>> <span style="color: #31A3DD; font-size: large; font-weight: bolder;">Color 1</span>

                    <p class="description">Choose a color apply on mouse hover.</p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label>Image View Icon</label></th>
                <td>
                    <input type="radio" name="wl-image-view-icon" id="wl-image-view-icon" value="fa-picture-o"  <?php if($WL_Image_View_Icon == 'fa-picture-o' ) { echo "checked"; } ?>> <i class="fa fa-picture-o fa-2x"></i>
                    <input type="radio" name="wl-image-view-icon" id="wl-image-view-icon" value="fa-camera" <?php if($WL_Image_View_Icon == 'fa-camera' ) { echo "checked"; } ?>> <i class="fa fa-camera fa-2x"></i>
                    <p class="description">Choose image view icon.</p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label>Caption Font Style</label></th>
                <td>
                    <select  name="wl-font-style" class="standard-dropdown" id="wl-font-style">
                        <optgroup label="Default Fonts">
                            <option value="Arial"           <?php if($WL_Font_Style == 'Arial' ) { echo "selected"; } ?>>Arial</option>
                            <option value="_arial_black"    <?php if($WL_Font_Style == '_arial_black' ) { echo "selected"; } ?>>Arial Black</option>
                            <option value="Courier New"     <?php if($WL_Font_Style == 'Courier New' ) { echo "selected"; } ?>>Courier New</option>
                            <option value="georgia"         <?php if($WL_Font_Style == 'georgia' ) { echo "selected"; } ?>>Georgia</option>
                            <option value="grande"          <?php if($WL_Font_Style == 'grande' ) { echo "selected"; } ?>>Grande</option>
                            <option value="_helvetica_neue" <?php if($WL_Font_Style == '_helvetica_neue' ) { echo "selected"; } ?>>Helvetica Neue</option>
                            <option value="_impact"         <?php if($WL_Font_Style == '_impact' ) { echo "selected"; } ?>>Impact</option>
                            <option value="_lucida"         <?php if($WL_Font_Style == '_lucida' ) { echo "selected"; } ?>>Lucida</option>
                            <option value="_lucida"         <?php if($WL_Font_Style == '_lucida' ) { echo "selected"; } ?>>Lucida Grande</option>
                            <option value="_OpenSansBold"   <?php if($WL_Font_Style == '_OpenSansBold' ) { echo "selected"; } ?>>OpenSansBold</option>
                            <option value="_palatino"       <?php if($WL_Font_Style == '_palatino' ) { echo "selected"; } ?>>Palatino</option>
                            <option value="_sans"           <?php if($WL_Font_Style == '_sans' ) { echo "selected"; } ?>>Sans</option>
                            <option value="_sans"           <?php if($WL_Font_Style == '_sans' ) { echo "selected"; } ?>>Sans-Serif</option>
                            <option value="_tahoma"         <?php if($WL_Font_Style == '_tahoma' ) { echo "selected"; } ?>>Tahoma</option>
                            <option value="_times"          <?php if($WL_Font_Style == '_times' ) { echo "selected"; } ?>>Times New Roman</option>
                            <option value="_trebuchet"      <?php if($WL_Font_Style == '_trebuchet' ) { echo "selected"; } ?>>Trebuchet</option>
                            <option value="_verdana"        <?php if($WL_Font_Style == '_verdana' ) { echo "selected"; } ?>>Verdana</option>
                        </optgroup>
                    </select>
                    <p class="description">Choose a caption font style.</p>
                </td>
            </tr>



        </tbody>
    </table>
    <p class="submit">
        <input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
    </p>
</form>

<?php
//print_r($_POST);
if(isset($_POST['wl_action'])) {
    $Action = $_POST['wl_action'];

    //save settings
    if($Action == "wl-save-settings") {
        //print_r($_POST);

        $WL_Hover_Animation     = $_POST['wl-hover-animation'];
        $WL_Gallery_Layout      = $_POST['wl-gallery-layout'];
        $WL_Hover_Color         = $_POST['wl-hover-color'];
        $WL_Font_Style          = $_POST['wl-font-style'];
        $WL_Image_View_Icon     = $_POST['wl-image-view-icon'];

        $SettingsArray = serialize( array(
            'WL_Hover_Animation' => $WL_Hover_Animation,
            'WL_Gallery_Layout' => $WL_Gallery_Layout,
            'WL_Hover_Color' => $WL_Hover_Color,
            'WL_Hover_Color_Opacity' => 1,
            'WL_Font_Style' => $WL_Font_Style,
            'WL_Image_View_Icon' => $WL_Image_View_Icon,
        ) );

        update_option("WL_IGP_Settings", $SettingsArray);
        echo "<script>location.href = location.href;</script>";
    }
}
