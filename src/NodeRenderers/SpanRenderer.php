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

namespace phpDocumentor\Guides\NodeRenderers;

use phpDocumentor\Guides\Nodes\TitleNode;
use phpDocumentor\Guides\References\ResolvedReference;
use phpDocumentor\Guides\RenderContext;
use phpDocumentor\Guides\Span\LiteralToken;

interface SpanRenderer
{
    public function emphasis(string $text): string;

    public function strongEmphasis(string $text): string;

    public function nbsp(): string;

    public function br(): string;

    public function literal(LiteralToken $token): string;

    /**
     * @param string[] $attributes
     * @param string|TitleNode $title
     */
    public function link(RenderContext $environment, ?string $url, $title, array $attributes = []): string;

    public function escape(string $span): string;

    /**
     * @param string[] $value
     */
    public function reference(RenderContext $environment, ResolvedReference $reference, array $value): string;
}
