@extends('admin.layouts.grid')
@section('section', translate('Help Center'))
@section('title', translate('Help Center Articles'))
@section('create', route('admin.help.articles.create'))
@section('content')
    <div class="card">
        <div class="card-header p-3 border-bottom-small">
            <form action="{{ request()->url() }}" method="GET">
                <div class="row g-3">
                    <div class="col-12 col-lg-8">
                        <input type="text" name="search" class="form-control" placeholder="{{ translate('Search...') }}"
                            value="{{ request()->input('search') ?? '' }}">
                    </div>
                    <div class="col-12 col-lg-2">
                        <select name="category" class="form-select selectpicker" title="{{ translate('Category') }}"
                            data-live-search="true">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
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
            @if ($articles->count() > 0)
                <div class="table-responsive">
                    <table class="table vironeer-normal-table w-100">
                        <thead>
                            <tr>
                                <th>{{ translate('ID') }}</th>
                                <th>{{ translate('Article') }}</th>
                                <th class="text-center">{{ translate('Categories') }}</th>
                                <th class="text-center">{{ translate('Views') }}</th>
                                <th class="text-center">{{ translate('Likes') }}</th>
                                <th class="text-center">{{ translate('Dislikes') }}</th>
                                <th class="text-center">{{ translate('Published date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articles as $article)
                                <tr>
                                    <td>{{ $article->id }}</td>
                                    <td>
                                        <div class="vironeer-content-box">
                                            <div class="icon me-3">
                                                <i class="fa-regular fa-file-lines fa-3x text-muted"></i>
                                            </div>
                                            <div>
                                                <a class="text-reset"
                                                    href="{{ route('admin.help.articles.edit', $article->id) }}">{{ shorterText($article->title, 50) }}</a>
                                                <p class="text-muted mb-0">
                                                    {{ shorterText($article->short_description, 60) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.help.categories.edit', $article->category->id) }}">
                                            <span class="badge bg-primary">{{ $article->category->name }}</span>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-dark">{{ $article->views }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $article->likes }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $article->dislikes }}</span>
                                    </td>
                                    <td class="text-center">
                                        {{ dateFormat($article->created_at) }}</td>
                                    <td>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                                aria-expanded="true">
                                                <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-sm-end"
                                                data-popper-placement="bottom-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ $article->getLink() }}"
                                                        target="_blank"><i
                                                            class="fa fa-eye me-2"></i>{{ translate('View') }}</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.help.articles.edit', $article->id) }}"><i
                                                            class="fa fa-edit me-2"></i>{{ translate('Edit') }}</a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.help.articles.destroy', $article->id) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <button class="action-confirm dropdown-item text-danger"><i
                                                                class="far fa-trash-alt me-2"></i>{{ translate('Delete') }}</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                @include('admin.partials.empty', ['empty_classes' => 'empty-lg'])
            @endif
        </div>
    </div>
    {{ $articles->links() }}
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
