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
                               value="{{ old('title') ? old('title') : $pivot->title }}"
                               required
                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}">
                        @if ($errors->has('title'))
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox"
                                   @if (old('requred'))
                                   checked
                                   @elseif ($pivot->required)
                                   checked
                                   @endif
                                   class="custom-control-input"
                                   name="required"
                                   id="requiredCheck">
                            <label class="custom-control-label" for="requiredCheck">Required</label>
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