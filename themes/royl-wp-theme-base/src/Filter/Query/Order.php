<?php

namespace Royl\WpThemeBase\Filter\Query;

class Order extends \Royl\WpThemeBase\Filter\Query
{
    public function getFilter()
    {
        $res = array();
        if ($this->filter_query['value']) {
            $res = ['order' => $this->filter_query['value']];
        } else if (isset($this->filter_query['default'])) {
            $res = ['order' => $this->filter_query['default']];
        }
        return $res;
    }
}
