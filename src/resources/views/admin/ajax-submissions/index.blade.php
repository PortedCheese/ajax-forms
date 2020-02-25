@extends('admin.layout')

@section('page-title', 'Формы - ')
@section('header-title', 'Формы')

@section('admin')
    @foreach ($forms as $item)
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('admin.ajax-forms.submissions.show', ['form' => $item]) }}">
                            {{ $item->title }}
                        </a>
                    </h5>
                </div>
            </div>
        </div>
    @endforeach
@endsection
