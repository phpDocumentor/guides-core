<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Nodes\Links;

final class Link
{
    public const TYPE_LINK = 'link';
    public const TYPE_ANCHOR = 'anchor';

    private string $name;

    private string $url;

    private string $type;

    public function __construct(string $name, string $url, string $type)
    {
        $this->name = $name;
        $this->url = $url;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
