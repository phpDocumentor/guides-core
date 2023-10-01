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

namespace phpDocumentor\Guides;

use Exception;
use League\Flysystem\FilesystemInterface;
use phpDocumentor\Guides\Nodes\DocumentNode;
use phpDocumentor\Guides\Nodes\DocumentTree\DocumentEntryNode;
use phpDocumentor\Guides\Nodes\Node;
use phpDocumentor\Guides\Nodes\ProjectNode;

use function dirname;
use function trim;

class RenderContext
{
    private string $destinationPath;

    private DocumentNode $document;
    /** @var DocumentNode[] */
    private array $allDocuments;

    private function __construct(
        string $outputFolder,
        private readonly string $currentFileName,
        private readonly FilesystemInterface $origin,
        private readonly FilesystemInterface $destination,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly string $outputFormat,
        private readonly ProjectNode $projectNode,
    ) {
        $this->destinationPath = trim($outputFolder, '/');
    }

    /** @param DocumentNode[] $allDocumentNodes */
    public static function forDocument(
        DocumentNode $documentNode,
        array $allDocumentNodes,
        FilesystemInterface $origin,
        FilesystemInterface $destination,
        string $destinationPath,
        UrlGeneratorInterface $urlGenerator,
        string $ouputFormat,
        ProjectNode $projectNode,
    ): self {
        $self = new self(
            $destinationPath,
            $documentNode->getFilePath(),
            $origin,
            $destination,
            $urlGenerator,
            $ouputFormat,
            $projectNode,
        );

        $self->document = $documentNode;
        $self->allDocuments = $allDocumentNodes;

        return $self;
    }

    /**
     * @param TType $default
     *
     * @phpstan-return TType|string|Node
     *
     * @template TType as mixed
     */
    public function getVariable(string $variable, mixed $default = null): mixed
    {
        return $this->document->getVariable($variable, $default);
    }

    public function getLink(string $name): string
    {
        $link = $this->document->getLink($name);

        return $link ?? '';
    }

    public function canonicalUrl(string $url): string
    {
        return $this->urlGenerator->canonicalUrl($this->getDirName(), $url);
    }

    public function relativeDocUrl(string $filename, string|null $anchor = null): string
    {
        return $this->urlGenerator->generateOutputUrlFromDocumentPath(
            $this->getDirName(),
            $this->destinationPath,
            // Todo: it does not really make sense to treat relavtive paths that are found in the project node differently?
            $this->projectNode->findDocumentEntry($filename) !== null,
            $filename,
            $this->outputFormat,
            $anchor,
        );
    }

    private function getDirName(): string
    {
        $dirname = dirname($this->currentFileName);

        if ($dirname === '.') {
            return '';
        }

        return $dirname;
    }

    public function getCurrentFileName(): string
    {
        return $this->currentFileName;
    }

    /** @return array<string, string> */
    public function getLoggerInformation(): array
    {
        return [
            'rst-file' => $this->currentFileName,
        ];
    }

    public function getOrigin(): FilesystemInterface
    {
        return $this->origin;
    }

    public function getCurrentDocumentEntry(): DocumentEntryNode|null
    {
        return $this->projectNode->findDocumentEntry($this->currentFileName);
    }

    public function getDestinationPath(): string
    {
        return $this->destinationPath;
    }

    public function setDestinationPath(string $path): void
    {
        $this->destinationPath = $path;
    }

    public function getDestination(): FilesystemInterface
    {
        return $this->destination;
    }

    public function getCurrentFileDestination(): string
    {
        return $this->destinationPath . '/' . $this->currentFileName . '.' . $this->outputFormat;
    }

    public function getProjectNode(): ProjectNode
    {
        return $this->projectNode;
    }

    public function getDocumentNodeForEntry(DocumentEntryNode $entryNode): DocumentNode
    {
        foreach ($this->allDocuments as $child) {
            if ($child->getDocumentEntry() === $entryNode) {
                return $child;
            }
        }

        throw new Exception('No document was found for document entry ' . $entryNode->getFile());
    }

    public function getRootDocumentNode(): DocumentNode
    {
        return $this->getDocumentNodeForEntry($this->getProjectNode()->getRootDocumentEntry());
    }
}
