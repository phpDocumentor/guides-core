<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Intersphinx;

use RuntimeException;

use function array_key_exists;
use function strtolower;

final class Inventory
{
    /** @var InventoryGroup[]  */
    private array $groups = [];
    private string $baseUrl;

    /** @param String $baseUrl */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function addGroup(string $key, InventoryGroup $group): void
    {
        $lowerCaseKey       = strtolower($key);
        $this->groups[$key] = $group;
    }

    /** @return InventoryGroup[] */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getInventory(string $key): InventoryGroup
    {
        $lowerCaseKey = strtolower($key);
        if (!$this->hasInventoryGroup($lowerCaseKey)) {
            throw new RuntimeException(
                'Inventory group with key ' . $lowerCaseKey . ' not found. ',
                1671398986
            );
        }

        return $this->groups[$lowerCaseKey];
    }

    public function getLink(string $group, string $key): InventoryLink
    {
        return $this->getInventory($group)->getLink($key);
    }

    public function hasInventoryGroup(string $key): bool
    {
        $lowerCaseKey = strtolower($key);

        return array_key_exists($lowerCaseKey, $this->groups);
    }
}
