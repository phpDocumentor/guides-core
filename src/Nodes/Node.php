<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link https://phpdoc.org
 */

namespace phpDocumentor\Guides\Nodes;

interface Node
{
    /**
     * @return array<string, scalar|null>
     */
    public function getOptions(): array;

    /**
     * @param array<string, scalar|null> $options
     * @return Node
     */
    public function withOptions(array $options): Node;

    public function hasOption(string $name): bool;

    /** @param mixed $value */
    public function setValue($value): void;

    /** @return mixed */
    public function getValue();

    /**
     * @return string[]
     */
    public function getClasses(): array;

    /**
     * @param string[] $classes
     */
    public function setClasses(array $classes): void;

    public function getClassesString(): string;
}
