@extends('admin.layout')

@section('page-title', 'Формы - ')
@section('header-title', 'Формы')

@section('admin')
    @include("ajax-forms::admin.ajax-forms.pills")

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        @include("ajax-forms::admin.ajax-forms.thead")
                        <tbody>
                        @foreach($forms as $item)
                            @include("ajax-forms::admin.ajax-forms.form-table-element", ['form' => $item])
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
