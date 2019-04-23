@extends('admin.layout')

@section('page-title', 'Добавить поле - ')
@section('header-title', "Добавить поле к форме {$form->title}")

@section('admin')
    <div class="col-12">
        <form action="{{ route('admin.ajax-fields.store', ['form' => $form]) }}" method="post" class="col-12">
            @csrf
            <div class="form-group">
                <label for="title">Заголовок</label>
                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title') }}"
                       required
                       class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}">
                @if ($errors->has('title'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>

            @if($available->count())
                <div class="form-group">
                    <label for="exists">Выбрать из существующих</label>
                    <select name="exists"
                            id="exists"
                            class="form-control">
                        <option value="">--Выберите--</option>
                        @foreach($available as $field)
                            <option value="{{ $field->id }}"
                                    @if(old('exists'))
                                    selected
                                    @endif>
                                {{ $field->name }} | {{ $field->type }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('exists'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('exists') }}</strong>
                        </span>
                    @endif
                </div>
            @endif

            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
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
@endsection