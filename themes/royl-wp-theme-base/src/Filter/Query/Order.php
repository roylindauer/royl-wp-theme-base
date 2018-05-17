<?php

namespace Royl\WpThemeBase\Filter\Query;

class Order extends \Royl\WpThemeBase\Filter\Query
{
    public function getFilter()
    {
        if(isset($this->filter_query['default']) && $this->getValue() === '') {
            return ['order' => $this->filter_query['default']];
        }
        if ($this->filter_query['value']) {
            return ['order' => $this->getValue()];
        }
        return [];
    }
}
