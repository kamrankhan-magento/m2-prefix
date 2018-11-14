<?php
Class ZOrderView
{
    public static function getOrder(\Magento\Sales\Api\Data\OrderInterface $order) : array
    {
        \Kint::$max_depth =7;
        return \ZReflection::recursiveData($order);
    }
}