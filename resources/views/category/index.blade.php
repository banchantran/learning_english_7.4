@extends('layout.default')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Category</li>
        </ol>
    </nav>
@endsection
@section('content')
    @auth
        <div class="d-flex justify-content-end mb15">
            <button class="btn btn-dark" type="button" onclick="System.showModal('#createCategory', this)">Add</button>
        </div>
    @endauth
    <div class="row mb-20">
        <div class="col-12">
            <div class="records">
                @include('elements.paging', ['paginator' => $data])
            </div>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Numbers of lessons</th>
            <th scope="col">Public for everyone</th>
            <th scope="col">Owner</th>
            @auth
                <th scope="col"></th>
            @endauth
        </tr>
        </thead>
        <tbody>
        @foreach($data as $index => $item)
            <tr>
                <th scope="row">{{ $index + 1 + (($data->currentPage() - 1) * $data->perPage()) }}</th>
                <td><span>{{$item->name}}</span>
                </td>
                <td>
                    <a class="link-success" href="{{url(route('lesson.index', ['categoryId' => $item->id]))}}">
                        {{$item->lessons->count()}} {{Str::plural('lesson', $item->lessons->count())}}
                    </a>
                </td>
                <td>
                    @if($item->is_public)
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 512 512">
                            <path
                                d="M362.6 192.9L345 174.8c-.7-.8-1.8-1.2-2.8-1.2-1.1 0-2.1.4-2.8 1.2l-122 122.9-44.4-44.4c-.8-.8-1.8-1.2-2.8-1.2-1 0-2 .4-2.8 1.2l-17.8 17.8c-1.6 1.6-1.6 4.1 0 5.7l56 56c3.6 3.6 8 5.7 11.7 5.7 5.3 0 9.9-3.9 11.6-5.5h.1l133.7-134.4c1.4-1.7 1.4-4.2-.1-5.7z"
                                fill="currentColor"/>
                        </svg>
                    @endif
                </td>
                <td>
                    {{$item->user->username}}
                </td>
                @auth
                    @if (\Illuminate\Support\Facades\Auth::user()->id === $item->user_id)
                        <td align="right">
                            <a class="btn-action" href="javascript:void(0)"
                               data-url="{{url(route('category.show', ['id' => $item->id]))}}"
                               onclick="System.showEditModal('#createCategory', this)">Edit</a>

                            <a class="btn-action ml10 text-danger" href="javascript:void(0)"
                               data-url="{{route('category.delete', ['id' => $item->id])}}"
                               onclick="System.showModal('#deleteConfirm', this)">Delete</a>
                        </td>
                    @else
                        <td></td>
                    @endif
                @endauth
            </tr>
        @endforeach
        @if (count($data) == 0)
            <tr>
                <td colspan="@auth 6 @else 5 @endauth" align="center">No data</td>
            </tr>
        @endif
        </tbody>
    </table>

    <div class="row mt-5 mb-4">
        <div class="col-12 d-flex justify-content-center">
            {{ $data->links() }}
        </div>
    </div>
@endsection
