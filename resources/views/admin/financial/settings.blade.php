@extends('admin.layouts.form')
@section('title', translate('Financial settings'))
@section('container', 'container-max-md')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.financial.settings') }}" method="POST">
        @csrf
        @if (!isAddonActive('multi_currency'))
            <div class="card mb-3">
                <div class="card-header">{{ translate('Currency') }}</div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">{{ translate('Currency Code') }}</label>
                            <input type="text" name="currency[code]" class="form-control form-control-md"
                                value="{{ @$settings->currency->code }}" placeholder="{{ translate('USD') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ translate('Currency Symbol') }}</label>
                            <input type="text" name="currency[symbol]" class="form-control form-control-md"
                                value="{{ @$settings->currency->symbol }}" placeholder="$" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ translate('Currency position') }}</label>
                            <select name="currency[position]" class="form-select form-select-md">
                                <option value="1" {{ @$settings->currency->position == 1 ? 'selected' : '' }}>
                                    {{ translate('Before price') }}</option>
                                <option value="2" {{ @$settings->currency->position == 2 ? 'selected' : '' }}>
                                    {{ translate('After price') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="card">
            <div class="card-header">{{ translate('Deposit') }}</div>
            <div class="card-body p-4">
                <div class="col-lg-5">
                    <label class="form-label">{{ translate('Status') }}</label>
                    <input type="checkbox" id="depositStatus" name="deposit[status]" data-toggle="toggle" data-height="40px"
                        @checked(@$settings->deposit->status)>
                </div>
                <div id="depositDetails" class="row g-3 mt-2 {{ !@$settings->deposit->status ? 'd-none' : '' }}">
                    <div class="col-12">
                        @include('admin.partials.input-price', [
                            'label' => translate('Minimum Deposit Amount'),
                            'name' => 'deposit[minimum]',
                            'input_classes' => 'form-control-md',
                            'value' => @$settings->deposit->minimum,
                            'min' => 1,
                            'integer' => true,
                            'required' => true,
                        ])
                    </div>
                    <div class="col-12">
                        @include('admin.partials.input-price', [
                            'label' => translate('Maximum Deposit Amount'),
                            'name' => 'deposit[maximum]',
                            'input_classes' => 'form-control-md',
                            'value' => @$settings->deposit->maximum,
                            'min' => 1,
                            'integer' => true,
                            'required' => true,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('scripts')
        <script>
            "use strict";
            let depositStatus = $('#depositStatus');
            depositStatus.on('change', function() {
                let depositDetails = $('#depositDetails');
                if ($(this).is(':checked')) {
                    depositDetails.removeClass('d-none');
                } else {
                    depositDetails.addClass('d-none');
                }
            });
        </script>
    @endpush
@endsection
