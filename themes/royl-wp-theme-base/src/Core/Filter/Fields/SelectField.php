<?php

namespace Royl\WpThemeBase\Core\Filter\Fields;

class SelectField extends \Royl\WpThemeBase\Core\Filter\Fields\BaseField {

    public function render() {
        $this->addClass('filter-field--select');
        $this->setPartial('fields/filter-select');
        parent::doRender();
    }
}