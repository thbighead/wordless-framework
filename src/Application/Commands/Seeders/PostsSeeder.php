<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders;

use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\ProgressBar;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Exceptions\FailedToGetCommandOptionValue;
use Wordless\Application\Commands\Exceptions\FailedToRunCommand;
use Wordless\Application\Commands\Seeders\Contracts\SeederCommand;
use Wordless\Application\Commands\Seeders\PostsSeeder\Exceptions\FailedToGenerateCategorizedPost;
use Wordless\Application\Commands\Seeders\PostsSeeder\Exceptions\FailedToPopulateCategories;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions\FailedToRunWpCliCommand;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\Internal\Exceptions\CallInternalCommandException;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Exceptions\InitializingModelWithWrongTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions\TermInstantiationError;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Read\Exceptions\CouldNotResolveNoneCreated;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Read\Exceptions\CouldNotResolveNoneCreatedForCategory;
use Wordless\Wordpress\Models\Category;

class PostsSeeder extends SeederCommand
{
    final public const COMMAND_NAME = 'seeder:posts';
    private const DEFAULT_POST_SIZE_IN_PARAGRAPHS = 3;
    private const MIN_POST_SIZE_IN_PARAGRAPHS = 1;
    private const OPTION_POST_SIZE_IN_PARAGRAPHS = 'paragraphs';

    protected function description(): string
    {
        return 'Creates dummy posts for each created category.';
    }

    protected function help(): string
    {
        return 'Creates a given number of dummy posts to each created category with a given number of paragraphs. Default number of posts per category is '
            . self::DEFAULT_NUMBER_OF_OBJECTS
            . ' and default number of paragraphs to each post is '
            . self::DEFAULT_POST_SIZE_IN_PARAGRAPHS
            . '.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return array_merge(parent::options(), [
            OptionDTO::make(
                self::OPTION_POST_SIZE_IN_PARAGRAPHS,
                'Specify how many paragraphs each post created must have.',
                mode: OptionMode::required_value,
                default: static::DEFAULT_POST_SIZE_IN_PARAGRAPHS
            ),
        ]);
    }

    /**
     * @return int
     * @throws FailedToPopulateCategories
     * @throws FailedToRunCommand
     */
    protected function runIt(): int
    {
        try {
            if (Category::noneCreated()) {
                $this->callConsoleCommand(TaxonomyTermsSeeder::COMMAND_NAME);
            }
        } catch (CallInternalCommandException
        |CliReturnedNonZero
        |CouldNotResolveNoneCreated
        |CouldNotResolveNoneCreatedForCategory $exception) {
            throw new FailedToPopulateCategories($exception);
        }

        try {
            $progressBar = $this->progressBar($posts_total = Category::count() * $this->getQuantity());
            $progressBar->setMessage('Creating Posts...');
            $progressBar->start();

            foreach (Category::all() as $category) {
                $this->generatePostsCategorizedAs($category, $progressBar);
            }
        } catch (FailedToGetCommandOptionValue
        |FailedToGenerateCategorizedPost
        |InitializingModelWithWrongTaxonomyName
        |TermInstantiationError $exception) {
            throw new FailedToRunCommand(static::COMMAND_NAME, $exception);
        }

        $progressBar->setMessage("Done! A total of $posts_total posts were generated.");
        $progressBar->finish();

        return Command::SUCCESS;
    }

    /**
     * @param Category $category
     * @param ProgressBar $progressBar
     * @return void
     * @throws FailedToGenerateCategorizedPost
     */
    private function generatePostsCategorizedAs(Category $category, ProgressBar $progressBar): void
    {
        try {
            for ($i = 0; $i < $this->getQuantity(); $i++) {
                $post_title = $this->faker->sentence();
                $category_name = $category->name;
                $post_date = Carbon::now()->subDays($i)->format('Y-m-d H:i:s');
                $post_content = Str::remove(
                    nl2br($this->faker->paragraphs($this->getPostParagraphs(), true)),
                    ["\n", "\r"]
                );

                $progressBar->setMessage("Creating post '$post_title' categorized as '$category_name'...");

                $this->runWpCliCommandSilently(
                    "post create --post_status=publish --post_date='$post_date' --post_title='$post_title' --post_content='$post_content' --post_excerpt='{$this->faker->paragraph()}' --post_category='$category->name' --quiet"
                );
            }
        } catch (FailedToGetCommandOptionValue|FailedToRunWpCliCommand|WpCliCommandReturnedNonZero $exception) {
            throw new FailedToGenerateCategorizedPost($category, $exception);
        }
    }

    private function getPostParagraphs(): int
    {
        try {
            return max(
                abs((int)$this->input->getOption(self::OPTION_POST_SIZE_IN_PARAGRAPHS)),
                self::MIN_POST_SIZE_IN_PARAGRAPHS
            );
        } catch (InvalidArgumentException) {
            return self::MIN_POST_SIZE_IN_PARAGRAPHS;
        }
    }
}
