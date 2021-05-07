<?php

namespace Modules\Menumaker\Http\Controllers\Backend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Menumaker\Entities\MenuMakerItem;
use Modules\Menumaker\Entities\MenuMaker;

class MenuBuilderController extends Controller
{
    public function render()
    {
        $menu = new MenuMaker();
        $menuitems = new MenuMakerItem();
        $menulist = $menu->select(['id', 'display_name'])->get();
        $menulist = $menulist->pluck('display_name', 'id')->prepend('Select menu', 0)->all();

        //$roles = Role::all();

        if ((request()->has("action") && empty(request()->input("menu"))) || request()->input("menu") == '0') {
            return view('menumaker::menu-html')->with("menulist" , $menulist);
        } else {

            $menu = MenuMaker::where('id',request()->input("menu"))->first();

            $menus = $menuitems->getall(request()->input("menu"));

            $data = ['menus' => $menus, 'indmenu' => $menu, 'menulist' => $menulist];
            if( config('menumaker.use_roles')) {
                $data['roles'] = \DB::table('roles')->select(['id','name'])->get();
                $data['role_pk'] = config('menumaker.roles_pk');
                $data['role_title_field'] = config('menumaker.roles_title_field');
            }
            return view('menumaker::menu-html', $data);
        }

    }

    public function scripts()
    {
        return view('vendor.harimayco-menu.scripts');
    }

    public function select($name = "menu", $menulist = array())
    {
        $html = '<select name="' . $name . '">';

        foreach ($menulist as $key => $val) {
            $active = '';
            if (request()->input('menu') == $key) {
                $active = 'selected="selected"';
            }
            $html .= '<option ' . $active . ' value="' . $key . '">' . $val . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    public static function getByName($name)
    {
        $menu_id = MenuMaker::byName($name)->id;
        return self::get($menu_id);
    }

    public static function get($menu_id)
    {
        $menuItem = new MenuMakerItem();
        $menu_list = $menuItem->getall($menu_id);

        $roots = $menu_list->where('menu_id', (integer) $menu_id)->where('parent_id', 0);

        $items = self::tree($roots, $menu_list);
        return $items;
    }

    private static function tree($items, $all_items)
    {
        $data_arr = array();
        $i = 0;
        foreach ($items as $item) {
            $data_arr[$i] = $item->toArray();
            $find = $all_items->where('parent_id', $item->id);

            $data_arr[$i]['child'] = array();

            if ($find->count()) {
                $data_arr[$i]['child'] = self::tree($find, $all_items);
            }

            $i++;
        }

        return $data_arr;
    }
}
