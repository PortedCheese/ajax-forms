<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'App\Http\Controllers\Vendor\AjaxForms\Admin',
    'middleware' => ['web', 'super'],
    'as' => 'admin.',
    'prefix' => 'admin',
], function () {
    //  Действия с формами.
    Route::resource('ajax-forms', 'FormController');
    // Действия с полями.
    Route::group([
        'as' => 'ajax-fields.',
        'prefix' => 'ajax-fields',
    ], function () {
        Route::get('create/{form}', 'FieldController@create')
            ->name('create');
        Route::post('/{form}', 'FieldController@store')
            ->name('store');

        Route::get('edit/{form}/{field}', 'FieldController@edit')
            ->name('edit');
        Route::put('{form}/{field}', "FieldController@update")
            ->name('update');

        Route::delete('detach/{form}/{field}', 'FieldController@detach')
            ->name('detach');
    });
});

Route::group([
    'namespace' => 'App\Http\Controllers\Vendor\AjaxForms\Admin',
    'middleware' => ['web', 'management'],
    'as' => 'admin.ajax-forms.',
    'prefix' => 'admin/ajax-forms/submissions/',
], function () {
    Route::get('{form}', "FormController@submissions")
        ->name('submissions');
    Route::delete('{submission}', "FormController@destroySubmission")
        ->name('submissions.destroy');
    Route::get('file/{submission}', "FormController@download")
        ->name('submission.download');
});