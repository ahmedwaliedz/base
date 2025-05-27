<?php

namespace App\Builders;

use App\Traits\Route\RouteTrait;
use Illuminate\Contracts\View\View;

/**
 * Base builder class for UI components
 */
abstract class BaseBuilder
{
    use RouteTrait;

    /**
     * Collection of items to be rendered
     *
     * @var array
     */
    protected array $items = [];

    /**
     * Add an item to the collection
     *
     * @param array $item The item to add
     * @param string|null $key Optional key for associative arrays
     * @return $this
     */
    protected function addItem(array $item, ?string $key = null): self
    {
        if ($key !== null) {
            $this->items[$key] = $item;
        } else {
            $this->items[] = $item;
        }
        return $this;
    }

    /**
     * Get all items in the collection
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Build the component from configuration
     *
     * @return mixed
     */
    abstract public static function buildFromConfig();

    /**
     * Render the component view
     *
     * @return mixed
     */
    abstract public function render();
}
