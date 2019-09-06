<?php

namespace Wiserbrand\LoginHistory\Observer;

use Magento\Framework\Event\ObserverInterface;
use Mageplaza\GeoIP\Helper\Address;

/**
 * Class LoginHistoryCustomerLogin
 *
 * @package Wiserbrand\LoginHistory\Observer
 */
class LoginHistoryCustomerLogin implements ObserverInterface
{
    /**
     * @var \Magento\Framework\HTTP\Header
     */
    protected $httpHeader;
    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $remoteIp;
    /**
     * @var \Wiserbrand\LoginHistory\Model\EntityFactory
     */
    protected $entityFactory;
    /**
     * @var \Wiserbrand\LoginHistory\Api\EntityRepositoryInterface
     */
    protected $entityRepository;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var Address
     */
    protected $address;

    /**
     * LoginHistoryCustomerLogin constructor.
     *
     * @param \Magento\Framework\HTTP\Header                       $httpHeader
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteIp
     * @param \Wiserbrand\LoginHistory\Model\EntityFactory         $entityFactory
     * @param \Psr\Log\LoggerInterface                             $logger
     */
    public function __construct(
        \Magento\Framework\HTTP\Header $httpHeader,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteIp,
        \Wiserbrand\LoginHistory\Model\EntityFactory $entityFactory,
        \Wiserbrand\LoginHistory\Api\EntityRepositoryInterface $entityRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Mageplaza\GeoIP\Helper\Address $address,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->httpHeader = $httpHeader;
        $this->remoteIp = $remoteIp;
        $this->entityFactory = $entityFactory;
        $this->entityRepository = $entityRepository;
        $this->scopeConfig = $scopeConfig;
        $this->address = $address;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORES;
        $moduleEnabled
            = $this->scopeConfig->getValue("login_history/general/enabled",
            $storeScope);
        if ($moduleEnabled) {
            /** @var \Wiserbrand\LoginHistory\Model\Entity $model */
            $model = $this->entityFactory->create();
            $data['customer_id'] = $observer->getEvent()->getCustomer()
                ->getId();
            $data['ip'] = $this->remoteIp->getRemoteAddress();
            $data['user_agent'] = $this->httpHeader->getHttpUserAgent();
            $geoData=$this->address->getGeoIpData();
            if (!empty($geoData)) {
                $data['location']='';
                $data['location'].= ($geoData['city'])?' City:'.$geoData['city']:'';
                $data['location'].= ($geoData['country_id'])?' Country:'.$geoData['country_id']:'';
                $data['location'].= ($geoData['postcode'])?' Postcode:'.$geoData['postcode']:'';
                $data['location'].= ($geoData['region'])?' Region:'.$geoData['region']:'';
            } else {
                $data['location'] = 'unavailable';
            }

            $model->setData($data);
            try {
                $this->entityRepository->save($model);
            } catch (\Exception $e) {
                $this->logger->error('Can`t save customer login history entity',
                    ['exception' => $e]);
            }
        }
    }
}