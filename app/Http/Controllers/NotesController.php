<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $noteId)
    {
        $user_uuid = $request->header('user_uuid');
        $note = Notes::where('id', $noteId)->get();

        if ($note->isEmpty()) {
            return response()->json([
                'message' => 'Note not found'
            ], 404);
        }

        if ($note[0]->user_uuid != $user_uuid) {
            return response()->json([
                'message' => 'Note does not belong to user'
            ], 404);
        }

        return response()->json([
            'message' => 'Note found',
            'data' => $note
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $note = new Notes();
        $note->title = $request->input('title');
        $note->body = $request->input('body');
        $note->user_uuid = $request->header('user_uuid');
        $note->save();
        return response()->json($note);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notes  $notes
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user_uuid = $request->header('user_uuid');
        $notes = Notes::where('user_uuid', $user_uuid)->get();
        return response()->json($notes);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notes  $notes
     * @return \Illuminate\Http\Response
     */
    public function edit(Notes $notes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notes  $notes
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $noteId)
    {
        $request->validate([
            'title' => 'required_without:body',
            'body' => 'required_without:title',
        ]);

        $note = Notes::where('user_uuid',$request->header('user_uuid'))->where('id',$noteId)->firstOrFail();

        $data = $request->only(['title', 'body']);
        $note->update($data);

        return response()->json([
            'message' => 'Successfully updated',
            'data' => $note
        ], 200);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notes  $notes
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $noteId)
    {
        $note = Notes::where('user_uuid',$request->header('user_uuid'))->where('id',$noteId)->firstOrFail();
        $note->delete();

        return response()->json([
            'message' => 'Successfully deleted',
            'data' => $note
        ], 200);
    }
}
