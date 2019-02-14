<?php declare(strict_types=1);

namespace Quanta\Container\Factories;

use Psr\Container\ContainerInterface;

use Quanta\Container\Compilation\Template;

final class Extension implements CompilableFactoryInterface
{
    /**
     * The factory to extend.
     *
     * @var callable
     */
    private $factory;

    /**
     * The extension.
     *
     * @var callable
     */
    private $extension;

    /**
     * Constructor.
     *
     * @param callable $factory
     * @param callable $extension
     */
    public function __construct(callable $factory, callable $extension)
    {
        $this->factory = $factory;
        $this->extension = $extension;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container)
    {
        $previous = ($this->factory)($container);

        return ($this->extension)($container, $previous);
    }

    /**
     * @inheritdoc
     */
    public function compiled(Template $template): string
    {
        $container = $template->containerVariableName();

        return $template
            ->withCallable('factory', $this->factory)
            ->withCallable('extension', $this->extension)
            ->strWithReturnf('($%s)($%s, ($%s)($%s))', ...[
                'extension',
                $container,
                'factory',
                $container,
            ]);
    }
}
