<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\Container\Values\Value;
use Quanta\Container\Values\ValueInterface;

describe('Value', function () {

    beforeEach(function () {

        $this->container = mock(ContainerInterface::class);

    });

    context('when the value is a boolean', function () {

        context('when the value is true', function () {

            beforeEach(function () {

                $this->value = new Value(true);

            });

            it('should implement ValueInterface', function () {

                expect($this->value)->toBeAnInstanceOf(ValueInterface::class);

            });

            describe('->value()', function () {

                it('should return true', function () {

                    $test = $this->value->value($this->container->get());

                    expect($test)->toBeTruthy();

                });

            });

            it('should return true string', function () {

                $test = $this->value->str('container');

                expect($test)->toEqual('true');

            });

        });

        context('when the value is false', function () {

            beforeEach(function () {

                $this->value = new Value(false);

            });

            it('should implement ValueInterface', function () {

                expect($this->value)->toBeAnInstanceOf(ValueInterface::class);

            });

            describe('->value()', function () {

                it('should return false', function () {

                    $test = $this->value->value($this->container->get());

                    expect($test)->toBeFalsy();

                });

            });

            it('should return false string', function () {

                $test = $this->value->str('container');

                expect($test)->toEqual('false');

            });

        });

    });

    context('when the value is an integer', function () {

        beforeEach(function () {

            $this->value = new Value(1);

        });

        it('should implement ValueInterface', function () {

            expect($this->value)->toBeAnInstanceOf(ValueInterface::class);

        });

        describe('->value()', function () {

            it('should return the integer', function () {

                $test = $this->value->value($this->container->get());

                expect($test)->toEqual(1);

            });

        });

        describe('->str()', function () {

            it('should return the integer string', function () {

                $test = $this->value->str('container');

                expect($test)->toEqual('1');

            });

        });

    });

    context('when the value is a float', function () {

        beforeEach(function () {

            $this->value = new Value(1.1);

        });

        it('should implement ValueInterface', function () {

            expect($this->value)->toBeAnInstanceOf(ValueInterface::class);

        });

        describe('->value()', function () {

            it('should return the float', function () {

                $test = $this->value->value($this->container->get());

                expect($test)->toEqual(1.1);

            });

        });

        describe('->str()', function () {

            it('should return the float string', function () {

                $test = $this->value->str('container');

                expect($test)->toEqual('1.1');

            });

        });

    });

    context('when the value is a string', function () {

        beforeEach(function () {

            $this->value = new Value('value');

        });

        it('should implement ValueInterface', function () {

            expect($this->value)->toBeAnInstanceOf(ValueInterface::class);

        });

        describe('->value()', function () {

            it('should return the string', function () {

                $test = $this->value->value($this->container->get());

                expect($test)->toEqual('value');

            });

        });

        describe('->str()', function () {

            it('should return the string with quotes', function () {

                $test = $this->value->str('container');

                expect($test)->toEqual('\'value\'');

            });

        });

    });

    context('when the value is an array', function () {

        it('should throw an InvalidArgumentException', function () {

            $test = function () { new Value([]); };

            expect($test)->toThrow(new InvalidArgumentException);

        });

    });

    context('when the value is an object', function () {

        beforeEach(function () {

            $this->object = new class {};

            $this->value = new Value($this->object);

        });

        it('should implement ValueInterface', function () {

            expect($this->value)->toBeAnInstanceOf(ValueInterface::class);

        });

        describe('->value()', function () {

            it('should return the object', function () {

                $test = $this->value->value($this->container->get());

                expect($test)->toBe($this->object);

            });

        });

        describe('->str()', function () {

            it('should throw a LogicException', function () {

                $test = function () { $this->value->str('container'); };

                expect($test)->toThrow(new LogicException);

            });

        });

    });

    context('when the value is a resource', function () {

        beforeEach(function () {

            $this->resource = tmpfile();

            $this->value = new Value($this->resource);

        });

        it('should implement ValueInterface', function () {

            expect($this->value)->toBeAnInstanceOf(ValueInterface::class);

        });

        describe('->value()', function () {

            it('should return the resource', function () {

                $test = $this->value->value($this->container->get());

                expect($test)->toEqual($this->resource);

            });

        });

        describe('->str()', function () {

            it('should throw a LogicException', function () {

                $test = function () { $this->value->str('container'); };

                expect($test)->toThrow(new LogicException);

            });

        });

    });

    context('when the value is null', function () {

        beforeEach(function () {

            $this->value = new Value(null);

        });

        it('should implement ValueInterface', function () {

            expect($this->value)->toBeAnInstanceOf(ValueInterface::class);

        });

        describe('->value()', function () {

            it('should return null', function () {

                $test = $this->value->value($this->container->get());

                expect($test)->toBeNull();

            });

        });

        describe('->str()', function () {

            it('should return null', function () {

                $test = $this->value->str('container');

                expect($test)->toEqual('null');

            });

        });

    });

});
