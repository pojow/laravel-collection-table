<div class="py-1">
    <div class="input-group">
        @if($icon)
            <span class="input-group-text text-secondary">
                {!! view($icon)->render() !!}
            </span>
        @endif
        <select {{ $multiple ? 'multiple' : null }}
                name="{{ $parameterName . ($multiple ? '[]' : '') }}"
                class="{{ $selectClasses }}"
                aria-label="{{ $label }}"
                {!! $selectAttributes !!}>
            @unless($multiple)
                <option value="">{{ $label }}</option>
            @endunless
            @foreach($options as $optionKey => $optionLabel)
                <option value="{{ $optionKey }}"
                    {{ ($multiple && in_array($optionKey, $selected ?? [])) || (!$multiple && $optionKey == $selected) ? 'selected' : null }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    </div>
</div>
