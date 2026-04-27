<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdatedEventRequest;
use App\Http\Resources\EventCategoryResource;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Services\MediaServices;
class EventController extends Controller
{
            protected $mediaServices ;
public function __construct(MediaServices $mediaServices)
    {
        $this->mediaServices = $mediaServices;
    }
    public function index()
    {
        //
        $events = Event::all();

        return response()->json([
            'status' => true,
            'success' => true,
            'data' => EventResource::collection($events)
        ], 200);
    }
    public function eventAllCategories()
    {
        //
            // $events = Event::all();
            $events = Event::with('category')->get();

        return response()->json([
            'status' => true,
            'success' => true,
            'data' => EventResource::collection($events)
        ], 200);
    }


    private function returnEvent($id){
         return Event::find($id);

    }
    public function show($id)
    {
        //
        $event = $this->returnEvent($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'success' => true,
            'data' => new EventResource($event)
        ]);
    }
    public function eventWithCategories($id)
    {
        //
        $event = Event::with('category')->find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'success' => true,
            'data' => new EventResource($event)
        ]);
    }
    public function create(CreateEventRequest $request)
    {
        //

//         $validator = Validator::make($request->all(), [
//     'title' => 'required',
// ]);

// if ($validator->fails()) {
//     return response()->json([
//         'status' => false,
//         'errors' => $validator->errors()
//     ], 422);
// }
             $request->validated();

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'available_seats' => $request->available_seats,
            'category_id' => $request->category_id,
        ]);

        //only one image
if ($request->hasFile('image')) {
$this->mediaServices->upload($event, $request->file('image'), 'event_cover');
}
// multiple images
if ($request->hasFile('images')) {

$this->mediaServices->upload($event, $request->file('images'), 'event_gallery');
  }



        return response()->json([
            'status' => true,
            'success' => true,
            'message' => 'Event created successfully',
            // 'data' => new EventResource($event)
        ], 201);



    }

    public function update(UpdatedEventRequest $request, $id)
    {
        //
        $request->validated();
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $event->update([
            'title' => $request->title ?? $event->title,
            'description' => $request->description ?? $event->description,
            'location' => $request->location ?? $event->location,
            'start_time' => $request->start_time ?? $event->start_time,
            'end_time' => $request->end_time ?? $event->end_time,
            'available_seats' => $request->available_seats ?? $event->available_seats,
            'category_id' => $request->category_id ?? $event->category_id,
        ]);
        if ($request->hasFile('image')) {
           $this->mediaServices->update($event, $request->file('image'), 'event_cover');
        }
        if ($request->hasFile('images')) {
            $this->mediaServices->update($event, $request->file('images'), 'event_gallery');
        }

        return response()->json([
            'status' => true,
            'success' => true,
            'message' => 'Event updated successfully',
            // 'data' => new EventResource($event)
        ]);

    }
    public function destroy($id)
    {
        //
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }
        $this->mediaServices->deleteMedia($event, 'event_cover');
        $this->mediaServices->deleteMedia($event, 'event_gallery');

        $event->delete();
        return response()->json([
            'status' => true,
            'success' => true,
            'message' => 'Event deleted successfully'
        ], 200);
    }
//     public function deleteMedia(Event $event, int $mediaId)
// {
//     $deleted = $this->mediaServices->deleteMediaItem($event, $mediaId, 'event_gallery');

//     if (!$deleted) {
//         return response()->json([
//             'message' => 'Media not found'
//         ], 404);
//     }

//     return response()->json([
//         'message' => 'Media deleted successfully'
//     ]);
// }

}
