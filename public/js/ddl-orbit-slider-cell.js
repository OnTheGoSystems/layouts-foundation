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

    self.init = function(){
        jQuery(document).on('cornerstone-orbitslider.dialog-open', self.handle_open);
        jQuery(document).on('cornerstone-orbitslider.dialog-close', self.handle_close);
    };

    self.handle_open = function(event, object, parent_dialog){
        dialog = parent_dialog;
        $nonce = $('#ddl-orbit-term-nonce');
        $select_terms = $('.js-ddl-select-orbit-term');
        $sel =  $('.js-orbit-taxonomy');
        self.set_events();

        if( dialog.is_new_cell() === false ){
            model = dialog.getCachedElement();
            console.log( model.content );
            if( model.content && model.content.orbit_taxonomy && model.content.orbit_taxonomy !== "" ) {
                self.do_ajax( model.content.orbit_taxonomy, true );
            }
        }

    };

    self.handle_close = function(){
        self.reset_events();
        model = null;
        dialog = null
    };

    self.set_events = function(){
        $sel.on('change', self.handle_taxonomy_change);
    };

    self.reset_events = function(){
        $sel.off('change', self.handle_taxonomy_change);
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
                        data = response.Data.message,
                        empty = document.createElement('option');

                        $select_terms.empty();


                    _.each(data, function(v,k,l){
                        var option = document.createElement('option');
                        if( self.has_selected( v, is_open ) ){
                            selected = ' selected="selected"';
                            value = v;
                        }
                        option.innerHTML = '<option value="'+k+'" '+selected+'>'+v+'</option>';
                        fragment.appendChild( option );
                    });

                    selected = selected === '' ? 'selected="selected"' : '';
                    empty.innerHTML = '<option value="" '+selected+'>Select '+$('.js-orbit-taxonomy').find('option:selected').text()+'</option>';

                    $select_terms.append( empty, fragment).show().val(value).trigger('change');
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