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
            } else {
                $this->items[] = new Item($item->name, sellIn: $item->sellIn, quality: $item->quality);
            }
        }
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            if ($item->name == $this->backstagePass) {
                $item->updateQuality();
                $item->updateSellIn();
            } else {
                if ($this->notBrieOrBackstagePassOrSulfuros($item)) {
                    if ($item->quality > 0) {
                        $item->quality = $item->quality - 1;
                    }
                } else {
                    if ($item->quality < 50) {
                        if ($item->name == $this->backstagePass) {
                            $item->updateQuality();
                        } else {
                            $item->quality++;
                        }
                    }
                }

                if ($item->name != $this->sulfuras) {
                    $item->sellIn = $item->sellIn - 1;
                }

                if ($item->sellIn < 0) {
                    if ($item->name != $this->agedBrie) {
                        if ($item->name != $this->backstagePass) {
                            if ($item->quality > 0) {
                                if ($item->name != $this->sulfuras) {
                                    $item->quality = $item->quality - 1;
                                }
                            }
                        } else {
                            $item->quality = 0;
                        }
                    } else {
                        if ($item->quality < 50) {
                            $item->quality++;
                        }
                    }
                }
            }
        }
    }

    public function notBrieOrBackstagePassOrSulfuros(Item $item): bool
    {
        return $item->name != $this->agedBrie and $item->name != $this->backstagePass and $item->name != $this->sulfuras;
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
