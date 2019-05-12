<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle{{ strstr($currentRoute, 'admin.ajax-forms') !== FALSE ? ' active' : '' }}"
       href="#"
       id="user-dropdown"
       role="button"
       data-toggle="dropdown"
       aria-haspopup="true"
       aria-expanded="false">
        @isset($ico)
            <i class="{{ $ico }}"></i>
        @endisset
        Формы
    </a>
    <div class="dropdown-menu" aria-labelledby="user-dropdown">
        @role('admin')
            <a href="{{ route('admin.ajax-forms.index') }}"
               class="dropdown-item">
                Список
            </a>
            <a href="{{ route('admin.ajax-forms.create') }}"
               class="dropdown-item">
                Создать
            </a>
        @endrole
        @foreach($ajaxForms as $form)
            <a href="{{ route('admin.ajax-forms.submissions', ['form' => $form]) }}"
               class="dropdown-item">
               {{ $form->title }}
            </a>
        @endforeach
    </div>
</li>