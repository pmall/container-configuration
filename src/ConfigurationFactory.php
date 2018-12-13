<?php declare(strict_types=1);

namespace Quanta\Container;

use Quanta\Utils\VendorDirectory;

final class ConfigurationFactory
{
    /**
     * Return a php file configuration from the given glob patterns.
     *
     * @param string ...$patterns
     * @return \Quanta\Container\ConfigurationInterface
     */
    public function files(string ...$patterns): ConfigurationInterface
    {
        return new PhpFileConfiguration(...$patterns);
    }

    /**
     * Return a class name collection configuration from the given vendor dir
     * path and arguments.
     *
     * @param string $path
     * @param string ...$xs
     * @return \Quanta\Container\ConfigurationInterface
     */
    public function vendor(string $path, string ...$xs): ConfigurationInterface
    {
        $collection = new VendorDirectory($path);

        return new ClassNameCollectionConfiguration($collection, ...$xs);
    }
}