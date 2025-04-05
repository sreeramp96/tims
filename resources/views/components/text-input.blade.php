@props(['disabled' => false, 'label' => 'Issue'])
<div>
    <label class="block text-gray-700">{{ $label }}</label>
    <input @disabled($disabled)
        {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
</div>
