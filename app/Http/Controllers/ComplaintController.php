<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $complaint = Complaint::with("user")->get();
        return response()->json([
            "data" => $complaint,
            "message" => "Data Berhasil Diambil!"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validateData = Validator::make($request->all(), [
            "title" => "required",
            "category" => "required",
            "description" => "required",
            "location" => "required",
            "proof" => "required",
        ]);

        if ($validateData->fails()) {
            return response()->json([
                "message" => "Data Harus Diisi",
                "data" => null
            ], 422);
        }

        $path = $request->file("proof")->store("complaint", "public");
        $complaint = Complaint::create([
            "user_id" => $user->id,
            "title" => $request->title,
            "category" => $request->category,
            "description" => $request->description,
            "location" => $request->location,
            "proof" => $path,
            "status" => "process"
        ]);
        $complaint->load('user');

        return response()->json([
            "data" => $complaint,
            "message" => "Data Berhasil Dibuat!"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $complaint = Complaint::with("user")->findOrFail($id);
        return response()->json([
            "data" => $complaint,
            "message" => "Data Berhasil Diambil!"
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $complaint = Complaint::with("user")->findOrFail($id);

        if ($request->hasFile("proof")) {
            Storage::disk("public")->delete($complaint->image);

            $path = $request->file("proof")->store("complaint", "public");
            $complaint->proof = $path;
        }
        
        $complaint->title = $request->title ?? $complaint->title;
        $complaint->category = $request->category ?? $complaint->category;
        $complaint->description = $request->description ?? $complaint->description;
        $complaint->location = $request->location ?? $complaint->location;
        $complaint->status = $request->status ?? $complaint->status;
        $complaint->date_followed_up = $request->date_followed_up ?? $complaint->date_followed_up;
        $complaint->information = $request->information ?? $complaint->information;
        $complaint->result = $request->result ?? $complaint->result;
        $complaint->save();
        $complaint->load('user');

        return response()->json([
            "data" => $complaint,
            "message" => "Data Berhasil Diubah!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $complaint = Complaint::with("user")->findOrFail($id);
        $complaint->delete();

        return response()->json([
            "data" => null,
            "message" => "Data Berhasil Dihapus!"
        ]);
    }
}
