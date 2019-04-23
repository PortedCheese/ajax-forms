<tr>
    <td>{{ $form->title }}</td>
    <td>{{ $form->name }}</td>
    <td>{{ $form->success_message }}</td>
    <td>{{ $form->fail_message }}</td>
    <td>{{ $form->email }}</td>
    <td>{{ $form->fields->count() }}</td>
    <td>
        <confirm-delete-model-button model-id="{{ $form->id }}">
            <template slot="edit">
                <a href="{{ route('admin.ajax-forms.edit', ['ajax_form' => $form]) }}"
                   class="btn btn-primary">
                    <i class="far fa-edit"></i>
                </a>
            </template>
            <template slot="edit">
                <a href="{{ route('admin.ajax-forms.show', ['ajax_form' => $form]) }}"
                   class="btn btn-dark">
                    <i class="far fa-eye"></i>
                </a>
            </template>
            <template slot="delete">
                <form action="{{ route('admin.ajax-forms.destroy', ['ajax_form' => $form]) }}"
                      id="delete-{{ $form->id }}"
                      class="btn-group"
                      method="post">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                </form>
            </template>
        </confirm-delete-model-button>
    </td>
</tr>