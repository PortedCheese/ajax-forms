<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'App\Http\Controllers\Vendor\AjaxForms\Site',
    'middleware' => ['web'],
    'as' => 'site.ajax-froms.',
], function () {
    Route::post('/ajax-forms/{form}', 'FormController@submit')
        ->name('submit');
});