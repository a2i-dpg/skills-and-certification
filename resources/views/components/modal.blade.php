<div class="modal fade modal-{{$type}}" tabindex="-1" id="{{$id}}" role="dialog" data-keyboard="false" data-backdrop="static">
    <div
        class="modal-dialog modal-dialog-scrollable {{($xl ? 'modal-xl' : ($lg ? 'modal-lg' : ($sm ? 'modal-sm' : '')))}}">
        <div class="modal-content">
            {{$slot}}
        </div>
    </div>
</div>
