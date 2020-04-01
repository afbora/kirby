<?php

namespace Kirby\Cms;

use Closure;
use ReflectionClass;

/**
 * Kirby hook object
 *
 * @package   Kirby Cms
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier GmbH
 * @license   https://getkirby.com/license
 */
class Hook
{
    /**
     * The hook name
     *
     * @var string
     */
    protected $name;

    /**
     * The hook type
     *
     * @var string
     */
    protected $type;

    /**
     * The hook action
     *
     * @var string
     */
    protected $action;

    /**
     * The hook state
     *
     * @var string
     */
    protected $state;

    /**
     * The hook arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * Magic caller for hook methods
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $method, array $arguments = [])
    {
        $method = strtolower($method);

        if (isset($this->arguments[$method]) === true) {
            return $this->arguments[$method];
        }

        return $this;
    }

    /**
     * Creates a new hook object
     *
     * @param string $name
     * @param array $arguments
     */
    public function __construct(string $name, $arguments)
    {
        list($parts, $state) = explode(':', $name);

        if (strpos($name, '.') !== false) {
            list($type, $action) = explode('.', $parts);
        }

        $this->name      = $name;
        $this->type      = $type ?? $parts;
        $this->action    = $action ?? null;
        $this->state     = $state;
        $this->arguments = $arguments;
    }

    /**
     * Returns the action of the hook
     *
     * @return string|null
     */
    public function action(): ?string
    {
        return $this->action;
    }

    /**
     * Returns the arguments of the hook
     *
     * @return array
     */
    public function arguments(): array
    {
        return array_values($this->arguments);
    }

    /**
     * Creates new hook instance for reflected $arguments
     *
     * @param string $name
     * @param mixed ...$arguments
     * @return self
     * @throws \ReflectionException
     */
    public static function for(string $name, ...$arguments): self
    {
        $hook = new ReflectionClass('Kirby\Cms\Hook');
        $hook->newInstance($name, ...$arguments);
        $constructor = $hook->getConstructor();

        $args = [];
        foreach ($constructor->getParameters() as $parameter) {
            $paramName     = $parameter->getName();
            $paramPosition = $parameter->getPosition();

            // change $this to called model as $page, $file, $user
            if ($paramName === 'this') {
                $paramName = $hook->getProperty('type');
            }

            $args[$paramName] = $arguments[$paramPosition] ?? null;
        }

        return new static($name, $args);
    }

    /**
     * Returns the name of the hook
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Returns the wildcard name of the hook if available
     * Wildcard hook available when only action is exists as type.action:state
     *
     * @return string|null
     */
    public function wildcard(): ?string
    {
        return empty($this->action) === false ? $this->type . ':' . $this->state : null;
    }

    /**
     * Returns the type of the hook
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Register actions
     * It promises to run a callback in the specified action
     *
     * @param string $action
     * @param Closure $callback
     * @return Hook
     */
    public function promise(string $action, Closure $callback): self
    {
        if ($this->action === $action) {
            $callback(...$this->arguments());
        }

        return $this;
    }

    /**
     * Returns the state of the hook
     *
     * @return string
     */
    public function state(): string
    {
        return $this->state;
    }

    /**
     * Returns the arguments array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->arguments;
    }

    /**
     * Makes it possible to simply echo
     * or stringify the entire object
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Returns the hook name as string
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->name;
    }
}
