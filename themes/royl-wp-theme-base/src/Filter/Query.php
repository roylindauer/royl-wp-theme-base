<?php

namespace Royl\WpThemeBase\Filter;

class Query
{
    public $filter_query = [];
    
    /**
     * Field Object
     * @var Royl\WpThemeBase\Core\Filter\Fields
     */
    public $Field;

    /**
     * Constructor
     */
    public function __construct($field_name, $params = [])
    {
        $this->filter_query = $params;
        $this->filter_query['value'] = \Royl\WpThemeBase\Filter\Util::getQueryVar($field_name);
    }
    
    /**
     * This method should be overridden in your filter class
     * It must return query args to pass to WP_Query
     */
    public function getFilter()
    {
    }
}
