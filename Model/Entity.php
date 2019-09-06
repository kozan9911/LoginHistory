<?php

namespace Wiserbrand\LoginHistory\Model;

/**
 * @method \Wiserbrand\LoginHistory\Model\ResourceModel\Entity getResource()
 * @method \Wiserbrand\LoginHistory\Model\ResourceModel\Entity\Collection getCollection()
 */
class Entity extends \Magento\Framework\Model\AbstractModel
    implements \Wiserbrand\LoginHistory\Api\Data\EntityInterface,
               \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'wiserbrand_loginhistory_entity';
    protected $_cacheTag = 'wiserbrand_loginhistory_entity';
    protected $_eventPrefix = 'wiserbrand_loginhistory_entity';

    protected function _construct()
    {
        $this->_init('Wiserbrand\LoginHistory\Model\ResourceModel\Entity');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}