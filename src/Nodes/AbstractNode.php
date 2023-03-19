<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Nodes;

/**
 * @template TValue
 */
abstract class AbstractNode implements Node
{
    /** @var string[] */
    protected $classes = [];

    /** @var array<string, scalar|null> */
    protected array $options = [];

    /** @var TValue */
    protected $value;

    /**
     * @return array<string, scalar|null>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param TValue $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return TValue
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string[]
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * Normalizes class names following the rules of identifier-normalization
     * @see https://docutils.sourceforge.io/docs/ref/rst/directives.html#identifier-normalization
     * @param string[] $classes
     */
    public function setClasses(array $classes): void
    {
        array_walk($classes, function (&$value) {
            // alphabetic characters to lowercase,
            $value = strtolower($value);
            // TODO: accented characters to the base character
            // non-alphanumeric characters to hyphens
            $value = (string)preg_replace("/[^a-z0-9]+/", '-', $value);
            // consecutive hyphens into one hyphen
            $value = (string)preg_replace("/-+/", '-', $value);
            // strip leading hyphens and number characters
            $value = (string)preg_replace("/^[0-9\-]+/", '', $value);
            // strip trailing hyphens
            $value = (string)preg_replace("/-$/", '', $value);
        });
        $this->classes = $classes;
    }

    public function getClassesString(): string
    {
        return implode(' ', $this->classes);
    }

    /**
     * @param array<string, scalar|null> $options
     * @return static
     */
    public function withOptions(array $options): Node
    {
        $result = clone $this;
        $result->options = $options;

        return $result;
    }

    public function hasOption(string $name): bool
    {
        return isset($this->options[$name]);
    }

    /**
     * @template TType as mixed
     * @param TType|null $default
     *
     * @return ($default is null ? mixed|null: TType|null)
     */
    public function getOption(string $name, $default = null)
    {
        return $this->options[$name] ?? $default;
    }
}
