<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Image_tag;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getImageTags']]);
    }

    public function getImageTags($imageId)
    {
        return Image::find($imageId)->tags()->get();
    }

    public function attachToImage(Request $request)
    {
        $request->validate(
            ['name' => 'required'],
            ['image_id' => 'required|integer']
        );

        $foundTag = Tag::where('name', $request->input('name'))->get();
        $newTagId = null;

        if ($foundTag->isEmpty()) {
            $newTag = new Tag;
            $newTag->name = $request->get('name');
            $newTag->save();
            $newTagId = $newTag->id;
        } else {
            $newTagId = $foundTag[0]->id;
        }

        $mappedTag = new Image_tag;
        $mappedTag->image_id = $request->input('image_id');
        $mappedTag->tag_id = $newTagId;
        $mappedTag->save();
    }

    public function detachFromImage(Request $request)
    {
        $request->validate(
            ['tag_id' => 'required'],
            ['image_id' => 'required|integer']
        );

        Image_tag::where([
            ['tag_id', "=", $request->input('tag_id')],
            ['image_id', "=", $request->input('image_id')],
        ])->delete();
    }
}
