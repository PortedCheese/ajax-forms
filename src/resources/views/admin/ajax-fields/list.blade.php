<div class="table-responsive mt-2">
    <table class="table">
        <thead>
        <tr>
            <th>Заголовок</th>
            <th>Имя</th>
            <th>Тип</th>
            <th>Required</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($form->fields as $field)
            <tr>
                <td>{{ $field->pivot->title }}</td>
                <td>{{ $field->name }}</td>
                <td>{{ $field->type }}</td>
                <td>{{ $field->pivot->required ? 'Да' : "Нет" }}</td>
                <td>
                    <div role="toolbar" class="btn-toolbar">
                        <div class="btn-group mr-1">
                            <a href="{{ route("admin.ajax-fields.edit", ['form' => $form, "field" => $field]) }}" class="btn btn-primary">
                                <i class="far fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger" data-confirm="{{ "delete-field-form-{$field->id}" }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                    <confirm-form :id="'{{ "delete-field-form-{$field->id}" }}'">
                        <template>
                            <form action="{{ route('admin.ajax-fields.detach', ['form' => $form, 'field' => $field]) }}"
                                  id="delete-field-form-{{ $field->id }}"
                                  class="btn-group"
                                  method="post">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                        </template>
                    </confirm-form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>