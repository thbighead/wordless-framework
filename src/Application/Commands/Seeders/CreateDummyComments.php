<?php

namespace Wordless\Application\Commands\Seeders;


use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Application\Commands\Seeders\Contracts\BaseCreateDummyCommand;
use Wordless\Application\Commands\WpCliCaller;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Wordpress\Models\Post;

class CreateDummyComments extends BaseCreateDummyCommand
{

    public const COMMAND_NAME = 'generate:comments';
    private const HOW_MANY_COMMENTS_PER_POST = 5;

    /**
     * @throws PathNotFoundException
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name);
        require_once ProjectPath::wpCore('wp-admin/includes/taxonomy.php');
    }

    protected function description(): string
    {
        return 'A custom command to create dummy comments to all posts.';
    }

    /**
     * @return int
     * @throws Exception
     * @throws ExceptionInterface
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Creating Comments...', function () {
            if (count(Post::getAll()) <= 1) {
                $this->callConsoleCommand(
                    CreateDummyPosts::COMMAND_NAME,
                );
            }

            foreach (Post::getAll() as $post) {
                for ($i = 0; $i < self::HOW_MANY_COMMENTS_PER_POST; $i++) {
                    $post_id = $post->ID;
                    $comment_author = $this->faker->name;
                    $comment_content = $this->faker->paragraph();
                    $full_command = "comment create --comment_post_ID=$post_id --comment_content='$comment_content' --comment_author='$comment_author' --quiet";

                    $this->callConsoleCommand(
                        WpCliCaller::COMMAND_NAME,
                        [WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $full_command],
                    );
                }

                $this->output->write(self::PROGRESS_MARK);
            }
        });

        return Command::SUCCESS;
    }
}
