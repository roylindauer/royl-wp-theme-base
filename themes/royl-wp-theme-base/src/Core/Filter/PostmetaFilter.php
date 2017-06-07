<?php

namespace Royl\WpThemeBase\Core\Filter;

class PostmetaFilter extends \Royl\WpThemeBase\Core\Filter\BaseFilter {
    
    public function getFilter() {
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