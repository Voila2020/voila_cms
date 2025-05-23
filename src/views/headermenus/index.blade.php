@extends('crudbooster::admin_template')
@section('content')
    @push('head')
        <style type="text/css">
            body.dragging,
            body.dragging * {
                cursor: move !important;
            }

            .dragged {
                position: absolute;
                opacity: 0.7;
                z-index: 2000;
            }

            .draggable-menu {
                padding: 0 0 0 0;
                margin: 0 0 0 0;
            }

            .draggable-menu li ul {
                margin-top: 6px;
            }

            .draggable-menu li div {
                padding: 5px;
                border: 1px solid #cccccc;
                background: #eeeeee;
                cursor: move;
            }

            .draggable-menu li .is-dashboard {
                background: #fff6e0;
            }

            .draggable-menu li .icon-is-dashboard {
                color: #ffb600;
            }

            .draggable-menu li {
                list-style-type: none;
                margin-bottom: 4px;
                min-height: 35px;
            }

            .draggable-menu li.placeholder {
                position: relative;
                border: 1px dashed #b7042c;
                background: #ffffff;
                /** More li styles **/
            }

            .draggable-menu li.placeholder:before {
                position: absolute;
                /** Define arrowhead **/
            }
        </style>
    @endpush

    @push('bottom')
        <script type="text/javascript">
            $(function() {
                function format(icon) {
                    var originalOption = icon.element;
                    var label = $(originalOption).text();
                    var val = $(originalOption).val();
                    if (!val) return label;
                    var $resp = $('<span><i style="margin-top:5px" class="pull-right ' + $(originalOption).val() +
                        '"></i> ' + $(originalOption).data('label') + '</span>');
                    return $resp;
                }
            })
        </script>
    @endpush
    @push('bottom')
        <script src='{{ asset('vendor/crudbooster/assets/jquery-sortable-min.js') }}'></script>
        <script type="text/javascript">
            $(function() {
                var id_cms_privileges = '{{ $id_cms_privileges }}';
                var sortactive = $(".draggable-menu").sortable({
                    group: '.draggable-menu',
                    delay: 200,
                    isValidTarget: function($item, container) {
                        var depth = 1, // Start with a depth of one (the element itself)
                            maxDepth = 3,
                            children = $item.find('ul').first().find('li');

                        // Add the amount of parents to the depth
                        depth += container.el.parents('ul').length;

                        // Increment the depth for each time a child
                        while (children.length) {
                            depth++;
                            children = children.find('ul').first().find('li');
                        }
                        return depth <= maxDepth;
                    },
                    onDrop: function($item, container, _super) {

                        if ($item.parents('ul').hasClass('draggable-menu-active')) {
                            var isActive = 1;
                            var data = $('.draggable-menu-active').sortable("serialize").get();
                            var jsonString = JSON.stringify(data, null, ' ');
                        } else {
                            var isActive = 0;
                            var data = $('.draggable-menu-inactive').sortable("serialize").get();
                            var jsonString = JSON.stringify(data, null, ' ');
                            $('#inactive_text').remove();
                        }

                        $.post("{{ route('AdminHeaderMenusControllerPostSaveMenu') }}", {
                            menus: jsonString,
                            isActive: isActive
                        }, function(resp) {
                            $('#menu-saved-info').fadeIn('fast').delay(1000).fadeOut('fast');
                        });

                        _super($item, container);
                    }
                });
            });
        </script>
    @endpush

    <div class='row'>
        <div class="col-sm-5">

            <div class="panel panel-success">
                <div class="panel-heading">
                    <strong>{{ cbLang('Menu Order') . '(' . cbLang('Active') . ')' }} </strong> <span id='menu-saved-info'
                        style="display:none" class='pull-right text-success'><i class='fa fa-check'></i> Menu Saved !</span>
                </div>
                <div class="panel-body clearfix">
                    <ul class='draggable-menu draggable-menu-active'>
                        @foreach ($menu_active ?? [] as $menu)
                            @php
                                $privileges = DB::table('cms_menus_privileges')
                                    ->join(
                                        'cms_privileges',
                                        'cms_privileges.id',
                                        '=',
                                        'cms_menus_privileges.id_cms_privileges',
                                    )
                                    ->where('id_cms_menus', $menu->id)
                                    ->pluck('cms_privileges.name')
                                    ->toArray();
                            @endphp
                            <li data-id='{{ $menu->id }}'
                                data-name='{{ $current_lang == 'en' ? $menu->name_en : $menu->name_ar }}'>
                                <div class='{{ $menu->is_dashboard ? 'is-dashboard' : '' }}'
                                    title="{{ $menu->is_dashboard ? 'This is set as Dashboard' : '' }}">
                                    <i
                                        class='{{ $menu->is_dashboard ? 'icon-is-dashboard fa fa-dashboard' : $menu->icon }}'></i>
                                    {{ $current_lang == 'en' ? $menu->name_en : $menu->name_ar }} <span
                                        class='pull-right'><a class='fa fa-pencil' title='Edit'
                                            href='{{ route('AdminHeaderMenusControllerGetEdit') . '/' . $menu->id }}?return_url={{ urlencode(Request::fullUrl()) }}'></a>&nbsp;&nbsp;<a
                                            title='Delete' class='fa fa-trash'
                                            onclick='{{ CRUDBooster::deleteConfirm(route('AdminHeaderMenusControllerGetDelete') . '/' . $menu->id) }}'
                                            href='javascript:void(0)'></a></span>
                                    <br /><em class="text-muted">
                                        <small><i class="fa fa-users"></i> &nbsp; {{ implode(', ', $privileges) }}
                                        </small>
                                    </em>
                                </div>
                                {!! getBuilderMenuChildren($menu,$current_lang) !!}
                            </li>
                        @endforeach
                    </ul>
                    @if (count($menu_active ?? []) == 0)
                        <div align="center">Active menu is empty, please add new menu</div>
                    @endif
                </div>
            </div>

            <div class="panel panel-danger">
                <div class="panel-heading">
                    <strong>{{ cbLang('Menu Order') . '(' . cbLang('Inactive') . ')' }} </strong>
                </div>
                <div class="panel-body clearfix">
                    <ul class='draggable-menu draggable-menu-inactive'>
                        @foreach ($menu_inactive ?? [] as $menu)
                            <li data-id='{{ $menu->id }}'
                                data-name='{{ $current_lang == 'en' ? $menu->name_en : $menu->name_ar }}'>
                                <div><i class='{{ $menu->icon }}'></i>
                                    {{ $current_lang == 'en' ? $menu->name_en : $menu->name_ar }} <span
                                        class='pull-right'><a class='fa fa-pencil' title='Edit'
                                            href='{{ route('AdminHeaderMenusControllerGetEdit') . '/' . $menu->id }}?return_url={{ urlencode(Request::fullUrl()) }}'></a>&nbsp;&nbsp;<a
                                            title='Delete' class='fa fa-trash'
                                            onclick='{{ CRUDBooster::deleteConfirm(route('AdminHeaderMenusControllerGetDelete') . '/' . $menu->id) }}'
                                            href='javascript:void(0)'></a></span></div>
                                <ul>
                                    @if ($menu->children)
                                        @foreach ($menu->children as $child)
                                            <li data-id='{{ $child->id }}'
                                                data-name='{{ $current_lang == 'en' ? $child->name_en : $child->name_ar }}'>
                                                <div><i class='{{ $child->icon }}'></i>
                                                    {{ $current_lang == 'en' ? $child->name_en : $child->name_ar }} <span
                                                        class='pull-right'><a class='fa fa-pencil' title='Edit'
                                                            href='{{ route('AdminHeaderMenusControllerGetEdit') . '/' . $child->id }}?return_url={{ urlencode(Request::fullUrl()) }}'></a>&nbsp;&nbsp;<a
                                                            title="Delete" class='fa fa-trash'
                                                            onclick='{{ CRUDBooster::deleteConfirm(route('AdminHeaderMenusControllerGetDelete') . '/' . $child->id) }}'
                                                            href='javascript:void(0)'></a></span></div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                    @if (count($menu_inactive ?? []) == 0)
                        <div align="center" id='inactive_text' class='text-muted'>Inactive menu is empty</div>
                    @endif
                </div>
            </div>


        </div>
        <div class="col-sm-7">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    {{ cbLang('Add Menu') }}
                </div>
                <div class="panel-body">
                    <form class='form-horizontal' method='post' id="form" enctype="multipart/form-data"
                        action='{{ CRUDBooster::mainpath('add-save') }}'>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type='hidden' name='return_url' value='{{ Request::fullUrl() }}' />
                        @include('crudbooster::default.form_body')
                        <p align="right"><input type='submit' class='btn btn-primary' value='{{ cbLang('Add Menu') }}' />
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@php

    function getBuilderMenuChildren($menu,$current_lang)
    {
        $results = '';
        if ($menu->children) {
            $results .= '<ul>';
            foreach ($menu->children as $child) {
                $privileges = DB::table('cms_menus_privileges')
                    ->join('cms_privileges', 'cms_privileges.id', '=', 'cms_menus_privileges.id_cms_privileges')
                    ->where('id_cms_menus', $child->id)
                    ->pluck('cms_privileges.name')
                    ->toArray();

                $editHref =
                    route('AdminHeaderMenusControllerGetEdit') .
                    '/' .
                    $child->id .
                    '?return_url=' .
                    urlencode(Request::fullUrl());

                $deleteClick = CRUDBooster::deleteConfirm(
                    route('AdminHeaderMenusControllerGetDelete') . '/' . $child->id,
                    true,
                );

                $results .=
                    "<li data-id='{$child->id}' data-name='" .
                    ($current_lang == 'en' ? $child->name_en : $child->name_ar) .
                    "'>";
                $results .=
                    "   <div class='" .
                    ($child->is_dashboard ? 'is-dashboard' : '') .
                    "' title='" .
                    ($child->is_dashboard ? 'This is set as Dashboard' : '') .
                    "'>";
                $results .=
                    "       <i class='" .
                    ($child->is_dashboard ? 'icon-is-dashboard fa fa-dashboard' : $child->icon) .
                    "'></i>";
                $results .= $current_lang == 'en' ? $child->name_en : $child->name_ar;
                $results .= "       <span class='pull-right'>";
                $results .= "           <a class='fa fa-pencil' title='Edit' href='$editHref'></a>&nbsp;&nbsp;";
                $results .=
                    "           <a class='fa fa-trash' title='Delete' onclick='" .
                    $deleteClick .
                    "' href='javascript:void(0)'></a>";
                $results .= '       </span>';
                $results .= '       <br />';
                $results .=
                    "       <em class='text-muted'><small><i class='fa fa-users'></i> &nbsp;" .
                    implode(', ', $privileges) .
                    '</small></em>';
                $results .= '   </div>';
                $results .= getBuilderMenuChildren($child,$current_lang);
                $results .= '</li>';
            }
            $results .= '</ul>';
        }
        return $results;
    }
@endphp
