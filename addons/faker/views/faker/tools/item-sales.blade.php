@extends('admin.layouts.grid')
@section('section', translate('Faker'))
@section('title', translate('Fake Item Sales'))
@section('container', 'container-max-lg')
@section('back', route('admin.faker.tools.index'))
@section('content')
    <div class="alert alert-primary">
        <h5>{{ translate('Notes!') }}</h5>
        <ol class="m-0">
            <li class="mb-1">
                {{ translate('This tool will increase the item sales and the author sales only and it will not generate real sales or purchases.') }}
            </li>
            <li>
                {{ translate('This tool will increase the item and author total sales amount also so the author level can be upgraded.') }}
            </li>
        </ol>
    </div>
    <div class="card mb-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.faker.tools.generate', $tool) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ translate('Item') }}</label>
                        <select name="item" class="form-select form-select-md selectpicker" title="--"
                            data-live-search="true" required>
                            @foreach (\App\Models\Item::approved()->get() as $item)
                                <option value="{{ $item->id }}" @selected(old('item') == $item->id)>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ translate('Number of sales') }}</label>
                        <input type="number" name="sales_number" class="form-control form-control-md"
                            value="{{ old('sales_number') ?? '10' }}">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary btn-md"><i
                                class="fa-solid fa-rotate me-2"></i>{{ translate('Generate') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
