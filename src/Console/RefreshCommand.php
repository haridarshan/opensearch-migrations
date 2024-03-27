<?php declare(strict_types=1);

namespace OpenSearch\Migrations\Console;

use OpenSearch\Migrations\Migrator;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class RefreshCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $signature = 'opensearch:migrate:refresh 
        {--force : Force the operation to run when in production.}';
    /**
     * @var string
     */
    protected $description = 'Reset and re-run all migrations.';

    /**
     * @var Migrator
     */
    protected Migrator $migrator;

    /**
     * @param Migrator $migrator
     */
    public function __construct(Migrator $migrator)
    {
        parent::__construct();

        $this->migrator = $migrator;
    }

    public function handle(): int
    {
        $this->migrator->setOutput($this->output);

        if (!$this->confirmToProceed() || !$this->migrator->isReady()) {
            return 1;
        }

        $this->migrator->rollbackAll();
        $this->migrator->migrateAll();

        return 0;
    }
}
