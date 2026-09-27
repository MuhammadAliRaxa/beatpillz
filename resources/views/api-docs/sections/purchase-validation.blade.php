<div class="tab-pane fade" id="purchases-validation" role="tabpanel" aria-labelledby="purchases-validation-tab">
    <div class="mb-4">
        <h2 class="mb-3">{{ translate('Purchase Validation') }}</h2>
        <p>
            {{ translate('Validate a purchase code and returns details about the purchase if valid.') }}
        </p>
        <div class="alert alert-warning" role="alert">
            <div>
                <i class="fa-regular fa-circle-question fa-lg me-1"></i>
                <span>{{ translate('This only works for authors and will not work for regular users.') }}</span>
            </div>
        </div>
    </div>
    <h4 class="mb-3">{{ translate('Endpoint') }}</h4>
    <div class="code mb-3">
        <div class="copy">
            <i class="far fa-clone"></i>
        </div>
        <code>
            <pre class="mb-0"><div class="method post">{{ translate('POST') }}</div><div class="endpoint copy-data">{{ route('api.purchases.validation') }}</div></pre>
        </code>
    </div>
    <h4 class="mb-3">{{ translate('Parameters') }}</h4>
    <ul>
        <li>
            <strong>api_key</strong>: {{ translate('Your API key') }}
            <code>({{ translate('required') }})</code>.
        </li>
        <li>
            <strong>purchase_code</strong>:
            {{ translate('The purchase code to validate') }}
            <code>({{ translate('required') }})</code>.
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
        "purchase": {
            "purchase_code": "abcdefghijklmnopqrstuvwxyz123456789",
            "license_type": "{{ translate('Regular') }}",
            "price": 19.99,
            "currency": "{{ defaultCurrency()->code }}",
            "item": {
                "id": 1,
                "name": "Sample Item",
                "description": "This is a sample item",
                "category": "Category Name",
                "sub_category": "Subcategory Name",
                "options": ["option1", "option2"],
                "version": 1.0.0,
                "demo_link": "https://example.com/demo",
                "tags": ["tag1", "tag2"],
                "media": {
                    "thumbnail": "https://example.com/thumbnail.png",
                    "preview_image": "https://example.com/preview.jpg", // {{ translate('This is not included for audio items') }}
                    "preview_video": "https://example.com/video.mp4", // {{ translate('This is only included for video items') }}
                    "preview_audio": "https://example.com/audio.mp3", // {{ translate('This is only included for audio items') }}
                    "screenshots": [ {{ translate('This is only included if item has screenshots') }}
                        "https://example.com/screenshot1.jpg",
                        "https://example.com/screenshot2.jpg"
                    ],
                },
                "price": {
                    "regular": 19.99,
                    "extended": 29.99
                },
                "currency": "{{ defaultCurrency()->code }}",
                "published_at": "2024-04-27T12:00:00Z"
            },
            "supported_until": "2024-04-27T12:00:00Z", // {{ translate('This will not exist if support is disabled or its not supported') }}
            "downloaded": false,
            "date": "2024-04-27T12:00:00Z"
        }
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
    "msg": "{{ translate('Invalid purchase code') }}"
}</pre>
        </code>
    </div>
</div>
