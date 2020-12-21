<?php

namespace PortedCheese\AjaxForms\Events;

use App\AjaxFormSubmission;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateNewSubmission
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $submission;

    /**
     * Create a new event instance.
     *
     * CreateNewSubmission constructor.
     * @param AjaxFormSubmission $submission
     */
    public function __construct(AjaxFormSubmission $submission)
    {
        $this->submission = $submission;
    }
}
