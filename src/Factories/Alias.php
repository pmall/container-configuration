<?php declare(strict_types=1);

namespace Quanta\Container\Factories;

use Psr\Container\ContainerInterface;

final class Alias implements CompilableFactoryInterface
{
    /**
     * The id of the container entry to alias.
     *
     * @var string
     */
    private $id;

    /**
     * Constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container)
    {
        return $container->get($this->id);
    }

    /**
     * @inheritdoc
     */
    public function compiled(Compiler $compiler): CompiledFactory
    {
        return new CompiledFactory('container', '', ...[
            sprintf('return $container->get(\'%s\');', addslashes($this->id)),
        ]);
    }
}
