<?php

namespace Kirby\Cms;

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
     * Creates a new field object
     *
     * @param string $name
     * @param array $arguments
     */
    public function __construct(string $name, array $arguments = [])
    {
        list($parts, $state) = explode(':', $name);
        list($type, $action) = explode('.', $parts);

        $this->name      = $name;
        $this->type      = $type;
        $this->action    = $action;
        $this->state     = $state;
        $this->arguments = $arguments;
    }

    /**
     * Returns the action of the hook
     *
     * @return string
     */
    public function action(): string
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
        return $this->arguments;
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
     * Returns the type of the hook
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
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
     * Returns the prop array
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
