<?php

namespace crocodicstudio\crudbooster\controllers;

error_reporting(E_ALL ^ E_NOTICE);

use crocodicstudio\crudbooster\export\DefaultExportXls;
use crocodicstudio\crudbooster\export\DefaultExportCsv;
use crocodicstudio\crudbooster\helpers\CB;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CBController extends Controller
{
    public $data_inputan;

    public $columns_table;

    public $module_name;

    public $table;

    public $translation_table;

    public $translation_main_column;

    public $title_field;

    public $primary_key = 'id';

    public $arr = [];

    public $col = [];

    public $form = [];

    public $data = [];

    public $addaction = [];

    public $orderby = null;

    public $password_candidate = null;

    public $date_candidate = null;

    public $limit = 20;

    public $global_privilege = false;

    public $show_numbering = false;

    public $alert = [];

    public $index_button = [];

    public $button_filter = true;

    public $button_export = true;

    public $button_import = true;

    public $button_show = true;

    public $sortable_table = true;

    public $pdf_direction = 'ltr';

    public $page_seo = false;

    public $record_seo = false;

    public $button_addmore = true;

    public $button_table_action = true;

    public $button_bulk_action = true;

    public $button_add = true;

    public $button_delete = true;

    public $button_cancel = true;

    public $button_save = true;

    public $button_edit = true;

    public $button_detail = true;

    public $button_action_style = 'button_icon';

    public $button_action_width = null;

    public $index_statistic = [];

    public $index_additional_view = [];

    public $pre_index_html = null;

    public $post_index_html = null;

    public $load_js = [];

    public $load_css = [];

    public $script_js = null;

    public $style_css = null;

    public $sub_module = [];

    public $show_addaction = true;

    public $table_row_color = [];

    public $button_selected = [];

    public $return_url = null;

    public $parent_field = null;

    public $parent_id = null;

    public $hide_form = [];

    public $index_return = false; //for export

    public $sidebar_mode = 'normal';

    public $websiteLanguages = [];

    public function cbLoader()
    {
        $this->cbInit();

        $this->checkHideForm();

        //-------------------------------------------//
        if ($this->translation_table) {
            $this->translation_main_column = CRUDBooster::getTranslationTableMainColumn($this->translation_table);
        }
        //-------------------------------------------//

        $this->primary_key = CB::pk($this->table);
        $this->translation_table = $this->translation_table;
        $this->columns_table = $this->col;
        $this->data_inputan = $this->form;
        $this->websiteLanguages = DB::table("languages")->where("active", 1)->orderby("default", "desc")->get();
        $this->data['pk'] = $this->primary_key;
        $this->data['forms'] = $this->data_inputan;
        $this->data['hide_form'] = $this->hide_form;
        $this->data['addaction'] = ($this->show_addaction) ? $this->addaction : null;
        $this->data['table'] = $this->table;
        $this->data['title_field'] = $this->title_field;
        $this->data['appname'] = CRUDBooster::getSetting('appname');
        $this->data['alerts'] = $this->alert;
        $this->data['index_button'] = $this->index_button;
        $this->data['show_numbering'] = $this->show_numbering;
        $this->data['button_detail'] = $this->button_detail;
        $this->data['button_edit'] = $this->button_edit;
        $this->data['button_show'] = $this->button_show;
        $this->data['sortable_table'] = $this->sortable_table;
        $this->data['pdf_direction'] = $this->pdf_direction;
        $this->data['button_add'] = $this->button_add;
        $this->data['button_delete'] = $this->button_delete;
        $this->data['button_filter'] = $this->button_filter;
        $this->data['button_export'] = $this->button_export;
        $this->data['button_addmore'] = $this->button_addmore;
        $this->data['button_cancel'] = $this->button_cancel;
        $this->data['button_save'] = $this->button_save;
        $this->data['button_table_action'] = $this->button_table_action;
        $this->data['button_bulk_action'] = $this->button_bulk_action;
        $this->data['button_import'] = $this->button_import;
        $this->data['button_action_width'] = $this->button_action_width;
        $this->data['page_seo'] = $this->page_seo;
        $this->data['record_seo'] = $this->record_seo;

        foreach ($this->col as $col) {
            if (isset($col['switch']) && $col['switch'] == true) {
                $acitvLabel = cbLang('activate_label') . ' ' . $col['label'];
                $deactiveLabel = cbLang('deactivate_label') . ' ' . $col['label'];
                $this->button_selected[] = ['label' => $acitvLabel, 'icon' => 'fa fa-check', 'name' => 'active_all-' . $col['name']];
                $this->button_selected[] = ['label' => $deactiveLabel, 'icon' => 'fa fa-ban', 'name' => 'deactive_all-' . $col['name']];
            }
        }
        $this->data['button_selected'] = $this->button_selected;
        $this->data['index_statistic'] = $this->index_statistic;
        $this->data['index_additional_view'] = $this->index_additional_view;
        $this->data['table_row_color'] = $this->table_row_color;
        $this->data['pre_index_html'] = $this->pre_index_html;
        $this->data['post_index_html'] = $this->post_index_html;
        $this->data['load_js'] = $this->load_js;
        $this->data['load_css'] = $this->load_css;
        $this->data['script_js'] = $this->script_js;
        $this->data['style_css'] = $this->style_css;
        $this->data['sub_module'] = $this->sub_module;
        $this->data['parent_field'] = (g('parent_field')) ?: $this->parent_field;
        $this->data['parent_id'] = (g('parent_id')) ?: $this->parent_id;

        if ($this->sidebar_mode == 'mini') {
            $this->data['sidebar_mode'] = 'sidebar-mini';
        } elseif ($this->sidebar_mode == 'collapse') {
            $this->data['sidebar_mode'] = 'sidebar-collapse';
        } elseif ($this->sidebar_mode == 'collapse-mini') {
            $this->data['sidebar_mode'] = 'sidebar-collapse sidebar-mini';
        } else {
            $this->data['sidebar_mode'] = '';
        }

        if (CRUDBooster::getCurrentMethod() == 'getProfile') {
            Session::put('current_row_id', CRUDBooster::myId());
            $this->data['return_url'] = Request::fullUrl();
        }

        view()->share($this->data);
    }

    public function view($template, $data)
    {
        $this->cbLoader();
        return view($template, $data);
    }

    private function checkHideForm()
    {
        if ($this->hide_form && count($this->hide_form)) {
            foreach ($this->form as $i => $f) {
                if (in_array($f['name'], $this->hide_form)) {
                    unset($this->form[$i]);
                }
            }
        }
    }

    public function getIndex()
    {
        $this->cbLoader();

        $module = CRUDBooster::getCurrentModule();
        if (!CRUDBooster::isView() && $this->global_privilege == false) {
            CRUDBooster::insertLog(cbLang('log_try_view', ['module' => $module->name]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), cbLang('denied_access'));
        }

        if (request('parent_table')) {
            $parentTablePK = CB::pk(g('parent_table'));
            $data['parent_table'] = DB::table(request('parent_table'))->where($parentTablePK, request('parent_id'))->first();
            if (request("parent_translation_table")) {
                $data['parent_table'] = CB::getRowWithTranslations(request('parent_table'), request("parent_translation_table"), request('parent_id'));
            }
            if (request('foreign_key')) {
                $data['parent_field'] = request('foreign_key');
            } else {
                $data['parent_field'] = CB::getTableForeignKey(g('parent_table'), $this->table);
            }

            if ($data['parent_field']) {
                foreach ($this->columns_table as $i => $col) {
                    if ($col['name'] == $data['parent_field']) {
                        unset($this->columns_table[$i]);
                    }
                }
            }
        }
        $data['table'] = $this->table;
        $data['table_pk'] = CB::pk($this->table);
        $data['page_title'] = $module->name;
        $data['page_description'] = cbLang('default_module_description');
        $data['date_candidate'] = $this->date_candidate;
        $data['limit'] = $limit = (request('limit')) ? request('limit') : $this->limit;

        $tablePK = $data['table_pk'];
        $table_columns = CB::getTableColumns($this->table);
        $translationColumns = CB::getTableColumns($this->translation_table);
        $result = DB::table($this->table)->select(DB::raw($this->table . "." . $this->primary_key));

        if (request('parent_id')) {
            $table_parent = $this->table;
            $table_parent = CRUDBooster::parseSqlTable($table_parent)['table'];
            $result->where($table_parent . '.' . request('foreign_key'), request('parent_id'));
        }

        $this->hook_query_index($result);

        if (in_array('deleted_at', $table_columns)) {
            $result->where($this->table . '.deleted_at', null);
        }

        $alias = [];
        $join_alias_count = 0;
        $join_table_temp = [];
        $table = $this->table;
        $columns_table = $this->columns_table;
        $translationTableJoined = false;
        foreach ($columns_table as $index => $coltab) {
            $table = $this->table;

            $join = @$coltab['join'];
            $join_where = @$coltab['join_where'];
            $join_id = @$coltab['join_id'];
            $field = @$coltab['name'];
            if ($coltab["translation"]) {
                $field = $this->translation_table . "." . $field;
                if (!$translationTableJoined) {
                    $join_table_temp[] = $this->translation_table;
                    $result->leftJoin($this->translation_table, function ($join) {
                        $join->on($this->table . '.id', '=', $this->translation_table . '.' . $this->translation_main_column);
                    })
                        ->where($this->translation_table . '.locale', "=", $this->websiteLanguages[0]->code);
                }
                $translationTableJoined = true;
            }
            $join_table_temp[] = $table;

            if (!$field) {
                continue;
            }

            if (strpos($field, ' as ') !== false) {
                $field = substr($field, strpos($field, ' as ') + 4);
                $field_with = (array_key_exists('join', $coltab)) ? str_replace(",", ".", $coltab['join']) : $field;
                $result->addselect(DB::raw($coltab['name']));
                $columns_table[$index]['type_data'] = 'varchar';
                $columns_table[$index]['field'] = $field;
                $columns_table[$index]['field_raw'] = $field;
                $columns_table[$index]['field_with'] = $field_with;
                $columns_table[$index]['is_subquery'] = true;
                continue;
            }

            if (strpos($field, '.') !== false) {
                $result->addselect($field);
            } else {
                $result->addselect($table . '.' . $field);
            }

            $field_array = explode('.', $field);

            if (isset($field_array[1])) {
                $field = $field_array[1];
                $table = $field_array[0];
            } else {
                $table = $this->table;
            }

            if ($join) {
                $join_exp = explode(',', $join);

                $join_table = $join_exp[0];
                $joinTablePK = CB::pk($join_table);
                $join_column = $join_exp[1];
                $join_alias = str_replace(".", "_", $join_table);

                if (in_array($join_table, $join_table_temp)) {
                    $join_alias_count += 1;
                    $join_alias = $join_table . $join_alias_count;
                }

                if (@$coltab['join_translation_table']) {
                    $join_table = @$coltab['join_translation_table'];
                    $joinTablePK = CRUDBooster::getTranslationTableMainColumn($join_table);
                    $join_where .= "$join_alias.locale = '" . $this->websiteLanguages[0]->code . "'";
                }
                $join_table_temp[] = $join_table;
                $result->leftjoin($join_table . ' as ' . $join_alias, $join_alias . (($join_id) ? '.' . $join_id : '.' . $joinTablePK), '=', DB::raw($table . '.' . $field . (($join_where) ? ' AND ' . $join_where . ' ' : '')));
                $result->addselect($join_alias . '.' . $join_column . ' as ' . $join_alias . '_' . $join_column);

                $join_table_columns = CRUDBooster::getTableColumns($join_table);
                if ($join_table_columns) {
                    foreach ($join_table_columns as $jtc) {
                        $result->addselect($join_alias . '.' . $jtc . ' as ' . $join_alias . '_' . $jtc);
                    }
                }

                $alias[] = $join_alias;
                $columns_table[$index]['type_data'] = CRUDBooster::getFieldType($join_table, $join_column);
                $columns_table[$index]['field'] = $join_alias . '_' . $join_column;
                $columns_table[$index]['field_with'] = $join_alias . '.' . $join_column;
                $columns_table[$index]['field_raw'] = $join_column;
                $columns_table[$index]['table'] = $table;

                @$join_table1 = $join_exp[2];
                @$joinTable1PK = CB::pk($join_table1);
                @$join_column1 = $join_exp[3];
                @$join_alias1 = $join_table1;
                if ($join_table1 && $join_column1) {

                    if (in_array($join_table1, $join_table_temp)) {
                        $join_alias_count += 1;
                        $join_alias1 = $join_table1 . $join_alias_count;
                    }

                    $join_table_temp[] = $join_table1;

                    $result->leftjoin($join_table1 . ' as ' . $join_alias1, $join_alias1 . '.' . $joinTable1PK, '=', $join_alias . '.' . $join_column);
                    $result->addselect($join_alias1 . '.' . $join_column1 . ' as ' . $join_column1 . '_' . $join_alias1);
                    $alias[] = $join_alias1;
                    $columns_table[$index]['type_data'] = CRUDBooster::getFieldType($join_table1, $join_column1);
                    $columns_table[$index]['field'] = $join_column1 . '_' . $join_alias1;
                    $columns_table[$index]['field_with'] = $join_alias1 . '.' . $join_column1;
                    $columns_table[$index]['field_raw'] = $join_column1;
                }
            } else {

                if (isset($field_array[1])) {
                    $result->addselect($table . '.' . $field . ' as ' . $table . '_' . $field);
                    $columns_table[$index]['type_data'] = CRUDBooster::getFieldType($table, $field);
                    $columns_table[$index]['field'] = $table . '_' . $field;
                    $columns_table[$index]['field_raw'] = $table . '.' . $field;
                } else {
                    $result->addselect($table . '.' . $field);
                    $columns_table[$index]['type_data'] = CRUDBooster::getFieldType($table, $field);
                    $columns_table[$index]['field'] = $field;
                    $columns_table[$index]['field_raw'] = $field;
                }

                $columns_table[$index]['field_with'] = $table . '.' . $field;
            }
        }
        if (request('q')) {
            $result->where(function ($w) use ($columns_table) {
                foreach ($columns_table as $col) {
                    if (!$col['field_with']) {
                        continue;
                    }
                    if ($col['is_subquery']) {
                        continue;
                    }
                    $w->orwhere($col['field_with'], "like", "%" . request("q") . "%");
                }
            });
        }

        if (request('where')) {
            foreach (request('where') as $k => $v) {
                $result->where($table . '.' . $k, $v);
            }
        }

        $filter_is_orderby = false;
        if (request('filter_column')) {

            $filter_column = request('filter_column');
            $result->where(function ($w) use ($filter_column) {
                foreach ($filter_column as $key => $fc) {

                    $value = @$fc['value'];
                    $type = @$fc['type'];

                    if ($type == 'empty') {
                        $w->whereNull($key)->orWhere($key, '');
                        continue;
                    }

                    if ($value == '' || $type == '') {
                        continue;
                    }

                    if ($type == 'between') {
                        continue;
                    }

                    switch ($type) {
                        default:
                            if ($key && $type && $value) {
                                $w->where($key, $type, $value);
                            }
                            break;
                        case 'like':
                        case 'not like':
                            $value = '%' . $value . '%';
                            if ($key && $type && $value) {
                                $w->where($key, $type, $value);
                            }
                            break;
                        case 'in':
                        case 'not in':
                            if ($value) {
                                $value = explode(',', $value);
                                if ($key && $value) {
                                    $w->whereIn($key, $value);
                                }
                            }
                            break;
                    }
                }
            });

            foreach ($filter_column as $key => $fc) {
                $value = @$fc['value'];
                $type = @$fc['type'];
                $sorting = @$fc['sorting'];

                if ($sorting != '') {
                    if ($key) {
                        $result->orderby($key, $sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type == 'between') {
                    if ($key && $value) {
                        $result->whereBetween($key, $value);
                    }
                } else {
                    continue;
                }
            }
        }

        if ($filter_is_orderby == true) {
            $data['result'] = $result->paginate($limit);
        } else {
            if ($this->orderby) {
                if (is_array($this->orderby)) {
                    foreach ($this->orderby as $k => $v) {
                        if (strpos($k, '.') !== false) {
                            $orderby_table = explode(".", $k)[0];
                            $k = explode(".", $k)[1];
                        } else {
                            $orderby_table = $this->table;
                        }
                        $result->orderby($orderby_table . '.' . $k, $v);
                    }
                } else {
                    $this->orderby = explode(";", $this->orderby);
                    foreach ($this->orderby as $o) {
                        $o = explode(",", $o);
                        $k = $o[0];
                        $v = $o[1];
                        if (strpos($k, '.') !== false) {
                            $orderby_table = explode(".", $k)[0];
                        } else {
                            $orderby_table = $this->table;
                        }
                        $result->orderby($orderby_table . '.' . $k, $v);
                    }
                }
                $data['result'] = $result->paginate($limit);
            } else {
                $data['result'] = $result->orderby($this->table . '.' . $this->primary_key, 'desc')->paginate($limit);
            }
        }

        $data['columns'] = $columns_table;

        if ($this->index_return) {
            return $data;
        }

        //LISTING INDEX HTML
        $addaction = $this->data['addaction'];

        if ($this->record_seo) {
            $addaction[] = ['label' => cbLang('action_set_seo'), 'url' => CRUDBooster::adminPath('seo') . '?page=' . CRUDBooster::getCurrentModule()->path . '&page_id=[id]', 'icon' => 'fa fa-globe', 'color' => 'success'];
        }

        if ($this->sub_module) {
            foreach ($this->sub_module as $s) {
                $table_parent = CRUDBooster::parseSqlTable($this->table)['table'];
                $addaction[] = [
                    'label' => $s['label'],
                    'icon' => $s['button_icon'],
                    'url' => CRUDBooster::adminPath($s['path']) . '?return_url=' . urlencode(Request::fullUrl()) . '&parent_table=' . $table_parent . '&parent_columns=' . $s['parent_columns'] . '&parent_columns_alias=' . $s['parent_columns_alias'] . '&parent_id=[' . (!isset($s['custom_parent_id']) ? "id" : $s['custom_parent_id']) . ']&foreign_key=' . $s['foreign_key'] . '&label=' . urlencode($s['label']) . '&parent_translation_table=' . $s['parent_translation_table'],
                    'color' => $s['button_color'],
                    'showIf' => $s['showIf'],
                    'target' => isset($s['target']) ?: '_self',
                ];
            }
        }

        $mainpath = CRUDBooster::mainpath();
        $orig_mainpath = $this->data['mainpath'];
        $title_field = $this->title_field;
        $html_contents = [];
        $page = (request('page')) ? request('page') : 1;
        $number = ($page - 1) * $limit + 1;
        foreach ($data['result'] as $row) {
            $html_content = [];

            if ($this->button_bulk_action) {

                $html_content[] = "<input type='checkbox' class='checkbox tbl-checkbox' name='checkbox[]' value='" . $row->{$tablePK} . "'/>";
            }

            if ($this->show_numbering) {
                $html_content[] = $number . '. ';
                $number++;
            }

            foreach ($columns_table as $col) {
                if ($col['visible'] === false) {
                    continue;
                }

                $value = @$row->{$col['field']};
                $title = @$row->{$this->title_field};
                $label = $col['label'];

                if (@$col['str_limit']) {
                    $value = trim(strip_tags($value));
                    $value = substr($value, 0, $col['str_limit']);
                }

                if (isset($col['image'])) {
                    if ($value == '') {
                        $value = "<a  data-lightbox='roadtrip' rel='group_{{$table}}' title='$label: $title' href='" . (CRUDBooster::getSetting('default_img') ? asset(CRUDBooster::getSetting('default_img')) : asset('vendor/crudbooster/avatar.jpg')) . "'><img width='40px' height='40px' src='" . (CRUDBooster::getSetting('default_img') ? asset(CRUDBooster::getSetting('default_img')) : asset('vendor/crudbooster/avatar.jpg')) . "'/></a>";
                    } else {
                        $matched_upload_word = '';
                        if (preg_match('/\w+/', config('crudbooster.filemanager_upload_dir'), $matches)) {
                            $matched_upload_word = $matches[0];
                        }
                        if (!empty($matched_upload_word)) {
                            $new_upload_word = config('crudbooster.filemanager_thumbs_base_path');
                            $new_value = preg_replace('/\b' . $matched_upload_word . '\b/', $new_upload_word, $value, 1);
                            $img_value = preg_replace('/\b' . $matched_upload_word . '\b/', config('crudbooster.filemanager_upload_dir'), $value, 1);
                        }
                        $pic = (strpos($new_value, 'http://') !== false) ? $new_value : asset($new_value);
                        $value = "<a data-lightbox='roadtrip'  rel='group_{{$table}}' title='$label: $title' href='" . $img_value . "'><img width='40px' height='40px' src='" . $pic . "'/></a>";
                    }
                }

                if (@$col['download']) {
                    $url = (strpos($value, 'http://') !== false) ? $value : asset($value) . '?download=1';
                    if ($value) {
                        $value = "<a class='btn btn-xs btn-primary' href='$url' target='_blank' title='Download File'><i class='fa fa-download'></i> Download</a>";
                    } else {
                        $value = " - ";
                    }
                }

                if (@$col['switch']) {
                    $checked = '';
                    if ($value == 1) {
                        $checked = 'checked';
                    }

                    $value = "<input row_id='{$row->id}' id='{$col["name"]}_{$row->id}' class='cms_switch_input' name='{$col["name"]}' type='checkbox' value='{$value}' {$checked} style='display:none;'/>
                            <label class='cms_switch_label' for='{$col["name"]}_{$row->id}'>Toggle</label>";
                }

                if ($col['nl2br']) {
                    $value = nl2br($value);
                }

                if ($col['callback_php']) {
                    foreach ($row as $k => $v) {
                        $col['callback_php'] = str_replace("[" . $k . "]", $v, $col['callback_php']);
                    }
                    @eval("\$value = " . $col['callback_php'] . ";");
                }

                //New method for callback
                if (isset($col['callback'])) {
                    $value = call_user_func($col['callback'], $row);
                }

                $datavalue = @unserialize($value);
                if ($datavalue !== false) {
                    if ($datavalue) {
                        $prevalue = [];
                        foreach ($datavalue as $d) {
                            if ($d['label']) {
                                $prevalue[] = $d['label'];
                            }
                        }
                        if ($prevalue && count($prevalue)) {
                            $value = implode(", ", $prevalue);
                        }
                    }
                }

                $html_content[] = $value;
            } //end foreach columns_table

            if ($this->button_table_action) :

                $button_action_style = $this->button_action_style;
                $html_content[] = "<div class='button_action' style='text-align:right'>" . view('crudbooster::components.action', compact('addaction', 'row', 'button_action_style', 'parent_field'))->render() . "</div>";

            endif; //button_table_action

            foreach ($html_content as $i => $v) {
                $this->hook_row_index($i, $v);
                $html_content[$i] = $v;
            }

            $html_contents[] = $html_content;
        } //end foreach data[result]

        $html_contents = ['html' => $html_contents, 'data' => $data['result']];

        $data['html_contents'] = $html_contents;

        $manualView = null;
        if (view()->exists(CRUDBooster::getCurrentModule()->path . '.index')) {
            $manualView = view(CRUDBooster::getCurrentModule()->path . '.index', $data);
        }

        if (view()->exists('modules.' . CRUDBooster::getCurrentModule()->path . '.index')) {
            $manualView = view('modules.' . CRUDBooster::getCurrentModule()->path . '.index', $data);
        }
        
        $view = $manualView ?: view("crudbooster::default.index", $data);
        return $view;
    }

    public function getExportData()
    {

        return redirect(CRUDBooster::mainpath());
    }

    public function postExportData()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(180);

        $this->limit = Request::input('limit');
        $this->index_return = true;
        $filetype = Request::input('fileformat');
        $filename = Request::input('filename');
        $papersize = Request::input('page_size');
        $paperorientation = Request::input('page_orientation');
        $response = $this->getIndex();

        if (Request::input('default_paper_size')) {
            DB::table('cms_settings')->where('name', 'default_paper_size')->update(['content' => $papersize]);
        }
        switch ($filetype) {
            case "pdf":
                $view = view('crudbooster::export', $response)->render();
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML($view);
                $pdf->setPaper($papersize, $paperorientation);

                return $pdf->stream($filename . '.pdf');
                break;
            case 'xls':
                return Excel::download(new DefaultExportXls($response), $filename . ".xls");
                break;
            case 'csv':

                return Excel::download(new DefaultExportCsv($response), $filename . ".csv", 'Csv');
                break;
        }
    }

    public function postDataQuery()
    {
        $query = request('query');
        $query = DB::select(DB::raw($query));

        return response()->json($query);
    }

    public function getDataTable()
    {
        $table = request('table');
        $label = request('label');
        $datatableWhere = urldecode(request('datatable_where'));
        $foreign_key_name = request('fk_name');
        $foreign_key_value = request('fk_value');
        if ($table && $label && $foreign_key_name && $foreign_key_value) {
            $query = DB::table($table);
            if ($datatableWhere) {
                $query->whereRaw($datatableWhere);
            }
            $query->select('id as select_value', $label . ' as select_label');
            $query->where($foreign_key_name, $foreign_key_value);
            $query->orderby($label, 'asc');

            return response()->json($query->get());
        } else {
            return response()->json([]);
        }
    }

    public function getModalData()
    {
        $table = request('table');
        $where = request('where');
        $where = urldecode($where);
        $columns = request('columns');
        $columns = explode(",", $columns);

        $table = CRUDBooster::parseSqlTable($table)['table'];
        $tablePK = CB::pk($table);
        $result = DB::table($table);

        if (request('q')) {
            $result->where(function ($where) use ($columns) {
                foreach ($columns as $c => $col) {
                    if ($c == 0) {
                        $where->where($col, 'like', '%' . request('q') . '%');
                    } else {
                        $where->orWhere($col, 'like', '%' . request('q') . '%');
                    }
                }
            });
        }

        if ($where) {
            $result->whereraw($where);
        }

        $result->orderby($tablePK, 'desc');

        $data['result'] = $result->paginate(6);
        $data['columns'] = $columns;

        return view('crudbooster::default.type_components.datamodal.browser', $data);
    }

    public function getUpdateSingle()
    {
        $this->cbLoader();
        $table = request('table');
        $column = request('column');
        $value = request('value');
        $id = request('id');
        $lang = request('lang');
        $tablePK = CB::pk($table);
        if($lang && $lang != null) {
            $column = substr($column, 0, -(strlen($lang) + 1));
            DB::table($this->translation_table)->where($this->translation_main_column, $id)->where('locale', $lang)->update([$column => $value]);
        } else
            DB::table($table)->where($tablePK, $id)->update([$column => $value]);

        return redirect()->back()->with(['message_type' => 'success', 'message' => cbLang('alert_delete_data_success')]);
    }

    public function getFindData()
    {
        $q = request('q');
        $id = request('id');
        $limit = request('limit') ?: 10;
        $format = request('format');

        $table1 = (request('table1')) ?: $this->table;
        $table1PK = CB::pk($table1);
        $column1 = (request('column1')) ?: $this->title_field;

        $orderby_table = $table1;
        $orderby_column = $table1PK;

        @$table2 = request('table2');
        @$column2 = request('column2');

        @$table3 = request('table3');
        @$column3 = request('column3');

        $where = request('where');

        $fk = request('fk');
        $fk_value = request('fk_value');

        if ($q || $id || $table1) {
            $rows = DB::table($table1);
            $rows->select($table1 . '.*');
            $rows->take($limit);

            if (CRUDBooster::isColumnExists($table1, 'deleted_at')) {
                $rows->where($table1 . '.deleted_at', null);
            }

            if ($fk && $fk_value) {
                $rows->where($table1 . '.' . $fk, $fk_value);
            }

            if ($table1 && $column1) {

                $orderby_table = $table1;
                $orderby_column = $column1;
            }

            if ($table2 && $column2) {
                $table2PK = CB::pk($table2);
                $rows->join($table2, $table2 . '.' . $table2PK, '=', $table1 . '.' . $column1);
                $columns = CRUDBooster::getTableColumns($table2);
                foreach ($columns as $col) {
                    $rows->addselect($table2 . "." . $col . " as " . $table2 . "_" . $col);
                }
                $orderby_table = $table2;
                $orderby_column = $column2;
            }

            if ($table3 && $column3) {
                $table3PK = CB::pk($table3);
                $rows->join($table3, $table3 . '.' . $table3PK, '=', $table2 . '.' . $column2);
                $columns = CRUDBooster::getTableColumns($table3);
                foreach ($columns as $col) {
                    $rows->addselect($table3 . "." . $col . " as " . $table3 . "_" . $col);
                }
                $orderby_table = $table3;
                $orderby_column = $column3;
            }

            if ($id) {
                $rows->where($table1 . "." . $table1PK, $id);
            }

            if ($where) {
                $rows->whereraw($where);
            }

            if ($format) {
                $format = str_replace('&#039;', "'", $format);
                $rows->addselect(DB::raw("CONCAT($format) as text"));
                if ($q) {
                    $rows->whereraw("CONCAT($format) like '%" . $q . "%'");
                }
            } else {
                $rows->addselect($orderby_table . '.' . $orderby_column . ' as text');
                if ($q) {
                    $rows->where($orderby_table . '.' . $orderby_column, 'like', '%' . $q . '%');
                }
                $rows->orderBy($orderby_table . '.' . $orderby_column, 'asc');
            }

            $result = [];
            $result['items'] = $rows->get();
        } else {
            $result = [];
            $result['items'] = [];
        }

        return response()->json($result);
    }

    public function validation($formArrToValidate, $id = null)
    {

        $request_all = Request::all();
        $array_input = [];
        if (is_array($formArrToValidate) || is_object($formArrToValidate)) {
            // foreach ($this->data_inputan as $di) {
            foreach ($formArrToValidate as $di) {
                $ai = [];
                $name = $di['name'];

                if (!isset($request_all[$name])) {
                    continue;
                }

                if ($di['type'] != 'upload') {
                    if (@$di['required']) {
                        $ai[] = 'required';
                    }
                }

                if ($di['type'] == 'upload') {
                    if ($id) {
                        $row = DB::table($this->table)->where($this->primary_key, $id)->first();
                        if ($row->{$di['name']} == '') {
                            $ai[] = 'required';
                        }
                    }
                }

                if (@$di['min']) {
                    $ai[] = 'min:' . $di['min'];
                }
                if (@$di['max']) {
                    $ai[] = 'max:' . $di['max'];
                }
                if (@$di['image']) {
                    $ai[] = 'image';
                }
                if (@$di['mimes']) {
                    $ai[] = 'mimes:' . $di['mimes'];
                }
                $name = $di['name'];
                if (!$name) {
                    continue;
                }

                if ($di['type'] == 'money') {
                    $request_all[$name] = preg_replace('/[^\d-]+/', '', $request_all[$name]);
                }

                if ($di['type'] == 'child') {
                    $slug_name = str_slug($di['label'], '');
                    foreach ($di['columns'] as $child_col) {
                        if (isset($child_col['validation'])) {
                            //https://laracasts.com/discuss/channels/general-discussion/array-validation-is-not-working/
                            if (strpos($child_col['validation'], 'required') !== false) {
                                $array_input[$slug_name . '-' . $child_col['name']] = 'required';

                                str_replace('required', '', $child_col['validation']);
                            }

                            $array_input[$slug_name . '-' . $child_col['name'] . '.*'] = $child_col['validation'];
                        }
                    }
                }

                if (@$di['validation']) {

                    $exp = explode('|', $di['validation']);
                    if ($exp && count($exp)) {
                        foreach ($exp as &$validationItem) {
                            if (substr($validationItem, 0, 6) == 'unique') {
                                $parseUnique = explode(',', str_replace('unique:', '', $validationItem));
                                $uniqueTable = ($parseUnique[0]) ?: $this->table;
                                $uniqueColumn = ($parseUnique[1]) ?: $name;
                                $uniqueIgnoreId = ($parseUnique[2]) ?: (($id) ?: '');

                                //Make sure table name
                                $uniqueTable = CB::parseSqlTable($uniqueTable)['table'];

                                //Rebuild unique rule
                                $uniqueRebuild = [];
                                $uniqueRebuild[] = $uniqueTable;
                                $uniqueRebuild[] = $uniqueColumn;
                                if ($uniqueIgnoreId) {
                                    $uniqueRebuild[] = $uniqueIgnoreId;
                                } else {
                                    $uniqueRebuild[] = 'NULL';
                                }

                                //Check whether deleted_at exists or not
                                if (CB::isColumnExists($uniqueTable, 'deleted_at')) {
                                    $uniqueRebuild[] = CB::findPrimaryKey($uniqueTable);
                                    $uniqueRebuild[] = 'deleted_at';
                                    $uniqueRebuild[] = 'NULL';
                                }
                                $uniqueRebuild = array_filter($uniqueRebuild);
                                $validationItem = 'unique:' . implode(',', $uniqueRebuild);
                            }
                        }
                    } else {
                        $exp = [];
                    }

                    $validation = implode('|', $exp);

                    $array_input[$name] = $validation;
                } else {
                    $array_input[$name] = implode('|', $ai);
                }
            }
        }
        $validator = Validator::make($request_all, $array_input);

        if ($validator->fails()) {
            $message = $validator->messages();
            $message_all = $message->all();

            if (Request::ajax()) {
                $res = response()->json([
                    'message' => cbLang('alert_validation_error', ['error' => implode(', ', $message_all)]),
                    'message_type' => 'warning',
                ])->send();
                exit;
            } else {
                $res = redirect()->back()->with("errors", $message)->with([
                    'message' => cbLang('alert_validation_error', ['error' => implode(', ', $message_all)]),
                    'message_type' => 'warning',
                ])->withInput();
                \Session::driver()->save();
                $res->send();
                exit;
            }
        }
    }

    public function input_assignment($id = null, $translationLocale = false)
    {

        $hide_form = (request('hide_form')) ? unserialize(request('hide_form')) : [];
        foreach ($this->data_inputan as $ro) {
            if (($ro["translation"] && !$translationLocale) || (!$ro["translation"] && $translationLocale)) {
                continue;
            }

            $name = $ro['name'];
            if (!$name) {
                continue;
            }

            if ($ro['exception']) {
                continue;
            }

            if ($name == 'hide_form') {
                continue;
            }

            if ($hide_form && count($hide_form)) {

                if (in_array($name, $hide_form)) {
                    continue;
                }
            }

            if ($ro['type'] == 'checkbox' && $ro['relationship_table']) {
                continue;
            }

            if ($ro['type'] == 'select2' && $ro['relationship_table']) {
                continue;
            }

            $inputdata = request($name);
            if ($translationLocale) {
                $inputdata = request($name . "_$translationLocale");
            }

            if ($ro['type'] == 'money') {
                $inputdata = preg_replace('/[^\d-]+/', '', $inputdata);
            }

            if ($ro['type'] == 'child') {
                continue;
            }

            if ($name && strpos($name, 'webp') === false) {
                if ($inputdata != '') {
                    $this->arr[$name] = $inputdata;
                } else {
                    if (CB::isColumnNULL($this->table, $name) && $ro['type'] != 'upload' && $ro['type'] != 'switch' && $ro['type'] != 'text' && $ro['type'] != 'wysiwyg') {
                        continue;
                    } else {
                        $this->arr[$name] = "";
                    }
                }
            }

            $password_candidate = explode(',', config('crudbooster.PASSWORD_FIELDS_CANDIDATE'));
            if (in_array($name, $password_candidate)) {
                if (!empty($this->arr[$name])) {
                    $this->arr[$name] = Hash::make($this->arr[$name]);
                } else {
                    unset($this->arr[$name]);
                }
            }

            if ($ro['type'] == 'checkbox') {
                if (is_array($inputdata)) {
                    if ($ro['datatable'] != '') {
                        $table_checkbox = explode(',', $ro['datatable'])[0];
                        $field_checkbox = explode(',', $ro['datatable'])[1];
                        $table_checkbox_pk = CB::pk($table_checkbox);
                        $data_checkbox = DB::table($table_checkbox)->whereIn($table_checkbox_pk, $inputdata)->pluck($field_checkbox)->toArray();
                        $this->arr[$name] = implode(";", $data_checkbox);
                    } else {
                        $this->arr[$name] = implode(";", $inputdata);
                    }
                }
            }

            //multitext colomn
            if ($ro['type'] == 'multitext') {
                $name = $ro['name'];
                $multitext = "";
                $maxI = ($this->arr[$name]) ? count($this->arr[$name]) : 0;
                for ($i = 0; $i <= $maxI - 1; $i++) {
                    $multitext .= $this->arr[$name][$i] . "|";
                }
                $multitext = substr($multitext, 0, strlen($multitext) - 1);
                $this->arr[$name] = $multitext;
            }

            if ($ro['type'] == 'googlemaps') {
                if ($ro['latitude'] && $ro['longitude']) {
                    $latitude_name = $ro['latitude'];
                    $longitude_name = $ro['longitude'];
                    $this->arr[$latitude_name] = request('input-latitude-' . $name);
                    $this->arr[$longitude_name] = request('input-longitude-' . $name);
                }
            }

            if ($ro['type'] == 'select' || $ro['type'] == 'select2') {
                if ($ro['datatable']) {
                    if ($inputdata == '') {
                        $this->arr[$name] = 0;
                    }
                }

                if($ro['multiple']) {
                    if (isset($inputdata) && is_array($inputdata)) {
                        $idsString = implode(',', $inputdata);
                        $this->arr[$name] = $idsString;
                    } else {
                        $this->arr[$name] = null;
                    }
                }
            }

            if (@$ro['type'] == 'upload') {

                $this->arr[$name] = CRUDBooster::uploadFile($name, $ro['encrypt'] || $ro['upload_encrypt'], $ro['resize_width'], $ro['resize_height'], CB::myId());

                if (!$this->arr[$name]) {
                    $this->arr[$name] = request('_' . $name);
                }
            }

            if ($ro['type'] == 'switch' && !$inputdata) {
                $this->arr[$name] = 0;
            }

            //Conversion Functionality to webp images
            if ($ro['type'] == 'hidden' && strpos($name, 'webp') !== false) {
                $mainImage = request(str_replace("_webp", "", $name));
                if ($mainImage) {
                    $image = request($name);
                    // Assuming $mainImage is the main image URL that might contain spaces So will update image and webp_image
                    $this->arr[str_replace("_webp", "", $name)] = urldecode($mainImage);
                    //-----------------------------------------//

                    //check if the image type is base64
                    if (strpos($image, 'data:image/') === 0) {
                        //---------------------------------------//
                        $image = str_replace('data:image/webp;base64,', '', $image);
                        $image = str_replace(' ', '+', $image);
                        $directory = public_path(config('crudbooster.filemanager_current_path') . 'webp_images/');
                        //---------------------------------------//
                        // Retrieve the main image name and use it to set a new image's name
                        $imageName = urldecode($this->arr[str_replace("_webp", "", $name)]);
                        $imageName = pathinfo($imageName, PATHINFO_FILENAME);
                        $imageName .= '.webp';
                        //---------------------------------------//
                        $imagePath = $directory . $imageName;
                        // Check if the image doesn't exist in the directory
                        if (!file_exists($imagePath)) {
                            file_put_contents($imagePath, base64_decode($image));
                        }
                        //---------------------------------------//
                        file_put_contents($directory . $imageName, base64_decode($image));
                        //---------------------------------------//
                        $this->arr[$name] = config('crudbooster.filemanager_current_path') . 'webp_images/' . $imageName;
                    } else {
                        $this->arr[$name] = $image;
                    }
                } else {
                    //If the image is empty, I also need to make image_webp empty.
                    $this->arr[$name] = null;
                }
            }
        }
    }

    public function getAdd()
    {
        $this->cbLoader();
        if (!CRUDBooster::isCreate() && $this->global_privilege == false || $this->button_add == false) {
            CRUDBooster::insertLog(cbLang('log_try_add', ['module' => CRUDBooster::getCurrentModule()->name]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), cbLang("denied_access"));
        }

        $page_title = cbLang("add_data_page_title", ['module' => CRUDBooster::getCurrentModule()->name]);
        $page_menu = Route::getCurrentRoute()->getActionName();

        $command = 'add';
        $manualView = null;
        if (view()->exists(CRUDBooster::getCurrentModule()->path . '.form')) {
            $manualView = view(CRUDBooster::getCurrentModule()->path . '.form', compact('page_title', 'page_menu', 'command'));
        }

        if (view()->exists('modules.' . CRUDBooster::getCurrentModule()->path . '.form')) {
            $manualView = view('modules.' . CRUDBooster::getCurrentModule()->path . '.form', compact('page_title', 'page_menu', 'command'));
        }

        $view = $manualView ?: view('crudbooster::default.form', compact('page_title', 'page_menu', 'command'));
        return $view;
    }

    public function postAddSave()
    {
        $this->cbLoader();
        if (!CRUDBooster::isCreate() && $this->global_privilege == false) {
            CRUDBooster::insertLog(cbLang('log_try_add_save', [
                'name' => Request::input($this->title_field),
                'module' => CRUDBooster::getCurrentModule()->name,
            ]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), cbLang("denied_access"));
        }
        $formArrToValidate = [];
        foreach ($this->data_inputan as $field) {
            if ($field["translation"] == null || $field["translation"] == "FALSE") {
                $formArrToValidate[] = $field;
                continue;
            }
            foreach ($this->websiteLanguages as $lang) {
                $temp = $field;
                $temp["name"] = $field["name"] . "_" . $lang->code;
                $formArrToValidate[] = $temp;
            }
        }
        $this->validation($formArrToValidate);
        $this->input_assignment();
        if (Schema::hasColumn($this->table, 'created_at')) {
            $this->arr['created_at'] = date('Y-m-d H:i:s');
        }

        if ($this->sortable_table && Schema::hasColumn($this->table, 'sorting')) {
            $sort = DB::table($this->table)->count() + 1;
            $this->arr['sorting'] = $sort;
        }

        $this->hook_before_add($this->arr);
        $lastInsertId = $id = DB::table($this->table)->insertGetId($this->arr);

        //fix bug if primary key is uuid
        if (isset($this->arr[$this->primary_key]) && $this->arr[$this->primary_key] != $id) {
            $id = $this->arr[$this->primary_key];
        }

        //Looping Data Input Again After Insert
        foreach ($this->data_inputan as $ro) {
            $name = $ro['name'];
            if (!$name) {
                continue;
            }

            $inputdata = request($name);

            //Insert Data Checkbox if Type Datatable
            if ($ro['type'] == 'checkbox') {
                if ($ro['relationship_table']) {
                    $datatable = explode(",", $ro['datatable'])[0];
                    $foreignKey2 = CRUDBooster::getForeignKey($datatable, $ro['relationship_table']);
                    $foreignKey = CRUDBooster::getForeignKey($this->table, $ro['relationship_table']);
                    DB::table($ro['relationship_table'])->where($foreignKey, $id)->delete();

                    if ($inputdata) {
                        $relationship_table_pk = CB::pk($ro['relationship_table']);
                        foreach ($inputdata as $input_id) {
                            DB::table($ro['relationship_table'])->insert([
                                //                                 $relationship_table_pk => CRUDBooster::newId($ro['relationship_table']),
                                $foreignKey => $id,
                                $foreignKey2 => $input_id,
                            ]);
                        }
                    }
                }
            }

            if ($ro['type'] == 'select2') {
                if ($ro['relationship_table']) {
                    $datatable = explode(",", $ro['datatable'])[0];
                    $foreignKey2 = CRUDBooster::getForeignKey($datatable, $ro['relationship_table']);
                    $foreignKey = CRUDBooster::getForeignKey($this->table, $ro['relationship_table']);
                    DB::table($ro['relationship_table'])->where($foreignKey, $id)->delete();

                    if ($inputdata) {
                        foreach ($inputdata as $input_id) {
                            DB::table($ro['relationship_table'])->insert([
                                $foreignKey => $id,
                                $foreignKey2 => $input_id,
                            ]);
                        }
                    }
                }
            }

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

                            // Conversion Functionality to webp images
                            if ($col['type'] == 'hidden' && strpos($colname, 'webp') !== false) {
                                $image = request($name . '-' . $colname)[$i];
                                //---------------------------------------//
                                if (isset($image)) {
                                    //check if the image type is base64
                                    if (strpos($image, 'data:image/') === 0) {
                                        $image = str_replace('data:image/webp;base64,', '', $image);
                                        $image = str_replace(' ', '+', $image);
                                        $directory = public_path(config('crudbooster.filemanager_current_path') . 'webp_images/');
                                        //---------------------------------------//
                                        // Retrieve the main image name and use it to set a new image's name
                                        $imageName = $column_data[str_replace("_webp", "", $colname)];
                                        $imageName = basename($imageName);
                                        $imageName = pathinfo($imageName, PATHINFO_FILENAME);
                                        $imageName = $imageName . '.webp';
                                        //---------------------------------------//
                                        $imagePath = $directory . $imageName;
                                        // Check if the image doesn't exist in the directory
                                        if (!file_exists($imagePath)) {
                                            file_put_contents($imagePath, base64_decode($image));
                                        }
                                        //---------------------------------------//
                                        $column_data[$colname] = config('crudbooster.filemanager_current_path') . 'webp_images/' . $imageName;
                                    } else {
                                        $column_data[$colname] = $image;
                                    }
                                }
                            } else if (isset($colvalue)) {
                                $column_data[$colname] = $colvalue;
                            }
                        }
                        if (isset($column_data) === true && !empty($column_data)) {
                            $column_data[$fk] = (!empty($id) ? $id : $lastInsertId);
                            $child_array[] = $column_data;
                        }
                    }

                    $childtable = CRUDBooster::parseSqlTable($ro['table'])['table'];
                    DB::table($childtable)->insert($child_array);
                }
            }
        }

        # in case module has images
        if (Request::input('list_images')) {
            $model_images = json_decode(Request::input('list_images')[0]);
            if ($model_images) {
                foreach ($model_images as $image) {
                    DB::table('model_images')->insert([
                        'model_type' => $this->table,
                        'model_id' => $lastInsertId,
                        'path' => $image,
                    ]);
                }
            }
        }

        //--- Check if translation table
        if ($this->translation_table) {
            foreach ($this->websiteLanguages as $lang) {
                $this->arr = [];
                $this->input_assignment("", $lang->code);
                //--- Get main column name
                $this->arr["locale"] = $lang->code;
                $this->arr[$this->translation_main_column] = $lastInsertId;
                //------------------------------//
                DB::table($this->translation_table)->insert($this->arr);
            }
        }
        //----------------------------------------------------//

        $this->hook_after_add($lastInsertId);

        $this->return_url = ($this->return_url) ? $this->return_url : request('return_url');

        //insert log
        CRUDBooster::insertLog(cbLang("log_add", ['name' => $this->arr[$this->title_field], 'module' => CRUDBooster::getCurrentModule()->name]));

        if ($this->return_url) {
            if (request('submit') == cbLang('button_save_more')) {
                CRUDBooster::redirect(Request::server('HTTP_REFERER'), cbLang("alert_add_data_success"), 'success');
            } else {
                CRUDBooster::redirect($this->return_url, cbLang("alert_add_data_success"), 'success');
            }
        } else {
            if (request('submit') == cbLang('button_save_more')) {
                CRUDBooster::redirect(CRUDBooster::mainpath('add'), cbLang("alert_add_data_success"), 'success');
            } else {
                CRUDBooster::redirect(CRUDBooster::mainpath(), cbLang("alert_add_data_success"), 'success');
            }
        }
    }

    public function getEdit($id)
    {
        $this->cbLoader();
        $row = DB::table($this->table)->where($this->primary_key, $id)->first();
        if ($this->translation_table) {
            $translationData = DB::table($this->translation_table)->where($this->translation_main_column, $id)->get();
            foreach ($translationData as $item) {
                foreach ($this->form as $column) {
                    if ($column["translation"]) {
                        $row->{$column["name"] . "_" . $item->locale} = $item->{$column["name"]};
                    }
                }
            }
        }
        if (!CRUDBooster::isRead() && $this->global_privilege == false || $this->button_edit == false) {
            CRUDBooster::insertLog(cbLang("log_try_edit", [
                'name' => $row->{$this->title_field},
                'module' => CRUDBooster::getCurrentModule()->name,
            ]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), cbLang('denied_access'));
        }

        $page_menu = Route::getCurrentRoute()->getActionName();
        $page_title = cbLang("edit_data_page_title", ['module' => CRUDBooster::getCurrentModule()->name, 'name' => $row->{$this->title_field}]);
        $command = 'edit';
        Session::put('current_row_id', $id);
        $manualView = null;
        $this->hook_before_get_edit($id, $row);

        if (view()->exists(CRUDBooster::getCurrentModule()->path . '.form')) {
            $manualView = view(CRUDBooster::getCurrentModule()->path . '.form', compact('id', 'row', 'page_menu', 'page_title', 'command'));
        }

        if (view()->exists('modules.' . CRUDBooster::getCurrentModule()->path . '.form')) {
            $manualView = view('modules.' . CRUDBooster::getCurrentModule()->path . '.form', compact('id', 'row', 'page_menu', 'page_title', 'command'));
        }

        $view = $view = $manualView ?: view('crudbooster::default.form', compact('id', 'row', 'page_menu', 'page_title', 'command'));
        return $view;
    }

    public function postEditSave($id)
    {
        $this->cbLoader();
        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if (!CRUDBooster::isUpdate() && $this->global_privilege == false) {
            CRUDBooster::insertLog(trans("crudbooster.log_try_add", ['name' => $row->{$this->title_field}, 'module' => CRUDBooster::getCurrentModule()->name]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
        }

        $formArrToValidate = [];
        foreach ($this->data_inputan as $field) {
            if ($field["translation"] == "FALSE") {
                $formArrToValidate[] = $field;
                continue;
            }
            foreach ($this->websiteLanguages as $lang) {
                $temp = $field;
                $temp["name"] = $field["name"] . "_" . $lang->code;
                $formArrToValidate[] = $temp;
            }
        }
        $this->validation($formArrToValidate, $id);
        $this->input_assignment($id);

        if (Schema::hasColumn($this->table, 'updated_at')) {
            $this->arr['updated_at'] = date('Y-m-d H:i:s');
        }
        $this->hook_before_edit($this->arr, $id);
        DB::table($this->table)->where($this->primary_key, $id)->update($this->arr);
        //Looping Data Input Again After Insert
        foreach ($this->data_inputan as $ro) {
            $name = $ro['name'];
            if (!$name) {
                continue;
            }
            $inputdata = Request::get($name);
            //Insert Data Checkbox if Type Datatable
            if ($ro['type'] == 'checkbox') {
                if ($ro['relationship_table']) {
                    $datatable = explode(",", $ro['datatable'])[0];

                    $foreignKey2 = CRUDBooster::getForeignKey($datatable, $ro['relationship_table']);
                    $foreignKey = CRUDBooster::getForeignKey($this->table, $ro['relationship_table']);
                    DB::table($ro['relationship_table'])->where($foreignKey, $id)->delete();

                    if ($inputdata) {
                        foreach ($inputdata as $input_id) {
                            $relationship_table_pk = CB::pk($ro['relationship_table']);
                            DB::table($ro['relationship_table'])->insert([
                                $foreignKey => $id,
                                $foreignKey2 => $input_id,
                            ]);
                        }
                    }
                }
            }

            if ($ro['type'] == 'select2') {
                if ($ro['relationship_table']) {
                    $datatable = explode(",", $ro['datatable'])[0];

                    $foreignKey2 = CRUDBooster::getForeignKey($datatable, $ro['relationship_table']);
                    $foreignKey = CRUDBooster::getForeignKey($this->table, $ro['relationship_table']);
                    DB::table($ro['relationship_table'])->where($foreignKey, $id)->delete();

                    if ($inputdata) {
                        foreach ($inputdata as $input_id) {
                            $relationship_table_pk = CB::pk($ro['relationship_table']);
                            DB::table($ro['relationship_table'])->insert([
                                $foreignKey => $id,
                                $foreignKey2 => $input_id,
                            ]);
                        }
                    }
                }
            }

            if ($ro['type'] == 'child') {
                $name = str_slug($ro['label'], '');
                $columns = $ro['columns'];
                $getColName = Request::get($name . '-' . $columns[0]['name']);
                $count_input_data = ($getColName) ? (count($getColName) - 1) : 0;
                $child_array = [];
                $childtable = CRUDBooster::parseSqlTable($ro['table'])['table'];
                $fk = $ro['foreign_key'];

                $lastId = CRUDBooster::newId($childtable);
                $childtablePK = CB::pk($childtable);
                if ($getColName > 0) {
                    //fesal
                    $updatedIds = [];
                    for ($i = 0; $i <= $count_input_data; $i++) {
                        $column_data = [];
                        $column_data[$childtablePK] = $lastId;
                        $column_data[$fk] = $id;
                        if (isset($column_data["sorting"])) {
                            $column_data["sorting"] = $i + 1;
                        }
                        foreach ($columns as $col) {
                            $colname = $col['name'];
                            // Conversion Functionality to webp images
                            if ($col['type'] == 'hidden' && strpos($colname, 'webp') !== false) {
                                $image = Request::get($name . '-' . $colname)[$i];
                                //---------------------------------------//
                                if (isset($image)) {
                                    //check if the image type is base64
                                    if (strpos($image, 'data:image/') === 0) {
                                        $image = str_replace('data:image/webp;base64,', '', $image);
                                        $image = str_replace(' ', '+', $image);
                                        $directory = public_path(config('crudbooster.filemanager_current_path') . 'webp_images/');
                                        //---------------------------------------//
                                        // Retrieve the main image name and use it to set a new image's name
                                        $imageName = $column_data[str_replace("_webp", "", $colname)];
                                        $imageName = basename($imageName);
                                        $imageName = pathinfo($imageName, PATHINFO_FILENAME);
                                        $imageName = $imageName . '.webp';
                                        //---------------------------------------//
                                        $imagePath = $directory . $imageName;
                                        // Check if the image doesn't exist in the directory
                                        if (!file_exists($imagePath)) {
                                            file_put_contents($imagePath, base64_decode($image));
                                        }
                                        //---------------------------------------//
                                        $column_data[$colname] = config('crudbooster.filemanager_current_path') . 'webp_images/' . $imageName;
                                    } else {
                                        $column_data[$colname] = $image;
                                    }
                                }
                            } else {
                                $column_data[$colname] = Request::get($name . '-' . $colname)[$i];
                            }
                        }
                        if (Request::get($name . '-id')[$i]) {
                            $updatedIds[] = Request::get($name . '-id')[$i];
                            unset($column_data[$childtablePK]);
                            DB::table($childtable)->where("id", Request::get($name . '-id')[$i])->update($column_data);
                            continue;
                        }
                        $child_array[] = $column_data;
                        $lastId++;
                    }
                    DB::table($childtable)->where($fk, $id)->whereNotIn("id", $updatedIds)->delete();
                    DB::table($childtable)->insert($child_array);
                } else {
                    DB::table($childtable)->where($fk, $id)->delete();
                }
            }
            //--- Check if translation table
            if ($this->translation_table) {
                DB::table($this->translation_table)->where($this->translation_main_column, $id)->delete();
                foreach ($this->websiteLanguages as $lang) {
                    $this->arr = [];
                    $this->input_assignment("", $lang->code);
                    //--- Get main column name
                    $this->arr["locale"] = $lang->code;
                    $this->arr[$this->translation_main_column] = $id;
                    //------------------------------//
                    DB::table($this->translation_table)->insert($this->arr);
                }
            }
            //----------------------------------------------------//
        }

        $this->hook_after_edit($id);

        $this->return_url = ($this->return_url) ? $this->return_url : Request::get('return_url');

        //insert log
        $old_values = json_decode(json_encode($row), true);
        CRUDBooster::insertLog(trans("crudbooster.log_update", [
            'name' => $this->arr[$this->title_field],
            'module' => CRUDBooster::getCurrentModule()->name,
        ]), LogsController::displayDiff($old_values, $this->arr));

        # if module has images
        if (Request::input('list_images')) {
            $model_images = json_decode(Request::input('list_images')[0]);
            # fetch existed from data base
            $existed_images = DB::table('model_images')->where([
                'model_type' => $this->table,
                'model_id' => $id,
            ])->get();
            foreach ($existed_images as $existed_image) {
                $row_id = array_keys(array_filter($model_images, function ($element) use ($existed_image) {
                    return $element == $existed_image->path;
                }));
                # delete after compare
                if (!$row_id) {
                    DB::table('model_images')->where([
                        'model_type' => $this->table,
                        'model_id' => $id,
                        'path' => $existed_image->path,
                    ])->delete();
                } else {
                    unset($model_images[$row_id[0]]);
                }
            }
            # insert the new images
            if (count($model_images)) {
                foreach ($model_images as $new_image) {
                    DB::table('model_images')->insert([
                        'model_type' => $this->table,
                        'model_id' => $id,
                        'path' => $new_image,
                    ]);
                }
            }
        }

        if ($this->return_url) {
            CRUDBooster::redirect($this->return_url, trans("crudbooster.alert_update_data_success"), 'success');
        } else {
            if (Request::get('submit') == trans('crudbooster.button_save_more')) {
                CRUDBooster::redirect(CRUDBooster::mainpath('add'), trans("crudbooster.alert_update_data_success"), 'success');
            } else {
                CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_update_data_success"), 'success');
            }
        }
    }

    public function getDelete($id)
    {
        $this->cbLoader();
        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if (!CRUDBooster::isDelete() && $this->global_privilege == false || $this->button_delete == false) {
            CRUDBooster::insertLog(cbLang("log_try_delete", [
                'name' => $row->{$this->title_field},
                'module' => CRUDBooster::getCurrentModule()->name,
            ]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), cbLang('denied_access'));
        }

        //insert log
        CRUDBooster::insertLog(cbLang("log_delete", ['name' => $row->{$this->title_field}, 'module' => CRUDBooster::getCurrentModule()->name]));

        $this->hook_before_delete($id);
        if ($this->translation_table) {
            DB::table($this->translation_table)->where($this->translation_main_column, $id)->delete();
        }

        if (CRUDBooster::isColumnExists($this->table, 'deleted_at')) {
            DB::table($this->table)->where($this->primary_key, $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
        } else {
            DB::table($this->table)->where($this->primary_key, $id)->delete();
        }
        if ($this->translation_table) {
            DB::table($this->translation_table)->where($this->translation_main_column, $id)->delete();
        }
        $this->hook_after_delete($id);

        $url = g('return_url') ?: CRUDBooster::referer();

        CRUDBooster::redirect($url, cbLang("alert_delete_data_success"), 'success');
    }

    public function getDetail($id)
    {
        $this->cbLoader();
        $row = DB::table($this->table)->where($this->primary_key, $id)->first();
        if ($this->translation_table) {
            $translationData = DB::table($this->translation_table)->where($this->translation_main_column, $id)->get();
            foreach ($translationData as $item) {
                foreach ($this->form as $column) {
                    if ($column["translation"]) {
                        $row->{$column["name"] . "_" . $item->locale} = $item->{$column["name"]};
                    }
                }
            }
        }

        if (!CRUDBooster::isRead() && $this->global_privilege == false || $this->button_detail == false) {
            CRUDBooster::insertLog(cbLang("log_try_view", [
                'name' => $row->{$this->title_field},
                'module' => CRUDBooster::getCurrentModule()->name,
            ]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), cbLang('denied_access'));
        }

        $module = CRUDBooster::getCurrentModule();

        $page_menu = Route::getCurrentRoute()->getActionName();
        $page_title = cbLang("detail_data_page_title", ['module' => $module->name, 'name' => $row->{$this->title_field}]);
        $command = 'detail';

        Session::put('current_row_id', $id);
        $manualView = null;
        if (view()->exists(CRUDBooster::getCurrentModule()->path . '.form')) {
            $manualView = view(CRUDBooster::getCurrentModule()->path . '.form', compact('row', 'page_menu', 'page_title', 'command', 'id'));
        }

        if (view()->exists('modules.' . CRUDBooster::getCurrentModule()->path . '.form')) {
            $manualView = view('modules.' . CRUDBooster::getCurrentModule()->path . '.form', compact('row', 'page_menu', 'page_title', 'command', 'id'));
        }

        $view = $manualView ?: view('crudbooster::default.form', compact('row', 'page_menu', 'page_title', 'command', 'id'));
        return $view;
    }

    public function getImportData()
    {
        $this->cbLoader();
        $data['page_menu'] = Route::getCurrentRoute()->getActionName();
        $data['page_title'] = 'Import Data';

        if (request('file') && !request('import')) {
            $file = base64_decode(request('file'));
            $file = storage_path('app/' . $file);
            $rows = Excel::toArray([], $file)[0];
            $countRows = ($rows) ? count($rows) : 0;

            Session::put('total_data_import', $countRows);

            $data_import_column = [];
            foreach ($rows as $value) {
                $a = [];
                foreach ($value as $k => $v) {
                    $a[] = $v;
                }
                if ($a && count($a)) {
                    $data_import_column = $a;
                }
                break;
            }

            $table_columns = DB::getSchemaBuilder()->getColumnListing($this->table);
            if ($this->translation_table) {
                $addedColumns = [];
                $translationColumns = DB::getSchemaBuilder()->getColumnListing($this->translation_table);
                foreach ($translationColumns as $column) {
                    if ($column != "id" && $column != "locale" && strpos($column, "_id") == 0) {
                        foreach ($this->websiteLanguages as $lang) {
                            $addedColumns[] = $column . "_" . $lang->code;
                        }
                    }
                }
                if ($addedColumns)
                    $table_columns = array_merge($addedColumns, $table_columns);
            }
            $data['table_columns'] = $table_columns;
            $data['data_import_column'] = $data_import_column;
        }

        return view('crudbooster::import', $data);
    }

    public function postDoneImport()
    {
        $this->cbLoader();
        $data['page_menu'] = Route::getCurrentRoute()->getActionName();
        $data['page_title'] = cbLang('button_import');
        Session::put('select_column', request('select_column'));

        return view('crudbooster::import', $data);
    }

    public function postDoImportChunk()
    {
        $this->cbLoader();
        $file_md5 = md5(request('file'));

        if (request('file') && request('resume') == 1) {
            $total = Session::get('total_data_import');
            $prog = intval(Cache::get('success_' . $file_md5)) / $total * 100;
            $prog = round($prog, 2);
            if ($prog >= 100) {
                Cache::forget('success_' . $file_md5);
            }

            return response()->json(['progress' => $prog, 'last_error' => Cache::get('error_' . $file_md5)]);
        }

        $select_column = Session::get('select_column');
        $select_column = array_filter($select_column);
        $table_columns = DB::getSchemaBuilder()->getColumnListing($this->table);
        $tableMainColumns = DB::getSchemaBuilder()->getColumnListing($this->table);
        $dateColumns = [];
        foreach ($tableMainColumns as $column) {
            $type = DB::getSchemaBuilder()->getColumnType($this->table, $column);
            if ($type == "date" || $type == "datetime")
                $dateColumns[] = $column;
        }
        if ($this->translation_table) {
            $translationAddedColumns = [];
            $translationColumns = DB::getSchemaBuilder()->getColumnListing($this->translation_table);
            foreach ($translationColumns as $column) {
                if ($column != "id" && $column != "locale" && strpos($column, "_id") == 0) {
                    foreach ($this->websiteLanguages as $lang) {
                        $translationAddedColumns[] = $column . "_" . $lang->code;
                    }
                }
            }
            if ($translationAddedColumns)
                $table_columns = array_merge($translationAddedColumns, $table_columns);
        }

        $file = base64_decode(request('file'));
        $file = storage_path('app/' . $file);

        // $rows = Excel::load($file, function ($reader) {
        // })->get();
        $rows = Excel::toArray([], $file)[0];
        $headerRow = $rows[0];
        $finalRows = [];
        foreach ($rows as $index => $row) {
            if ($index == 0)
                continue;
            $temp = new stdClass();
            foreach ($headerRow as $headerIndex => $headerValue) {
                $temp->$headerValue = $row[$headerIndex];
            }
            $finalRows[] = $temp;
        }

        $has_created_at = false;
        if (CRUDBooster::isColumnExists($this->table, 'created_at')) {
            $has_created_at = true;
        }
        foreach ($finalRows as $index => $value) {
            $a = [];
            foreach ($select_column as $sk => $s) {
                $colname = $table_columns[$sk];
                if (CRUDBooster::isForeignKey($colname)) {

                    //Skip if value is empty
                    if ($value->$s == '') {
                        continue;
                    }

                    if (intval($value->$s)) {
                        $a[$colname] = $value->$s;
                    } else {
                        $relation_table = CRUDBooster::getTableForeignKey($colname);
                        $relation_moduls = DB::table('cms_moduls')->where('table_name', $relation_table)->first();

                        $relation_class = __NAMESPACE__ . '\\' . $relation_moduls->controller;
                        if (!class_exists($relation_class)) {
                            $relation_class = '\App\Http\Controllers\\' . $relation_moduls->controller;
                        }
                        $relation_class = new $relation_class;
                        $relation_class->cbLoader();

                        $title_field = $relation_class->title_field;

                        $relation_insert_data = [];
                        $relation_insert_data[$title_field] = $value->$s;

                        if (CRUDBooster::isColumnExists($relation_table, 'created_at')) {
                            $relation_insert_data['created_at'] = date('Y-m-d H:i:s');
                        }

                        try {
                            //--- Check if title field is exist
                            $relationColumn = array_values(array_filter($relation_class->columns_table, function ($column) use ($title_field) {
                                if ($column["name"] == $title_field)
                                    return $column;
                            }))[0];
                            //----------------------------------------//
                            if (!optional($relationColumn)["translation"]) {
                                $relation_exists = DB::table($relation_table)->where($title_field, $value->$s)->first();
                                if ($relation_exists) {
                                    $relation_primary_key = $relation_class->primary_key;
                                    $relation_id = $relation_exists->$relation_primary_key;
                                } else {
                                    $relation_id = DB::table($relation_table)->insertGetId($relation_insert_data);
                                }
                            } else {
                                $relation_exists = DB::table($relation_class->translation_table)->where($title_field, $value->$s)->first();
                                if ($relation_exists) {
                                    $relation_id = $relation_exists->$colname;
                                } else {
                                    $relation_id = DB::table($relation_table)->insertGetId($relation_insert_data);
                                    // --- TODO Insert into translation table
                                }
                            }
                            $a[$colname] = $relation_id;
                        } catch (\Exception $e) {
                            exit($e);
                        }
                    } //END IS INT

                } else {
                    $a[$colname] = $value->$s;
                }
            }
            $has_title_field = true;
            foreach ($a as $k => $v) {
                if ($k == $this->title_field && $v == '') {
                    $has_title_field = false;
                    break;
                }
            }

            if ($has_title_field == false) {
                continue;
            }

            try {
                if ($has_created_at) {
                    $a['created_at'] = date('Y-m-d H:i:s');
                }
                $dataToInsert = [];
                foreach ($a as $column => $columnValue) {
                    if (in_array($column, $tableMainColumns)) {
                        if (in_array($column, $dateColumns)) {
                            $dateValue = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($a[$column])->format('Y-m-d');
                            $dataToInsert[$column] = $dateValue;
                        } else {
                            $dataToInsert[$column] = $a[$column];
                        }
                    }
                }
                $lastInsertId = DB::table($this->table)->insertGetId($dataToInsert);
                if ($this->translation_table) {
                    foreach ($this->websiteLanguages as $lang) {
                        $dataToInsert = [];
                        //------------------------------//
                        foreach ($translationColumns as $column) {
                            if ($column != "id" && $column != "locale" && strpos($column, "_id") == 0) {
                                $dataToInsert[$column] = $a[$column . "_" . $lang->code];
                            }
                        }
                        $dataToInsert["locale"] = $lang->code;
                        $dataToInsert[$this->translation_main_column] = $lastInsertId;
                        //------------------------------//
                        DB::table($this->translation_table)->insert($dataToInsert);
                    }
                }
                Cache::increment('success_' . $file_md5);
            } catch (\Exception $e) {
                $e = (string) $e;
                Log::error("Error on importing $e");
                Cache::put('error_' . $file_md5, $e, 500);
            }
        }

        return response()->json(['status' => true]);
    }

    public function postDoUploadImportData()
    {
        $this->cbLoader();
        if (Request::hasFile('userfile')) {
            $file = Request::file('userfile');
            $ext = $file->getClientOriginalExtension();

            $validator = Validator::make([
                'extension' => $ext,
            ], [
                'extension' => 'in:xls,xlsx,csv',
            ]);

            if ($validator->fails()) {
                $message = $validator->errors()->all();

                return redirect()->back()->with(['message' => implode('<br/>', $message), 'message_type' => 'warning']);
            }

            //Create Directory Monthly
            $filePath = 'uploads/' . CB::myId() . '/' . date('Y-m');
            Storage::makeDirectory($filePath);

            //Move file to storage
            $filename = md5(str_random(5)) . '.' . $ext;
            $url_filename = '';
            if (Storage::putFileAs($filePath, $file, $filename)) {
                $url_filename = $filePath . '/' . $filename;
            }
            $url = CRUDBooster::mainpath('import-data') . '?file=' . base64_encode($url_filename);

            return redirect($url);
        } else {
            return redirect()->back();
        }
    }

    public function postActionSelected()
    {
        $this->cbLoader();
        $id_selected = Request::input('checkbox');
        $button_name = Request::input('button_name');

        if (!$id_selected) {
            CRUDBooster::redirect($_SERVER['HTTP_REFERER'], cbLang("alert_select_a_data"), 'warning');
        }

        if ($button_name == 'delete') {
            if (!CRUDBooster::isDelete()) {
                CRUDBooster::insertLog(cbLang("log_try_delete_selected", ['module' => CRUDBooster::getCurrentModule()->name]));
                CRUDBooster::redirect(CRUDBooster::adminPath(), cbLang('denied_access'));
            }
            if ($this->translation_table) {
                DB::table($this->translation_table)->where($this->translation_main_column, $id)->delete();
            }
            $this->hook_before_delete($id_selected);
            $tablePK = CB::pk($this->table);
            if (CRUDBooster::isColumnExists($this->table, 'deleted_at')) {

                DB::table($this->table)->whereIn($tablePK, $id_selected)->update(['deleted_at' => date('Y-m-d H:i:s')]);
            } else {
                DB::table($this->table)->whereIn($tablePK, $id_selected)->delete();
            }
            CRUDBooster::insertLog(cbLang("log_delete", ['name' => implode(',', $id_selected), 'module' => CRUDBooster::getCurrentModule()->name]));

            $this->hook_after_delete($id_selected);

            $message = cbLang("alert_delete_selected_success");

            return redirect()->back()->with(['message_type' => 'success', 'message' => $message]);
        }

        $action = str_replace(['-', '_'], ' ', $button_name);
        $action = ucwords($action);
        $type = 'success';
        $message = cbLang("alert_action", ['action' => $action]);

        if ($this->actionButtonSelected($id_selected, $button_name) === false) {
            $message = !empty($this->alert['message']) ? $this->alert['message'] : 'Error';
            $type = !empty($this->alert['type']) ? $this->alert['type'] : 'danger';
        }

        if (str_contains($button_name, 'active_all') || str_contains($button_name, 'deactive_all')) {
            $table_name = CRUDBooster::getCurrentModule()->table_name;
            if (str_contains($button_name, 'deactive_all')) {
                $button_name = str_replace('deactive_all-', '', $button_name);
                DB::table($table_name)->whereIn('id', $id_selected)->update([$button_name => 0]);
            } else {
                $button_name = str_replace('active_all-', '', $button_name);
                DB::table($table_name)->whereIn('id', $id_selected)->update([$button_name => 1]);
            }
        }

        return redirect()->back()->with(['message_type' => $type, 'message' => $message]);
    }

    public function getDeleteImage()
    {
        $this->cbLoader();
        $id = request('id');
        $column = request('column');

        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if (!CRUDBooster::isDelete() && $this->global_privilege == false) {
            CRUDBooster::insertLog(cbLang("log_try_delete_image", [
                'name' => $row->{$this->title_field},
                'module' => CRUDBooster::getCurrentModule()->name,
            ]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), cbLang('denied_access'));
        }

        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        $file = str_replace('uploads/', '', $row->{$column});
        if (Storage::exists($file)) {
            Storage::delete($file);
        }

        DB::table($this->table)->where($this->primary_key, $id)->update([$column => null]);

        CRUDBooster::insertLog(cbLang("log_delete_image", [
            'name' => $row->{$this->title_field},
            'module' => CRUDBooster::getCurrentModule()->name,
        ]));

        CRUDBooster::redirect(Request::server('HTTP_REFERER'), cbLang('alert_delete_data_success'), 'success');
    }

    public function postUploadSummernote()
    {
        $this->cbLoader();
        $name = 'userfile';
        if ($file = CRUDBooster::uploadFile($name, true)) {
            echo asset($file);
        }
    }

    public function postUploadFile()
    {
        $this->cbLoader();
        $name = 'userfile';
        if ($file = CRUDBooster::uploadFile($name, true)) {
            echo asset($file);
        }
    }

    public function actionButtonSelected($id_selected, $button_name)
    {
    }

    public function hook_query_index(&$query)
    {
    }

    public function hook_row_index($index, &$value)
    {
    }

    public function hook_before_add(&$arr)
    {
    }

    public function hook_after_add($id)
    {
    }

    public function hook_before_get_edit($id, &$row)
    {
    }

    public function hook_before_edit(&$arr, $id)
    {
    }

    public function hook_after_edit($id)
    {
    }

    public function hook_before_delete($id)
    {
    }

    public function hook_after_delete($id)
    {
    }

    # voila additional functions
    public function postEditSwitchAction(Request $request)
    {
        DB::table(Request::input('table'))
            ->where('id', Request::input('id'))
            ->update([Request::input('feild') => Request::input('value')]);
        CRUDBooster::insertLog(cbLang('log_switch_value', [
            'module' => CRUDBooster::getCurrentModule()->name,
            'name' => Request::input('feild'),
        ]));
    }

    public function postSortTable(Request $request)
    {
        if (Request::input("data")) {
            $sortedItems = Request::input('data');
            $table_name = Request::input("table_name");
            foreach ($sortedItems as $key => $sortedItem) {
                DB::table($table_name)->where("id", $sortedItems[$key])->update([
                    'sorting' => $key + 1,
                ]);
            }
            return response()->json(array("message" => "done", "status" => true));
        }
        return response()->json(array("message" => "faild", "status" => false));
    }

    public function getClearLogs()
    {
        try {
            DB::table('cms_logs')->delete();
        } catch (Exception $ex) {
            Log::log("error", "Error while delete logs" . $ex->getMessage());
        }
        return redirect()->back();
    }

    public function getSwitchLanguage($locale)
    {
        Session::put('lang', $locale);
        Session::put('locale', $locale);
        App::setlocale($locale);
        set_setting('default_language', $locale);
        return redirect()->back()->withInput();
    }
}
