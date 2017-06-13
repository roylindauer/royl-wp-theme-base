<?php

namespace Royl\WpThemeBase\Filter\Field;

class Text extends \Royl\WpThemeBase\Filter\Field
{
    public function render()
    {
        $this->addClass('filter-field--text');
        $this->setPartial('fields/filter-text');
        parent::doRender();
    }
}
