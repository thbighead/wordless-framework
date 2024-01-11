<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Migration;

interface Script
{
    const FILENAME_DATE_FORMAT = 'Y_m_d_His_';

    public function up(): void;

    public function down(): void;
}
