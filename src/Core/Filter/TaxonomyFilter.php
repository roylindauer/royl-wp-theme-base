<?php

namespace Royl\WpThemeBase\Core\Filter;

class TaxonomyFilter extends \Royl\WpThemeBase\Core\Filter\BaseFilter {
    
    public function doFilter() {
        $args = [];
        
        if ( $this->hasValue() ) {
            $args = [
                'tax_query' => [
                    [
                        'taxonomy' => $this->filter_query['taxonomy'],
                        'field' => 'slug',
                        'terms' => $_GET[ $this->field_params['name'] ],
                    ]
                ]
            ];
        }
        
        return $args;
    }
}