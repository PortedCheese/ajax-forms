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

// Отправления.
Route::group([
    'namespace' => 'App\Http\Controllers\Vendor\AjaxForms\Admin',
    'middleware' => ['web', 'management'],
    'as' => 'admin.ajax-forms.submissions.',
    'prefix' => 'admin/ajax-forms/submissions',
], function () {
    Route::get('/list', "SubmissionController@index")
        ->name('index');
    Route::get('/{form}', "SubmissionController@show")
        ->name('show');
    Route::delete('/{submission}', "SubmissionController@destroy")
        ->name('destroy');
    Route::get('/file/{submission}', "SubmissionController@download")
        ->name('download');
});