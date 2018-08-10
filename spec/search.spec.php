<?php
namespace ElasticPress\MediaLibraryEmbargo;

use \phpmock\mockery\PHPMockery;

describe(\ElasticPress\MediaLibraryEmbargo\Search::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->search = new \ElasticPress\MediaLibraryEmbargo\Search();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });
    
    it('is registerable', function () {
        expect($this->search)->to->be->an->instanceof(\Dxw\Iguana\Registerable::class);
    });
    
    describe('->register()', function () {
        it('adds the action', function () {
            \WP_Mock::expectActionAdded('pre_get_posts', [$this->search, 'removeEmbargoDocs']);
            $this->search->register();
        });
    });
    
    describe('->removeEmbargoDocs()', function () {
        beforeEach(function () {
            $this->query = \Mockery::mock(\WP_Query::class);
        });
        context('in admin pages', function () {
            it('does nothing', function () {
                \WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => true
                ]);
                $this->search->removeEmbargoDocs($this->query);
            });
        });
        context('is an archive', function () {
            it('does nothing', function () {
                \WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => false
                ]);
                \WP_Mock::wpFunction('is_archive', [
                    'times' => 1,
                    'return' => true
                ]);
                $this->search->removeEmbargoDocs($this->query);
            });
        });
        context('not a search', function () {
            it('does nothing', function () {
                \WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => false
                ]);
                \WP_Mock::wpFunction('is_archive', [
                    'times' => 1,
                    'return' => false
                ]);
                $this->query->shouldReceive('is_search')
                    ->once()
                    ->andReturn(false);
                $this->search->removeEmbargoDocs($this->query);
            });
        });
        context('is a search', function () {
            it('sets the meta_query', function () {
                \WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => false
                ]);
                \WP_Mock::wpFunction('is_archive', [
                    'times' => 1,
                    'return' => false
                ]);
                $this->query->shouldReceive('is_search')
                    ->once()
                    ->andReturn(true);
                
                PHPMockery::mock(__NAMESPACE__, 'date')->andReturnUsing(function ($a) {
                    if ($a === 'Y-m-d H:i:s') {
                        return '2018-03-01 12:00:01';
                    }
                    // Returns false on error
                    return false;
                });

                $this->query->shouldReceive('set')
                    ->once()
                    ->with('meta_query', [
                        [
                            'relation' => 'OR',
                            [
                                'key' => 'embargo_datetime',
                                'compare' => 'NOT EXISTS'
                            ],
                            [
                                'key' => 'embargo_datetime',
                                'compare' => '<=',
                                'value' => '2018-03-01 12:00:01',
                                'type' => 'DATETIME'
                            ]
                        ]
                    ]);
                $this->search->removeEmbargoDocs($this->query);
            });
        });
    });
});
