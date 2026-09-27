<div class="tab-pane fade" id="authentication" role="tabpanel" aria-labelledby="authentication-tab">
    <div class="mb-4">
        <h2 class="mb-4">{{ translate('Authentication') }}</h2>
        <div class="card mb-4">
            <div class="card-body p-4">
                <h4>{{ translate('Navigate to Workspace Settings') }}</h4>
                <p class="card-text">
                    {{ translate('The user should first log in to their account on the platform. Then, they can navigate to the "Settings" section of their workspace.') }}
                </p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body p-4">
                <h4>{{ translate('Locate API Key Section') }}</h4>
                <p class="card-text">
                    {{ translate('Within the workspace settings, the user should look for a section specifically labeled "API Key" or "API Access."') }}
                </p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body p-4">
                <h4>{{ translate('Generate or Retrieve API Key') }}</h4>
                <p class="card-text">
                    {{ translate('In this section, the user can either generate a new API key or retrieve an existing one if it has been previously generated. If there is an option to generate a new key, the user can click on it to create a fresh API key.') }}
                </p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body p-4">
                <h4>{{ translate('Copy the API Key') }}</h4>
                <p class="card-text">
                    {{ translate('Once the API key is generated or retrieved, the user should be able to see it displayed on the screen. They can simply click on a button or icon next to the key to copy it to their clipboard.') }}
                </p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body p-4">
                <h4>{{ translate('Use the API Key') }}</h4>
                <p class="card-text">
                    {{ translate('With the API key copied, the user can now use it to authenticate their requests when accessing the platform API endpoints. They typically need to include the API key as part of the request headers or parameters, depending on the API authentication mechanism.') }}
                </p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body p-4">
                <h4>{{ translate('Secure the API Key') }}</h4>
                <p class="card-text">
                    {{ translate('Its essential to remind users to keep their API keys secure and not share them publicly. They should avoid hardcoding API keys in client-side code or sharing them in publicly accessible repositories. Instead, they should consider storing the API key securely on their server-side applications and using appropriate access controls.') }}
                </p>
            </div>
        </div>
    </div>
</div>
