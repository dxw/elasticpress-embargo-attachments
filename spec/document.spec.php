<?php

describe(\ElasticPress\MediaLibraryEmbargo\Document::class, function () {
    beforeEach(function () {
        $this->document = new ElasticPress\MediaLibraryEmbargo\Document();
    });

    
    it('is registerable', function () {
        expect($this->document)->toBeAnInstanceOf(\Dxw\Iguana\Registerable::class);
    });
    
    describe('->register()', function () {
        it('adds the action', function () {
            allow('add_action')->toBeCalled();
            expect('add_action')->toBeCalled()->once()->with('init', [$this->document, 'addEmbargoField']);
            $this->document->register();
        });
    });
    
    describe('->addEmbargoField()', function () {
        it('adds the field', function () {
            allow('function_exists')->toBeCalled()->andReturn(true);
            allow('acf_add_local_field_group')->toBeCalled();
            expect('acf_add_local_field_group')->toBeCalled()->once()->with(\Kahlan\Arg::toBeAn('array'));

            $this->document->addEmbargoField();
        });
    });
});
