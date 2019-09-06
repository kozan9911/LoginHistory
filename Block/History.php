<?php

namespace Wiserbrand\LoginHistory\Block;

/**
 * Login history history block
 */
class History extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'Wiserbrand_LoginHistory::account/history.phtml';

    /**
     * @var \Wiserbrand\LoginHistory\Model\EntityFactory
     */
    protected $entityFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Wiserbrand\LoginHistory\Model\ResourceModel\Entity\Collection
     */
    protected $entities;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $avaliableSortFields;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Wiserbrand\LoginHistory\Model\EntityFactory     $entityFactory
     * @param \Magento\Customer\Model\Session                  $customerSession
     * @param \Magento\Framework\App\RequestInterface          $request
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Wiserbrand\LoginHistory\Model\EntityFactory $entityFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\RequestInterface $request,
        array $data = []
    ) {
        $this->entityFactory = $entityFactory;
        $this->customerSession = $customerSession;
        $this->request = $request;

        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Login History'));
    }

    /**
     * @return bool|\Wiserbrand\LoginHistory\Model\ResourceModel\Entity\Collection
     */
    public function getEntities()
    {
        if (!($customerId = $this->customerSession->getCustomerId())) {
            return false;
        }
        $params = $this->request->getParams();
        $factory = $this->entityFactory->create();
        $collection = $factory->getCollection();
        $collection->addFieldToFilter('customer_id', $customerId);
        if (isset($params['sort']) && isset($params['direction'])
            && in_array($params['sort'], $this->getAvaliableSortFields())
            && in_array($params['direction'], ['ASC', 'DESC'])
        ) {
            $collection->setOrder($params['sort'], $params['direction']);
        } else {
            $collection->setOrder('created_at', 'DESC');
        }
        $this->entities = $collection;

        return $this->entities;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getEntities()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'wiserbrand.loginhistory.history.pager'
            )->setCollection(
                $this->getEntities()
            );
            $this->setChild('pager', $pager);
            $this->getEntities()->load();
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * return avaliable fields for sorting
     *
     * @return array
     */
    public function getAvaliableSortFields()
    {
        if (!$this->avaliableSortFields) {
            $this->avaliableSortFields = ['ip', 'user_agent', 'created_at'];
        }
        return $this->avaliableSortFields;
    }

    /**
     *
     * @param $field
     *
     * @return string
     */
    public function getSortUrl($field)
    {
        $params = $this->request->getParams();
        $direction = 'ASC';
        if (isset($params['sort'])) {
            if ($params['sort'] == $field) {
                if ($params['direction'] == $direction) {
                    $params['direction'] = 'DESC';
                } else {
                    $params['direction'] = $direction;
                }
            } else {
                $params['sort'] = $field;
                $params['direction'] = $direction;
            }
        }

        return $this->getUrl('*/*/*',
            ['_current' => true, '_use_rewrite' => true, '_query' => $params]);
    }
}
