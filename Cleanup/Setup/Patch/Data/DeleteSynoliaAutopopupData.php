<?php
declare(strict_types=1);

namespace Aiops\Cleanup\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class DeleteSynoliaAutopopupData implements DataPatchInterface
{
    const SYNOLIA_AUTOPOPUP_TABLE = 'synolia_autopopup_autopopup';

    const SYNOLIA_AUTOPOPUP_STORE_TABLE = 'synolia_autopopup_autopopup_store';

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
        $connection = $this->moduleDataSetup->getConnection();
        $connection->dropTable($connection->getTableName(self::SYNOLIA_AUTOPOPUP_TABLE));
        $connection->dropTable($connection->getTableName(self::SYNOLIA_AUTOPOPUP_STORE_TABLE));
        $this->moduleDataSetup->endSetup();
    }
}
