<?php

/**
 * Putting this here to help remind you where this came from.
 *
 * I'll get back to improving this and adding more as time permits
 * if you need some help feel free to drop me a line.
 *
 * * Twenty-Years Experience
 * * PHP, JavaScript, Laravel, MySQL, Java, Python and so many more!
 *
 *
 * @author  Simple-Pleb <plebeian.tribune@protonmail.com>
 * @website https://www.simple-pleb.com
 * @source https://github.com/simplepleb/article-module
 *
 * @license Free to do as you please
 *
 * @since 1.0
 *
 */

namespace Modules\Menumaker\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Menumaker\Entities\MenuMaker;
use Modules\Menumaker\Observers\MenuObserver;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        MenuMaker::observe(MenuObserver::class);
    }
}
