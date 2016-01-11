<?php
/**
 * Example of custom Layouts cell.
 */

/**
 * Cell abstraction. Defines the cell with Layouts.
 */
class WPDDL_Integration_Layouts_Cell_Orbit_Slider extends WPDDL_Cell_Abstract {
	protected $id = 'cornerstone-orbitslider';

	protected $factory = 'WPDDL_Integration_Layouts_Cell_Orbit_Slider_Cell_Factory';
}

/**
 * Represents the actual cell.
 */
class WPDDL_Integration_Layouts_Cell_Orbit_Slider_Cell extends WPDDL_Cell_Abstract_Cell {
	protected $id = 'cornerstone-orbitslider';

	/**
	 * Each cell has it's view, which is a file that is included when the cell is being rendered.
	 *
	 * @return string Path to the cell view.
	 */
	protected function setViewFile() {
		return dirname( __FILE__ ) . '/view/cornerstone-orbitslider.php';
	}
}


/**
 * Cell factory.
 */
class WPDDL_Integration_Layouts_Cell_Orbit_Slider_Cell_Factory extends WPDDL_Cell_Abstract_Cell_Factory {
	protected $name = 'Cornerstone Orbit Slider';
    private $orbit = 'orbit';
    protected $allow_multiple = true;
    protected $has_settings = true;

	protected $cell_class = 'WPDDL_Integration_Layouts_Cell_Orbit_Slider_Cell';

    public function __construct(){
            $this->preview_image_url = DDL_ICONS_PNG_REL_PATH . 'slider_expand-image.png';
            $this->description = __('Display an Orbit (Foundation carousel plugin) Slider based on Orbit post type. Slides can be filtered by tags or categories.', 'ddl-layouts');
            add_action('wp_ajax_ddl_orbit_fetch_terms', array(&$this, 'ddl_orbit_fetch_terms'));
    }

	protected function setCellImageUrl() {
		$this->cell_image_url = DDL_ICONS_SVG_REL_PATH . 'layouts-slider-cell.svg';
	}

