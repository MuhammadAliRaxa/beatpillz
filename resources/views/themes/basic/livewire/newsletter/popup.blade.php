<div>
    @if (
        @$settings->newsletter->status &&
            @$settings->newsletter->popup_status &&
            !request()->hasCookie('nl_subscribed') &&
            !request()->hasCookie('nl_remind'))
        <div wire:ignore.self class="modal fade" id="newsletterModal" tabindex="-1" aria-labelledby="newsletterModalLabel"
            aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4 py-5">
                    <div class="modal-body p-0">
                        <div class="text-center">
                            <div class="mb-3">
                                <img src="{{ asset(@$settings->newsletter->popup_image) }}" width="220px"
                                    alt="{{ translate('Subscribe to Our Newsletter') }}">
                            </div>
                            <h4>{{ translate('Subscribe to Our Newsletter') }}</h4>
                            <p class="mb-3">
                                {{ translate('Stay tuned for the latest and greatest items and offers, delivered right to your inbox!') }}
                            </p>
                        </div>
                        <div class="card-bg border rounded-3 p-4">
                            <form wire:submit.prevent="subscribe">
                                <div class="mb-3">
                                    <label class="form-label">{{ translate('Your Email') }}</label>
                                    <input type="email" wire:model.defer="email" class="form-control form-control-md"
                                        placeholder="name@example.com" value="{{ authUser() ? authUser()->email : '' }}"
                                        required>
                                </div>
                                <button class="btn btn-primary btn-md w-100">{{ translate('Subscribe') }}</button>
                            </form>
                            <button class="btn btn-outline-primary btn-md mt-3 w-100"
                                wire:click="remindLater">{{ translate('Remind me later') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            "use strict";
            document.addEventListener("DOMContentLoaded", function() {
                var newsletterModal = new bootstrap.Modal(document.getElementById('newsletterModal'));
                newsletterModal.show();
            });
        </script>
    @endif
</div>
