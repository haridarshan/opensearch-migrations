<?php declare(strict_types=1);

namespace OpenSearch\Migrations\Factories;

use OpenSearch\Migrations\Filesystem\MigrationFile;
use OpenSearch\Migrations\MigrationInterface;
use Illuminate\Support\Str;

class MigrationFactory
{
    public function makeFromFile(MigrationFile $file): MigrationInterface
    {
        require_once $file->path();

        $className = Str::studly(implode('_', array_slice(explode('_', $file->name()), 4)));
        /** @var MigrationInterface $migration */
        $migration = resolve($className);

        return $migration;
    }
}
