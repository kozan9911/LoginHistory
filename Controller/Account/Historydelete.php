<?php

namespace Wiserbrand\LoginHistory\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class History
 *
 * @package Wiserbrand\LoginHistory\Controller\Account
 */
class Historydelete extends \Magento\Customer\Controller\AbstractAccount
    implements HttpPostActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Wiserbrand\LoginHistory\Model\EntityFactory
     */
    protected $entityFactory;
    /**
     * @var \Wiserbrand\LoginHistory\Api\EntityRepositoryInterface
     */
    protected $entityRepository;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Wiserbrand\LoginHistory\Model\EntityFactory $entityFactory,
        \Wiserbrand\LoginHistory\Api\EntityRepositoryInterface $entityRepository,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        PageFactory $resultPageFactory
    ) {
        $this->customerSession = $customerSession;
        $this->entityFactory = $entityFactory;
        $this->entityRepository = $entityRepository;
        $this->messageManager = $messageManager;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Default customer account page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultRedirect
            = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $post = $this->getRequest()->getPostValue();
        if (isset($post['delete-all']) && $post['delete-all']) {
            $customerId = $this->customerSession->getCustomerId();
            $factory = $this->entityFactory->create();
            $collection = $factory->getCollection();
            $collection->addFieldToFilter('customer_id', $customerId);
            $ids = $collection->getAllIds();
            try {
                foreach ($ids as $id) {
                    $this->entityRepository->deleteById($id);
                }
                $this->messageManager->addSuccessMessage(__('Your login records deleted.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        if (isset($post['delete-selected']) && $post['delete-selected']) {
            $ids = explode(',', $post['delete-selected']);
            try {
                foreach ($ids as $id) {
                    $this->entityRepository->deleteById($id);
                }
                $this->messageManager->addSuccessMessage(__('Your login records deleted.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        $resultRedirect->setUrl($this->_redirect->getRefererUrl());

        return $resultRedirect;
    }
}
