<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits;

use LogicException;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException as SymfonyLogicException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

trait Questions
{
    private QuestionHelper $questionHelper;

    /**
     * @param Question $question
     * @return mixed
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws SymfonyLogicException
     */
    protected function ask(Question $question): mixed
    {
        return $this->getQuestionHelper()
            ->ask($this->input, $this->output, $question);
    }

    /**
     * @param string $question
     * @param array<string|bool|int|float|null> $choices
     * @param bool $use_first_as_default
     * @return string|bool|int|float|null
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws RuntimeException
     * @throws SymfonyLogicException
     */
    protected function choiceQuestion(
        string $question,
        array  $choices,
        bool   $use_first_as_default = true
    ): string|bool|int|float|null
    {
        return $this->ask(new ChoiceQuestion(
            $question,
            $choices,
            $use_first_as_default ? $choices[0] : null
        ));
    }

    /**
     * @param string $question
     * @param bool $default
     * @return bool
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws SymfonyLogicException
     */
    protected function confirmationQuestion(string $question, bool $default = true): bool
    {
        return $this->ask(new ConfirmationQuestion($question, $default));
    }

    /**
     * @return QuestionHelper
     * @throws InvalidArgumentException
     * @throws SymfonyLogicException
     */
    protected function getQuestionHelper(): QuestionHelper
    {
        return $this->questionHelper ?? $this->questionHelper = $this->getHelper('question');
    }

    /**
     * @param string $question
     * @param string|bool|int|float|null $default
     * @return string|bool|int|float|null
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws SymfonyLogicException
     */
    protected function question(
        string                     $question,
        string|bool|int|float|null $default = null
    ): string|bool|int|float|null
    {
        return $this->ask(new Question($question, $default));
    }
}
