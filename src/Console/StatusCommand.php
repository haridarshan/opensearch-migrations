<?php declare(strict_types=1);

namespace OpenSearch\Migrations\Console;

use OpenSearch\Migrations\Migrator;
use Illuminate\Console\Command;

class StatusCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'opensearch:migrate:status {--pending}';
    /**
     * @var string
     */
    protected $description = 'Show the status of each migration.';

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

        if (!$this->migrator->isReady()) {
            return 1;
        }

        $onlyPending = (bool)$this->option('pending');
        $this->migrator->showStatus($onlyPending);

        return 0;
    }
}
