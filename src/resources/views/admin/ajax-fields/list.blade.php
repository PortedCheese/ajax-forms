<div class="table-responsive mt-2">
    <table class="table">
        <thead>
        <tr>
            <th>Заголовок</th>
            <th>Имя</th>
            <th>Тип</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($form->fields as $field)
            <tr>
                <td>{{ $field->pivot->title }}</td>
                <td>{{ $field->name }}</td>
                <td>{{ $field->type }}</td>
                <td>
                    <confirm-delete-model-button model-id="{{ $field->id }}">
                        <template slot="edit">
                            <a href="{{ route('admin.ajax-fields.edit', ['form' => $form, 'field' => $field]) }}"
                               class="btn btn-primary">
                                <i class="far fa-edit"></i>
                            </a>
                        </template>
                        <template slot="delete">
                            <form action="{{ route('admin.ajax-fields.detach', ['form' => $form, 'field' => $field]) }}"
                                  id="delete-{{ $field->id }}"
                                  class="btn-group"
                                  method="post">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                        </template>
                    </confirm-delete-model-button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>