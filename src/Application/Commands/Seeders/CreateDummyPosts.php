<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders;


use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Application\Commands\Seeders\Contracts\BaseCreateDummyCommand;
use Wordless\Application\Commands\WpCliCaller;
use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\Models\Category;

class CreateDummyPosts extends BaseCreateDummyCommand
{
    final public const COMMAND_NAME = 'generate:posts';
    private const HOW_MANY_PARAGRAPHS_PER_POST = 3;
    private const HOW_MANY_POSTS_PER_CATEGORY = 20;
    private const UNCATEGORIZED_CATEGORY = 'uncategorized';

    protected function description(): string
    {
        return 'Creates dummy posts.';
    }

    protected function help(): string
    {
        return 'This command will help create posts in the database.';
    }

    /**
     * @return int
     * @throws Exception
     * @throws ExceptionInterface
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Creating Posts...', function () {
            if (count($categories = Category::all()) <= 1 && $categories[1]->slug === self::UNCATEGORIZED_CATEGORY) {
                $this->callConsoleCommand(
                    CreateDummyTaxonomyTerms::COMMAND_NAME,
                );
            }

            foreach (Category::all() as $category) {
                for ($i = 0; $i < self::HOW_MANY_POSTS_PER_CATEGORY; $i++) {
                    $date = date('Y-m-d', strtotime($i > 0 ? "-$i days" : 'now'));
                    $uuid = Str::uuid();
                    $post_content = str_replace(["\n", "\r"], '', nl2br($this->faker->paragraphs(
                        self::HOW_MANY_PARAGRAPHS_PER_POST,
                        true
                    )));
                    $post_excerpt = $this->faker->paragraph();
                    $category_name = $category->name;

                    $full_command = "post create --post_status=publish --post_date=$date --post_title=Post-$category_name-$uuid --post_content='$post_content' --post_excerpt='$post_excerpt' --post_category=$category_name --quiet";

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
