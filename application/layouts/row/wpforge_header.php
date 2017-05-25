<?php
class WPDDL_Integration_Layouts_Row_WPForge_header extends WPDDL_Row_Type_Preset_Fullwidth{
    public function setup() {

        $this->image =
        $this->id   = 'wpforge_header';
        $this->name = __('WPForge Header', 'ddl-layouts');
        $this->desc = sprintf( __('%sWPForge%s site header row', 'ddl-layouts'), '<b>', '</b>' );

        $this->addCssClass( 'top-bar' );
        $this->addDataAttributes( 'options', 'mobile_show_parent_link: true' );
        $this->addDataAttributes( 'topbar', '' );

        parent::setup();
    }

    public function htmlOpen( $markup, $args ) {

        if( $args['mode'] === $this->id ) {

            $el_css = 'ddl-full-width-row ' . $args['row_class'] . $args['type'];

            $css_classes = $this->getCssClasses();

            $el_css .= ! empty( $css_classes )
                ? ' ' . implode( $css_classes, ' ' )
                : '';

            $el_css .= isset( $args['additionalCssClasses'] )
                ? ' '.$args['additionalCssClasses']
                : '';

            $el_id = isset( $args['cssId'] ) && ! empty( $args['cssId'] )
                ? ' id="' . $args['cssId'] . '"'
                : '';

            ob_start();
            echo '<nav class="' . $args['container_class'] . '" '.$el_id.' >';
            ?>

            <div class="title-bar" data-responsive-toggle="top-menu" data-hide-for="large">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="float-left"><?php bloginfo( 'name' ); ?></a>
                <div class="float-right">
                    <div class="title-bar-title">Menu</div>
                    <button class="menu-icon" type="button" data-toggle></button>
                </div>
            </div>

            <?php
            echo '<' . $args['tag']  . ' class="' . $el_css . '" '.$this->renderDataAttributes().' id="top-menu" >';

            $markup = ob_get_clean();
        }

        return $markup;
    }

    public function htmlClose( $output, $mode, $tag ) {
        if( $mode === $this->id ) {
            $output = '</' . $tag . '></nav>';
        }

        return $output;
    }
}