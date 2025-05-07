<?php
declare(strict_types=1);

namespace Aiops\Cleanup\Setup\Patch\Data;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Customer\Model\Customer;

class DeleteLastBikeConfigAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        AttributeRepositoryInterface $attributeRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->attributeRepository = $attributeRepository;
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
     * @return void
     * @throws NoSuchEntityException
     * @throws StateException
     */
    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();
        $attribute = $this->attributeRepository->get(
            Customer::ENTITY,
            'last_bike_config'
        );
        $attribute->setIsUserDefined(true);
        $this->attributeRepository->save($attribute);
        $this->attributeRepository->delete($attribute);
        $this->moduleDataSetup->endSetup();
    }
}
