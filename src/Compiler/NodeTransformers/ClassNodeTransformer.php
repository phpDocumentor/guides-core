<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Compiler\NodeTransformers;

use phpDocumentor\Guides\Compiler\NodeTransformer;
use phpDocumentor\Guides\Nodes\ClassNode;
use phpDocumentor\Guides\Nodes\DocumentNode;
use phpDocumentor\Guides\Nodes\Node;

use function array_merge;

/**
 * @implements NodeTransformer<Node>
 *
 * The "class" directive sets the "classes" attribute value on its content or on the first immediately following
 * non-comment element. https://docutils.sourceforge.io/docs/ref/rst/directives.html#class
 */
class ClassNodeTransformer implements NodeTransformer
{
    /** @var string[] */
    private array $classes = [];

    public function enterNode(Node $node): Node
    {
        if ($node instanceof DocumentNode) {
            // unset classes when entering the next document
            $this->classes = [];
        }

        if ($node instanceof ClassNode) {
            $this->classes = $node->getClasses();
        }

        if ($this->classes !== [] && !$node instanceof ClassNode) {
            $node->setClasses(array_merge($node->getClasses(), $this->classes));
            // Unset the classes after applied to the first direct successor
            $this->classes = [];
        }

        return $node;
    }

    public function leaveNode(Node $node): ?Node
    {
        if ($node instanceof ClassNode) {
            //Remove the class node from the tree.
            return null;
        }

        return $node;
    }

    public function supports(Node $node): bool
    {
        // Every node can have a class attached to it, however the node renderer decides on if to render the class
        return true;
    }
}
