<?php

namespace Royl\WpThemeBase\Filter\Query;

class OrderBy extends \Royl\WpThemeBase\Filter\Query
{
    public function getFilter()
    {
        if ( $this->getValue() == 'rand' && isset( $this->filter_query['rand_seed'] ) ) {
            return ['orderby' => sprintf('RAND(%d)', $this->filter_query['rand_seed'])];
        }
        
        if(isset($this->filter_query['default']) && $this->getValue() === '') {
            return ['orderby' => $this->filter_query['default']];
        }

        if ($this->getValue()) {
            return ['orderby' => $this->getValue()];
        }
        return [];
    }
}
