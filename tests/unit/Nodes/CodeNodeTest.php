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

namespace phpDocumentor\Guides\Nodes;

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link https://phpdoc.org
 */

use PHPUnit\Framework\TestCase;

final class CodeNodeTest extends TestCase
{
    public function test_it_can_be_created_with_series_of_lines(): void
    {
        $node = new CodeNode(['line1', 'line2']);

        self::assertSame("line1\nline2", $node->getValue());
    }

    public function test_a_language_can_be_provided(): void
    {
        $node = new CodeNode([]);
        self::assertNull($node->getLanguage());
        $node->setLanguage('php');

        self::assertSame('php', $node->getLanguage());
    }

    public function test_a_starting_line_number_can_be_provided(): void
    {
        $node = new CodeNode([]);
        self::assertNull($node->getStartingLineNumber());
        $node->setStartingLineNumber(100);

        self::assertSame(100, $node->getStartingLineNumber());
    }

    public function test_lines_are_normalized_by_removing_whitespace(): void
    {
        $node = new CodeNode([
            '  line1',
            '    line2',
            '      line3',
            "\t\t\tline4",
        ]);

        self::assertSame("  line1\n    line2\n      line3\n\t\t\tline4", $node->getValue());
    }

    public function test_that_normalizing_keeps_spaces_intact_when_the_first_line_has_no_spaces(): void
    {
        $node = new CodeNode([
            'line1',
            '  line2',
            '    line3',
            "\t\t\tline4",
        ]);

        self::assertSame("line1\n  line2\n    line3\n\t\t\tline4", $node->getValue());
    }
}
