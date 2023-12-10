<?php

namespace App\Infolists\Components;

use Closure;
use Filament\Infolists\Components\Entry;
use Illuminate\Database\Eloquent\Model;

class TableEntry extends Entry
{
    protected string $view = 'infolists.components.table-entry';

    protected string $tableTitle;


    /**
     * @param  array<Tab> | Closure  $tabs
     */
    public function useColumns(array | Closure $columns): static
    {
        $this->childComponents($columns);

        return $this;
    }

    public function getTableTitle()
    {
        return $this->tableTitle;
    }

    public function title(string $title)
    {
        $this->tableTitle = $title;
        return $this;
    }

        /**
     * @return array<ComponentContainer>
     */
    public function getChildComponentContainers(bool $withHidden = false): array
    {
        if ((! $withHidden) && $this->isHidden()) {
            return [];
        }

        $containers = [];

        foreach ($this->getState() ?? [] as $itemKey => $itemData) {
            $container = $this
                ->getChildComponentContainer()
                ->getClone()
                ->statePath($itemKey)
                ->inlineLabel(false);

            if ($itemData instanceof Model) {
                $container->record($itemData);
            }

            $containers[$itemKey] = $container;
        }

        return $containers;
    }
}
