@can("viewAny", \App\AjaxFormSubmission::class)
    @can('settings-management')
        <li class="nav-item{{ in_array($currentRoute, [
                            "admin.ajax-forms.index",
                            "admin.ajax-forms.create",
                            "admin.ajax-forms.show",
                            "admin.ajax-forms.edit"
                        ]) ? ' active' : '' }}">
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
                <span>Формы</span>
            </a>
        </li>
    @endcan
    <li class="nav-item {{ strstr($currentRoute, 'admin.ajax-forms.submissions.') !== FALSE ? ' active' : '' }}">
        <a href="{{ route("admin.ajax-forms.submissions.index") }}"
           class="nav-link{{ strstr($currentRoute, 'admin.ajax-forms.submissions.') !== FALSE ? ' active' : '' }}">
            @isset($ico)
                <i class="{{ $ico }}"></i>
            @endisset
            @can('settings-management')
                <span>Отправления</span>
            @endcan
            @cannot("settings-management")
                <span>Формы</span>
            @endcannot
        </a>
    </li>
@endcan