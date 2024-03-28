<?php declare(strict_types=1);

namespace OpenSearch\Migrations;

function prefixIndexName(string $indexName): string
{
    return config('opensearch.migrations.prefixes.index') . $indexName;
}

function prefixAliasName(string $aliasName): string
{
    return config('opensearch.migrations.prefixes.alias') . $aliasName;
}
