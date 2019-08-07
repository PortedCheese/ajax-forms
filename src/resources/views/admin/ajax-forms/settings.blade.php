@extends('admin.layout')

@section('page-title', 'Настройки форм - ')
@section('header-title', 'Настройки форм')

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.ajax-forms.settings-save') }}" method="post">
                    @csrf
                    @method('put')

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                               @if($config->useOwnAdminRoutes)
                               checked
                               @endif
                               class="custom-control-input"
                               value=""
                               name="own-admin"
                               id="own-admin">
                        <label for="own-admin" class="custom-control-label">
                            Собственные адреса для админки
                        </label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                               @if($config->useOwnSiteRoutes)
                               checked
                               @endif
                               class="custom-control-input"
                               value=""
                               name="own-site"
                               id="own-site">
                        <label for="own-site" class="custom-control-label">
                            Собственные адреса для сайта
                        </label>
                    </div>

                    <div class="btn-group mt-2"
                         role="group">
                        <button type="submit" class="btn btn-success">
                            Обновить
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
