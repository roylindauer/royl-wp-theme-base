<?php

namespace Royl\WpThemeBase\Filter;

class Query
{
    
    public $field_type = '';
    public $field_params = [];
    
    /**
     * Field Object
     * @var Royl\WpThemeBase\Core\Filter\Fields
     */
    public $Field;

    /**
     * Constructor
     */
    public function __construct($params = [])
    {
        $this->field_type = $params['field']['type'];

        // shove all of the user defined field params into the field_params array
        // this array gets passed around a bit...
        $this->field_params = $params['field'];
        $this->filter_query = $params['filter_query'];

        // Set field value if the filter is available in query params
        // Use this in child classes to get the value passed in query vars
        $this->field_params['value'] = $_REQUEST[$this->field_params['name']];

        // Init the field class
        $fieldclass = '\Royl\WpThemeBase\Filter\Field\\' . $this->field_type;
        $this->Field = new $fieldclass($this->field_params);
    }
    
    /**
     * Render the field
     */
    public function render()
    {
        $this->Field->render();
    }
    
    /**
     * This method should be overridden in your filter class
     * It must return query args to pass to WP_Query
     */
    public function getFilter()
    {
    }
}
