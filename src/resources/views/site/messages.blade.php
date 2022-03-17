<div class="alert alert-{{ $result['success'] ? 'success' : 'danger' }}{{ config("ajax-forms.alertAbsolute") ? " alert-absolute" : "" }}" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    @foreach($result['messages'] as $message)
        {{ $message }}
        <br>
    @endforeach
</div>