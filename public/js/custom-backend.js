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
    };

    self.init();
};

DDLayout.ThemeIntegrations.MenuCellOverides = function($){
        var self = this;

        self.init = function(){
            jQuery(document).on('menu-cell.dialog-open', self.handle_open);
        };

        self.handle_open = function(){
            self.init_pointer_event();
        };

        self.init_pointer_event = function(){
            $('.js-ddl-question-mark').toolsetTooltip({
                additionalClass:'ddl-tooltip-info'
            });
        };

    self.init();
};

(function ($) {
    $(function () {
        DDLayout.ThemeIntegrations.CustomBackendFunctionality.call({}, $);
    });
}(jQuery));