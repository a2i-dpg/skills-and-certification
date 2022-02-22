<?php


namespace App\Http\Controllers\Frontend;


use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventPageController
{
    const VIEW_PATH = "frontend.events.";

    public function showEventDetails($event)
    {
        $event = Event::findOrFail($event);

        return view(self::VIEW_PATH . 'event', compact('event'));
    }

    public function instituteEvent(): array
    {
        $currentInstitute = app('currentInstitute');

        $events = Event::select([
            DB::raw('DATE(DATE_FORMAT(events.date, "%Y-%c-%d")) as start'),
            DB::raw('DATE(DATE_FORMAT(events.date, "%Y-%c-%d")) as end'),
            DB::raw('count(*) as title')
        ]);

        if ($currentInstitute) {
            $events->where(['events.institute_id' => $currentInstitute->id]);
        }

        $events->groupBy('start');
        return $events->get()->toArray();
    }

    public function instituteEventDate(Request $request): array
    {
        $currentInstitute = app('currentInstitute');
        $currentInstituteEvents = Event::query();

        if ($currentInstitute) {
            $currentInstituteEvents->where([
                'institute_id' => $currentInstitute->id,
            ]);
        }

        if (!empty($request->date)) {
            $currentInstituteEvents->where('events.date', 'LIKE', '%' . $request->date . '%');
        }
        $currentInstituteEvents->orderBy('date', 'ASC');
        $currentInstituteEvents = $currentInstituteEvents->limit(5);

        return $currentInstituteEvents->get()->toArray();
    }
}
