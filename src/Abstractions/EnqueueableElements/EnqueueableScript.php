<?php

namespace Wordless\Abstractions\EnqueueableElements;

use Wordless\Abstractions\AbstractEnqueueableElement;
use Wordless\Abstractions\AbstractEnqueueableMounter;
use Wordless\Abstractions\Cachers\ScriptCacher;
use Wordless\Abstractions\InternalCache;
use Wordless\Exceptions\DuplicatedEnqueuableId;
use Wordless\Exceptions\FailedToFindCachedKey;
use Wordless\Exceptions\InternalCacheNotLoaded;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class EnqueueableScript extends AbstractEnqueueableElement
{
    /**
     * @return void
     * @throws InternalCacheNotLoaded
     * @throws PathNotFoundException
     */
    public static function enqueueAll(): void
    {
        try {
            $script_mounters_to_queue = InternalCache::getValueOrFail(
                'scripts.' . ScriptCacher::CLASSES_KEY
            );
        } catch (FailedToFindCachedKey $exception) {
            $script_mounters_to_queue = ScriptCacher::listEnqueueableElementsClasses()[ScriptCacher::CLASSES_KEY] ??
                [];
        }

        foreach ($script_mounters_to_queue as $script_mounter_class) {
            /** @var AbstractEnqueueableMounter $enqueueableScriptMounter */
            $enqueueableScriptMounter = new $script_mounter_class;
            $enqueueableScriptMounter->mountAndEnqueue();
        }
    }

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public function enqueue(): void
    {
        wp_enqueue_script($this->id, $this->filepath(), $this->dependencies, $this->version(), false);
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    protected function filepath(): string
    {
        return ProjectPath::theme($this->relative_file_path);
    }

    /**
     * @param string $id
     * @return void
     * @throws DuplicatedEnqueuableId
     */
    protected function setId(string $id): void
    {
        parent::setId("script-$id");
    }
}