<?php

namespace Royl\WpThemeBase\Core\Filter;

class BaseFilter {
    
    public $field_type = '';
    public $field_params = array();
    
    /**
     * Field Object
     * @var Royl\WpThemeBase\Core\Filter\Fields
     */
    public $Field;

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

        $fieldclass = '\Royl\WpThemeBase\Core\Filter\Fields\\' . $this->field_type;
        $this->Field = new $fieldclass($this->field_params);
    }
    
    public function render(){
        $this->Field->render();
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