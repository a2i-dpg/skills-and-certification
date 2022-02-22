<div {{ $attributes->merge(['class' => 'form-group']) }}>
    <label for="{{$id ?: $name}}">
        {{ __($label) }}
    </label>
    <input
        type="text"
        class="form-control"
        id="{{$id}}"
        name="{{$name}}"
        value="{{$defaultValue}}"
        placeholder="{{ $placeholder ?: $label }}"
    >
</div>
