<?php declare(strict_types=1);

namespace Wordless\Tests\Unit\StrHelperTest\Traits\MutatorsTests\Traits;

use Wordless\Tests\Unit\StrHelperTest\Traits\MutatorsTests\Traits\CaseStyleTests\Traits\FromCamelToAnother;
use Wordless\Tests\Unit\StrHelperTest\Traits\MutatorsTests\Traits\CaseStyleTests\Traits\FromKebabToAnother;
use Wordless\Tests\Unit\StrHelperTest\Traits\MutatorsTests\Traits\CaseStyleTests\Traits\FromPascalToAnother;
use Wordless\Tests\Unit\StrHelperTest\Traits\MutatorsTests\Traits\CaseStyleTests\Traits\FromRawToAnother;
use Wordless\Tests\Unit\StrHelperTest\Traits\MutatorsTests\Traits\CaseStyleTests\Traits\FromSnakeToAnother;
use Wordless\Tests\Unit\StrHelperTest\Traits\MutatorsTests\Traits\CaseStyleTests\Traits\FromTitleToAnother;

trait CaseStyleTests
{
    use FromCamelToAnother;
    use FromKebabToAnother;
    use FromPascalToAnother;
    use FromRawToAnother;
    use FromSnakeToAnother;
    use FromTitleToAnother;

    private const CLEAN_CAMEL_CASE_EXAMPLE = 'thanksForReading';
    private const CLEAN_KEBAB_CASE_EXAMPLE = 'thanks-for-reading';
    private const CLEAN_PASCAL_CASE_EXAMPLE = 'ThanksForReading';
    private const CLEAN_RAW_CASE_EXAMPLE = 'thanks for reading';
    private const CLEAN_SNAKE_CASE_EXAMPLE = 'thanks_for_reading';
    private const CLEAN_TITLE_CASE_EXAMPLE = 'Thanks For Reading';
    private const NUMERICAL_CAMEL_CASE_EXAMPLE = 'thanks4Reading';
    private const NUMERICAL_KEBAB_CASE_EXAMPLE = 'thanks-4-reading';
    private const NUMERICAL_PASCAL_CASE_EXAMPLE = 'Thanks4Reading';
    private const NUMERICAL_RAW_CASE_EXAMPLE = 'thanks 4 reading';
    private const NUMERICAL_SNAKE_CASE_EXAMPLE = 'thanks_4_reading';
    private const NUMERICAL_TITLE_CASE_EXAMPLE = 'Thanks 4 Reading';
    private const ACCENTED_CAMEL_CASE_EXAMPLE = 'aCacadaHegemonicaNaoEResponsavelPelaDestruicao';
    private const ACCENTED_KEBAB_CASE_EXAMPLE = 'a-cacada-hegemonica-nao-e-responsavel-pela-destruicao';
    private const ACCENTED_PASCAL_CASE_EXAMPLE = 'ACacadaHegemonicaNaoEResponsavelPelaDestruicao';
    private const ACCENTED_RAW_CASE_EXAMPLE = 'a caçada hegemônica não é responsável pela destruição?!';
    private const ACCENTED_SNAKE_CASE_EXAMPLE = 'a_cacada_hegemonica_nao_e_responsavel_pela_destruicao';
    private const ACCENTED_TITLE_CASE_EXAMPLE = 'A Cacada Hegemonica Nao E Responsavel Pela Destruicao';
}
