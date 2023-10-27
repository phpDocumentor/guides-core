<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Nodes\Inline;

/**
 * Represents a link to document
 *
 * Supported formats
 * :doc:`foo`
 * :doc:`domain:foo`
 * :doc:`foo/subdoc#anchor`
 * :doc:`custom text <foo>`
 * :doc:`custom text <domain:foo/subdoc#anchor>`
 */
class DocReferenceNode extends AbstractLinkInlineNode implements CrossReferenceNode
{
    final public const TYPE = 'doc';

    public function __construct(
        string $targetDocument,
        string $value = '',
        private readonly string $intersphinxDomain = '',
    ) {
        parent::__construct(self::TYPE, $targetDocument, $value);
    }

    public function getIntersphinxDomain(): string
    {
        return $this->intersphinxDomain;
    }
}