    protected function _dialog_template() {
        ob_start();?>
        <ul class="ddl-form js-form-cornerstone-orbitslider-wrap form-cornerstone-orbitslider-wrap">
            <!--<li>
                <label for="<?php the_ddl_name_attr('orbit_height'); ?>" class="ddl-manual-width-201"><?php _e( 'Slider height', 'ddl-layouts' ) ?>:</label>
                <span class="ddl-input-wrap"><input type="number" name="<?php the_ddl_name_attr('orbit_height'); ?>" value="300" class="ddl-input-half-width"><span class="ddl-measure-unit ddl-measure-unit-orbit"><?php _e( 'px', 'ddl-layouts' ) ?></span></span>
            </li>-->
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
                        </label>
                        <label class="checkbox" for="<?php the_ddl_name_attr('pause'); ?>">
                            <input type="checkbox" name="<?php the_ddl_name_attr('pause'); ?>" id="<?php the_ddl_name_attr('pause'); ?>" value="pause" disabled>
                            <?php _e( 'Pause on hover', 'ddl-layouts' ) ?>
                        </label>
                        <label class="checkbox" for="<?php the_ddl_name_attr('motion_ui'); ?>">
                            <input type="checkbox" name="<?php the_ddl_name_attr('motion_ui'); ?>" id="<?php the_ddl_name_attr('motion_ui'); ?>" value="true">
                            <?php _e( 'Use Motion-UI', 'ddl-layouts' ) ?>
                        </label>
                        <label class="checkbox" for="<?php the_ddl_name_attr('bullets'); ?>">
                            <input type="checkbox" name="<?php the_ddl_name_attr('bullets'); ?>" id="<?php the_ddl_name_attr('bullets'); ?>" value="bullets">
                            <?php _e( 'Bullets', 'ddl-layouts' ) ?>
                        </label>
                    </div>
                </fieldset>
            </li>
            <li>
                <label for="<?php the_ddl_name_attr('orbit_aria'); ?>"><?php _e( 'Orbit aria label', 'ddl-layouts' ) ?>:</label>
                <input type="text" name="<?php the_ddl_name_attr('orbit_aria'); ?>" id="<?php the_ddl_name_attr('orbit_aria'); ?>" value="" placeholder="Aria label">
            </li>
            <li>
                <label for="<?php the_ddl_name_attr('orbitsize'); ?>"><?php _e('Orbit size', 'ddl-layouts') ?>:<i class="fa fa-question-circle question-mark-and-the-mysterians js-ddl-question-mark" data-tooltip-text="<?php _e( 'The size option works only with image only slides. If post slides and featured image slides are mixed, the size maybe taken from the former.', 'ddl-layouts' ) ?>"></i></label>
                <select name="<?php the_ddl_name_attr('orbitsize'); ?>">
                    <?php echo Layouts_cell_imagebox::imagebox_cell_get_image_size_options(); ?>
                </select>
            </li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
            <li>
                <label for="<?php the_ddl_name_attr('orbit_taxonomy'); ?>"><?php _e('Select a taxonomy', 'ddl-layouts') ?>:<i class="fa fa-question-circle question-mark-and-the-mysterians js-ddl-question-mark" data-tooltip-text="<?php _e( 'Filter Orbit slides post type by taxonomy selected term. Leave it empty will fetch all.', 'ddl-layouts' ) ?>"></i></label>
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
        $unique_id = uniqid();
        $count_slides = 0;
        $orbitsize = isset( $content['orbitsize'] ) ? $content['orbitsize'] : 'original';
        $args = array(
            'post_type' => 'orbit',
            'posts_per_page' => -1
        );

        if ( isset( $content['orbit_taxonomy'] ) && $content['orbit_taxonomy'] !== ''  && isset( $content['orbit_term'] ) && $content['orbit_term'] !== '' ){
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $content['orbit_taxonomy'],
                    'field'    => 'slug',
                    'terms'    => array( $content['orbit_term'] )
                )
            );
        }

        $loop = new WP_Query( $args );
        $orbitparam = self::carousel_element_data_options( $content );

        echo '<div id="orbit-'.$unique_id.'" class="orbit" role="region"' .
            (isset($content['orbit_aria']) && $content['orbit_aria'] ? ' aria-label="' . $content['orbit_aria'] . '"' : '' ) .
            ' data-orbit ' . $orbitparam .
            (isset($content['motion_ui']) && $content['motion_ui'] ? ' data-use-m-u-i="true"' : ' data-use-m-u-i="false"' ) .'>';

        echo '<ul class="orbit-container">';?>

        <button class="orbit-previous" aria-label="<?php _e('previous','cornerstone'); ?>"><span class="show-for-sr"><?php _e('Previous Slide','cornerstone'); ?></span>&#10094;</button>
    	<button class="orbit-next" aria-label="<?php _e('next','cornerstone'); ?>"><span class="show-for-sr"><?php _e('Next Slide','cornerstone'); ?></span>&#10095;</button>

        <?php
        global $post, $wp_query;

        while ( $loop->have_posts() ) : $loop->the_post();
            $add_style = '';
            echo '<li class="' . ($wp_query->current_post == 0 && !is_paged() ? 'is-active ' : '' ) . 'orbit-slide">';
            if( has_post_thumbnail() ) {
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

                if($orbitlink != '') {echo '<a href="' . $orbitlink . '">';}
                echo '<img class="orbit-image" src="'. $orbitimage . '" alt="' . $orbitimagealttext . '"/>';
                if($orbitcaption != '') {echo '<figcaption class="orbit-caption">' . $orbitcaption . '</figcaption>';}
                if($orbitlink != '') {echo '</a>';}
                echo '</li>';

            } else {

                the_title();
                echo '</h3>';
                the_content();

            }
            echo '</li>';
            $count_slides++;
        endwhile;
        wp_reset_query ();
        echo '</ul>';
        if( $content['bullets'] ):
        ?>
        <nav class="orbit-bullets" id="bullets-<?php $unique_id;?>"> <?php
		for($i=0;$i<$count_slides;$i++) {
            echo '<button class="' . ($i == 0 ? 'is-active ' : '') . '" data-slide="' . $i . '"><span class="show-for-sr">slide' . $i . 'details.</span></button>';
        }
		 ?></nav> <?php
            endif;
        echo '</div>';

    }

    public static function carousel_element_data_options($content){
        $data = '';
        $autoplay = $content['autoplay'] ? 'true' : 'false';
        $bullets = $content['bullets'] ? 'true' : 'false';

        $data .= 'data-options="autoPlay:'.$autoplay.';
                  pauseOnHover:'.$content['pause'].';
                  resume_on_mouseout: true;
                  timerDelay:' . $content['interval']. ';
                  navButtons:true;
                  bullets:'. $bullets .';
                  accessible:true;
                  navButtons:true;
                  boxOfBullets:orbit-bullets;"';

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


