<?php
declare(strict_types=1);

namespace Aiops\Cleanup\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class RemoveNotLinkedToStoresBlocks implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
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
        $connection = $this->moduleDataSetup->getConnection();
        $cmsBlockStoreTable = $connection->getTableName('cms_block_store');
        $storeTable = $connection->getTableName('store');
        $select = $connection->select();
        $select->from(['main_table' => $cmsBlockStoreTable], ['row_id', 'store_id']);
        $select->joinLeft(['s' => $storeTable], 'main_table.store_id = s.store_id', []);
        $select->where('s.store_id IS NULL');
        $result = $connection->fetchAll($select);
        $blockIds = array_column($result, 'row_id');
        $connection->delete($cmsBlockStoreTable, ['row_id IN (?)' => $blockIds]);
        $this->moduleDataSetup->endSetup();
    }
}
