<?php
namespace Modules\Menumaker\Observers;

use Modules\Menumaker\Entities\MenuMaker;

class MenuObserver
{
    /**
     * Handle the MenuMaker "created" event.
     *
     * @param MenuMaker $menuMaker
     * @return void
     */
    public function created(MenuMaker $menuMaker)
    {
        \Session::forget('menu_maker');
        \Cache::forget('menu_maker');
    }

    /**
     * Handle the MenuMaker "updated" event.
     *
     * @param MenuMaker $menuMaker
     * @return void
     */
    public function updated(MenuMaker $menuMaker)
    {
        \Session::forget('menu_maker');
        \Cache::forget('menu_maker');
    }

    /**
     * Handle the MenuMaker "deleted" event.
     *
     * @param MenuMaker $menuMaker
     * @return void
     */
    public function deleted(MenuMaker $menuMaker)
    {
        \Session::forget('menu_maker');
        \Cache::forget('menu_maker');
    }

    /**
     * Handle the MenuMaker "forceDeleted" event.
     *
     * @param MenuMaker $menuMaker
     * @return void
     */
    public function forceDeleted(MenuMaker $menuMaker)
    {
        //
    }
}
