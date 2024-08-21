<tr>
    <td>{{ $form->title }}</td>
    <td>{{ $form->name }}</td>
    <td>{{ $form->success_message }}</td>
    <td>{{ $form->fail_message }}</td>
    <td>{{ $form->email }}</td>
    <td>{{ $form->fields->count() }}</td>
    <td>
        <div role="toolbar" class="btn-toolbar">
            <div class="btn-group me-1">
                <a href="{{ route("admin.ajax-forms.edit", ["ajax_form" => $form]) }}" class="btn btn-primary">
                    <i class="far fa-edit"></i>
                </a>
                <a href="{{ route('admin.ajax-forms.show', ['ajax_form' => $form]) }}" class="btn btn-dark">
                    <i class="far fa-eye"></i>
                </a>
                <button type="button" class="btn btn-danger" data-confirm="{{ "delete-ajax-form-form-{$form->id}" }}">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
        <confirm-form :id="'{{ "delete-ajax-form-form-{$form->id}" }}'">
            <template>
                <form action="{{ route('admin.ajax-forms.destroy', ['ajax_form' => $form]) }}"
                      id="delete-ajax-form-form-{{ $form->id }}"
                      class="btn-group"
                      method="post">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                </form>
            </template>
        </confirm-form>
    </td>
</tr>