<?php

namespace ElasticPress\MediaLibraryEmbargo;

class Search implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('pre_get_posts', [$this, 'removeEmbargoDocs']);
    }
    
    public function removeEmbargoDocs(\WP_Query $query)
    {
        if (is_admin()) {
            return;
        }
        
        if (is_archive()) {
            return;
        }
        
        if (!$query->is_search()) {
            return;
        }
        
        $dateTime = new \DateTime("now", new \DateTimeZone('Europe/London'));

        $query->set('meta_query', [
            [
                'relation' => 'OR',
                [
                    'key' => 'embargo_datetime',
                    'compare' => 'NOT EXISTS'
                ],
                [
                    'key' => 'embargo_datetime',
                    'value' => $dateTime->format('Y-m-d H:i:s'),
                    'compare' => '<=',
                    'type' => 'DATETIME'
                ]
            ]
        ]);
    }
}
