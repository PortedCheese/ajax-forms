@can("viewAny", \App\AjaxFormSubmission::class)
    @can('settings-management')
        <li class="nav-item">
            <a class="nav-link{{ in_array($currentRoute, [
                            "admin.ajax-forms.index",
                            "admin.ajax-forms.create",
                            "admin.ajax-forms.show",
                            "admin.ajax-forms.edit"
                        ]) ? ' active' : '' }}"
               href="{{ route('admin.ajax-forms.index') }}">
                @isset($ico)
                    <i class="{{ $ico }}"></i>
                @endisset
                Формы
            </a>
        </li>
    @endcan
    <li class="nav-item">
        <a href="{{ route("admin.ajax-forms.submissions.index") }}"
           class="nav-link{{ strstr($currentRoute, 'admin.ajax-forms.submissions.') !== FALSE ? ' active' : '' }}">
            @isset($ico)
                <i class="{{ $ico }}"></i>
            @endisset
            @can('settings-management')
                Отправления
            @endcan
            @cannot("settings-management")
                Формы
            @endcannot
        </a>
    </li>
@endcan