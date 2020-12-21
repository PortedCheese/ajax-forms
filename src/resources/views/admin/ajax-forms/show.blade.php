@extends('admin.layout')

@section('page-title', 'Просмотр формы - ')
@section('header-title', "Просмотр формы {$form->title}")

@section('admin')
    @include("ajax-forms::admin.ajax-forms.pills")

    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        @include("ajax-forms::admin.ajax-forms.thead")
                        <tbody>
                        @include("ajax-forms::admin.ajax-forms.form-table-element", ['form' => $form])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route("admin.ajax-fields.create", ['form' => $form]) }}"
                   class="btn btn-success">
                    Добавить поле
                </a>
            </div>
            <div class="card-body">
                @include("ajax-forms::admin.ajax-fields.list", ['form' => $form])
            </div>
        </div>
    </div>
@endsection