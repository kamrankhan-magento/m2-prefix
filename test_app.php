<?php
class TestApp
    extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {

    public function getObjectManager()
    {
        return $this->_objectManager;
    }
}