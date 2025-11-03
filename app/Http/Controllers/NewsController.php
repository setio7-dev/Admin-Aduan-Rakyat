<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::all();
        return response()->json([
            "message" => "Data Berhasil Diambil!",
            "data" => $news
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
        $validateData = Validator::make($request->all(), [
            "title" => 'required',
            "description" => 'required',
            "text" => 'required',
            "image" => 'required',
        ]);

        if ($validateData->fails()) {
            return response()->json([
                "message" => "Data Harus Diisi!",
                "data" => null
            ], 422);
        }

        $path = $request->file("image")->store("news", "public");
        $news = News::create([
            "title" => $request->title,
            "description" => $request->description,
            "text" => $request->text,
            "image" => $path,
        ]);

        return response()->json([
            "message" => "Data Berhasil Ditambah!",
            "data" => $news
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $news = News::findOrFail($id);
        return response()->json([
            "message" => "Data Berhasil Diambil!",
            "data" => $news
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
        $news = News::findOrFail($id);
        if ($request->hasFile("image")) {
            Storage::disk("public")->delete($news->image);

            $path = $request->file("image")->store("news", "public");
            $news->image = $path;
        }

        $news->title = $request->title ?? $news->title;
        $news->description = $request->description ?? $news->description;
        $news->text = $request->text ?? $news->text;
        $news->save();

        return response()->json([
            "message" => "Data Berhasil Diubah!",
            "data" => $news
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $news = News::findOrFail($id);
        Storage::disk("public")->delete($news->image);        

        $news->delete();
        return response()->json([
            "message" => "Data Berhasil Dihapus!",
            "data" => null
        ]);
    }
}
