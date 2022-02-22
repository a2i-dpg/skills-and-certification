<ol class="dd-list">
    @foreach ($items as $item)
        <li class="dd-item" data-id="{{ $item->id }}">
            <div class="float-right item_actions">
                <div class="btn-group btn-group-sm mt-2 mr-2">
                    <div class="btn btn-sm btn-outline-danger delete" data-id="{{ $item->id }}">
                        <i class="fas fa-trash"></i>
                    </div>
                    <div class="btn btn-sm btn-outline-primary float-right edit"
                         data-id="{{ $item->id }}"
                         data-title="{{ $item->title }}"
                         data-title_lang_key="{{ $item->title_lang_key }}"
                         data-url="{{ $item->url }}"
                         data-target="{{ $item->target }}"
                         data-icon_class="{{ $item->icon_class }}"
                         data-color="{{ $item->color }}"
                         data-route="{{ $item->route }}"
                         data-permission_key="{{ $item->permission_key }}"
                         data-parameters="{{ json_encode($item->parameters) }}"
                    >
                        <i class="fas fa-edit"></i>
                    </div>
                </div>
            </div>
            <div class="dd-handle">
                <span>{{ $item->title }}</span> <small class="url">{{ $item->link() }}</small>
            </div>
            @if(!$item->children->isEmpty())
                @include('menu-builder::menu.menu-builder-editor', ['items' => $item->children])
            @endif
        </li>
    @endforeach
</ol>
