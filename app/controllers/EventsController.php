<?php

class EventsController extends BaseController
{
    private Event $eventModel;

    public function __construct()
    {
        parent::__construct();
        $this->eventModel = new Event();
    }

    public function index(): void
    {
        $events = $this->eventModel->all();

        $this->render('events/index', [
            'events' => $events,
        ]);
    }
}

