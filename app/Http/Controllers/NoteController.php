<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Exception;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::all();

        return response()->json(['notes' => $notes], 200);
    }

    public function show($id)
    {
        $note = Note::findOrFail($id);

        return response()->json(['note' => $note], 200);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            $note = Note::create($validatedData);

            return response()->json(['message' => 'Note created successfully', 'note' => $note], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Note creation failed, ' . $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'string|max:255',
                'content' => 'string',
            ]);

            $note = Note::findOrFail($id);
            $note->update($validatedData);

            if ($note->wasChanged()) {
                return response()->json(['message' => 'Note updated successfully', 'note' => $note], 200);
            } else {
                return response()->json(['message' => 'No changes were made to the note', 'note' => $note], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Note update failed, ' . $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $note = Note::findOrFail($id);
            $note->delete();

            return response()->json(['message' => 'Note deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Note deletion failed, ' . $e->getMessage()], 400);
        }
    }
}
