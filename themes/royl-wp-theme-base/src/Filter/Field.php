<?php

namespace Royl\WpThemeBase\Filter;

use Royl\WpThemeBase\Wp;
use Royl\WpThemeBase\Util;

class Field
{
    /**
     * Array of field parameter
     * Sets some default classes for the field
     * @var array
     */
    public $field_params = [
        'classes' => [ 'filter-field' ],
        'placeholder' => ''
    ];

    /**
     * Partial to use to render FieldClass
     * @var string
     */
    public $partial = '';
    
    /**
     * [__construct description]
     * @param array $params [description]
     */
    public function __construct($params = [])
    {
        $this->field_params = array_merge( $this->field_params, $params );
        $this->field_params['value'] = \Royl\WpThemeBase\Filter\Util::getQueryVar($this->field_params['name']);
    }
    
    /**
     * Render the field
     * @return [type] [description]
     */
    public function doRender()
    {
        $field_container_classes = [ 'filter-container' ];
        $field_container_classes = apply_filters( 'filter_field_container_classes', $field_container_classes );

        $field_wrapper_classes = [ 'filter-wrapper' ];
        $field_wrapper_classes = apply_filters( 'filter_field_wrapper_classes', $field_wrapper_classes );

        $field_label_classes = [ 'filter-label' ];
        $field_label_classes = apply_filters( 'filter_field_label_classes', $field_label_classes );

        do_action('royl_before_render_filter_field_' . $this->field_params['name']);

        // Field Container
        echo '<div class="' . implode( ' ', $field_container_classes ) . '">';

        // Field Label
        if (isset($this->field_params['label'])) {
            echo '<label class="' . implode( ' ', $field_label_classes ) . '" for="' . $this->field_params['id'] . '">' . Util\Text::translate( $this->field_params['label'] ) . '</label>';
        }

        // Field Wrapper
        echo '<div class="' . implode( ' ', $field_wrapper_classes ) . '">';

        do_action('royl_before_render_filter_field_wrapper_' . $this->field_params['name']);
        $this->processFieldClasses();
        Wp\Template::load('filter/' . $this->partial, [ 'field' => $this->field_params ]);
        do_action('royl_after_render_filter_field_wrapper_' . $this->field_params['name']);
        
        // Close Field Wrapper
        echo '</div>';

        // Close Field Container
        echo '</div>';

        do_action('royl_after_render_filter_field_' . $this->field_params['name']);
    }

    /**
     * Set the partial to render
     * @param string $partial [description]
     */
    public function setPartial($partial = '')
    {
        $this->partial = $partial;
    }

    /**
     * Add CSS classes to field_params class array
     * @param string $class [description]
     */
    public function addClass($class = '')
    {
        $this->field_params['classes'][] = $class;
    }

    /**
     * Convert array of classes intro string
     * @return [type] [description]
     */
    private function processFieldClasses()
    {
        $this->field_params['classes'] = join(' ', $this->field_params['classes']);
    }
}
