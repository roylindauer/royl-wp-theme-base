<?php

namespace Royl\WpThemeBase\Core\Filter\Modifiers;

class Search extends \Royl\WpThemeBase\Core\Filter\Modifiers\Base {
    
    public function doFilter() {
        return [ 's' => $this->field_params['value'] ];
    }
}