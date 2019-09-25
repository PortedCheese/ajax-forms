@extends('admin.layout')

@section('page-title', 'Добавить поле - ')
@section('header-title', "Добавить поле к форме {$form->title}")

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.ajax-fields.store', ['form' => $form]) }}" method="post">
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

                    @if($available->count())
                        <div class="form-group">
                            <label for="exists">Выбрать из существующих</label>
                            <select name="exists"
                                    id="exists"
                                    class="form-control custom-select @error("exists") is-invalid @enderror">
                                <option value="">Выберите...</option>
                                @foreach($available as $item)
                                    <option value="{{ $item->id }}"
                                            {{ old("exists") == $item->id ? "selected" : "" }}>
                                        {{ $item->name }} | {{ $item->type }}
                                    </option>
                                @endforeach
                            </select>
                            @error("exists")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="name">Имя</label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control @error("name") is-invalid @enderror">
                        <small class="form-text text-muted">Аттрибут name из поля</small>
                        @error("name")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="type">Тип</label>
                        <select name="type"
                                id="type"
                                class="form-control">
                            <option value="">--Выберите--</option>
                            @foreach($types as $key => $value)
                                <option value="{{ $key }}"
                                        @if(old('type'))
                                        selected
                                        @endif>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('type'))
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox"
                                   class="custom-control-input"
                                   id="required"
                                   {{ old("required") ? "checked" : "" }}
                                   name="required">
                            <label class="custom-control-label" for="required">Required</label>
                        </div>
                    </div>

                    <div class="btn-group"
                         role="group">
                        <button type="submit" class="btn btn-success">Создать</button>
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