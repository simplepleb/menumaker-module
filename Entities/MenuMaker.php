<?php

namespace Modules\Menumaker\Entities;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Cache;
use Session;

class MenuMaker extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menu_maker';

    public $timestamps = true;

    protected $fillable = [
        'machine_name',
        'display_name',
        'description',
        'lang'
    ];

    public function items()
    {
        return $this->hasMany('Modules\Menumaker\Entities\MenuMakerItem' , 'menu_id');
    }

    public function refresh()
    {
        Session::forget('menu_maker');
        Cache::forget('menu_maker');
    }

    public function save(array $options = [])
    {
        parent::save($options);
        (new MenuMaker)->refresh();
    }
}
