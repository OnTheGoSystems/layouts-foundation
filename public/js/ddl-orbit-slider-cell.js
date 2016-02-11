var DDLayout = DDLayout || {};
DDLayout.OrbitSliderCell = DDLayout.OrbitSliderCell || {};


DDLayout.OrbitSliderCell = function($){
        var self = this,
            $sel,
            $nonce,
            $select_terms,
            selected,
            model = null,
            dialog = null;

    self.selected_term = null;

    self.init = function(){
        jQuery(document).on('cornerstone-orbitslider.dialog-open', self.handle_open);
        jQuery(document).on('cornerstone-orbitslider.dialog-close', self.handle_close);
    };

    self.handle_save = function( target_cell, cached_element, dialog ){
            if( self.selected_term ){
                var content = target_cell.get('content');
                content.orbit_term = self.selected_term;
                target_cell.set('content', content);
            }
    };

    self.handle_open = function(event, object, parent_dialog){
        dialog = parent_dialog;
        $nonce = $('#ddl-orbit-term-nonce');
        $select_terms = $('.js-ddl-select-orbit-term');
        $sel =  $('.js-orbit-taxonomy');
        self.set_events();


        $select_terms.select2({
            'width' : 'resolve',
            enable : false
        });

        if( dialog.is_new_cell() === false ){
            model = dialog.getCachedElement();
            if( model.content && model.content.orbit_taxonomy && model.content.orbit_taxonomy !== "" ) {
                self.do_ajax( model.content.orbit_taxonomy, true );
            }
        }

    };

    self.init_pointer_event = function(){
        $('.js-ddl-question-mark').toolsetTooltip({
            additionalClass:'ddl-tooltip-info'
        });
    };


    self.handle_close = function(){
        self.reset_events();
        model = null;
        dialog = null
    };

    self.set_events = function(){
        self.init_pointer_event();
        $sel.on('change', self.handle_taxonomy_change);
        wp.hooks.addFilter('ddl-layouts-before-cell-save', self.handle_save);
    };

    self.reset_events = function(){
        $sel.off('change', self.handle_taxonomy_change);
        $select_terms.empty().select2('destroy');
        wp.hooks.removeFilter('ddl-layouts-before-cell-save', self.handle_save);
    };

    self.handle_taxonomy_change = function(event){
        var val = $(this).val(), params;

        if( val ){
            self.do_ajax( val, false );
        }
    };

    self.do_ajax = function( val, is_open ){

        var params = {
            'ddl-orbit-term-nonce' : $nonce.val(),
            'taxonomy' : val,
            'action' : 'ddl_orbit_fetch_terms'
        };

        dialog.insert_spinner_after_absolute( $select_terms, {"position":"relative", "bottom": "20px"} );
        $select_terms.off('change', self.handle_terms_change);

        WPV_Toolset.Utils.do_ajax_post(params, {
            success:function( response ){
                $('.ajax-loader').remove();
                if( response.Data.message.errors ){

                    jQuery('.js-element-box-message-container').wpvToolsetMessage({
                        text: _.first( _.keys( response.Data.message.errors ) ) ,
                        stay: true,
                        close: true,
                        type: 'error'
                    });

                } else {
                    var fragment = document.createDocumentFragment(),
                        selected = '',
                        value = '',
                        default_text = CornerstoneOrbit.Settings.strings.select_default+' '+$('.js-orbit-taxonomy').find('option:selected').text(),
                        text = default_text,
                        data = response.Data.message,
                        empty = document.createElement('option');

                    $select_terms.empty();

                    _.each(data, function(v,k,l){
                        var option = document.createElement('option');
                        if( self.has_selected( v, is_open ) ){
                            selected = ' selected="selected"';
                            value = k;
                            text = v;
                            self.selected_term = v;
                        }
                        option.innerHTML = '<option value="'+k+'" '+selected+'>'+v+'</option>';
                        fragment.appendChild( option );
                    });



                    selected = selected === '' ? 'selected="selected"' : '';

                    empty.innerHTML = '<option value="" '+selected+'>'+default_text+'</option>';

                    $select_terms.append( empty, fragment);
                    $select_terms.select2('data', {id: value, text: text});
                    $select_terms.on('change', self.handle_terms_change);
                }

            },
            error:function(response){
                $('.ajax-loader').remove();
                jQuery('.js-element-box-message-container').wpvToolsetMessage({
                    text: response.error,
                    stay: true,
                    close: true,
                    type: 'error'
                });
            },
            fail:function(response){
                console.error( 'Fail', response );
                $('.ajax-loader').remove();
            }
        });
    };

    self.handle_terms_change = function(event){
        var value = $(this).val();
        self.selected_term = value;
    };

    self.has_selected = function( v, is_open ){

        if( is_open === false ) return false;
        if( model.content.orbit_term === v ){
            return true;
        }
    };

    self.init();
};

(function($){
    $(function($){
        DDLayout.OrbitSliderCell.call({}, $);
    });
}(jQuery));