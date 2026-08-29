@extends('admin.layouts.form')
@section('section', translate('Financial'))
@section('title', translate('Edit Currency'))
@section('back', route('admin.financial.currencies.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.financial.currencies.update', $currency->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card mb-4">
            <div class="card-header">{{ translate('Currency Details') }}</div>
            <div class="card-body p-4">
                <div class="row g-3 mb-2">
                    <div class="col-12">
                        <div class="vironeer-file-preview-box mb-3 bg-light p-4 text-center">
                            <div class="file-preview-box  mb-3">
                                <img id="filePreview" src="{{ $currency->getIconLink() }}" width="50px">
                            </div>
                            <button id="selectFileBtn" type="button" class="btn btn-secondary mb-2"><i
                                    class="fas fa-camera me-2"></i>{{ translate('Choose Icon') }}</button>
                            <input id="selectedFileInput" type="file" name="icon" accept=".png, .jpg, .jpeg, .webp"
                                hidden>
                            <small class="text-muted d-block">{{ translate('Allowed (PNG, JPG, JPEG, WEBP)') }}</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ translate('Currency Code') }}</label>
                        <input type="text" class="form-control form-control-md" value="{{ $currency->code }}" disabled>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ translate('Currency Symbol') }}</label>
                        <input type="text" name="symbol" class="form-control form-control-md"
                            value="{{ $currency->symbol }}" placeholder="$" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ translate('Currency position') }}</label>
                        <select name="position" class="form-select form-select-md">
                            @foreach (\App\Models\Currency::getCurrencyPositionOptions() as $positioKey => $positionValue)
                                <option value="{{ $positioKey }}" @selected($currency->position == $positioKey)>{{ $positionValue }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">{{ translate('Currency Exchange Rate') }}</div>
            <div class="card-body p-3">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-5">
                        @include('admin.partials.input-price', [
                            'input_classes' => 'form-control-md',
                            'value' => 1,
                            'disabled' => true,
                        ])
                    </div>
                    <div class="col text-center d-none d-lg-inline">
                        <div class="fs-1">=</div>
                    </div>
                    <div class="col-lg-5">
                        <input type="number" name="rate" class="form-control form-control-md"
                            value="{{ $currency->rate }}" step="any" placeholder="0.00" required>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
