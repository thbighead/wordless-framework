<?php

namespace App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ConfirmationQuestion;

trait SymfonyQuestionHelper
{
    private ConfirmationQuestion $continueToNextChunkConfimationQuestion;
    private QuestionHelper $questionHelper;

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

    private function getQuestionHelper(): QuestionHelper
    {
        return $this->questionHelper ?? $this->questionHelper = $this->getHelper('question');
    }
}
