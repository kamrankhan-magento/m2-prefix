<?php


class DTOCreateItem
{
    public $itemId;
    public $quantity;
    public function getClone($itemId,$quantity)
    {
        $createItem =  clone $this;
        $createItem->itemId = $itemId;
        $createItem->quantity = $quantity;
        return $createItem;
    }
}