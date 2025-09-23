<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\PolymorphicConstructor\Traits\PolymorphicConstructorGuesser\DTO;

use Wordless\Application\Helpers\GetType;

readonly class ParsedArgumentsDTO
{
    public int $count;
    /** @var string[] $types */
    public array $types;

    public function __construct(public array $arguments)
    {
        $this->count = count($this->arguments);
        $this->setTypes();
    }

    /**
     * @return string[]
     */
    public function typesConcat(): array
    {
        $types_concat = [];

        foreach ($this->types as $type) {
            if (empty($types_concat)) {
                $types_concat[] = $type;

                switch ($type) {
                    case GetType::ASSOCIATIVE_ARRAY:
                    case GetType::LIST_ARRAY:
                        $types_concat[] = GetType::ARRAY;
                        break;
                    case class_exists($type, false):
                        $types_concat[] = GetType::OBJECT;
                        break;
                }

                continue;
            }

            $additional_types_concat = [];

            foreach ($types_concat as &$type_concat) {
                switch ($type) {
                    case GetType::ASSOCIATIVE_ARRAY:
                    case GetType::LIST_ARRAY:
                        $additional_types_concat[] = $type_concat . GetType::ARRAY;
                        break;
                    case class_exists($type, false):
                        $additional_types_concat[] = $type_concat . GetType::OBJECT;
                        break;
                }

                $type_concat .= $type;
            }

            $types_concat = array_merge($types_concat, $additional_types_concat);
        }

        return $types_concat;
    }

    private function setTypes(): void
    {
        $types = [];

        foreach ($this->arguments as $argument) {
            $types[] = GetType::of($argument);
        }

        $this->types = $types;
    }
}
