var Integration2015 = Integration2015 || {};


Integration2015.PostEditPageOverrides = function ($) {
    var self = this, themes = Integration2015.templates;

    self.init = function () {
        wp.hooks.addFilter('ddl-init-template-selector-on-ready', function (bool) {
            return false;
        });
        self.populate_combined_select_box_if_empty();
    };

    self.populate_combined_select_box_if_empty = function () {
        var $after = $('#parent_id'),
            $select_combined = $('#js-combined-layout-template-name'),
            $options = $select_combined.find('option'), selected;

        if ( $('#page_template').length && $options.length ) {
            return;
        }

        _.each(_.omit(themes, 'default'), function (value, key, list) {
            selected = key;
            return;
        });

        wp.hooks.addFilter('ddl-selected-template', function (template) {
            return selected;
        });

        self.create_wp_default_page_selector($after);

        DDLayout.template_selector = new DDLayout.templateSelector($);

    };

    self.create_wp_default_page_selector = function ($after) {
        var just_created = false,
            $select = null,
            fragment = document.createDocumentFragment();

        _.each(themes, function (value, key, list) {
            var option = '';
            if (key === 'default') {
                option = $('<option value="' + key + '" id="ddl-option-default">' + value + '</option>');
            } else {
                option = $('<option value="' + key + '">' + value + '</option>');
            }
            fragment.appendChild(option[0]);
        });

        if( $('#page_template').length ){
            $select = $('select[name="page_template"]');
        } else {
            $select = $('<select name="page_template" id="page_template" />');
            just_created = true;
        }

        $select.append(fragment);

        if( just_created === true ){
            $after.after($select);
        }

        return just_created ? $select : null;
    };

    self.init();
};

(function ($) {
    $(function () {
        Integration2015.PostEditPageOverrides.call({}, $);
    });
}(jQuery));