<?php
namespace ElasticPress\MediaLibraryEmbargo;

use \phpmock\mockery\PHPMockery;

describe(\ElasticPress\MediaLibraryEmbargo\Search::class, function () {
    beforeEach(function () {
        $this->search = new \ElasticPress\MediaLibraryEmbargo\Search();
    });
    
    it('is registerable', function () {
        expect($this->search)->toBeAnInstanceOf(\Dxw\Iguana\Registerable::class);
    });
    
    describe('->register()', function () {
        it('adds the action', function () {
            allow('add_action')->toBeCalled();
            expect('add_action')->toBeCalled()->once()->with('pre_get_posts', [$this->search, 'removeEmbargoDocs']);

            $this->search->register();
        });
    });
    
    describe('->removeEmbargoDocs()', function () {
        beforeEach(function () {
            $this->query = \Kahlan\Plugin\Double::instance([
                'class' => 'WP_Query'
            ]);
        });
        context('in admin pages', function () {
            it('does nothing', function () {
                allow('is_admin')->toBeCalled()->andReturn(true);

                $this->search->removeEmbargoDocs($this->query);
            });
        });
        context('is an archive', function () {
            it('does nothing', function () {
                allow('is_admin')->toBeCalled()->andReturn(false);
                allow('is_archive')->toBeCalled()->andReturn(true);

                $this->search->removeEmbargoDocs($this->query);
            });
        });
        context('not a search', function () {
            it('does nothing', function () {
                allow('is_admin')->toBeCalled()->andReturn(false);
                allow('is_archive')->toBeCalled()->andReturn(false);

                allow($this->query)->toReceive('is_search')
                    ->andReturn(false);
                $this->search->removeEmbargoDocs($this->query);
            });
        });
        context('is a search', function () {
            it('sets the meta_query', function () {
                allow('is_admin')->toBeCalled()->andReturn(false);
                allow('is_archive')->toBeCalled()->andReturn(false);
                allow($this->query)->toReceive('is_search')->andReturn(true);
                allow('DateTime')->toReceive('format')->with('Y-m-d H:i:s')->andReturn('2018-03-01 12:00:01');

                allow($this->query)->toReceive('set');
                expect($this->query)->toReceive('set')
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
                                'value' => '2018-03-01 12:00:01',
                                'compare' => '<=',
                                'type' => 'DATETIME'
                            ]
                        ]
                    ]);
                $this->search->removeEmbargoDocs($this->query);
            });
        });
    });
});
