<div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
    <h1 class="mb-4">{{ translate('API Documentation Overview') }}</h1>
    <div class="card mb-4">
        <div class="card-header bg-white p-3">
            <h4 class="mb-0">{{ translate('1. Get Account Details') }}</h4>
        </div>
        <div class="card-body p-4">
            <p><strong>{{ translate('Endpoint') }}:</strong>
                <code>{{ translate('GET') }} {{ route('api.account.details') }}</code>
            </p>
            <p class="mb-0">
                <strong>{{ translate('Description') }}:</strong>
                {{ translate('Retrieves details of the account associated with the provided API key.') }}
            </p>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header bg-white p-3">
            <h4 class="mb-0">{{ translate('2. Get All Items') }}</h4>
        </div>
        <div class="card-body p-4">
            <p><strong>{{ translate('Endpoint') }}:</strong>
                <code>{{ translate('GET') }} {{ route('api.items.all') }}</code>
            </p>
            <p class="mb-0">
                <strong>{{ translate('Description') }}:</strong>
                {{ translate('Retrieves all items associated with the provided API key.') }}
            </p>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header bg-white p-3">
            <h4 class="mb-0">{{ translate('3. Get An Item Details') }}</h4>
        </div>
        <div class="card-body p-4">
            <p><strong>{{ translate('Endpoint') }}:</strong>
                <code>{{ translate('GET') }} {{ route('api.items.item') }}</code>
            </p>
            <p class="mb-0">
                <strong>{{ translate('Description') }}:</strong>
                {{ translate('Retrieves details of a specific item based on the provided item ID and API key.') }}
            </p>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header bg-white p-3">
            <h4 class="mb-0">{{ translate('4. Purchase Validation') }}</h4>
        </div>
        <div class="card-body p-4">
            <p><strong>{{ translate('Endpoint') }}:</strong>
                <code>{{ translate('POST') }} {{ route('api.purchases.validation') }}</code>
            </p>
            <p class="mb-0">
                <strong>{{ translate('Description') }}:</strong>
                {{ translate('Validate a purchase code and returns details about the purchase if valid.') }}
            </p>
        </div>
    </div>
</div>
