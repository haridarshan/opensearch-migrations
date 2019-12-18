<?php
declare(strict_types=1);

namespace ElasticMigrations\Console;

use ElasticMigrations\Migrator;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

final class RefreshCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $signature = 'elastic:migrate:refresh 
        {--force : Force the operation to run when in production}';
    /**
     * @var string
     */
    protected $description = 'Reset and re-run all migrations';
    /**
     * @var Migrator
     */
    private $migrator;

    public function __construct(Migrator $migrator)
    {
        parent::__construct();

        $this->migrator = $migrator;
    }

    public function handle()
    {
        $this->migrator->setOutput($this->output);

        if (!$this->confirmToProceed() || !$this->migrator->isReady()) {
            return;
        }

        $this->migrator->rollbackAll();
        $this->migrator->migrateAll();
    }
}