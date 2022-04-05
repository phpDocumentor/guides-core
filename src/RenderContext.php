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

use League\Flysystem\FilesystemInterface;
use phpDocumentor\Guides\Meta\Entry;
use phpDocumentor\Guides\Nodes\DocumentNode;

use function array_shift;
use function dirname;
use function strtolower;
use function trim;

class RenderContext
{
    /** @var UrlGenerator */
    private $urlGenerator;

    /** @var string */
    private $currentFileName = '';

    /** @var FilesystemInterface */
    private $origin;

    /** @var Metas */
    private $metas;

    /** @var string[] */
    private $links = [];

    /** @var string */
    private $destinationPath;

    /** @var string */
    private $currentAbsolutePath = '';

    private string $outputFormat;
    private DocumentNode $document;
    private FilesystemInterface $destination;

    private function __construct(
        string $outputFolder,
        string $currentFileName,
        FilesystemInterface $origin,
        FilesystemInterface $destination,
        Metas $metas,
        UrlGenerator $urlGenerator,
        string $outputFormat
    ) {
        $this->currentFileName = $currentFileName;
        $this->destinationPath = $outputFolder;
        $this->origin = $origin;
        $this->urlGenerator = $urlGenerator;
        $this->metas = $metas;
        $this->outputFormat = $outputFormat;
        $this->destination = $destination;
    }

    public static function forDocument(
        DocumentNode $documentNode,
        FilesystemInterface $origin,
        FilesystemInterface $destination,
        string $destinationPath,
        Metas $metas,
        UrlGenerator $urlGenerator,
        string $ouputFormat
    ) {
        $self = new self(
            $destinationPath,
            $documentNode->getFilePath(),
            $origin,
            $destination,
            $metas,
            $urlGenerator,
            $ouputFormat
        );

        $self->document = $documentNode;

        return $self;
    }

    /**
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function getVariable(string $variable, $default = null)
    {
        return $this->document->getVariable($variable, $default);
    }

    public function setLink(string $name, string $url): void
    {
        $name = strtolower(trim($name));
        $this->links[$name] = trim($url);
    }

    public function getLink(string $name, bool $relative = true): string
    {
        $name = strtolower(trim($name));

        if (isset($this->links[$name])) {
            $link = $this->links[$name];

            if ($relative) {
                return $this->urlGenerator->relativeUrl($link);
            }

            return $link;
        }

        return '';
    }

    public function canonicalUrl(string $url): ?string
    {
        return $this->urlGenerator->canonicalUrl($this->getDirName(), $url);
    }

    public function relativeDocUrl(string $filename, ?string $anchor = null): string
    {
        return $this->urlGenerator->relativeUrl(
            $this->destinationPath . '/' .
            $filename . '.' . $this->outputFormat .
            ($anchor !== null ? '#' . $anchor : '')
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

    public function getOrigin(): FilesystemInterface
    {
        return $this->origin;
    }

    public function getMetas(): Metas
    {
        return $this->metas;
    }

    public function getMetaEntry(): ?Entry
    {
        return $this->metas->get($this->currentFileName);
    }

    public function getSourcePath(): string
    {
        return $this->currentAbsolutePath;
    }

    public function getOutputFormat(): string
    {
        return $this->outputFormat;
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
}
