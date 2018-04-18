<?php

namespace Royl\WpThemeBase\Filter\Query;

class OrderBy extends \Royl\WpThemeBase\Filter\Query
{
    public function getFilter()
    {
        $res = array();

        // Order by can be RAND(SEED),  a user supplied value, or a default value if avail..
        if ( isset( $this->filter_query['value'] ) && $this->filter_query['value'] == 'rand' && isset( $this->filter_query['rand_seed'] ) ) {
            $res = ['orderby' => sprintf('RAND(%d)', $this->filter_query['rand_seed'])];
        } else if ($this->filter_query['value']) {
            $res = ['orderby' => $this->filter_query['value']];
        } else if (isset($this->filter_query['default'])) {
            $res = ['orderby' => $this->filter_query['default']];
        }
        return $res;
    }
}
