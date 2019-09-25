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
                        <label for="title">Заголовок <span class="text-danger">*</span></label>
                        <input type="text"
                               id="title"
                               required
                               name="title"
                               value="{{ old("title", $form->title) }}"
                               class="form-control @error("title") is-invalid @enderror">
                        @error("title")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail <span class="text-danger">*</span></label>
                        <input type="email"
                               required
                               id="email"
                               name="email"
                               value="{{ old("email", $form->email) }}"
                               class="form-control @error("email") is-invalid @enderror">
                        @error("email")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
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