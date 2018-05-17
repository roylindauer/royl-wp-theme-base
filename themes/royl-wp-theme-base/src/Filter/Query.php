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
     * @var string
     */
    public $value = false;

    /**
     * Constructor
     */
    public function __construct($field_name, $params = [])
    {
        $this->filter_query = $params;
        $this->filter_query['value'] = \Royl\WpThemeBase\Filter\Util::getQueryVar($field_name);

        $_value = false;

        if ($this->filter_query['value'] && isset($this->filter_query['value_format'])) {
            $_value = sprintf( $this->filter_query['value_format'], $this->filter_query['value'] );
        } else if ($this->filter_query['value']) {
            $_value = $this->filter_query['value'];
        }

        $this->setValue($_value);
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }
    
    /**
     * This method should be overridden in your filter class
     * It must return query args to pass to WP_Query
     */
    public function getFilter()
    {
    }
}
