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

/**
 * @extends CompoundNode<Node>
 */
class SeparatorNode extends CompoundNode
{
    protected int $level;

    public function __construct(int $level)
    {
        parent::__construct([]);

        $this->level = $level;
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}
