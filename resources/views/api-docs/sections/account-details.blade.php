<div class="tab-pane fade" id="account-details" role="tabpanel" aria-labelledby="account-details-tab">
    <div class="mb-4">
        <h2 class="mb-3">{{ translate('Get Account Details') }}</h2>
        <p>
            {{ translate('Retrieves details of the account associated with the provided API key') }}
        </p>
    </div>
    <h4 class="mb-3">{{ translate('Endpoint') }}</h4>
    <div class="code mb-3">
        <div class="copy">
            <i class="far fa-clone"></i>
        </div>
        <code>
            <pre class="mb-0"><div class="method get">{{ translate('GET') }}</div><div class="endpoint copy-data">{{ route('api.account.details') }}</div></pre>
        </code>
    </div>
    <h4 class="mb-3">{{ translate('Parameters') }}</h4>
    <ul>
        <li><strong>api_key</strong>: {{ translate('Your API key') }}
            <code>({{ translate('required') }})</code>
        </li>
    </ul>
    <h4 class="mb-3">{{ translate('Responses') }}</h4>
    <p><strong>{{ translate('Success Response') }}:</strong></p>
    <div class="code mb-3">
        <code>
            <pre class="mb-0 text-success">
{
    "status": "{{ translate('success') }}",
    "data": {
        "name": {
            "firstname": "John",
            "lastname": "Doe",
            "full_name": "John Doe"
        },
        "username": "johndoe",
        "email": "john.doe@example.com",
        "balance": 100.00,
        "currency": "{{ defaultCurrency()->code }}",
        "profile": {
            "heading": "Profile Heading",
            "description": "Profile Description",
            "contact": {
                "email": "contact@example.com"
            },
            "social_links": [
                "facebook": "/",
                "x": "/",
                // etc...
            ],
            "media": {
                "avatar": "https://example.com/avatar.jpg",
                "cover": "https://example.com/cover.jpg"
            }
        },
        "registered_at": "2024-04-27T12:00:00Z"
    }
}</pre>
        </code>
    </div>
    <p><strong>{{ translate('Error Response') }}:</strong></p>
    <div class="code mb-3">
        <code>
            <pre class="mb-0 text-danger">
{
    "status": "{{ translate('error') }}",
    "msg": "{{ translate('Invalid request') }}"
}</pre>
        </code>
    </div>
</div>
