@if (isAddonActive('multi_currency'))
    @php
        $currencies = currencies();
        $currentCurrency = currency(config('app.currency'));
    @endphp
    @if ($currencies->count() > 0)
        <div class="drop-down drop-down-img drop-down-scroll" data-dropdown data-dropdown-position="top">
            <div class="drop-down-btn">
                <div class="drop-down-btn-img">
                    <img src="{{ $currentCurrency->getIconLink() }}" alt="{{ $currentCurrency->code }}">
                </div>
                <span class="me-2">{{ $currentCurrency->code }}</span>
                <i class="fa fa-angle-down ms-auto"></i>
            </div>
            <div class="drop-down-menu drop-down-menu-sm {{ $group_classes ?? '' }}">
                @foreach (currencies() as $currency)
                    <a href="{{ route('currency', $currency->code) }}"
                        class="drop-down-item {{ $currentCurrency->id == $currency->id ? 'active' : '' }}">
                        <div class="drop-down-item-img">
                            <img src="{{ $currency->getIconLink() }}" alt="{{ $currency->code }}">
                        </div>
                        <span>{{ $currency->code }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
@endif
