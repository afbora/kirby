<?php

namespace Kirby\Cms;

use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\PermissionException;
use Kirby\Toolkit\Str;

/**
 * Validators for all site actions
 *
 * @package   Kirby Cms
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier GmbH
 * @license   https://getkirby.com/license
 */
class SiteRules
{
    /**
     * @param \Kirby\Cms\Site $site
     * @param string $title
     * @return bool
     * @throws \Kirby\Exception\InvalidArgumentException
     * @throws \Kirby\Exception\PermissionException
     */
    public static function changeTitle(Site $site, string $title): bool
    {
        if ($site->permissions()->changeTitle() !== true) {
            throw new PermissionException(['key' => 'site.changeTitle.permission']);
        }

        if (Str::length($title) === 0) {
            throw new InvalidArgumentException(['key' => 'site.changeTitle.empty']);
        }

        return true;
    }

    /**
     * @param \Kirby\Cms\Site $site
     * @param array $content
     * @return bool
     * @throws \Kirby\Exception\PermissionException
     */
    public static function update(Site $site, array $content = []): bool
    {
        if ($site->permissions()->update() !== true) {
            throw new PermissionException(['key' => 'site.update.permission']);
        }

        return true;
    }
}
