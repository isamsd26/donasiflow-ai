@switch($icon)
    @case('dashboard')
        <svg class="{{ $class ?? 'h-5 w-5' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path d="M3 13h8V3H3v10Zm10 8h8v-6h-8v6Zm0-10h8V3h-8v8ZM3 21h8v-6H3v6Z"></path>
        </svg>
        @break
    @case('campaign')
        <svg class="{{ $class ?? 'h-5 w-5' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path d="m3 11 18-5-5 18-3-7-7-3Z"></path>
        </svg>
        @break
    @case('category')
        <svg class="{{ $class ?? 'h-5 w-5' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path d="M20 12V4H4v8m16 0v8H4v-8m8-8v16"></path>
        </svg>
        @break
    @case('updates')
        <svg class="{{ $class ?? 'h-5 w-5' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path d="M4 5h16v14H4z"></path>
            <path d="M8 9h8M8 13h8M8 17h5"></path>
        </svg>
        @break
    @case('transactions')
        <svg class="{{ $class ?? 'h-5 w-5' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
            <path d="M3 10h18M7 15h3"></path>
        </svg>
        @break
    @case('donors')
        <svg class="{{ $class ?? 'h-5 w-5' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"></path>
            <circle cx="9.5" cy="7" r="4"></circle>
            <path d="M20 8v6M17 11h6"></path>
        </svg>
        @break
    @case('banner')
        <svg class="{{ $class ?? 'h-5 w-5' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
            <path d="m8 13 2.5-3 3 4 2-2 2.5 3"></path>
        </svg>
        @break
    @case('faq')
        <svg class="{{ $class ?? 'h-5 w-5' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path d="M9.1 9a3 3 0 1 1 5.8 1c-.5 1-1.7 1.5-2.3 2.2-.4.4-.6.9-.6 1.8"></path>
            <path d="M12 17h.01"></path>
            <path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
        </svg>
        @break
    @case('testimonial')
        <svg class="{{ $class ?? 'h-5 w-5' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path d="M7 10h6v4H9l-2 2v-6Z"></path>
            <path d="M13 8h4v4h-2l-2 2V8Z"></path>
            <path d="M5 5h14a2 2 0 0 1 2 2v8"></path>
        </svg>
        @break
    @case('pages')
        <svg class="{{ $class ?? 'h-5 w-5' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path d="M7 3h8l4 4v14H7z"></path>
            <path d="M15 3v5h5M10 13h6M10 17h6"></path>
        </svg>
        @break
    @case('settings')
        <svg class="{{ $class ?? 'h-5 w-5' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path d="M12 15.5A3.5 3.5 0 1 0 12 8.5a3.5 3.5 0 0 0 0 7Z"></path>
            <path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1.04 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1.04-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1.04H3a2 2 0 1 1 0-4h.09A1.7 1.7 0 0 0 4.64 8.9a1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.64a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.36 9c.16.39.25.8.24 1.23A1.7 1.7 0 0 0 21 12a2 2 0 1 1 0 4h-.09A1.7 1.7 0 0 0 19.4 15Z"></path>
        </svg>
        @break
@endswitch
