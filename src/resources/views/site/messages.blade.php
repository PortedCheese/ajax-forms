<div class="alert alert-{{ $result['success'] ? 'success' : 'danger' }}{{ config("ajax-forms.alertAbsolute") ? " alert-absolute" : "" }} d-flex justify-content-between" role="alert">

    @foreach($result['messages'] as $message)
        {{ $message }}
    @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
</div>