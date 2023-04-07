<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Renderer;

use Exception;

use function sprintf;

class InMemoryRendererFactory implements TypeRendererFactory
{
    /** @var iterable<TypeRenderer> */
    private iterable $renderSets;

    /**
     * @param iterable<TypeRenderer> $renderSets
     */
    public function __construct(iterable $renderSets)
    {
        $this->renderSets = $renderSets;
    }

    public function getRenderSet(string $outputFormat): TypeRenderer
    {
        foreach ($this->renderSets as $renderSet) {
            if ($renderSet->supports($outputFormat)) {
                return $renderSet;
            }
        }

        throw new Exception(sprintf('No render set found for output format "%s"', $outputFormat));
    }
}
