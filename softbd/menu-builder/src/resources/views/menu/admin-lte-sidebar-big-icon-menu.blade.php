@if(!isset($innerLoop))
    <ul class="nav nav-pills nav-sidebar flex-column nav-big-icon-sidebar" data-widget="treeview" role="menu">
        @else
            <ul class="nav nav-treeview">
                @endif
                @foreach ($items as $item)
                    @php
                        /** @var \Softbd\MenuBuilder\Models\MenuItem $originalItem */
                        $originalItem = $item;
                        $listItemClass = 'nav-item '.(!isset($innerLoop) ? 'parent':'');
                        $linkAttributes =  null;
                        $styles = null;
                        $icon = null;
                        $caret = null;

                        // Background Color or Color
                        if (isset($options->color) && $options->color == true) {
                            $styles = 'color:'.$item->color;
                        }
                        if (isset($options->background) && $options->background == true) {
                            $styles = 'background-color:'.$item->color;
                        }

                        // With Children Attributes
                        if(!$originalItem->children->isEmpty()) {
                            $caret = '<i class="right fas fa-angle-left"></i>';
                            if($originalItem->active) {
                                $listItemClass .= ' menu-open';
                            }
                        }

                        // Set Icon
                        if(isset($options->icon) && $options->icon == true){
                            $icon = '<div><i class="nav-icon fas ' . $item->icon_class . '"></i></div>';
                        }
                    @endphp


                    @if($originalItem->children->isEmpty() && $originalItem->url == '#')
                        @continue
                    @endif
                    <li class="{{ $listItemClass }}">
                        <a href="{{ url($item->link()) }}" target="{{ $item->target }}"
                           class="nav-link {{$item->active ? 'active': ''}}">
                            {!! $icon !!}
                            <p>
                                {{ $item->title }}
                                {!! $caret !!}
                            </p>
                        </a>
                        @if(!$originalItem->children->isEmpty())
                            @include('menu-builder::menu.admin-lte-sidebar-menu', ['items' => $originalItem->children, 'options' => $options, 'innerLoop' => true])
                        @endif
                    </li>
                @endforeach
            </ul>
