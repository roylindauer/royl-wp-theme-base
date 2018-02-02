<?php

namespace Royl\WpThemeBase\Filter\Query;

class Order extends \Royl\WpThemeBase\Filter\Query
{
    public function getFilter()
    {
        if ($this->filter_query['value']) {
            return ['order' => $this->filter_query['value']];
        }
        return [];
    }
}
