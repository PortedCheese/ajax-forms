@extends('admin.layout')

@section('page-title', 'Обновить форму - ')
@section('header-title', "Обновить форму {$form->title}")

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.ajax-forms.update', ['ajax_form' => $form]) }}"
                      method="post"
                      class="col-12">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="title">Заголовок</label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title') ? old('title') : $form->title }}"
                               required
                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}">
                        @if ($errors->has('title'))
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="success_message">Удачная отправка</label>
                        <input type="text"
                               id="success_message"
                               name="success_message"
                               value="{{ old('success_message') ? old('success_message') : $form->success_message }}"
                               class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="fail_message">Неудачная отправка</label>
                        <input type="text"
                               id="fail_message"
                               name="fail_message"
                               value="{{ old('fail_message') ? old('fail_message') : $form->fail_message }}"
                               class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') ? old('email') : $form->email }}"
                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                        @endif
                    </div>

                    <div class="btn-group"
                         role="group">
                        <button type="submit" class="btn btn-success">Обновить</button>
                        <a href="{{ route('admin.ajax-forms.show', ['ajax_form' => $form]) }}"
                           class="btn btn-dark">
                            Просмотр
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection