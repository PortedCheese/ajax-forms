@extends('admin.layout')

@section('page-title', 'Добавить форму - ')
@section('header-title', 'Добавить форму')

@section('admin')
    @include("ajax-forms::admin.ajax-forms.pills")

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.ajax-forms.store') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="title">Заголовок <span class="text-danger">*</span></label>
                        <input type="text"
                               required
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               class="form-control @error("title") is-invalid @enderror">
                        @error("title")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Имя <span class="text-danger">*</span></label>
                        <input type="text"
                               id="name"
                               required
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control @error("name") is-invalid @enderror">
                        <small class="form-text text-muted">Аттрибут data-name (name) из формы</small>
                        @error("name")
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
                               value="{{ old('email') }}"
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
                               value="{{ old('success_message') }}"
                               class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="fail_message">Неудачная отправка</label>
                        <input type="text"
                               id="fail_message"
                               name="fail_message"
                               value="{{ old('fail_message') }}"
                               class="form-control">
                    </div>

                    <div class="btn-group"
                         role="group">
                        <button type="submit" class="btn btn-success">Создать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection