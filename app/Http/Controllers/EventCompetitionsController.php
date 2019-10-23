<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Competition;
use App\Models\Category;
use App\Models\Level;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Log;
use DB;

class EventCompetitionsController extends MyBaseController
{
  /**
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function showCompetitions(Request $request, $event_id)
    {
        $allowed_sorts = [
            'created_at'    => trans("Controllers.sort.created_at"),
            'title'         => trans("Controllers.sort.title"),
           /* 'quantity_sold' => trans("Controllers.sort.quantity_sold"),
            'sales_volume'  => trans("Controllers.sort.sales_volume"),
            
            'sort_order'  => trans("Controllers.sort.sort_order"),
            */
        ];

        // Getting get parameters.
        $q = $request->get('q', '');
        $sort_by = $request->get('sort_by');
        if (isset($allowed_sorts[$sort_by]) === false) {
            $sort_by = 'id';
        }

        // Find event or return 404 error.
        $event = Event::scope()->find($event_id);
        if ($event === null) {
            abort(404);
        }

        // Get competitions for event.
        $competitions = empty($q) === false
            ? $event->competitions()->where('title', 'like', '%' . $q . '%')->orderBy($sort_by, 'asc')->paginate()
            : $event->competitions()->orderBy($sort_by, 'asc')->paginate();

        // Return view.
        return view('ManageEvent.Competitions', compact('event', 'competitions', 'sort_by', 'q', 'allowed_sorts'));
    }

    /**
     * Show the edit ticket modal
     *
     * @param $event_id
     * @param $competition_id
     * @return mixed
     */
    public function showEditCompetition($event_id, $competition_id)
    {
        $data = [
            'event'  => Event::scope()->find($event_id),
            'competition' => Competition::scope()->find($competition_id),
        ];

        return view('ManageEvent.Modals.EditCompetition', $data);
    }

    /**
     * Show the create ticket modal
     *
     * @param $event_id
     * @return \Illuminate\Contracts\View\View
     */
    public function showCreateCompetition($event_id)
    {
        $disciplines = DB::table('disciplines')
                    ->get();
        Log::debug('total:' . $disciplines->count());

        return view('ManageEvent.Modals.CreateCompetition', [
            'event' => Event::scope()->find($event_id),
            'disciplines' => $disciplines,
        ]);
    }

    /**
     * Creates a competition
     *
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateCompetition(Request $request, $event_id)
    {
       $request->validate([
            'title' => 'required',
            'level.*' => 'required',
            'category.*' => 'required',
            'type' => 'required',
            'discipline_id' => 'required',
            'price' => 'numeric|min:1',
            'max_competitors' => 'numeric|min:1'
        ]);

        Log::debug('creating competition');
        $competition = Competition::createNew();
		Log::debug('competition created');
		Log::debug($event_id);
        $competition->event_id = $event_id;
        
        
        $competition->title = strip_tags($request->get('title'));
        Log::debug('type : '  .$request->get('type'));
        $competition->type = strip_tags($request->get('type'));

        Log::debug('discipline : '  .$request->get('discipline_id'));
        $competition->discipline_id = $request->get('discipline_id');

        Log::debug('mp3_upload : ' .$request->get('mp3_upload'));
        $competition->mp3_upload = $request->get('mp3_upload') ? 1 : 0;
        Log::debug('price : ' .$request->get('price'));
        $competition->price = $request->get('price');
        Log::debug('max_competitors : ' .$request->get('max_competitors'));
        $competition->max_competitors = !$request->get('max_competitors') ? null : $request->get('max_competitors');
        $competition->save();

        /* insert into category tabel */
        $categories = $request->category;
        $categoriesColl = new Collection();
        for($count = 0; $count < count($categories); $count++)
        {
        Log::debug('category : ' .$categories[$count]);
        $categoriesColl->push(
            new Category([
                'category' => $categories[$count],
                'event_id' => $event_id
            ])
        );
        }
        $competition->categories()->saveMany($categoriesColl);

         /* insert into level tabel */
         $levels = $request->level;
         $levelsColl = new Collection();
         for($count = 0; $count < count($levels); $count++)
         {
         Log::debug('level : ' .$levels[$count]);
         $levelsColl->push(
             new Level([
                 'level' => $levels[$count],
                 'event_id' => $event_id
             ])
         );
         }
         $competition->levels()->saveMany($levelsColl);

        session()->flash('message', 'Successfully Created Competition');

        return response()->json([
            'status'      => 'success',
            'id'          => $competition->id,
            'message'     => trans("Controllers.refreshing"),
            'redirectUrl' => route('showEventCompetitions', [
                'event_id' => $event_id,
            ]),
        ]);
    }

    /**
     * Pause Competition / take it off sale
     *
     * @param Request $request
     * @return mixed
     */
    public function postPauseCompetition(Request $request)
    {
        $ticket_id = $request->get('ticket_id');

        $ticket = Ticket::scope()->find($ticket_id);

        $ticket->is_paused = ($ticket->is_paused == 1) ? 0 : 1;

        if ($ticket->save()) {
            return response()->json([
                'status'  => 'success',
                'message' => trans("Controllers.ticket_successfully_updated"),
                'id'      => $ticket->id,
            ]);
        }

        Log::error('Ticket Failed to pause/resume', [
            'ticket' => $ticket,
        ]);

        return response()->json([
            'status'  => 'error',
            'id'      => $ticket->id,
            'message' => trans("Controllers.whoops"),
        ]);
    }

    /**
     * Deleted a ticket
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDeleteCompetition(Request $request)
    {
        $ticket_id = $request->get('ticket_id');

        $ticket = Ticket::scope()->find($ticket_id);

        /*
         * Don't allow deletion of tickets which have been sold already.
         */
        if ($ticket->quantity_sold > 0) {
            return response()->json([
                'status'  => 'error',
                'message' => trans("Controllers.cant_delete_ticket_when_sold"),
                'id'      => $ticket->id,
            ]);
        }

        if ($ticket->delete()) {
            return response()->json([
                'status'  => 'success',
                'message' => trans("Controllers.ticket_successfully_deleted"),
                'id'      => $ticket->id,
            ]);
        }

        Log::error('Ticket Failed to delete', [
            'ticket' => $ticket,
        ]);

        return response()->json([
            'status'  => 'error',
            'id'      => $ticket->id,
            'message' => trans("Controllers.whoops"),
        ]);
    }

    /**
     * Edit a competition
     *
     * @param Request $request
     * @param $event_id
     * @param $competition_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEditCompetition(Request $request, $event_id, $competition_id)
    {
        $competition = Competition::scope()->findOrFail($competition_id);

        $request->validate([
            'title' => 'required',
             /*'level' => 'required',
            'type' => 'required',*/
            'price' => 'numeric|min:1',
            'max_competitors' => 'numeric|min:1'
        ]);

        /*
         * Add validation message
         */
        /*
        $validation_messages['quantity_available.min'] = trans("Controllers.quantity_min_error");
        $competition->messages = $validation_messages + $competition->messages;*/
        /*
        if (!$competition->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $competition->errors(),
            ]);
        }*/

        $competition->title = $request->get('title');
        $competition->max_competitors = !$request->get('max_competitors') ? null : $request->get('max_competitors');
        $competition->price = $request->get('price');
       /*
        $competition->level = $request->get('level');
        $competition->category = $request->get('category');
        */
        $competition->mp3_upload = $request->get('mp3_upload') ? 1 : 0;
        $competition->type = strip_tags($request->get('type'));
        $competition->save();

        return response()->json([
            'status'      => 'success',
            'id'          => $competition->id,
            'message'     => trans("Controllers.refreshing"),
            'redirectUrl' => route('showEventCompetitions', [
                'event_id' => $event_id,
            ]),
        ]);
    }

    /**
     * Updates the sort order of tickets
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUpdateCompetitionsOrder(Request $request)
    {
        $ticket_ids = $request->get('ticket_ids');
        $sort = 1;

        foreach ($ticket_ids as $ticket_id) {
            $ticket = Ticket::scope()->find($ticket_id);
            $ticket->sort_order = $sort;
            $ticket->save();
            $sort++;
        }

        return response()->json([
            'status'  => 'success',
            'message' => trans("Controllers.ticket_order_successfully_updated"),
        ]);
    }
}
