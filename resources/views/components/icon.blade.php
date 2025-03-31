@props(['name'])

@if ($name === 'chevron-left')
    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M14 16l-6-6 6-6v12z" clip-rule="evenodd" />
    </svg>
@elseif ($name === 'chevron-right')
    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M6 16l6-6-6-6v12z" clip-rule="evenodd" />
    </svg>
@endif
