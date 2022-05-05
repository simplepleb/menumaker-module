<?php

namespace Modules\Menumaker\Http\Controllers\Backend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Menu;
use Modules\Menumaker\Entities\MenuMaker;
use Modules\Menumaker\Entities\MenuMakerItem;

class MenumakerController extends Controller
{
    /**
     * Create Menu for Module
     */
    public static function generateModuleMenu()
    {

        Menu::modify('super_admin', function ($menu) {
            if (\Auth::user()->can('edit_settings')) {

                $menu->add([
                    'url' => route('backend.menumaker.index'),
                    'title' => __('Menu Builder'),
                    'icon' => 'ni ni-briefcase-24 text-primary'
                ])/*->order(2)*/
                ;

            }

        });

    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('menumaker::index');
    }

    public function api_index($menu_name)
    {
        $menu = Menu::get($menu_name)->all();
        return $menu;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('menumaker::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('menumaker::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('menumaker::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function createnewmenu()
    {

        $menu = new MenuMaker();
        $menu->display_name = request()->input("menuname");
        $menu->machine_name = slug_format(request()->input("menuname"));
        $menu->save();
        return json_encode(array("resp" => $menu->id));
    }

    public function deleteitemmenu()
    {
        $menuitem = MenuMakerItem::find(request()->input("id"));

        $menuitem->delete();
    }

    public function deletemenug()
    {
        $menus = new MenuMakerItem();
        $getall = $menus->getall(request()->input("id"));
        if (count($getall) == 0) {
            $menudelete = MenuMaker::find(request()->input("id"));
            $menudelete->delete();

            return json_encode(array("resp" => "you delete this item"));
        } else {
            return json_encode(array("resp" => "You have to delete all items first", "error" => 1));

        }
    }

    public function updateitem()
    {
        $arraydata = request()->input("arraydata");
        if (is_array($arraydata)) {
            foreach ($arraydata as $value) {
                $menuitem = MenuMakerItem::find($value['id']);
                $menuitem->label = $value['label'];
                $menuitem->link = $value['link'];
                $menuitem->class = $value['class'];
                if (config('menumaker.use_roles')) {
                    $menuitem->role_id = $value['role_id'];
                }
                $menuitem->save();
            }
        } else {
            $menuitem = MenuMakerItem::find(request()->input("id"));
            $menuitem->label = request()->input("label");
            $menuitem->link = request()->input("url");
            $menuitem->class = request()->input("clases");
            if (config('menumaker.use_roles')) {
                $menuitem->role_id = request()->input("role_id");
            }
            $menuitem->save();
        }
    }

    public function addcustommenu()
    {
// dd( request()->input());
        $menuitem = new MenuMakerItem();
        $menuitem->label = request()->input("labelmenu");
        $menuitem->link = request()->input("linkmenu");
        $menuitem->unique_name = slug_format(request()->input("labelmenu"));
        $menuitem->parameters = '{}';
        if (config('menu.use_roles')) {
            $menuitem->role_id = request()->input("role_id");
        }
        $menuitem->menu_id = request()->input("menu_id");
        $menuitem->sort = MenuMakerItem::getNextSortRoot(request()->input("menu_id"));
        $menuitem->save();

    }

    public function generatemenucontrol()
    {
        $menu = MenuMaker::find(request()->input("menu_id"));
        $menu->display_name = request()->input("menuname");

        $menu->save();
        if (is_array(request()->input("arraydata"))) {
            // dd( request()->input("arraydata") );
            foreach (request()->input("arraydata") as $value) {
                $parent = NULL;
                if( $value["parent"] > 0 ) {
                    $parent = $value["parent"];
                }
                $menuitem = MenuMakerItem::find($value["id"]);
                $menuitem->parent_id = $parent;
                $menuitem->sort = $value["sort"];
                $menuitem->depth = $value["depth"];
                if (config('menu.use_roles')) {
                    $menuitem->role_id = request()->input("role_id");
                }
                $menuitem->save();
            }
        }
        echo json_encode(array("resp" => 1));

    }
}
