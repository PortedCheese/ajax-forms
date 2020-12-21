<?php

namespace PortedCheese\AjaxForms\Listeners;

use PortedCheese\AjaxForms\Events\CreateNewSubmission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewSubmissionNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CreateNewSubmission  $event
     * @return void
     */
    public function handle(CreateNewSubmission $event)
    {
        $submission = $event->submission;
        $submission->notify($submission->getNotifyClass($submission));
    }
}
