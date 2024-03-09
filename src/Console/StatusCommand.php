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

    public function handle(Migrator $migrator): int
    {
        $migrator->setOutput($this->output);

        if (!$migrator->isReady()) {
            return 1;
        }

        $onlyPending = (bool)$this->option('pending');
        $migrator->showStatus($onlyPending);

        return 0;
    }
}
