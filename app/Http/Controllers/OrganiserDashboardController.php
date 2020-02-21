<?php

namespace App\Http\Controllers;

use App\Models\Organiser;
use Carbon\Carbon;

class OrganiserDashboardController extends MyBaseController
{
    /**
     * Show the organiser dashboard
     *
     * @param $organiser_id
     * @return mixed
     */
    public function showDashboard($organiser_id)
    {
        $organiser = Organiser::scope()->findOrFail($organiser_id);
        $upcoming_events = $organiser->events()->where('end_date', '>=', Carbon::now())->get();
        $upcoming_nights = $organiser->nights()->where('end_date', '>=', Carbon::now())->get();
        $calendar_events = [];
        $calendar_nights = [];

        /* Prepare JSON array for events for use in the dashboard calendar */
        foreach ($organiser->events as $event) {
            $calendar_events[] = [
                'title' => $event->title,
                'start' => $event->start_date->toIso8601String(),
                'end'   => $event->end_date->toIso8601String(),
                'url'   => route('showEventDashboard', [
                    'event_id' => $event->id
                ]),
                'color' => '#4E558F'
            ];
        }

        /* Prepare JSON array for events for use in the dashboard calendar */
        foreach ($organiser->nights as $night) {
            $calendar_nights[] = [
                'title' => $night->title,
                'start' => $night->start_date->toIso8601String(),
                'end'   => $night->end_date->toIso8601String(),
                'url'   => route('showEventDashboard', [
                    'event_id' => $night->id
                ]),
                'color' => '#4E558F'
            ];
        }

        $data = [
            'organiser'       => $organiser,
            'upcoming_events' => $upcoming_events,
            'upcoming_nights' => $upcoming_nights,
            'calendar_events' => json_encode($calendar_events),
            'calendar_nights' =>json_encode($calendar_nights),
        ];

        return view('ManageOrganiser.Dashboard', $data);
    }
}
