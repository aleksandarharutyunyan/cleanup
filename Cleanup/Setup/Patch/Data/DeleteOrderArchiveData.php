<?php
declare(strict_types=1);

namespace Aiops\Cleanup\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class DeleteOrderArchiveData implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
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
     */
    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();
        $synoliaOrderArchiveHeadersTable = $this->moduleDataSetup->getTable('synolia_order_archive_headers');
        $synoliaOrderArchiveItems = $this->moduleDataSetup->getTable('synolia_order_archive_items');
        $connection = $this->moduleDataSetup->getConnection();
        $connection->dropTable($synoliaOrderArchiveHeadersTable);
        $connection->dropTable($synoliaOrderArchiveItems);
        $this->moduleDataSetup->endSetup();
    }
}
