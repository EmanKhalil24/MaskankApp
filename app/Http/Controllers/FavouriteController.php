<?php
namespace App\Http\Controllers;

use App\Http\Resources\FavouriteResource;
use App\Http\Resources\PostResource;
use App\Models\Favourite;
use App\Models\Post;
use App\Http\Requests\FavouriteRequest;
use Illuminate\Validation\ValidationException;

class FavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favourite = Favourite::latest()->paginate();

        return FavouriteResource::collection($favourite);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FavouriteRequest $request , string $post_id)
    {
        $post = Post::find($post_id);
        $fav = Favourite::create([
            'renter_id' => $request->renter,
            'post_id'   => $post['post_id'] ,
        ]);
        if($fav){
            return response()->json([
                'message' => 'you favourite this post',
                'status'  => true,
                'data' => new FavouriteResource($fav),
            ],200);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Favourite $favourite)
    {
        return new FavouriteResource($favourite);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)

    {
        $fav = Favourite::find($id);

    if (!$fav) {
        return response()->json([
            'message' => 'Favorite  not found.',
        ], 404);
    }

    $post = $fav->post;

    if ($fav->delete()) {
        return response()->json([
            'message' => 'Favorite entry deleted successfully.',
            'data' => new PostResource($post),
        ]);
    }

    return response()->json([
        'message' => 'Failed to delete favorite ',
    ], 500);


    }
    public function showFavouritePosts($id)
    {
        try{
            $fav = Favourite::where('renter_id', $id)->get();

            if ($fav->isEmpty()) {
                return response()->json([
                    'message' => 'No favorite posts found for you',
                    'data' => [],
                ]);
            }
    
            return response()->json([
                'message' => 'These are the favorite posts',
                'data' => FavouriteResource::collection($fav),
            ]);    
        }
        catch (ValidationException $e) {
            return response()->json(["error" => $e->errors()], 422);
        }

    }
}
