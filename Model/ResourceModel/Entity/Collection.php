<?php

namespace Wiserbrand\LoginHistory\Model\ResourceModel\Entity;

class Collection extends
    \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'wiserbrand_loginhistory_id';


    protected function _construct()
    {
        $this->_init('Wiserbrand\LoginHistory\Model\Entity',
            'Wiserbrand\LoginHistory\Model\ResourceModel\Entity');
    }

}