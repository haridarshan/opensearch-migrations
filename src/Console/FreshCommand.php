<?php declare(strict_types=1);

namespace OpenSearch\Migrations\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use OpenSearch\Migrations\IndexManagerInterface;
use OpenSearch\Migrations\Migrator;
use OpenSearch\Migrations\Repositories\MigrationRepository;

class FreshCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $signature = 'opensearch:migrate:fresh 
        {--force : Force the operation to run when in production.}';
    /**
     * @var string
     */
    protected $description = 'Drop all indices and re-run all migrations.';

    protected Migrator $migrator;

    protected MigrationRepository $migrationRepository;

    protected IndexManagerInterface $indexManager;

    public function __construct(
        Migrator $migrator,
        MigrationRepository $migrationRepository,
        IndexManagerInterface $indexManager
    ) {
        parent::__construct();

        $this->migrator = $migrator;
        $this->migrationRepository = $migrationRepository;
        $this->indexManager = $indexManager;
    }

    public function handle(): int
    {
        $this->migrator->setOutput($this->output);

        if (!$this->confirmToProceed() || !$this->migrator->isReady()) {
            return 1;
        }

        $this->indexManager->drop('*');
        $this->migrationRepository->purge();
        $this->migrator->migrateAll();

        return 0;
    }
}
