<?php

namespace Royl\WpThemeBase\Core\Filter;

class SearchFilter extends \Royl\WpThemeBase\Core\Filter\BaseFilter {
    
    public function getFilter() {
    	if ($this->field_params['value']) {
	        return ['s' => $this->field_params['value']];
    	}
    }
}