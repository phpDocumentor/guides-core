<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Compiler;

use phpDocumentor\Guides\Compiler\NodeTransformers\NodeTransformerFactory;
use phpDocumentor\Guides\Compiler\NodeTransformers\TransformerPass;
use phpDocumentor\Guides\Nodes\DocumentNode;
use SplPriorityQueue;

class Compiler
{
    /** @var SplPriorityQueue<int, CompilerPass> */
    private readonly SplPriorityQueue $passes;

    /** @param iterable<CompilerPass> $passes */
    public function __construct(
        iterable $passes,
        NodeTransformerFactory $nodeTransformerFactory,
    ) {
        $this->passes = new SplPriorityQueue();
        foreach ($passes as $pass) {
            $this->passes->insert($pass, $pass->getPriority());
        }

        $transformerPriorities = $nodeTransformerFactory->getPriorities();
        foreach ($transformerPriorities as $transformerPriority) {
            $this->passes->insert(
                new TransformerPass(new DocumentNodeTraverser($nodeTransformerFactory, $transformerPriority), $transformerPriority),
                $transformerPriority,
            );
        }
    }

    /**
     * @param DocumentNode[] $documents
     *
     * @return DocumentNode[]
     */
    public function run(array $documents): array
    {
        $clonedPasses = clone$this->passes;
        foreach ($clonedPasses as $pass) {
            $documents = $pass->run($documents);
        }

        return $documents;
    }
}
