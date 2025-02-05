<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ConfirmationQuestion;

trait SymfonyQuestionHelper
{
    private ConfirmationQuestion $continueToNextChunkConfimationQuestion;
    private QuestionHelper $questionHelper;

    /**
     * @return bool
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws RuntimeException
     */
    private function askToContinueToNextChunk(): bool
    {
        return $this->getQuestionHelper()->ask(
            $this->input,
            $this->output,
            $this->getContinueToNextChunkConfirmationQuestion()
        );
    }

    public function getContinueToNextChunkConfirmationQuestion(): ConfirmationQuestion
    {
        return $this->continueToNextChunkConfimationQuestion ??
            $this->continueToNextChunkConfimationQuestion = new ConfirmationQuestion(
                "\nContinue processing? (y/n)",
                true
            );
    }
}
