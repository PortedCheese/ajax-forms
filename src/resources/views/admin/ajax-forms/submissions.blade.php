@extends('admin.layout')

@section('page-title', "{$form->title} - ")
@section('header-title', "{$form->title}")

@section('admin')
    <div class="col-12">
        @include("ajax-forms::admin.ajax-forms.search-form")
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    @foreach($headers as $head)
                        <th>{{ $head->title }}</th>
                    @endforeach
                    <th>Дата</th>
                    <th>Автор</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
                        <tr>
                            <td>
                                {{ $page * $per + $loop->iteration }}
                            </td>
                            @foreach($headers as $head)
                                <td>
                                    @isset($submission->fields[$head->id])
                                        @if ($submission->fields[$head->id]['type'] == 'file')
                                            <a href="{{ $submission->fields[$head->id]['value'] }}">Скачать</a>
                                        @else
                                            {{ $submission->fields[$head->id]['value'] }}
                                        @endif
                                    @endisset
                                    @empty($submission->fields[$head->id])
                                        -
                                    @endempty
                                </td>
                            @endforeach
                            <td>
                                {{ date('d.m.Y H:i', strtotime($submission->model->created_at)) }}
                            </td>
                            <td>
                                {{ $submission->author }}
                            </td>
                            <td>
                                <confirm-delete-model-button model-id="{{ $submission->model->id }}">
                                    <template slot="delete">
                                        <form action="{{ route('admin.ajax-forms.submissions.destroy', ['submission' => $submission->model]) }}"
                                              id="delete-{{ $submission->model->id }}"
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
    </div>
    <div class="col-sm-12">
        {{ $collection->links() }}
    </div>
@endsection
