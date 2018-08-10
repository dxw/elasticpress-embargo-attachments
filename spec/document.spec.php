<?php

describe(\ElasticPress\MediaLibraryEmbargo\Document::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->document = new ElasticPress\MediaLibraryEmbargo\Document();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });
    
    it('is registerable', function () {
        expect($this->document)->to->be->an->instanceof(\Dxw\Iguana\Registerable::class);
    });
    
    describe('->register()', function () {
        it('adds the action', function () {
            WP_Mock::expectActionAdded('init', [$this->document, 'addEmbargoField']);
            $this->document->register();
        });
    });
    
    describe('->addEmbargoField()', function () {
        it('adds the field', function () {
            WP_Mock::wpFunction('acf_add_local_field_group', [
                'times' => 1,
                'args' => [
                    \WP_Mock\Functions::type('array')
                ]
            ]);
            $this->document->addEmbargoField();
        });
    });
});
