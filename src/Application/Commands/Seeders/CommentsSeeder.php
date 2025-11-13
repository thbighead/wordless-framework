<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Exceptions\FailedToGetCommandOptionValue;
use Wordless\Application\Commands\Exceptions\FailedToRunCommand;
use Wordless\Application\Commands\Seeders\CommentsSeeder\Exceptions\FailedToRunCommentsSeederCommand;
use Wordless\Application\Commands\Seeders\Contracts\SeederCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions\FailedToRunWpCliCommand;
use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\Internal\Exceptions\CallInternalCommandException;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Post;

class CommentsSeeder extends SeederCommand
{

    final public const COMMAND_NAME = 'seeder:comments';
    protected const DEFAULT_NUMBER_OF_OBJECTS = 5;

    protected function description(): string
    {
        return 'Create dummy comments to all posts.';
    }

    protected function help(): string
    {
        return 'Creates a given number of dummy comments to each even indexed created post. Default is '
            . static::DEFAULT_NUMBER_OF_OBJECTS
            . '.';
    }

    /**
     * @return int
     * @throws FailedToGetCommandOptionValue
     * @throws FailedToRunCommand
     * @throws FailedToRunCommentsSeederCommand
     */
    protected function runIt(): int
    {
        try {
            $have_no_posts = Post::noneCreated();
        } catch (EmptyQueryBuilderArguments $exception) {
            throw new FailedToRunCommentsSeederCommand(
                'Failed to check if no posts were created.',
                $exception
            );
        }

        if ($have_no_posts) {
            try {
                $this->callConsoleCommand($command = PostsSeeder::COMMAND_NAME);
            } catch (CallInternalCommandException $exception) {
                throw new FailedToRunCommand($command, $exception);
            } catch (CliReturnedNonZero $exception) {
                throw new FailedToRunCommand($exception->full_command, $exception);
            }
        }

        try {
            $posts = Arr::random($posts = Post::all(), intdiv(count($posts), 2));
        } catch (EmptyQueryBuilderArguments $exception) {
            throw new FailedToRunCommentsSeederCommand('Failed to retrieve posts.', $exception);
        }

        $progressBar = $this->progressBar($comments_total = count($posts) * $this->getQuantity());
        $progressBar->setMessage('Creating Comments...');
        $progressBar->start();

        foreach ($posts as $post) {
            $this->generateCommentsForPost($post, $progressBar);
        }

        $progressBar->setMessage("Done! A total of $comments_total comments were generated.");
        $progressBar->finish();
        $this->writeln('');

        return Command::SUCCESS;
    }

    /**
     * @param Post $post
     * @param ProgressBar $progressBar
     * @return void
     * @throws FailedToRunCommand
     * @throws FailedToGetCommandOptionValue
     */
    private function generateCommentsForPost(Post $post, ProgressBar $progressBar): void
    {
        for ($i = 0; $i < $this->getQuantity(); $i++) {
            $comment_author = $this->faker->userName();

            $progressBar->setMessage("Generating user $comment_author comment for post $post->ID.");
            $progressBar->advance(0);

            $command =
                "comment create --comment_post_ID=$post->ID --comment_content='{$this->faker->paragraph()}' --comment_author='$comment_author' --quiet";

            try {
                $this->runWpCliCommandSilently($command);
            } catch (WpCliCommandReturnedNonZero|FailedToRunWpCliCommand $exception) {
                throw new FailedToRunCommand($exception->full_command ?? $command);
            }

            $progressBar->advance();
        }
    }
}
