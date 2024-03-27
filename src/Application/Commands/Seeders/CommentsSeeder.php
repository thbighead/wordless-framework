<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\ProgressBar;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Seeders\Contracts\SeederCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
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
        return 'Creates a given number of dummy comments to each created post. Default is '
            . static::DEFAULT_NUMBER_OF_OBJECTS
            . '.';
    }

    /**
     * @return int
     * @throws ExceptionInterface
     * @throws CommandNotFoundException
     * @throws InvalidArgumentException
     * @throws CliReturnedNonZero
     * @throws WpCliCommandReturnedNonZero
     */
    protected function runIt(): int
    {
        if (Post::noneCreated()) {
            $this->callConsoleCommand(PostsSeeder::COMMAND_NAME);
        }

        $posts = Post::getAll();

        $progressBar = $this->progressBar($comments_total = count($posts) * $this->getQuantity());
        $progressBar->setMessage('Creating Comments...');
        $progressBar->start();

        foreach ($posts as $post) {
            $this->generateCommentsForPost($post, $progressBar);
        }

        $progressBar->setMessage("Done! A total of $comments_total comments were generated.");
        $progressBar->finish();

        return Command::SUCCESS;
    }

    /**
     * @param Post $post
     * @param ProgressBar $progressBar
     * @return void
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function generateCommentsForPost(Post $post, ProgressBar $progressBar): void
    {
        for ($i = 0; $i < $this->getQuantity(); $i++) {
            $comment_author = $this->faker->name;

            $progressBar->setMessage(
                "Generating a comment from user $comment_author for post $post->post_title."
            );

            $this->runWpCliCommandSilently(
                "comment create --comment_post_ID=$post->ID --comment_content='{$this->faker->paragraph()}' --comment_author='$comment_author' --quiet"
            );
        }
    }
}
