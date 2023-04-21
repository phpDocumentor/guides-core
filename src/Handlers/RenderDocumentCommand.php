<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Handlers;

use phpDocumentor\Guides\Nodes\DocumentNode;
use phpDocumentor\Guides\RenderContext;

final class RenderDocumentCommand
{
    public function __construct(private DocumentNode $document, private RenderContext $renderContext)
    {
    }

    public function getDocument(): DocumentNode
    {
        return $this->document;
    }

    public function getContext(): RenderContext
    {
        return $this->renderContext;
    }

    public function getFileDestination(): string
    {
        return $this->getContext()->getCurrentFileDestination();
    }
}
