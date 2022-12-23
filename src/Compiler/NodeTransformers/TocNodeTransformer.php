<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Compiler\NodeTransformers;

use ArrayIterator;
use phpDocumentor\Guides\Compiler\NodeTransformer;
use phpDocumentor\Guides\Meta\Entry as MetaEntry;
use phpDocumentor\Guides\Meta\DocumentEntry;
use phpDocumentor\Guides\Meta\DocumentReferenceEntry;
use phpDocumentor\Guides\Meta\SectionEntry;
use phpDocumentor\Guides\Metas;
use phpDocumentor\Guides\Nodes\Node;
use phpDocumentor\Guides\Nodes\TableOfContents\Entry;
use phpDocumentor\Guides\Nodes\TocNode;
use Traversable;

/** @implements NodeTransformer<TocNode> */
final class TocNodeTransformer implements NodeTransformer
{
    private Metas $metas;

    public function __construct(Metas $metas)
    {
        $this->metas = $metas;
    }

    public function enterNode(Node $node): Node
    {
        $entries = [];

        foreach ($node->getFiles() as $file) {
            $metaEntry = $this->metas->findDocument(ltrim($file, '/'));
            if ($metaEntry instanceof DocumentEntry) {
                foreach ($this->buildFromDocumentEntry($metaEntry, 1, $node) as $entry) {
                    $entries[] = $entry;
                }
            }
        }

        return $node->withEntries($entries);
    }

    public function leaveNode(Node $node): ?Node
    {
        return $node;
    }

    public function supports(Node $node): bool
    {
        return $node instanceof TocNode;
    }

    /** @return iterable<Entry> */
    private function buildFromDocumentEntry(DocumentEntry $document, int $depth, TocNode $node): iterable
    {
        if ($depth > $node->getDepth()) {
            return new ArrayIterator([]);
        }

        //TocTree's of children are added, unless :titlesonly: is defined. Then only page titles are added, no sections
        //Under the section where they are define.
        //If Toctree is defined at level 2, and max is 3, only titles of the documents are added to the toctree.

        foreach ($document->getChildren() as $child) {
            yield from $this->buildLevel($child, $document, $depth, $node);
        }
    }

    /** @return Traversable<Entry> */
    private function buildFromSection(DocumentEntry $document, SectionEntry $entry, int $depth, TocNode $node): Traversable
    {
        if ($depth > $node->getDepth()) {
            return new ArrayIterator([]);
        }

        foreach ($entry->getChildren() as $child) {
            yield from $this->buildLevel($child, $document, $depth, $node);
        }
    }

    /** @return Traversable<Entry> */
    private function buildLevel(MetaEntry $child, DocumentEntry $document, int $depth, TocNode $node): Traversable
    {
        if ($child instanceof SectionEntry) {
            yield new Entry(
                $document->getFile(),
                $child->getTitle(),
                iterator_to_array($this->buildFromSection($document, $child, ++$depth, $node), false)
            );
        }

        if ($child instanceof DocumentReferenceEntry) {
            $subDocument = $this->metas->findDocument($child->getFile());
            if ($subDocument instanceof DocumentEntry) {
                yield from $this->buildFromDocumentEntry($subDocument, ++$depth, $node);
            }
        }
    }
}
