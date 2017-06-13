<?php

namespace Royl\WpThemeBase\Filter\Field;

class Select extends \Royl\WpThemeBase\Filter\Field
{
    public function render()
    {
        $this->addClass('filter-field--select');
        $this->setPartial('fields/filter-select');
        parent::doRender();
    }
}
