<?php

namespace Royl\WpThemeBase\Core\Filter;

class TaxonomyFilter extends \Royl\WpThemeBase\Core\Filter\BaseFilter
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
