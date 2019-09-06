<?php

namespace Wiserbrand\LoginHistory\Block;

/**
 * Class LastLoginInfo
 *
 * @package Wiserbrand\LoginHistory\Block
 */
class LastLoginInfo extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Wiserbrand\LoginHistory\Model\EntityFactory
     */
    protected $entityFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * LastLoginInfo constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Wiserbrand\LoginHistory\Model\EntityFactory     $entityFactory
     * @param \Magento\Customer\Model\Session                  $customerSession
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Wiserbrand\LoginHistory\Model\EntityFactory $entityFactory,
        \Magento\Customer\Model\Session $customerSession
    ) {
        parent::__construct($context);
        $this->entityFactory = $entityFactory;
        $this->customerSession = $customerSession;
    }

    /**
     * retrive last login information
     *
     * @return bool|\Magento\Framework\DataObject
     */
    public function getLastLoginInfo()
    {
        $customerId = $this->customerSession->getCustomer()->getId();
        $entity = $this->entityFactory->create();
        $collection = $entity->getCollection();
        $collection->addFieldToFilter('customer_id', $customerId);
        $collection->setOrder('created_at', 'DESC');
        $collection->setPageSize(2);
        $collection->setCurPage(1);
        if ($collection->count() == 2) {
            return $collection->getLastItem();
        }

        return false;
    }
}