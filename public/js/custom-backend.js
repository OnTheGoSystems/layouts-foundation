var DDLayout = DDLayout || {};
DDLayout.ThemeIntegrations = DDLayout.ThemeIntegrations || {};

/**
 * Custom backend functionality.
 *
 * @param $
 * @constructor
 */
DDLayout.ThemeIntegrations.CustomBackendFunctionality = function ($) {

    var self = this;

    self.init = function () {
        DDLayout.ThemeIntegrations.MenuCellOverides.call({}, $);
        DDLayout.ThemeIntegrations.SliderCellOverrides.call({}, $);
    };

    self.init();
};

DDLayout.ThemeIntegrations.MenuCellOverides = function ($) {
    var self = this, $dir, $top;

    self.init = function () {
        jQuery(document).on('menu-cell.dialog-open', self.handle_open);
        jQuery(document).on('menu-cell.dialog-close', self.handle_close);
    };

    self.handle_open = function () {
        $dir = $('select[name="ddl-layout-menu_dir"]');
        $top = $('input[name="ddl-layout-topbar"]');

        self.init_pointer_event();
        self.handle_menu_top_cornerstone();
    };

    self.init_pointer_event = function () {
        $('.js-ddl-question-mark').toolsetTooltip({
            additionalClass: 'ddl-tooltip-info'
        });
    };

    self.handle_close = function () {
        // do stuff on close
        $dir.off('change', self.menu_dir_change);
    };

    self.handle_menu_top_cornerstone = function(){
        $dir.on('change', self.menu_dir_change);
    };

    self.menu_dir_change = function() {

        var dir = $(this).val();

        if (dir === 'nav-stacked') {
            $top.prop('checked', false).trigger('change').prop('disabled', true);
        } else {
            $top.prop('checked', true).trigger('change').prop('disabled', false);
        }
    };

    self.init();
};

DDLayout.ThemeIntegrations.SliderCellOverrides = function($){
    var self = this, $dir, $top;

    self.init = function () {
        jQuery(document).on('slider-cell.dialog-open', self.handle_open);
        jQuery(document).on('slider-cell.dialog-close', self.handle_close);
    };

    self.handle_open = function(){
        $('#ddl-layout-autoplay').on('click', self.handle_auto_change );
    };

    self.handle_auto_change = function(event){

            if( $(this).is(':checked') ){

                $('#ddl-layout-pause').prop('disabled', false);
            } else{

                $('#ddl-layout-pause').prop('disabled', true);
            }
    };

    self.handle_close = function(){
        $('#ddl-layout-autoplay').off('change',self.handle_auto_change );
    };

    self.init();
};

(function ($) {
    $(function () {
        DDLayout.ThemeIntegrations.CustomBackendFunctionality.call({}, $);
    });
}(jQuery));