<form action="{{ $url ?? url()->current() }}" method="GET">
    <div class="search-input">
        <input type="text" name="search" placeholder="{{ $placeholder ?? translate('Search for...') }}" required
            value="{{ request('search') }}" />
        <button class="btn btn-primary">
            <i class="fa fa-search"></i>
            <span class="d-none d-lg-inline ms-2">{{ translate('Search') }}</span>
        </button>
    </div>
</form>
