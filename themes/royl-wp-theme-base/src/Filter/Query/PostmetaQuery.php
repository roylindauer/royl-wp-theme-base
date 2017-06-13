<?php

namespace Royl\WpThemeBase\Filter\Query;

class PostmetaQuery extends \Royl\WpThemeBase\Filter\Query\Query
{
    public function getFilter()
    {
        $args = [];
        if ($this->field_params['value']) {
            $args = [
                'meta_query' => [
                    [
                        'key' => $this->filter_query['key'],
                        'value' => $this->field_params['value'],
                        'compare' => $this->filter_query['compare']
                    ]
                ]
            ];
        }
        
        return $args;
    }
}
