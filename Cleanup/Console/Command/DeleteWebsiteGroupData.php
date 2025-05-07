<?php
declare(strict_types=1);

namespace Aiops\Cleanup\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Console\Cli;
use Magento\Framework\App\ResourceConnection;

class DeleteWebsiteGroupData extends Command
{
    private $resource;

    /**
     * @param ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('aiops:cleanup:website-group-data:delete');
        $this->setDescription('Delete Synolia website group data.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->resource->getConnection();
        $connection->beginTransaction();
        try {
            $connection->dropTable($connection->getTableName('synolia_website_group'));
            $connection->dropColumn(
                $connection->getTableName('synolia_website_group'),
                'website_group_id'
            );
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $output->writeln("Error occurred while deleting data.");
            return Cli::RETURN_FAILURE;
        }
        $output->writeln("Synolia website group data has been deleted.");
        return Cli::RETURN_SUCCESS;
    }
}
