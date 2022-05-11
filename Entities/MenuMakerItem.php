<?php

namespace Modules\Menumaker\Entities;

use Illuminate\Database\Eloquent\Model;

class MenuMakerItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menu_maker_items';

    public $timestamps = true;

    protected $touches = ['menu'];

    protected $casts = [
        'parameters' => 'json',
        'meta_data' => 'json'
    ];

    protected $fillable = [
        'menu_id',
        'parent_id',
        'unique_name',
        'weight',
        'menu_text',
        'parameters',
        'divide',
        'meta_data',
        'label',
        'link'
    ];

    public function menu()
    {
        return $this->belongsTo('Modules\Menumaker\Entities\MenuMaker','menu_id');
    }

    public function parent()
    {
        return $this->belongsTo('Modules\Menumaker\Entities\MenuMakerItem', 'parent_id', 'id');
    }

    public function setParentByUniqueName($name)
    {
        $parent = MenuMakerItem::where('menu_id', $this->menu_id)->where('unique_name', $name)->first();
        if($parent) {
            $this->parent_id = $parent->id;
        }
    }

    public function save(array $options = [])
    {
        parent::save($options);
        (new MenuMaker)->refresh();
    }

    public function getsons($id) {
        return $this -> where("parent_id", $id) -> get();
    }
    public function getall($id) {
        return $this -> where("menu_id", $id) -> orderBy("sort", "asc")->get();
    }

    public static function getNextSortRoot($menu){
        return self::where('menu_id',$menu)->where('depth',0)->max('sort') + 1;
    }
}
