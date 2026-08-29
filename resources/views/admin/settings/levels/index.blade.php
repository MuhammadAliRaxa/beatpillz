@extends('admin.layouts.grid')
@section('section', translate('Settings'))
@section('title', translate('Author levels'))
@section('create', route('admin.settings.levels.create'))
@section('content')
    <div class="card">
        <table class="table datatable2 w-100">
            <thead>
                <tr>
                    <th class="tb-w-2x">#</th>
                    <th class="tb-w-7x">{{ translate('Name') }}</th>
                    <th class="tb-w-7x">{{ translate('Minimum Earnings') }}</th>
                    <th class="tb-w-3x">{{ translate('Fees') }}</th>
                    <th class="tb-w-7x">{{ translate('Created date') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($levels as $level)
                    <tr>
                        <td>{{ $level->id }}</td>
                        <td>
                            <a href="{{ route('admin.settings.levels.edit', $level->id) }}" class="text-dark">
                                <i class="fa-solid fa-snowflake me-1"></i>
                                {{ $level->name }}
                            </a>
                        </td>
                        <td>{{ getAmount($level->min_earnings) }}</td>
                        <td>{{ $level->fees }}%</td>
                        <td>{{ dateFormat($level->created_at) }}</td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.settings.levels.edit', $level->id) }}"><i
                                                class="fa-regular fa-pen-to-square me-2"></i>{{ translate('Edit') }}</a>
                                    </li>
                                    @if (!$level->isDefault())
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.settings.levels.destroy', $level->id) }}"
                                                method="POST">
                                                @csrf @method('DELETE')
                                                <button class="action-confirm dropdown-item text-danger"><i
                                                        class="far fa-trash-alt me-2"></i>{{ translate('Delete') }}</button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
