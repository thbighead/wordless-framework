<?php declare(strict_types=1);

namespace Wordless\Infrastructure;

abstract class Migration
{
    final public const FILENAME_DATE_FORMAT = 'Y_m_d_His_';

    abstract public function up(): void;

    abstract public function down(): void;
}
