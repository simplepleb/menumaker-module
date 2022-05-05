<?php

namespace Modules\Menumaker\Http\Middleware;

use Closure;
use Modules\Menumaker\Entities\MenuMaker;
use Menu;
use Auth;
use Cache;
use Session;


Use Illuminate\Support\Facades\App;

class MenuMakerMiddleware
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // @todo this fails unless the session and cache are cleared before (??)
        \Session::forget('menu_maker');
        \Cache::forget('menu_maker');
        // return $next($request);
        $cached_menu = unserialize(\Session::get('menu_maker'));

        if( !Auth::check() ) {
            $cached_menu = unserialize(Cache::get('menu_maker'));

        }
        if(!$cached_menu) {
            $this->makeMenus();
            return $next($request);
        }

        //if( $cached_menu !== false ) {
            $app = App::getFacadeApplication();
// dd('Not False');
            $app->instance('menu',null);
            $app['menu'] = $cached_menu;
            // dd( serialize($app['menu']),'WHERE' );
            return $next($request);
        //}

        // $this->makeMenus();

        //return $next($request);
    }

    public function makeMenus()
    {
        $menu_records = MenuMaker::with('items')->get();
// dd('MenuMaker', $menu_records);
        foreach ($menu_records as $menu_record) {
            $menu = Menu::make($menu_record->machine_name,function($menu){});

            $items = $menu_record->items->filter(function ($value, $key) {
                return $value->parent_id == null;
            })->sortBy('sort');

            $this->addItems($menu, $items, $menu_record->items->sortBy('sort'));

        }

        $app = App::getFacadeApplication();

        if(\Auth::user()) {
            session(['menu_maker' => serialize($app['menu'])] );
            \Session::set('menu_maker',  serialize($app['menu']));
        }
        else {
            \Cache::forever('menu_maker', serialize($app['menu']));
        }
        //dd( session('menu_maker'), 'THERE' );
    }

    private function addItems($to, $items, $all_items)
    {
        $user = Auth::user();

        foreach ($items as $item) {
            $sub_items = [];

            foreach ($all_items as $key => $it) {
                if($it->parent_id == $item->id) {
                    $sub_items[] = $it;
                    unset($all_items[$key]);
                }
            }

            $can_access = true;

            if( is_array($item->meta_data) && array_key_exists('permission',$item->meta_data) ) {
                if(!$user || !$user->can($item->meta_data['permission'])) {
                    $can_access = false;
                }
            }
            if( is_array($item->meta_data) && array_key_exists('role',$item->meta_data) ) {
                if (!$user || !$user->hasRole($item->meta_data['role'])) {
                    $can_access = false;
                }
            }
            if($can_access) {
                // dd( $item, 'HERE' );
                $new_item = $to->add($item->label, $item->link)->nickname($item->label.rand(0,5000));
                //dd( $new_item );
                $new_item->data($item->meta_data);
                $this->addItems($new_item, $sub_items, $all_items);
            }
        }
    }
}
