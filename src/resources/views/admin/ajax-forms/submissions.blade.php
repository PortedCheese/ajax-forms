@extends('admin.layout')

@section('page-title', "{$form->title} - ")
@section('header-title', "{$form->title}")

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                @include("ajax-forms::admin.ajax-forms.search-form")
            </div>
            <div class="card-body">
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
                                    <div role="toolbar" class="btn-toolbar">
                                        <div class="btn-group mr-1">
                                            <button type="button" class="btn btn-danger" data-confirm="{{ "delete-submission-form-{$submission->model->id}" }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <confirm-form :id="'{{ "delete-submission-form-{$submission->model->id}" }}'">
                                        <template>
                                            <form action="{{ route('admin.ajax-forms.submissions.destroy', ['submission' => $submission->model]) }}"
                                                  id="delete-submission-form-{{ $submission->model->id }}"
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
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        {{ $collection->links() }}
    </div>
@endsection
