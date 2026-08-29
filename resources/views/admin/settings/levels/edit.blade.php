@extends('admin.layouts.form')
@section('section', translate('Settings'))
@section('title', translate('Edit Author Level'))
@section('back', route('admin.settings.levels.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.levels.update', $level->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card p-2 pb-3">
            <div class="card-body">
                <div class="row g-3 row-cols-1">
                    <div class="col">
                        <label class="form-label">{{ translate('Name') }} </label>
                        <input type="text" name="name" class="form-control" value="{{ $level->name }}" required
                            autofocus />
                    </div>
                    @if (!$level->isDefault())
                        <div class="col">
                            @include('admin.partials.input-price', [
                                'label' => translate('Minimum Earnings'),
                                'name' => 'min_earnings',
                                'value' => $level->min_earnings,
                                'integer' => true,
                                'required' => true,
                            ])
                        </div>
                    @endif
                    <div class="col">
                        <label class="form-label">{{ translate('Fees') }} </label>
                        <div class="input-group">
                            <input type="number" name="fees" class="form-control input-numeric"
                                value="{{ $level->fees }}" placeholder="0" min="0" max="100" required />
                            <span class="input-group-text px-3"><i class="fa-solid fa-percent"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
