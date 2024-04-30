<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

use App\Models\Post;
use App\Models\Owner;
use App\Models\Admins;
use App\Models\request_renter;
use App\Models\Renter;

class postController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'images.*' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|integer',
            'size' => 'required|string',
            'purpose' => 'required|string',
            'bedrooms' => 'required|integer',
            'bathrooms' => 'required|integer',
            'region' => 'required|string',
            'city' => 'required|string',
            'floor' => 'required|string',
            'condition' => 'required|string',
            'status' => 'required|integer',
            'ownerId' => 'required|integer',
        ]);
    
        try {
            $item = new Post();
    
            $item->description = $validatedData['description'];
            $item->price = $validatedData['price'];
            $item->size = $validatedData['size'];
            $item->purpose = $validatedData['purpose'];
            $item->bedrooms = $validatedData['bedrooms'];
            $item->bathrooms = $validatedData['bathrooms'];
            $item->region = $validatedData['region'];
            $item->city = $validatedData['city'];
            $item->floor = $validatedData['floor'];
            $item->condition = $validatedData['condition'];
            $item->status = $validatedData['status'];
            $item->ownerId = $validatedData['ownerId'];
    
            $item->images = json_encode($validatedData['images']);
    
            $item->save();
    
            return response()->json([
                'message' => 'Item created successfully',
                'item' => $item,
            ], 201);
        } catch (QueryException $exception) {
            logger()->error('Failed to create item: ' . $exception->getMessage());
            return response()->json(['message' => 'Failed to create item. Error: ' . $exception->getMessage()], 500);
        }
    }    


    public function updateStatus(Request $request, $post_id)
    {
        $post = Post::findOrFail($post_id);

        $post->status = 1;
        $post->save();

        return response()->json(['message' => 'Post status updated successfully', 'post' => $post]);
    }

    public function numberOfWaitingAndDone(Request $request, $ownerId)
    {
        $waiting = DB::table('posts')->where('ownerId', $ownerId)->where('status', false)->count();

        $done = DB::table('posts')->where('ownerId', $ownerId)->where('status', true)->count();

        return response()->json(['message' => 'Number of Waiting and Done posts', 'Waiting' => $waiting , 'Done' => $done]);
    }

    public function showPostDetails($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $owner_id = $post->ownerId;

        $owner = Owner::find($owner_id);

        if (!$owner_id) {
            return response()->json(['message' => 'Owner not found'], 404);
        }

        return response()->json(['post details' => $post,'Owner' => $owner]);
    }

    public function filterPosts(Request $request)
    {
        $criteria = $request->all();
        $filteredPosts = $this->filterPostsFromDatabase($criteria);

        return response()->json(['filteredPosts' => $filteredPosts]);
    }

    private function filterPostsFromDatabase($criteria)
{
    $query = Post::query();

    if (isset($criteria['description'])) {
        $query->where('description', $criteria['description']);
    }

    if (isset($criteria['price'])) {
        $query->where('price', $criteria['price']);
    }

    if (isset($criteria['bedrooms'])) {
        $query->where('bedrooms', $criteria['bedrooms']);
    }

    if (isset($criteria['bathrooms'])) {
        $query->where('bathrooms', $criteria['bathrooms']);
    }
    
    if (isset($criteria['size'])) {
        $query->where('size', $criteria['size']);
    }

    if (isset($criteria['condition'])) {
        $query->where('condition', $criteria['condition']);
    }

    if (isset($criteria['city'])) {
        $query->where('city', $criteria['city']);
    }

    $filteredPosts = $query->get();

    return $filteredPosts;
}

public function showRandomPosts(Request $request)
{
    $numberOfPosts = $request->input('limit', 5);
    $posts = Post::inRandomOrder()->take($numberOfPosts)->get();
    return response()->json($posts);
}

public function showBooked($renter_id)
{
    $request = request_renter::where('renterId', $renter_id)->first();
    if (!$request) {
        return response()->json(['message' => 'Request not found'], 404);
    }

    $post_id = $request->postId;

    $post = Post::find($post_id);
    $renter = Renter::find($renter_id);

    if (!$post) {
        return response()->json(['message' => 'Post not found'], 404);
    }

    return response()->json(['Request details' => $request, 'Post' => $post, 'Renter' => $renter]);
}


public function login(Request $request)
{
    $admin = Admins::where("username", $request->input("username"))->first();
    
    if (!$admin) {
        return response()->json(["message" => "Unauthorized"], 401);
    }

    if ($request->input("password") !== $admin->password) {
        return response()->json(["message" => "Wrong password"], 404);
    }

    try {
        $token = Str::random(60);
        $admin->admin_token = $token;
        $admin->save();

        return response()->json([
            'message' => 'Successfully Logged in!',
            'admin' => $admin,
            'token' => $token
        ], 200);
    } catch (QueryException $exception) {
        logger()->error('Failed to update admin token: ' . $exception->getMessage());
        return response()->json(['message' => 'Failed to update admin token. Error: ' . $exception->getMessage()], 500);
    }
}

public function search(string $post_id)
{
    $post_search =Post::where('id',$post_id)
    ->get();
    return response()->json($post_search);
}

public function searchLocal(Request $request){
    $term = $request->query('term','');
    $posts = Post::search($term)->paginate(10);
    return response()->json(compact('posts'));
}
}
