<?php

namespace Royl\WpThemeBase\Filter\Query;

class Postmeta extends \Royl\WpThemeBase\Filter\Query
{
    public function getFilter()
    {
        $args = [];
        if ($this->getValue()) {
            $args = [
                'meta_query' => [
                    [
                        'key' => $this->filter_query['key'],
                        'value' => $this->getValue(),
                        'compare' => $this->filter_query['compare']
                    ]
                ]
            ];
        }
        
        return $args;
    }
}
