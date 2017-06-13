<?php

namespace Royl\WpThemeBase\Filter\Query;

class Taxonomy extends \Royl\WpThemeBase\Filter\Query
{
    public function getFilter()
    {
        $args = [];
        if ($this->field_params['value']) {
            $args = [
                'tax_query' => [
                    [
                        'taxonomy' => $this->filter_query['taxonomy'],
                        'field' => 'slug',
                        'terms' => $this->field_params['value'],
                    ]
                ]
            ];
        }
        return $args;
    }
}
