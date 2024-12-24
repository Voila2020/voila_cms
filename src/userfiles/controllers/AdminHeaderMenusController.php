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
		$this->translation_table = "menu_translations";
		$id = CRUDBooster::getCurrentId();
		if (Request::segment(3) == 'edit') {
			$id = Request::segment(4);
			Session::put('current_row_id', $id);
		}
		$row = CRUDBooster::first($this->table, $id);
		$row = (Request::segment(3) == 'edit') ? $row : null;
		$this->col = [];
		$this->col[] = ["label" => "URL", "name" => "link", "translation" => false];
		$this->col[] = ["label" => "Name", "name" => "name", "translation" => true];
		$this->col[] = ["label" => "Is Active", "name" => "active"];
		$this->form = [];
		// Fetch supported languages
		$this->form[] = [
			'label' => 'Name',
			'name' => 'name',
			'type' => 'text',
			'validation' => 'required|min:1|max:255',
			'translation' => true,
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


	public function getIndex()
	{
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
		
		return $this->view('crudbooster::headermenus.index', compact('menu_active', 'menu_inactive', 'return_url', 'page_title'));
	}


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


	public function hook_before_add(&$postdata)
	{
		$postdata['type'] = 'header';
	}


	public function hook_before_edit(&$postdata, $id)
	{
		$postdata['type'] = 'header';
	}


	public function hook_after_delete($id)
	{
		DB::table('menus')->where('type', 'header')->where('parent_id', $id)->delete();
	}


// New method to handle child inputs
	private function handleChildInputs($ro, $id, $lastInsertId)
	{
		if ($ro['type'] == 'child') {
			$name = str_slug($ro['label'], '');
			$columns = $ro['columns'];
			$getColName = request($name . '-' . $columns[0]['name']);
			$count_input_data = ($getColName) ? (count($getColName) - 1) : 0;
			$child_array = [];
			$fk = $ro['foreign_key'];
			if ($getColName > 0) {
				for ($i = 0; $i <= $count_input_data; $i++) {
					$column_data = [];
					foreach ($columns as $col) {
						$colname = $col['name'];
						$colvalue = request($name . '-' . $colname)[$i];
						$column_data[$colname] = $col['type'] == 'hidden' && strpos(
							$colname,
							'webp'
						) !== false ? $this->handleWebpImage($colname, $colvalue, $column_data) : $colvalue;
					}
					if (!empty($column_data)) {
						$column_data[$fk] = (!empty($id) ? $id : $lastInsertId);
						$child_array[] = $column_data;
					}
				}
				$childtable = CRUDBooster::parseSqlTable($ro['table'])['table'];
				DB::table($childtable)->insert($child_array);
			}
		}
	}


// New method to handle webp images
	private function handleWebpImage($colname, $colvalue, &$column_data)
	{
		$image = request($colname);
		if (isset($image) && strpos($image, 'data:image/') === 0) {
			$image = str_replace('data:image/webp;base64,', '', $image);
			$image = str_replace(' ', '+', $image);
			$directory = public_path(config('crudbooster.filemanager_current_path') . 'webp_images/');
			$imageName = pathinfo(
					basename($column_data[str_replace("_webp", "", $colname)]),
					PATHINFO_FILENAME
				) . '.webp';
			$imagePath = $directory . $imageName;
			if (!file_exists($imagePath)) {
				file_put_contents($imagePath, base64_decode($image));
			}

			return config('crudbooster.filemanager_current_path') . 'webp_images/' . $imageName;
		}

		return $colvalue;
	}


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
