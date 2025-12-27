<?php

namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns\Column;

class SliderColumn extends Column
{
    protected string $view = 'filament.tables.columns.slider-column';

    /**
     * Spatie collection name.
     */
    protected string $collection ;

    public function collection(string $name): static
    {
        $this->collection = $name;
        return $this;
    }

    public function getCollection(): string
    {
        return $this->collection;
    }
}
