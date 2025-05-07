<?php
declare(strict_types=1);

namespace Aiops\Cleanup\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\WebsiteRepository;
use Magento\Store\Model\ResourceModel\Website as WebsiteResourceModel;
use Magento\Store\Model\GroupFactory as WebsiteGroupFactory;
use Magento\Store\Model\Group as WebsiteGroup;
use Magento\Store\Model\ResourceModel\Group as WebsiteGroupResourceModel;
use Magento\Store\Model\StoreRepository;
use Magento\Store\Model\ResourceModel\Store as StoreResourceModel;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Exception;

class DeleteTimeWebsite implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var WebsiteRepository
     */
    private $websiteRepository;

    /**
     * @var WebsiteResourceModel
     */
    private $websiteResourceModel;

    /**
     * @var WebsiteGroupResourceModel
     */
    private $websiteGroupResourceModel;

    /**
     * @var WebsiteGroupFactory
     */
    private $websiteGroupFactory;

    /**
     * @var StoreRepository
     */
    private $storeRepository;

    /**
     * @var StoreResourceModel
     */
    private $storeResourceModel;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param WebsiteRepository $websiteRepository
     * @param WebsiteResourceModel $websiteResourceModel
     * @param WebsiteGroupResourceModel $websiteGroupResourceModel
     * @param WebsiteGroupFactory $websiteGroupFactory
     * @param StoreRepository $storeRepository
     * @param StoreResourceModel $storeResourceModel
     * @param LoggerInterface $logger
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        WebsiteRepository $websiteRepository,
        WebsiteResourceModel $websiteResourceModel,
        WebsiteGroupResourceModel $websiteGroupResourceModel,
        WebsiteGroupFactory $websiteGroupFactory,
        StoreRepository $storeRepository,
        StoreResourceModel $storeResourceModel,
        LoggerInterface $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->websiteRepository = $websiteRepository;
        $this->websiteResourceModel = $websiteResourceModel;
        $this->websiteGroupResourceModel = $websiteGroupResourceModel;
        $this->websiteGroupFactory = $websiteGroupFactory;
        $this->storeRepository = $storeRepository;
        $this->storeResourceModel = $storeResourceModel;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @retrun void
     * @throws Exception
     */
    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();

        $store = null;

        try {
            $store = $this->storeRepository->get('tme_fr_fr');
        } catch (NoSuchEntityException $e) {
            $this->logger->info(__('Delete Time Website: Requested store was not found'));
        }

        if ($store) {
            $this->storeResourceModel->delete($store);
        }

        $websiteGroup = null;
        $website = null;

        try {
            $website = $this->websiteRepository->get('rb2cti_fr');
            $websiteGroup = $this->getWebsiteGroup((int) $website->getId());
        } catch (NoSuchEntityException $e) {
            $this->logger->info(__('Delete Time Website: Requested website or website group was not found.'));
        }

        if ($websiteGroup) {
            $this->websiteGroupResourceModel->delete($websiteGroup);
        }

        if ($website) {
            $this->websiteResourceModel->delete($website);
        }

        $defaultWebsite = null;

        try {
            $defaultWebsite = $this->websiteRepository->get('rb2cro_fr');
        } catch (NoSuchEntityException $e) {
            $this->logger->info(__('Delete Time Website: Requested default website was not found.'));
        }

        if ($defaultWebsite) {
            $defaultWebsite->setIsDefault(1);
            $this->websiteResourceModel->save($defaultWebsite);
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * @param int $websiteId
     * @return WebsiteGroup
     * @throws NoSuchEntityException
     */
    private function getWebsiteGroup(int $websiteId): WebsiteGroup
    {
        $websiteGroup = $this->websiteGroupFactory->create();
        $this->websiteGroupResourceModel->load($websiteGroup, $websiteId, 'website_id');

        if (!$websiteGroup->getGroupId()) {
            throw new NoSuchEntityException(__('The requested website group was not found.'));
        }

        return $websiteGroup;
    }
}
