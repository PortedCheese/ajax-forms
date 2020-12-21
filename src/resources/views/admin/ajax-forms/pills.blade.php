<div class="col-12 mb-3">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link{{ $currentRoute == 'admin.ajax-forms.index' ? ' active' : '' }}"
                       href="{{ route('admin.ajax-forms.index') }}">
                        Список
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $currentRoute == 'admin.ajax-forms.create' ? ' active' : '' }}"
                       href="{{ route('admin.ajax-forms.create') }}">
                        Добавить
                    </a>
                </li>
                @if (! empty($form))
                    <li class="nav-item">
                        <a class="nav-link{{ $currentRoute == 'admin.ajax-forms.show' ? ' active' : '' }}"
                           href="{{ route('admin.ajax-forms.show', ['ajax_form' => $form]) }}">
                            Просмотр
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ $currentRoute == 'admin.ajax-forms.edit' ? ' active' : '' }}"
                           href="{{ route('admin.ajax-forms.edit', ['ajax_form' => $form]) }}">
                            Редактировать
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>