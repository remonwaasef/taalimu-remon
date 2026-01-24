@props(['action', 'placeholder' => 'Search...'])

<form action="{{ $action }}" method="GET" class="d-flex position-relative">
    <input 
        type="text" 
        name="search" 
        value="{{ request('search') }}" 
        class="form-control ps-5 rounded-pill border-0 shadow-sm" 
        placeholder="{{ $placeholder }}"
        style="background-color: var(--color-light); height: 48px;"
    >
    <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </span>
</form>
