<?php

namespace Wordless\Abstractions\Cachers;

use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

abstract class EnqueueableElementCacher extends BaseCacher
{
    abstract protected static function mounterDirectoryPath(): string;

    public const CLASSES_KEY = 'classes';

    /**
     * @return array
     * @throws PathNotFoundException
     */
    public static function listEnqueueableElementsClasses(): array
    {
        $enqueueable_classes_list = [self::CLASSES_KEY => []];
        $project_root_path = ProjectPath::root();

        foreach (DirectoryFiles::recursiveRead(static::mounterDirectoryPath()) as $enqueuable_class_filepath) {
            if (!Str::endsWith($enqueuable_class_filepath, '.php')) {
                continue;
            }

            $enqueuableClass = '\\'
                . str_replace('/', '\\', ucfirst(Str::afterLast(
                    $enqueuable_class_filepath,
                    Str::finishWith($project_root_path, DIRECTORY_SEPARATOR)
                )));

            $enqueueable_classes_list[self::CLASSES_KEY][] = $enqueuableClass;
        }

        return $enqueueable_classes_list;
    }

    /**
     * @return array
     * @throws PathNotFoundException
     */
    protected function mountCacheArray(): array
    {
        return static::listEnqueueableElementsClasses();
    }
}