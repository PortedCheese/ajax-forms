<?php

Route::group([
    'namespace' => 'PortedCheese\AjaxForms\Http\Controllers\Site',
    'middleware' => ['web'],
    'as' => 'site.ajax-froms.',
], function () {
    Route::post('/ajax-forms/{form}', 'FormController@submit')
        ->name('submit');

});

Route::group([
    'namespace' => 'PortedCheese\AjaxForms\Http\Controllers\Admin',
    'middleware' => ['web', 'role:admin'],
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
    'namespace' => 'PortedCheese\AjaxForms\Http\Controllers\Admin',
    'middleware' => ['web', 'role:admin|editor'],
    'as' => 'admin.ajax-forms.',
    'prefix' => 'admin/ajax-forms/submissions/',
], function () {
    Route::get('{form}', "FormController@submissions")
        ->name('submissions');
});