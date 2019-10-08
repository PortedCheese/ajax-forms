<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input type="checkbox"
               class="custom-control-input"
               id="privacyPolicy"
               {{ (! count($errors->all()) && $config->data['privacyPolicy']) || old("data-privacyPolicy") ? "checked" : "" }}
               name="data-privacyPolicy">
        <label class="custom-control-label" for="privacyPolicy">Согласие на политику</label>
    </div>
</div>

<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input type="checkbox"
               class="custom-control-input"
               id="recaptchaEnabled"
               {{ (! count($errors->all()) && $config->data['recaptchaEnabled']) || old("data-recaptchaEnabled") ? "checked" : "" }}
               name="data-recaptchaEnabled">
        <label class="custom-control-label" for="recaptchaEnabled">Google captcha</label>
    </div>
</div>
