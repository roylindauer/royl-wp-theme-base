<?php

namespace Royl\WpThemeBase\Filter\Query;

class Post extends \Royl\WpThemeBase\Filter\Query
{
    public function getFilter()
    {
        $args = [];
        if(empty($this->filter_query['']) && !empty($this->filter_query['post_type'])) {
            $args = [
                'post_type' => $this->filter_query['post_type'],
                'post_status' => 'publish'
            ];
        }

        if ($this->getValue()) {
            $args = [
                'post_type' => $this->getValue(),
                'post_status' => 'publish'
            ];
        }
        return $args;
    }
}
