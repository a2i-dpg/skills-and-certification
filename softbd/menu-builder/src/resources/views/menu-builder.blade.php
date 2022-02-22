@extends('menu-builder::core.main')

@push(config('menu-builder.template.css_placeholder', 'css'))
    <style>
        .dd {
            position: relative;
            display: block;
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 13px;
            line-height: 20px;
        }
        .dd-list {
            display: block;
            position: relative;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .dd-list .dd-list {
            padding-left: 30px;
        }
        .dd-collapsed .dd-list {
            display: none;
        }
        .dd-item,
        .dd-empty,
        .dd-placeholder {
            display: block;
            position: relative;
            margin: 0;
            padding: 0;
            min-height: 20px;
            font-size: 13px;
            line-height: 20px;
        }
        .dd-handle {
            display: block;
            height: 50px;
            margin: 5px 0;
            padding: 14px 25px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #ccc;
            background: #fafafa;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .dd-handle:hover {
            color: #2ea8e5;
            background: #fff;
        }
        .dd-item > button {
            display: block;
            position: relative;
            cursor: pointer;
            float: left;
            width: 40px;
            height: 37px;
            margin: 5px 0;
            padding: 0;
            text-indent: 100%;
            white-space: nowrap;
            overflow: hidden;
            border: 0;
            background: transparent;
            font-size: 12px;
            line-height: 1;
            text-align: center;
            font-weight: bold;
        }
        .dd-item > button:before {
            content: '+';
            display: block;
            position: absolute;
            width: 100%;
            text-align: center;
            text-indent: 0;
        }
        .dd-item > button[data-action="collapse"]:before {
            content: '-';
        }
        .dd-placeholder,
        .dd-empty {
            margin: 5px 0;
            padding: 0;
            min-height: 30px;
            background: #f2fbff;
            border: 1px dashed #b6bcbf;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .dd-empty {
            border: 1px dashed #bbb;
            min-height: 100px;
            background-color: #e5e5e5;
            background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
            -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
            background-image: -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
            -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
            background-image: linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
            linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
            background-size: 60px 60px;
            background-position: 0 0, 30px 30px;
        }

        .dd-dragel {
            position: absolute;
            pointer-events: none;
            z-index: 9999;
        }

        .dd-dragel > .dd-item .dd-handle {
            margin-top: 0;
        }
        .dd-dragel .dd-handle {
            -webkit-box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
            box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
        }
    </style>
@endpush

@section(config('menu-builder.template.content_placeholder','content'))
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <p class="card-title" style="color:#777">{{ __('Menu Builder') }}</p>
                        <div class="btn-group btn-group-sm">
                            <a href="{{route('menu-builder.menus.index')}}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-backward"></i> Return to list
                            </a>
                            <a href="#" class="add_item btn btn-sm btn-primary">
                                <i class="fas fa-plus-circle"></i> Add Item
                            </a>
                        </div>
                    </div>

                    <div class="card-body menu-builder-nestable" style="padding:30px;">
                        <div class="dd">
                            {!! menu($menu->name, 'builder') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-primary fade" id="menu_item_modal" tabindex="-1" role="dialog"
         aria-labelledby="menu_item_modal_label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="m_hd_add" class="modal-title hidden"><i
                            class="fas fa-plus-circle"></i> {{ __('Create new menu item.') }}</h4>
                    <h4 id="m_hd_edit" class="modal-title hidden"><i
                            class="fas fa-edit"></i> {{ __('Edit item.') }}</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label=""><span
                            aria-hidden="true">&times;</span></button>
                </div>

                <form action="" id="m_form" method="POST"
                      data-action-add="{{ route('menu-builder.menus.item.store', ['menu' => $menu->id]) }}"
                      data-action-update="{{ route('menu-builder.menus.item.update', ['menu' => $menu->id]) }}">

                    <input id="m_form_method" type="hidden" name="_method" value="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <label for="m_title">{{ __('Title') }}</label>
                        <input type="text" class="form-control" id="m_title" name="title"
                               placeholder="{{ __('Title') }}"><br>
                        <label for="m_title_lang_key">{{ __('Language Key') }}</label>
                        <input type="text" class="form-control" id="m_title_lang_key" name="title_lang_key"
                               placeholder="{{ __('Language Key') }}"><br>
                        <label for="m_link_type">{{ __('Link type') }}</label>
                        <select id="m_link_type" class="form-control" name="type">
                            <option value="url"
                                    selected="selected">{{ __('Static url') }}</option>
                            <option value="route">{{ __('Dynamic route') }}</option>
                        </select><br>
                        <div id="m_url_type">
                            <label for="m_url">{{ __('Url') }}</label>
                            <input type="text" class="form-control" id="m_url" name="url"
                                   placeholder="{{ __('Url') }}"><br>
                        </div>
                        <div id="m_route_type">
                            <label for="m_route">{{ __('Route') }}</label>
                            <input type="text" class="form-control" id="m_route" name="route"
                                   placeholder="{{ __('Route') }}"><br>
                            <label for="m_parameters">{{ __('Route parameters') }}</label>
                            <textarea rows="3" class="form-control" id="m_parameters" name="parameters"
                                      placeholder="{{ json_encode(['key' => 'value'], JSON_PRETTY_PRINT) }}"></textarea><br>
                        </div>
                        <label for="m_icon_class">{{ __('Icon class (Fontawesome)') }}</label>
                        <input type="text" class="form-control" id="m_icon_class" name="icon_class"
                               placeholder="{{ __('Icon class') }}"><br>
                        <!-- Added by Mahmud -->
                        <label for="m_permission_key">{{ __('Permission key') }}</label>
                        <input type="text" class="form-control" id="m_permission_key" name="permission_key"
                               placeholder="{{ __('Permission key') }}"><br>
                        <!-- end of Added by Mahmud -->
                        <label for="m_color">{{ __('Color') }}</label>
                        <input type="color" class="form-control" id="m_color" name="color"
                               placeholder="{{ __('Color') }}"><br>
                        <label for="m_target">{{ __('Open In') }}</label>
                        <select id="m_target" class="form-control" name="target">
                            <option value="_self"
                                    selected="selected">{{ __('Same Tab') }}</option>
                            <option value="_blank">{{ __('New Tab') }}</option>
                        </select>
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                        <input type="hidden" name="id" id="m_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">{{ __('Cancel') }}</button>
                        <input type="submit" class="btn btn-success delete-confirm__"
                               value="{{ __('Update') }}">
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('Are you sure?') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label=""><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>This action is permanent.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right"
                            data-dismiss="modal">{{ __('Cancel') }}</button>
                    <form action=""
                          id="delete_form"
                          method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="{{ __('Delete') }}">
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@push(config('menu-builder.template.js_placeholder', 'js'))
    <script>
        /*!
         * Nestable jQuery Plugin - Copyright (c) 2012 David Bushell - http://dbushell.com/
         * Dual-licensed under the BSD or MIT licenses
         */
        !function(p,c,g,n){var i="ontouchstart"in g,f=function(){var t=g.createElement("div"),e=g.documentElement;if(!("pointerEvents"in t.style))return!1;t.style.pointerEvents="auto",t.style.pointerEvents="x",e.appendChild(t);var s=c.getComputedStyle&&"auto"===c.getComputedStyle(t,"").pointerEvents;return e.removeChild(t),!!s}(),s={listNodeName:"ol",itemNodeName:"li",rootClass:"dd",listClass:"dd-list",itemClass:"dd-item",dragClass:"dd-dragel",handleClass:"dd-handle",collapsedClass:"dd-collapsed",placeClass:"dd-placeholder",noDragClass:"dd-nodrag",emptyClass:"dd-empty",expandBtnHTML:'<button data-action="expand" type="button">Expand</button>',collapseBtnHTML:'<button data-action="collapse" type="button">Collapse</button>',group:0,maxDepth:5,threshold:20};function a(t,e){this.w=p(g),this.el=p(t),this.options=p.extend({},s,e),this.init()}a.prototype={init:function(){var a=this;a.reset(),a.el.data("nestable-group",this.options.group),a.placeEl=p('<div class="'+a.options.placeClass+'"/>'),p.each(this.el.find(a.options.itemNodeName),function(t,e){a.setParent(p(e))}),a.el.on("click","button",function(t){var e,s,i;a.dragEl||(s=(e=p(t.currentTarget)).data("action"),i=e.parent(a.options.itemNodeName),"collapse"===s&&a.collapseItem(i),"expand"===s&&a.expandItem(i))});function t(t){var e=p(t.target);if(!e.hasClass(a.options.handleClass)){if(e.closest("."+a.options.noDragClass).length)return;e=e.closest("."+a.options.handleClass)}e.length&&!a.dragEl&&(a.isTouch=/^touch/.test(t.type),a.isTouch&&1!==t.touches.length||(t.preventDefault(),a.dragStart(t.touches?t.touches[0]:t)))}function e(t){a.dragEl&&(t.preventDefault(),a.dragMove(t.touches?t.touches[0]:t))}function s(t){a.dragEl&&(t.preventDefault(),a.dragStop(t.touches?t.touches[0]:t))}i&&(a.el[0].addEventListener("touchstart",t,!1),c.addEventListener("touchmove",e,!1),c.addEventListener("touchend",s,!1),c.addEventListener("touchcancel",s,!1)),a.el.on("mousedown",t),a.w.on("mousemove",e),a.w.on("mouseup",s)},serialize:function(){var o=this;return step=function(t,i){var a=[];return t.children(o.options.itemNodeName).each(function(){var t=p(this),e=p.extend({},t.data()),s=t.children(o.options.listNodeName);s.length&&(e.children=step(s,i+1)),a.push(e)}),a},step(o.el.find(o.options.listNodeName).first(),0)},serialise:function(){return this.serialize()},reset:function(){this.mouse={offsetX:0,offsetY:0,startX:0,startY:0,lastX:0,lastY:0,nowX:0,nowY:0,distX:0,distY:0,dirAx:0,dirX:0,dirY:0,lastDirX:0,lastDirY:0,distAxX:0,distAxY:0},this.isTouch=!1,this.moving=!1,this.dragEl=null,this.dragRootEl=null,this.dragDepth=0,this.hasNewRoot=!1,this.pointEl=null},expandItem:function(t){t.removeClass(this.options.collapsedClass),t.children('[data-action="expand"]').hide(),t.children('[data-action="collapse"]').show(),t.children(this.options.listNodeName).show()},collapseItem:function(t){t.children(this.options.listNodeName).length&&(t.addClass(this.options.collapsedClass),t.children('[data-action="collapse"]').hide(),t.children('[data-action="expand"]').show(),t.children(this.options.listNodeName).hide())},expandAll:function(){var t=this;t.el.find(t.options.itemNodeName).each(function(){t.expandItem(p(this))})},collapseAll:function(){var t=this;t.el.find(t.options.itemNodeName).each(function(){t.collapseItem(p(this))})},setParent:function(t){t.children(this.options.listNodeName).length&&(t.prepend(p(this.options.expandBtnHTML)),t.prepend(p(this.options.collapseBtnHTML))),t.children('[data-action="expand"]').hide()},unsetParent:function(t){t.removeClass(this.options.collapsedClass),t.children("[data-action]").remove(),t.children(this.options.listNodeName).remove()},dragStart:function(t){var e=this.mouse,s=p(t.target),i=s.closest(this.options.itemNodeName);this.placeEl.css("height",i.height()),e.offsetX=t.offsetX!==n?t.offsetX:t.pageX-s.offset().left,e.offsetY=t.offsetY!==n?t.offsetY:t.pageY-s.offset().top,e.startX=e.lastX=t.pageX,e.startY=e.lastY=t.pageY,this.dragRootEl=this.el,this.dragEl=p(g.createElement(this.options.listNodeName)).addClass(this.options.listClass+" "+this.options.dragClass),this.dragEl.css("width",i.width()),i.after(this.placeEl),i[0].parentNode.removeChild(i[0]),i.appendTo(this.dragEl),p(g.body).append(this.dragEl),this.dragEl.css({left:t.pageX-e.offsetX,top:t.pageY-e.offsetY});for(var a,o=this.dragEl.find(this.options.itemNodeName),l=0;l<o.length;l++)(a=p(o[l]).parents(this.options.listNodeName).length)>this.dragDepth&&(this.dragDepth=a)},dragStop:function(t){var e=this.dragEl.children(this.options.itemNodeName).first();e[0].parentNode.removeChild(e[0]),this.placeEl.replaceWith(e),this.dragEl.remove(),this.el.trigger("change"),this.hasNewRoot&&this.dragRootEl.trigger("change"),this.reset()},dragMove:function(t){var e,s,i=this.options,a=this.mouse;this.dragEl.css({left:t.pageX-a.offsetX,top:t.pageY-a.offsetY}),a.lastX=a.nowX,a.lastY=a.nowY,a.nowX=t.pageX,a.nowY=t.pageY,a.distX=a.nowX-a.lastX,a.distY=a.nowY-a.lastY,a.lastDirX=a.dirX,a.lastDirY=a.dirY,a.dirX=0===a.distX?0:0<a.distX?1:-1,a.dirY=0===a.distY?0:0<a.distY?1:-1;var o=Math.abs(a.distX)>Math.abs(a.distY)?1:0;if(!a.moving)return a.dirAx=o,void(a.moving=!0);a.dirAx!==o?(a.distAxX=0,a.distAxY=0):(a.distAxX+=Math.abs(a.distX),0!==a.dirX&&a.dirX!==a.lastDirX&&(a.distAxX=0),a.distAxY+=Math.abs(a.distY),0!==a.dirY&&a.dirY!==a.lastDirY&&(a.distAxY=0)),a.dirAx=o,a.dirAx&&a.distAxX>=i.threshold&&(a.distAxX=0,s=this.placeEl.prev(i.itemNodeName),0<a.distX&&s.length&&!s.hasClass(i.collapsedClass)&&(e=s.find(i.listNodeName).last(),this.placeEl.parents(i.listNodeName).length+this.dragDepth<=i.maxDepth&&(e.length?(e=s.children(i.listNodeName).last()).append(this.placeEl):((e=p("<"+i.listNodeName+"/>").addClass(i.listClass)).append(this.placeEl),s.append(e),this.setParent(s)))),a.distX<0&&(this.placeEl.next(i.itemNodeName).length||(r=this.placeEl.parent(),this.placeEl.closest(i.itemNodeName).after(this.placeEl),r.children().length||this.unsetParent(r.parent()))));var l=!1;if(f||(this.dragEl[0].style.visibility="hidden"),this.pointEl=p(g.elementFromPoint(t.pageX-g.body.scrollLeft,t.pageY-(c.pageYOffset||g.documentElement.scrollTop))),f||(this.dragEl[0].style.visibility="visible"),this.pointEl.hasClass(i.handleClass)&&(this.pointEl=this.pointEl.parent(i.itemNodeName)),this.pointEl.hasClass(i.emptyClass))l=!0;else if(!this.pointEl.length||!this.pointEl.hasClass(i.itemClass))return;var n=this.pointEl.closest("."+i.rootClass),d=this.dragRootEl.data("nestable-id")!==n.data("nestable-id");if(!a.dirAx||d||l){if(d&&i.group!==n.data("nestable-group"))return;if(this.dragDepth-1+this.pointEl.parents(i.listNodeName).length>i.maxDepth)return;var h=t.pageY<this.pointEl.offset().top+this.pointEl.height()/2,r=this.placeEl.parent();l?((e=p(g.createElement(i.listNodeName)).addClass(i.listClass)).append(this.placeEl),this.pointEl.replaceWith(e)):h?this.pointEl.before(this.placeEl):this.pointEl.after(this.placeEl),r.children().length||this.unsetParent(r.parent()),this.dragRootEl.find(i.itemNodeName).length||this.dragRootEl.append('<div class="'+i.emptyClass+'"/>'),d&&(this.dragRootEl=n,this.hasNewRoot=this.el[0]!==this.dragRootEl[0])}}},p.fn.nestable=function(e){var s=this;return this.each(function(){var t=p(this).data("nestable");t?"string"==typeof e&&"function"==typeof t[e]&&(s=t[e]()):(p(this).data("nestable",new a(this,e)),p(this).data("nestable-id",(new Date).getTime()))}),s||this}}(window.jQuery||window.Zepto,window,document);
    </script>

    <script>
        @if (!empty($errors) && $errors->any())
        @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
        @endforeach
        @endif
    </script>



    <script>
        //TODO: Add jQuery validation
        $(document).ready(function () {
            $('.dd').nestable({
                expandBtnHTML: '',
                collapseBtnHTML: ''
            });

            /**
             * Set Variables
             */
            let $m_modal = $('#menu_item_modal'),
                $m_hd_add = $('#m_hd_add').hide().removeClass('hidden'),
                $m_hd_edit = $('#m_hd_edit').hide().removeClass('hidden'),
                $m_form = $('#m_form'),
                $m_form_method = $('#m_form_method'),
                $m_title = $('#m_title'),
                $m_title_lang_key = $('#m_title_lang_key'),
                $m_url_type = $('#m_url_type'),
                $m_url = $('#m_url'),
                $m_link_type = $('#m_link_type'),
                $m_route_type = $('#m_route_type'),
                $m_route = $('#m_route'),
                $m_parameters = $('#m_parameters'),
                $m_permission_key = $('#m_permission_key'),
                $m_icon_class = $('#m_icon_class'),
                $m_color = $('#m_color'),
                $m_target = $('#m_target'),
                $m_id = $('#m_id');

            /**
             * Add Menu
             */
            $('.add_item').click(function () {
                $m_form.trigger('reset');
                $m_form.find("input[type=submit]").val('{{ __('Add') }}');
                $m_modal.modal('show', {data: null});
            });

            /**
             * Edit Menu
             */
            $('.item_actions').on('click', '.edit', function (e) {
                $m_form.find("input[type=submit]").val('{{ __('Update') }}');
                $m_modal.modal('show', {data: $(e.currentTarget)});
            });

            /**
             * Menu Modal is Open
             */
            $m_modal.on('show.bs.modal', function (e, data) {
                let _adding = e.relatedTarget.data ? false : true;

                if (_adding) {
                    $m_form.attr('action', $m_form.data('action-add'));
                    $m_form_method.val('POST');
                    $m_hd_add.show();
                    $m_hd_edit.hide();
                    $m_target.val('_self').change();
                    $m_link_type.val('url').change();
                    $m_url.val('');
                    $m_icon_class.val('');

                } else {
                    $m_form.attr('action', $m_form.data('action-update'));
                    $m_form_method.val('PUT');
                    $m_hd_add.hide();
                    $m_hd_edit.show();

                    let _src = e.relatedTarget.data, // the source
                        id = _src.data('id');

                    $m_title.val(_src.data('title'));
                    $m_url.val(_src.data('url'));
                    $m_route.val(_src.data('route'));
                    $m_parameters.val(JSON.stringify(_src.data('parameters')));
                    $m_permission_key.val(_src.data('permission_key'));
                    $m_title_lang_key.val(_src.data('title_lang_key'));
                    $m_icon_class.val(_src.data('icon_class'));
                    $m_color.val(_src.data('color'));
                    $m_id.val(id);

                    if (_src.data('target') == '_self') {
                        $m_target.val('_self').change();
                    } else if (_src.data('target') == '_blank') {
                        $m_target.find("option[value='_self']").removeAttr('selected');
                        $m_target.find("option[value='_blank']").attr('selected', 'selected');
                        $m_target.val('_blank');
                    }
                    if (_src.data('route') != "") {
                        $m_link_type.val('route').change();
                        $m_url_type.hide();
                    } else {
                        $m_link_type.val('url').change();
                        $m_route_type.hide();
                    }
                    if ($m_link_type.val() == 'route') {
                        $m_url_type.hide();
                        $m_route_type.show();
                    } else {
                        $m_route_type.hide();
                        $m_url_type.show();
                    }
                }
            });


            /**
             * Toggle Form Menu Type
             */
            $m_link_type.on('change', function (e) {
                if ($m_link_type.val() == 'route') {
                    $m_url_type.hide();
                    $m_route_type.show();
                } else {
                    $m_url_type.show();
                    $m_route_type.hide();
                }
            });


            /**
             * Delete menu item
             */
            $('.item_actions').on('click', '.delete', function (e) {
                let id = $(e.currentTarget).data('id');
                $('#delete_form')[0].action = '{{ route('menu-builder.menus.item.destroy', ['menu_id' => $menu->id, 'id' => '__id']) }}'.replace('__id', id);
                $('#delete_modal').modal('show');
            });


            /**
             * Reorder items
             */
            $('.dd').on('change', function (e) {
                $.post('{{ route('menu-builder.menus.order',['menu' => $menu->id]) }}', {
                    order: JSON.stringify($('.dd').nestable('serialize')),
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    toastr.success("{{ __('Menu item order updated.') }}");
                });
            });
        });
    </script>
@endpush
