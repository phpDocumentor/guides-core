<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\ReferenceResolvers;

use phpDocumentor\Guides\Interlink\InventoryRepository;
use phpDocumentor\Guides\Nodes\Inline\CrossReferenceNode;
use phpDocumentor\Guides\Nodes\Inline\LinkInlineNode;
use phpDocumentor\Guides\RenderContext;

class InterlinkReferenceResolver implements ReferenceResolver
{
    public final const PRIORITY = 50;

    public function __construct(
        private readonly InventoryRepository $inventoryRepository,
    ) {
    }

    public function resolve(LinkInlineNode $node, RenderContext $renderContext, Messages $messages): bool
    {
        if (!$node instanceof CrossReferenceNode || $node->getInterlinkDomain() === '') {
            return false;
        }

        $inventory = $this->inventoryRepository->getInventory($node, $renderContext, $messages);
        if ($inventory === null) {
            return false;
        }

        $link = $this->inventoryRepository->getLink($node, $renderContext, $messages);
        if ($link === null) {
            return false;
        }

        $node->setUrl($inventory->getBaseUrl() . $link->getPath());
        if ($node->getValue() === '') {
            $node->setValue($link->getTitle());
        }

        return true;
    }

    public static function getPriority(): int
    {
        return self::PRIORITY;
    }
}
