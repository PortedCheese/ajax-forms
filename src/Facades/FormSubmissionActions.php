<?php

namespace PortedCheese\AjaxForms\Facades;

use App\AjaxForm;
use App\AjaxFormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use PortedCheese\AjaxForms\Helpers\FormSubmissionActionsManager;

/**
 * @method static AjaxFormSubmission createSubmission(AjaxForm $form, Request $request)
 * @method static array prepareFieldsForRender(AjaxFormSubmission $submission)
 *
 * @see FormSubmissionActionsManager
 */
class FormSubmissionActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "form-submissions-actions";
    }
}