<div class="py-1">
    <div class="flex items-stretch w-full relative">
        @if($icon)
            <span class="bg-gray-100 text-gray-600 px-2.5 grid place-content-center border border-r-0 rounded-l">
                {!! view($icon)->render() !!}
            </span>
        @endif
        <select {{ $multiple ? 'multiple' : null }}
                name="{{ $parameterName . ($multiple ? '[]' : '') }}"
                class="{{ $selectClasses }}"
                aria-label="{{ $label }}"
            {{ $selectAttributes }}>
            @unless($multiple)
                <option value="">{{ $label }}</option>
            @endunless
            @foreach($options as $optionKey => $optionLabel)
                <option value="{{ $optionKey }}"
                    {{ ($multiple && in_array($optionKey, $selected ?? [], true)) || (!$multiple && $optionKey === $selected) ? 'selected' : null }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    </div>
</div>
