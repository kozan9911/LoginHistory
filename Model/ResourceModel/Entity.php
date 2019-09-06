<?php

namespace Wiserbrand\LoginHistory\Model\ResourceModel;

class Entity extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('wiserbrand_loginhistory', 'wiserbrand_loginhistory_id');
    }

}