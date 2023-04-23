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

namespace phpDocumentor\Guides\NodeRenderers\Html;

use phpDocumentor\Guides\NodeRenderers\NodeRenderer;
use phpDocumentor\Guides\Nodes\Node;
use phpDocumentor\Guides\Nodes\TocNode;
use phpDocumentor\Guides\RenderContext;
use phpDocumentor\Guides\TemplateRenderer;
use Webmozart\Assert\Assert;

/** @implements NodeRenderer<TocNode> */
final class TocNodeRenderer implements NodeRenderer
{
    public function __construct(private readonly TemplateRenderer $renderer)
    {
    }

    public function render(Node $node, RenderContext $renderContext): string
    {
        Assert::isInstanceOf($node, TocNode::class);

        if ($node->getOption('hidden', false)) {
            return '';
        }

        return $this->renderer->renderTemplate(
            $renderContext,
            'body/toc/toc.html.twig',
            ['node' => $node],
        );
    }

    public function supports(Node $node): bool
    {
        return $node instanceof TocNode;
    }
}
