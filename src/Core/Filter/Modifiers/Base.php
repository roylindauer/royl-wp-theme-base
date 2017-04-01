<?php

namespace Royl\WpThemeBase\Core\Filter\Modifiers;

class Base {
    
    public $field_type = '';
    public $field_params = array();
    
    public function __construct( $params = array() ) {
        
        $this->field_type = $params['field']['type'];

        // shove all of the user defined field params into the field_params array
        // this array gets passed around a bit... 
        $this->field_params = $params['field'];

        $this->filter_query = $params['filter_query'];

        // Prepend filter_ to field name to avoid clashes and to keep things "namespaced"
        $this->field_params['name'] = 'filter_' . $this->field_params['name'];

        // Set field value if the filter is available in $_GET
        if ( $this->hasValue() ) {
            $this->field_params['value'] = sanitize_text_field( $_GET[ $this->field_params['name'] ] );
        }
    }
    
    public function render(){
        $fieldclass = '\Royl\WpThemeBase\Core\Filter\Fields\\' . $this->field_type;
        $field = new $fieldclass( $this->field_params );
        $field->render();
    }

    public function hasValue() {
        if ( isset( $_GET[ $this->field_params['name'] ] ) && !empty( $_GET[ $this->field_params['name'] ] ) ) {
            return true;
        }
        return false;
    }
    
    public function doFilter() {
        return [];
    }
}