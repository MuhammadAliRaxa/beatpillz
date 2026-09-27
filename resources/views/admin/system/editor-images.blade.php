@extends('admin.layouts.grid')
@section('section', translate('System'))
@section('title', translate('Editor Images'))
@section('content')
    <div class="card">
        <div class="card-header p-3 border-bottom-small">
            <form action="{{ url()->current() }}" method="GET">
                <div class="row g-3">
                    <div class="col-12 col-lg-10">
                        <input type="text" name="search" class="form-control" placeholder="{{ translate('Search...') }}"
                            value="{{ request('search') ?? '' }}">
                    </div>
                    <div class="col">
                        <button class="btn btn-primary w-100"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="col">
                        <a href="{{ url()->current() }}" class="btn btn-secondary w-100">{{ translate('Reset') }}</a>
                    </div>
                </div>
            </form>
        </div>
        <div>
            @if ($editorImages->count() > 0)
                <div class="overflow-hidden">
                    <div class="table-custom-container">
                        <table class="table-custom table">
                            <thead>
                                <tr class="bg-light">
                                    <th>{{ translate('ID') }}</th>
                                    <th>{{ translate('Details') }}</th>
                                    <th class="text-center">{{ translate('Uploaded Date') }}</th>
                                    <th class="text-end">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($editorImages as $editorImage)
                                    <tr>
                                        <td>{{ $editorImage->id }}</td>
                                        <td>
                                            <div class="vironeer-content-box">
                                                <span class="vironeer-content-image">
                                                    <img src="{{ $editorImage->getLink() }}">
                                                </span>
                                                <div>
                                                    <span
                                                        class="text-reset">{{ shorterText($editorImage->name, 40) }}</span>
                                                    <p class="text-muted small mb-0">
                                                        {{ $editorImage->getLink() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ dateFormat($editorImage->created_at) }}</td>
                                        <td class="text-end">
                                            <form
                                                action="{{ route('admin.system.editor-images.destroy', $editorImage->id) }}"
                                                method="POST">
                                                @csrf @method('DELETE')
                                                <button class="action-confirm btn btn-danger"><i
                                                        class="far fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                @include('admin.partials.empty', ['empty_classes' => 'empty-lg'])
            @endif
        </div>
    </div>
    {{ $editorImages->links() }}
@endsection
