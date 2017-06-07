<?php

namespace Royl\WpThemeBase\Core\Filter;

class BaseFilter {
    
    public $field_type = '';
    public $field_params = array();
    public $prefix = 'filter_';
    
    /**
     * Field Object
     * @var Royl\WpThemeBase\Core\Filter\Fields
     */
    public $Field;

    /**
     * Constructor
     */
    public function __construct( $params = [] ) {
        
        $this->field_type = $params['field']['type'];

        // shove all of the user defined field params into the field_params array
        // this array gets passed around a bit... 
        $this->field_params = $params['field'];

        $this->filter_query = $params['filter_query'];

        // Prefix field name to avoid query var clashes
        $this->field_params['name'] = $this->prefix . $this->field_params['name'];

        // Set field value if the filter is available in query params
        $this->field_params['value'] = $this->hasValue();

        // Init the field class
        $fieldclass = '\Royl\WpThemeBase\Core\Filter\Fields\\' . $this->field_type;
        $this->Field = new $fieldclass($this->field_params);
    }
    
    /**
     * Render the field
     */
    public function render(){
        $this->Field->render();
    }

    /**
     * Check if the field has a value in the query vars
     */
    public function hasValue() {
        return get_query_var($this->field_params['name'], false);
    }
    
    /**
     * This method should be overridden in your filter class
     * It must return query args to pass to WP_Query
     */
    public function doFilter() { }
}