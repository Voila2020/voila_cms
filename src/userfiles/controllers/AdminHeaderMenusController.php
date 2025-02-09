<?php

namespace App\Http\Controllers;


use App\Models\Language;
use App\Models\Menu;
use crocodicstudio\crudbooster\controllers\CBController;
use crocodicstudio\crudbooster\helpers\CB;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Log;
use crocodicstudio\crudbooster\fonts\Fontawesome;
use PHPUnit\Exception;

class AdminHeaderMenusController extends CBController
{
    public function cbInit()
    {
        $this->title_field = "name";
        $this->limit = "20";
        $this->orderby = "id,desc";
        $this->sortable_table = true;
        $this->global_privilege = false;
        $this->button_table_action = true;
        $this->button_bulk_action = true;
        $this->button_action_style = "button_icon";
        $this->record_seo = false;
        $this->button_add = false;
        $this->button_edit = true;
        $this->button_delete = true;
        $this->button_detail = true;
        $this->pdf_direction = "ltr";
        $this->button_show = true;
        $this->button_filter = false;
        $this->button_import = false;
        $this->button_export = false;
        $this->page_seo = false;
        $this->table = "menus";
        $this->translation_table = "";
        $id = CRUDBooster::getCurrentId();
        if (Request::segment(3) == 'edit') {
            $id = Request::segment(4);
            Session::put('current_row_id', $id);
        }
        $row = CRUDBooster::first($this->table, $id);
        $row = (Request::segment(3) == 'edit') ? $row : null;
        $this->col = [];
        $this->col[] = ["label" => "URL", "name" => "link"];
        $this->col[] = ["label" => "Name", "name" => "name"];
        $this->col[] = ["label" => "Is Active", "name" => "active"];
        //-----------------------------------------------//
        $this->form = [];
        $this->form[] = [
            'label' => 'Name(en)',
            'name' => 'name_en',
            'type' => 'text',
            'validation' => 'required|min:1|max:255',
            'width' => 'col-sm-10',
        ];
        $this->form[] = [
            'label' => 'Name(ar)',
            'name' => 'name_ar',
            'type' => 'text',
            'validation' => 'required|min:1|max:255',
            'width' => 'col-sm-10',
        ];
        $this->form[] = [
            'label' => 'URL',
            'name' => 'link',
            'type' => 'text',
            'validation' => 'required|min:1|max:8000|url',
            'translation' => false,
            'width' => 'col-sm-10',
        ];
        $this->form[] = [
            "label" => "Active",
            "name" => "active",
            "type" => "radio",
            "required" => true,
            "validation" => "required|integer",
            "dataenum" => ['1|Active', '0|InActive'],
            'value' => '1',
        ];
    }
    //-----------------------------------------------//
    public function getIndex()
    {
        $current_lang = app()->getLocale();
        $this->cbLoader();
        $module = CRUDBooster::getCurrentModule();
        if (!CRUDBooster::isView() && $this->global_privilege == false) {
            CRUDBooster::insertLog(cbLang('log_try_view', ['module' => $module->name]));

            return CRUDBooster::redirect(CRUDBooster::adminPath(), cbLang('denied_access'));
        }
        $menu_active = Menu::where('type', 'header')
            ->whereNull('parent_id')
            ->where('active', 1)
            ->orderBy('sorting', 'asc')
            ->with('children') // Eager load children menus
            ->get();
        $menu_inactive = Menu::withoutGlobalScope('active')->where('type', 'header')
            ->whereNull('parent_id')
            ->where('active', 0)
            ->orderBy('sorting', 'asc')
            ->get();
        foreach ($menu_inactive as &$menu) {
            $child = Menu::where('type', 'header')
                ->where('active', 1)
                ->where('parent_id', $menu->id)
                ->orderBy('sorting', 'asc')
                ->get();
            if ($child->isNotEmpty()) {
                $menu->children = $child;
            }
        }
        $return_url = Request::fullUrl();
        $page_title = 'Menu Management';

        return $this->view('crudbooster::headermenus.index', compact('menu_active', 'menu_inactive', 'return_url', 'page_title', 'current_lang'));
    }
    //-----------------------------------------------//
    private function getMenuChildren($menu)
    {
        $children = Menu::where('type', 'header')
            ->where('active', 1)
            ->where('parent_id', $menu->id)
            ->orderBy('sorting', 'asc')
            ->get();
        $children->each(function ($child) {
            $child->children = $this->getMenuChildren($child);
        });

        return $children;
    }
    //-----------------------------------------------//
    public function hook_before_add(&$postdata)
    {
        $postdata['type'] = 'header';
    }
    //-----------------------------------------------//
    public function hook_before_edit(&$postdata, $id)
    {
        $postdata['type'] = 'header';
    }
    //-----------------------------------------------//
    public function hook_after_delete($id)
    {
        DB::table('menus')->where('type', 'header')->where('parent_id', $id)->delete();
    }
    //-----------------------------------------------//
    public function postSaveMenu()
    {
        $menus = json_decode(Request::input('menus'), true)[0];
        $isActive = Request::input('isActive');
        DB::beginTransaction();
        try {
            foreach ($menus as $index => $menu) {
                $pid = trim(str_replace(['{', '}'], '', $menu['id']));
                $this->updateChildrenSorting($menu, $isActive);
                Menu::where('type', 'header')
                    ->where('id', $pid)
                    ->update([
                        'sorting' => $index + 1,
                        'parent_id' => null,
                        'active' => $isActive,
                    ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(['success' => true]);
    }
    //-----------------------------------------------//
    private function updateChildrenSorting($item, $isActive)
    {
        $parentId = trim(str_replace(['{', '}'], '', $item['id']));
        if (!empty($item['children'][0])) {
            foreach ($item['children'][0] as $index => $child) {
                $childId = trim(str_replace(['{', '}'], '', $child['id']));
                $menuItem = Menu::find($childId);  // Preserve translation and model integrity
                if ($menuItem) {
                    $menuItem->update([
                        'sorting' => $index + 1,
                        'parent_id' => $parentId,
                        'active' => $isActive,
                    ]);
                }
                $this->updateChildrenSorting($child, $isActive);
            }
        }
    }
}
