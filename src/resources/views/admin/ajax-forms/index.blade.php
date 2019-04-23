@extends('admin.layout')

@section('page-title', 'Формы - ')
@section('header-title', 'Формы')

@section('admin')
    <div class="col-12">
        <div class="table-responsive">
            <table class="table">
                @include("ajax-forms::admin.ajax-forms.thead")
                <tbody>
                @foreach($forms as $form)
                    @include("ajax-forms::admin.ajax-forms.form-table-element", ['form' => $form])
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
