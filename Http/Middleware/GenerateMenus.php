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
 * @source https://github.com/simplepleb/thememanager-module
 *
 * @license Free to do as you please
 *
 * @since 1.0
 *
 */

namespace Modules\Menumaker\Http\Middleware;

use Closure;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        /*\Menu::make('admin_sidebar', function ($menu) {

            // comments
            $menu->add('<i class="fas fa-sitemap c-sidebar-nav-icon"></i> Menus', [
                'route' => 'backend.menumaker.index',
                'class' => 'c-sidebar-nav-item',
            ])
                ->data([
                    'order' => 102,
                    'activematches' => ['admin/thememanager*'],
                    'permission' => ['view_menus'],
                ])
                ->link->attr([
                    'class' => 'c-sidebar-nav-link',
                ]);
        })->sortBy('order');*/

        return $next($request);


    }
}
