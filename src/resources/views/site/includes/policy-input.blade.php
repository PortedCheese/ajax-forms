<div class="custom-control custom-checkbox">
    <input type="checkbox"
           class="custom-control-input @error("privacy_policy") is-invalid @enderror"
           id="privacy_policy{{ $postfix }}"
           required
           name="privacy_policy">
    <label class="custom-control-label" for="privacy_policy{{ $postfix }}">
        Я даю {{ config("policy.company", "") }} свое
        @if (\Illuminate\Support\Facades\Route::has("policy"))
            <a class="{{ isset($class)? $class: '' }}" href="#agreementModal" data-bs-toggle="modal" data-bs-target="#agreementModal">Согласие на обработку персональных данных</a> и принимаю условия <a class="{{ isset($class)? $class: '' }}" href="{{ route("policy") }}" target="_blank">Политики по обработке персональных данных</a>
        @else
            Cогласие на обработку персональных данных и принимаю условия Политики по обработке персональных данных
        @endif
    </label>
    @error("privacy_policy")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror
</div>