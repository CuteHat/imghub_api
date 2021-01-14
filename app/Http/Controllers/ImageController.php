<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

//use this library

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Image[]|Collection|Response
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

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
     * @return Image
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'url' => 'required',
        ]);
        $decodedToken = JWTAuth::getPayload(JWTAuth::getToken())->toArray();
        $newImage = new Image;
        $newImage->user_id = $decodedToken['sub'];
        $newImage->title = $request->input('title');
        $newImage->url = $request->input('url');
        $newImage->save();
        return $newImage;
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

    public function getUserImages()
    {
        $decodedToken = JWTAuth::getPayload(JWTAuth::getToken())->toArray();
        $userId = $decodedToken['sub'];
        return Image::where('user_id', "=", $userId)->get();
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


    public function getImagesWithTag(Request $request)
    {
        $tag = trim($request->query('tag'));
        $result = [];

        if (strlen($tag) >= 3) {
            $foundTags = Tag::where('name', 'LIKE', '%' . $tag . '%')->get();
            foreach ($foundTags as &$currentTag) {
                $foundImages = $currentTag->images()->get();
                foreach ($foundImages as &$foundImage) {
                    if (!$this->resultContainsId($result, $foundImage->id)) {
                        array_push($result, $foundImage);
                    }
                }
            }
            return $result;
        }
    }

    private function resultContainsId($array, $id)
    {
        foreach ($array as &$element) {
            if ($element->id == $id) {
                return true;
            }
        }
        return false;
    }
}
