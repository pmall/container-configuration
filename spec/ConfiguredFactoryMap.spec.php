<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\Container\ConfiguredFactoryMap;
use Quanta\Container\Maps\FactoryMapInterface;
use Quanta\Container\Passes\ProcessingPassInterface;
use Quanta\Container\Factories\Tag;
use Quanta\Container\Factories\Alias;

require_once __DIR__ . '/.test/classes.php';

describe('ConfiguredFactoryMap', function () {

    beforeEach(function () {

        $this->delegate = mock(FactoryMapInterface::class);
        $this->pass = mock(ProcessingPassInterface::class);

        $this->map = new ConfiguredFactoryMap($this->delegate->get(), $this->pass->get());

    });

    it('should implement FactoryMapInterface', function () {

        expect($this->map)->toBeAnInstanceOf(FactoryMapInterface::class);

    });

    describe('->map()', function () {

        it('should return the factory map', function () {

            $test = $this->map->map();

            expect($test)->toBe($this->delegate->get());

        });

    });

    describe('->pass()', function () {

        it('should return the processing pass', function () {

            $test = $this->map->pass();

            expect($test)->toBe($this->pass->get());

        });

    });

    describe('->factories()', function () {

        it('should process the factories provided by the delegate with the processing pass', function () {

            $this->delegate->factories->returns([
                'id1' => $factory1 = new Test\TestFactory('factory1'),
                'id2' => $factory2 = new Test\TestFactory('factory2'),
                'id3' => $factory3 = new Test\TestFactory('factory3'),
            ]);

            $this->pass->aliases->with('id1')->returns(['alias1', 'alias3']);
            $this->pass->aliases->with('id2')->returns([]);
            $this->pass->aliases->with('id3')->returns(['alias2']);

            $this->pass->tags->with('id1', 'id2', 'id3')->returns([
                'tag1' => ['id1', 'id2'],
                'tag2' => [],
                'tag3' => ['id3'],
            ]);

            $this->pass->processed->with('id1', $factory1)->returns($processed1 = function () {});
            $this->pass->processed->with('id2', $factory2)->returns($processed2 = function () {});
            $this->pass->processed->with('id3', $factory3)->returns($processed3 = function () {});
            $this->pass->processed->with('alias1', new Alias('id1'))->returns($processed4 = function () {});
            $this->pass->processed->with('alias2', new Alias('id3'))->returns($processed5 = function () {});
            $this->pass->processed->with('alias3', new Alias('id1'))->returns($processed6 = function () {});
            $this->pass->processed->with('tag1', new Tag('id1', 'id2'))->returns($processed7 = function () {});
            $this->pass->processed->with('tag2', new Tag)->returns($processed8 = function () {});
            $this->pass->processed->with('tag3', new Tag('id3'))->returns($processed9 = function () {});

            $test = $this->map->factories();

            expect($test)->toBeAn('array');
            expect($test)->toHaveLength(9);
            expect($test['id1'])->toBe($processed1);
            expect($test['id2'])->toBe($processed2);
            expect($test['id3'])->toBe($processed3);
            expect($test['alias1'])->toBe($processed4);
            expect($test['alias2'])->toBe($processed5);
            expect($test['alias3'])->toBe($processed6);
            expect($test['tag1'])->toBe($processed7);
            expect($test['tag2'])->toBe($processed8);
            expect($test['tag3'])->toBe($processed9);

        });

    });

});
