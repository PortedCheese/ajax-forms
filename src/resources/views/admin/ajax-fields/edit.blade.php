@extends('admin.layout')

@section('page-title', 'Редактировать поле - ')
@section('header-title', "Редактировать поле {$pivot->title}")

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.ajax-fields.update', ['form' => $form, 'field' => $field]) }}"
                      method="post"
                      class="col-12">
                    @method('put')
                    @csrf

                    <div class="form-group">
                        <label for="title">Заголовок</label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old("title", $pivot->title) }}"
                               class="form-control @error("title") is-invalid @enderror">
                        @error("title")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox"
                                   class="custom-control-input"
                                   id="required"
                                   {{ (! count($errors->all()) && $pivot->required) || old("required") ? "checked" : "" }}
                                   name="required">
                            <label class="custom-control-label" for="required">Required</label>
                        </div>
                    </div>

                    <div class="btn-group"
                         role="group">
                        <button type="submit" class="btn btn-success">Обновить</button>
                        <a href="{{ route('admin.ajax-forms.show', ['ajax_form' => $form]) }}"
                           class="btn btn-secondary">
                            Назад к форме
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection