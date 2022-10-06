<?php

namespace ElasticPress\MediaLibraryEmbargo;

class Document implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('init', [$this, 'addEmbargoField']);
    }
    
    public function addEmbargoField()
    {
        if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group([
            'key' => 'group_5ab246239058c',
            'title' => 'Embargo datetime',
            'fields' => [
                [
                    'key' => 'field_5ab24629f9d23',
                    'label' => 'Embargo until',
                    'name' => 'embargo_datetime',
                    'type' => 'date_time_picker',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'display_format' => 'F j, Y g:i a',
                    'return_format' => 'd/m/Y g:i a',
                    'first_day' => 1,
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'attachment',
                        'operator' => '==',
                        'value' => 'all',
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ]);

        endif;
    }
}
