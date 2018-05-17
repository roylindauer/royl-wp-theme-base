<?php

namespace Royl\WpThemeBase\Filter\Query;

class Search extends \Royl\WpThemeBase\Filter\Query
{
    public function getFilter()
    {
        if ($this->getValue()) {
            return ['s' => $this->getValue()];
        }
        return [];
    }
}
