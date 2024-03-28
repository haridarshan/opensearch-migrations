<?php declare(strict_types=1);

namespace OpenSearch\Migrations\Console;

use Illuminate\Console\Command;
use OpenSearch\Migrations\Migrator;

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

    protected Migrator $migrator;

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
