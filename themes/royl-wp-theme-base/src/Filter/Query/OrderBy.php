<?php

namespace Royl\WpThemeBase\Filter\Query;

class OrderBy extends \Royl\WpThemeBase\Filter\Query
{
    public function getFilter()
    {
        if ( isset( $this->filter_query['value'] ) && $this->filter_query['value'] == 'rand' && isset( $this->filter_query['rand_seed'] ) ) {
            return ['orderby' => sprintf('RAND(%d)', $this->filter_query['rand_seed'])];
        }

        if ($this->filter_query['value']) {
            return ['orderby' => $this->filter_query['value']];
        }
        return [];
    }
}
