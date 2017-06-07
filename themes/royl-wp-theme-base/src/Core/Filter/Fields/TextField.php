<?php

namespace Royl\WpThemeBase\Core\Filter\Fields;

class TextField extends \Royl\WpThemeBase\Core\Filter\Fields\BaseField{

    public function render() {
        $this->addClass('filter-field--text');
        $this->setPartial('fields/filter-text');
        parent::doRender();
    }
}