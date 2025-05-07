<?php
declare(strict_types=1);

namespace Aiops\Cleanup\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Console\Cli;
use Magento\Framework\App\ResourceConnection;

class DeleteTaxClassCode extends Command
{
    const TABLE_NAME = 'tax_class';

    const COLUMN_NAME = 'code';

    /**
     * @var ResourceConnection
     */
    private $resource;

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
        $this->setName('aiops:cleanup:delete-tax-class-code');
        $this->setDescription('Delete tax class code column.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->resource->getConnection();
        $tableName = $connection->getTableName(self::TABLE_NAME);
        $connection->dropColumn($tableName, self::COLUMN_NAME);
        $output->writeln("<info>Tax Class Code column successfully deleted.</info>");
        return Cli::RETURN_SUCCESS;
    }
}
