<?php
/**
 * Example of custom Layouts cell.
 */

/**
 * Cell abstraction. Defines the cell with Layouts.
 *
 * @todo You can, of course, abandon this mechanism and create custom cells entirely by hand:
 * @link https://wp-types.com/documentation/user-guides/creating-custom-cells-unique-functionality/
 *
 * @todo Name of this class should be "WPDDL_Integration_Layouts_Cell_{$cell_name}", where $cell_name corresponds to
 * @todo     the filename (so it is loaded properly by the autoloader).
 *
 * @todo You need to choose an unique cell ID and set it properly in all three classes below.
 */
class WPDDL_Integration_Layouts_Cell_Orbit_Slider extends WPDDL_Cell_Abstract {
	protected $id = 'cornerstone-orbitslider'; // @todo update cell ID

	// @todo Update to the factory class name (the last one)
	protected $factory = 'WPDDL_Integration_Layouts_Cell_Orbit_Slider_Cell_Factory';

}

/**
 * Represents the actual cell.
 *
 * @todo Rename this class to "WPDDL_Integration_Layouts_Cell_{$cell_name}_Cell".
 */
class WPDDL_Integration_Layouts_Cell_Orbit_Slider_Cell extends WPDDL_Cell_Abstract_Cell {
	protected $id = 'cornerstone-orbitslider'; // @todo Update cell ID.

	/**
	 * Each cell has it's view, which is a file that is included when the cell is being rendered.
	 *
	 * @return string Path to the cell view.
	 * @todo Provide your own view and update the path.
	 */
	protected function setViewFile() {
		return dirname( __FILE__ ) . '/view/cornerstone-orbitslider.php';
	}
}


/**
 * Cell factory.
 *
 * @todo Rename this class to "WPDDL_Integration_Layouts_Cell_{$cell_name}_Cell_Factory".
 * @todo Take a look at WPDDL_Cell_Abstract_Cell_Factory and consider providing more complete information about the cell.
 */
class WPDDL_Integration_Layouts_Cell_Orbit_Slider_Cell_Factory extends WPDDL_Cell_Abstract_Cell_Factory {
	protected $name = 'Cornerstone Orbit slider cell'; // @todo Provide cell display name
    private $orbit = 'orbit';
    protected $has_settings = true;

	// @todo Update to the cell class name (the second one in this file)
	protected $cell_class = 'WPDDL_Integration_Layouts_Cell_Orbit_Slider_Cell';

    public function __construct(){
            add_action('wp_ajax_ddl_orbit_fetch_terms', array(&$this, 'ddl_orbit_fetch_terms'));
    }

	// @todo Provide an URL to cell image.
	protected function setCellImageUrl() {
		$this->cell_image_url = DDL_ICONS_SVG_REL_PATH . 'layouts-slider-cell.svg';
	}

    protected function _dialog_template() {
       ob_start();?>
        <ul class="ddl-form js-form-cornerstone-orbitslider-wrap form-cornerstone-orbitslider-wrap">
            <li>
                <label for="<?php the_ddl_name_attr('interval'); ?>" class="ddl-manual-width-201"><?php _e( 'Interval', 'ddl-layouts' ) ?>:</label>
                <span class="ddl-input-wrap"><input type="number" name="<?php the_ddl_name_attr('interval'); ?>" value="5000" class="ddl-input-half-width"><span class="ddl-measure-unit"><?php _e( 'ms', 'ddl-layouts' ) ?></span><i class="fa fa-question-circle question-mark-and-the-mysterians js-ddl-question-mark" data-tooltip-text="<?php _e( 'The amount of time to delay between automatically cycling an item, ms.', 'ddl-layouts' ) ?>"></i></span>
            </li>
            <li>
            <fieldset>
                <legend><?php _e( 'Options', 'ddl-layouts' ) ?></legend>
                <div class="fields-group">
                    <label class="checkbox" for="<?php the_ddl_name_attr('autoplay'); ?>">
                        <input type="checkbox" name="<?php the_ddl_name_attr('autoplay'); ?>" id="<?php the_ddl_name_attr('autoplay'); ?>" value="true">
                        <?php _e( 'Autoplay', 'ddl-layouts' ) ?>
                        <input type="hidden" name="<?php the_ddl_name_attr('pause'); ?>" id="<?php the_ddl_name_attr('pause'); ?>" value="pause">
                    </label>
                    <?php apply_filters('ddl-slider_cell_additional_options', '');?>
                </div>
            </fieldset>
            </li>
            <li>
                <label for="<?php the_ddl_name_attr('image_size'); ?>"><?php _e('Orbit size', 'ddl-layouts') ?>:</label>
            <select name="<?php the_ddl_name_attr('orbitsize'); ?>">
                <?php echo Layouts_cell_imagebox::imagebox_cell_get_image_size_options(); ?>
            </select>
            </li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
            <li>
                <label for="<?php the_ddl_name_attr('orbit_taxonomy'); ?>"><?php _e('Select a taxonomy', 'ddl-layouts') ?>:</label>
                <select class="orbit-taxonomy js-orbit-taxonomy" name="<?php the_ddl_name_attr('orbit_taxonomy'); ?>">
                   <option value=""><?php _e('Select a taxonomy', 'ddl-layouts');?></option>
                    <?php echo $this->get_cat_select_options();?>
                </select>
            </li>
            <li class="js-ddl-orbit-term" class="ddl-manual-width-190">
                <label for="<?php the_ddl_name_attr('orbit_term'); ?>"><?php _e('Select term', 'ddl-layouts') ?>:</label>
                <select name="<?php the_ddl_name_attr('orbit_term'); ?>" class="js-ddl-select-orbit-term">

                </select>
            </li>
            <?php wp_nonce_field( 'ddl_orbit_fetch_terms', 'ddl-orbit-term-nonce' ); ?>
        </ul>
        <?php
        return ob_get_clean();

    }

