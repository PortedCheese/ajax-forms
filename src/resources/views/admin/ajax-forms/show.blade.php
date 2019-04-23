@extends('admin.layout')

@section('page-title', 'Просмотр формы - ')
@section('header-title', "Просмотр формы {$form->title}")

@section('admin')
    <div class="col-12">
        <div class="table-responsive">
            <table class="table">
                @include("ajax-forms::admin.ajax-forms.thead")
                <tbody>
                @include("ajax-forms::admin.ajax-forms.form-table-element", ['form' => $form])
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-12">
        <a href="{{ route("admin.ajax-fields.create", ['form' => $form]) }}"
           class="btn btn-success">
            Добавить поле
        </a>
    </div>
    <div class="col-12">
        @include("ajax-forms::admin.ajax-fields.list", ['form' => $form])
    </div>
@endsection