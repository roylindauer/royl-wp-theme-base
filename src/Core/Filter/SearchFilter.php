<?php

namespace Royl\WpThemeBase\Core\Filter;

class SearchFilter extends \Royl\WpThemeBase\Core\Filter\BaseFilter {
    
    public function doFilter() {
        return [ 's' => $this->field_params['value'] ];
    }
}