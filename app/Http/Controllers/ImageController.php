<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Image[]|Collection|Response
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort');
        $sortColumn = $sort == 1 ? 'created_at' : 'view_count';
        return Image::orderBy($sortColumn, 'DESC')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'url' => 'required',
        ]);

        $newImage = new Image($request->all());
        $newImage->save();
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return void
     */
    public function show($id)
    {
        $foundImage = Image::find($id);
        $foundImage->increment('view_count');
        return $foundImage;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        Image::find($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return void
     */
    public function destroy($id)
    {
        Image::destroy($id);
    }
}
