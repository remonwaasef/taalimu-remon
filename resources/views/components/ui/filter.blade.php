@props(['name', 'options', 'label' => 'Filter'])

<div class="dropdown">
    <button class="btn btn-light shadow-sm rounded-pill px-4 dropdown-toggle d-flex align-items-center justify-content-between ui-filter-select" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
            style="height: 48px; min-width: 150px;">
        <span>{{ request($name) ? $options[request($name)] : $label }}</span>
    </button>
    <ul class="dropdown-menu shadow border-0 mt-2 rounded-3">
        <li>
            <a class="dropdown-item {{ !request($name) ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery([$name => null]) }}">
                All
            </a>
        </li>
        @foreach($options as $value => $text)
            <li>
                <a class="dropdown-item {{ request($name) == $value ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery([$name => $value]) }}">
                    {{ $text }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
