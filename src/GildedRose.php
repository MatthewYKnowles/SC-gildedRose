<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    private string $agedBrie = 'Aged Brie';
    private string $backstagePass = 'Backstage passes to a TAFKAL80ETC concert';
    private string $sulfuras = 'Sulfuras, Hand of Ragnaros';
    public array $items = [];

    public function __construct(array $items) {
        foreach ($items as $item) {
            if ($item->name == $this->backstagePass) {
                $this->items[] = new BackstagePass($item->name, sellIn: $item->sellIn, quality: $item->quality);
            }
            else if ($item->name == $this->agedBrie) {
                $this->items[] = new AgedBrie($item->name, sellIn: $item->sellIn, quality: $item->quality);
            }
            else if ($item->name == $this->sulfuras) {
                $this->items[] = new Sulfuras($item->name, sellIn: $item->sellIn, quality: $item->quality);
            }
            else {
                $this->items[] = new StandardItem($item->name, sellIn: $item->sellIn, quality: $item->quality);
            }
        }
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $item->updateQuality();
            $item->updateSellIn();
        }
    }
}
class Sulfuras extends Item {
    public function __construct(public string $name, public int $sellIn, public int $quality)
    {
        parent::__construct($name, $sellIn, $quality);
    }

    public function updateQuality(): void {
        if ($this->quality < 50){
            $this->quality++;
        }
    }

    public function updateSellIn(): void {
    }
}

class AgedBrie extends Item {
    public function __construct(public string $name, public int $sellIn, public int $quality)
    {
        parent::__construct($name, $sellIn, $quality);
    }

    public function updateQuality(): void {
        if ($this->quality < 50){
            $this->quality++;
        }
        if ($this->sellIn < 1) {
            if ($this->quality < 50) {
                $this->quality++;
            }
        }
    }

    public function updateSellIn(): void {
        $this->sellIn--;
    }
}

class BackstagePass extends Item {
    public function __construct(public string $name, public int $sellIn, public int $quality)
    {
        parent::__construct($name, $sellIn, $quality);
    }

    public function updateQuality(): void {
        if ($this->quality < 50) {
            $this->quality++;
            if ($this->sellIn < 11) {
                if ($this->quality < 50) {
                    $this->quality++;
                }
            }
            if ($this->sellIn < 6) {
                if ($this->quality < 50) {
                    $this->quality++;
                }
            }
        }
        if ($this->sellIn < 1) {
            $this->quality = 0;
        }
    }

    public function updateSellIn(): void {
        $this->sellIn--;
    }
}

class StandardItem extends Item {
    public function __construct(public string $name, public int $sellIn, public int $quality)
    {
        parent::__construct($name, $sellIn, $quality);
    }

    public function updateQuality(): void {
        if ($this->quality > 0) {
            $this->quality = $this->quality - 1;
        }
        if ($this->sellIn < 1) {
            if ($this->quality > 0) {
                $this->quality = $this->quality - 1;
            }
        }
    }

    public function updateSellIn(): void {
        $this->sellIn--;
    }
}
