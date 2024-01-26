<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Provider\DTO;

use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO\Exceptions\TriedToSetFunctionWhenRemovingListener;
use Wordless\Infrastructure\Wordpress\Hook;
use Wordless\Infrastructure\Wordpress\Listener;

class RemoveHookDTO
{
    final public const FUNCTION_KEY = 'function';
    final public const PRIORITY_KEY = 'priority';

    private array $functions_used_on_hook = [];
    private bool $is_on_listener;

    public static function make(Hook|string $hook): static
    {
        return new static($hook instanceof Hook ? $hook->value : $hook);
    }

    public function getFunctionsUsedOnHook(): array
    {
        return $this->functions_used_on_hook;
    }

    public function isOnListener(): bool
    {
        if (isset($this->is_on_listener)) {
            return $this->is_on_listener;
        }

        return $this->is_on_listener = is_a($this->hook, Listener::class, true);
    }

    /**
     * @param string $function_used_on_hook
     * @param int|null $function_priority_used_on_hook
     * @return $this
     * @throws TriedToSetFunctionWhenRemovingListener
     */
    public function setFunction(string $function_used_on_hook, ?int $function_priority_used_on_hook = null): static
    {
        if ($this->isOnListener()) {
            throw new TriedToSetFunctionWhenRemovingListener($this->hook);
        }

        $this->functions_used_on_hook[] = [
            self::FUNCTION_KEY => $function_used_on_hook,
            self::PRIORITY_KEY => $function_priority_used_on_hook,
        ];

        return $this;
    }

    private function __construct(public readonly string $hook)
    {
    }
}