    private function get_cat_select_options(){
        $taxonomies = get_object_taxonomies( $this->orbit, 'objects' );
        ob_start();
        foreach( $taxonomies as $slug => $taxonomy ):?>
            <option value="<?php echo $slug; ?>" ><?php echo $taxonomy->label; ?></option>
        <?php
        endforeach;
        return ob_get_clean();
    }

    public function ddl_orbit_fetch_terms(){
        if( user_can_edit_layouts() === false ){
            die( WPDD_Utils::ajax_caps_fail( __METHOD__ ) );
        }
        if( $_POST && wp_verify_nonce( $_POST['ddl-orbit-term-nonce'], 'ddl_orbit_fetch_terms' ) )
        {
            $send = wp_json_encode( array( 'Data' => array( 'message' =>  $this->get_term_data( $_POST['taxonomy'] ) ) ) );
        }
        else
        {
            $send = wp_json_encode( array( 'error' =>  __( sprintf('Nonce problem: apparently we do not know where the request comes from. %s', __METHOD__ ), 'ddl-layouts') ) );
        }

        die( $send );
    }

    private function get_term_data( $taxonomy ){
        return get_terms( array( $taxonomy ), array('fields' => 'id=>name') );
    }

    public static function orbit_slider( $content ) {

        $orbitsize = isset( $content['orbitsize'] ) ? $content['orbitsize'] : 'original';
        $args = array( 'post_type' => 'orbit');

        if ( isset( $content['orbit_taxonomy'] ) && $content['orbit_taxonomy'] !== ''  && isset( $content['orbit_term'] ) && $content['orbit_term'] !== '' ){
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $content['orbit_taxonomy'],
                    'field'    => 'name',
                    'terms'    => $content['orbit_term'],
                    'operator' => '=',
                )
            );
        }

        $loop = new WP_Query( $args );

        $orbitparam = self::carousel_element_data_options( $content );

        echo '<ul data-orbit ' . $orbitparam . '>';

        while ( $loop->have_posts() ) : $loop->the_post();

            if(has_post_thumbnail()) {

                if($orbitsize != '') {
                    $orbitimagethumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), $orbitsize);
                    $orbitimage = $orbitimagethumbnail['0'];
                } else {
                    $orbitimagefull = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail_size');
                    $orbitimage = $orbitimagefull['0'];
                }
                $orbitimagealttext = get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true);
                $orbitcaption = get_post_meta(get_the_ID(), '_orbit_meta_box_caption_text', true );
                $orbitlink = get_post_meta(get_the_ID(), '_orbit_meta_box_link_text', true );
                echo '<li>';
                if($orbitlink != '') {echo '<a href="' . $orbitlink . '">';}
                echo '<img src="'. $orbitimage . '" alt="' . $orbitimagealttext . '"/>';
                if($orbitcaption != '') {echo '<div class="orbit-caption">' . $orbitcaption . '</div>';}
                if($orbitlink != '') {echo '</a>';}
                echo '</li>';

            } else {

                echo '<li><h2>';
                the_title();
                echo '</h2>';
                the_content();
                echo '</li>';

            }

        endwhile;

        echo '</ul>';
    }

    public static function carousel_element_data_options($content){
        $data = '';
        $content['autoplay'] = $content['autoplay'] === 1 ? "true" : "";

        $data .= 'data-options="timer:'.$content['autoplay'].';
                  animation:slide;
                  slide_number: '.$content['slide_number'].';
                  pause_on_hover:' .$content['pause'].';
                  timer_speed:' .$content['interval'].';
                  animation_speed:500;
                  navigation_arrows:true;
                  bullets:'.$content['bullets'].';"';

        return $data;
    }

    public function get_editor_cell_template(){
        ob_start();
        ?>
        <div class="cell-content">
            <p class="cell-name"><?php echo $this->name; ?></p>
            <div class="cell-preview">
                <div class="ddl-slider-preview ddl-orbit-slider-preview">
                    <span class="ddl-orbit-slider-preview-img">
                    <img src="<?php echo WPDDL_RES_RELPATH . '/images/cell-icons/slider.svg'; ?>" height="130px">
                        </span>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

}


