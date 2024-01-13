<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    //create Favorite
    public function create(Request $request)
    {
        $blog_id = $request->blog_id;
        $email = $request->user_email;
        $favorite = $request->favorite;

        // Check if the item already exists based on blog_id and email
        $existingFavorite = Favorite::where('blog_id', $blog_id)
            ->where('user_email', $email)
            ->first();

        if ($existingFavorite) {
            // Item already exists
            return response()->json([
                'success' => false,
                'message' => 'Already saved for the specified blog and user',
            ]);
        }

        // Item doesn't exist, save it
        $newFavorite = new Favorite();
        $newFavorite->blog_id = $blog_id;
        $newFavorite->user_email = $email;
        $newFavorite->favorite = $favorite;
        $newFavorite->save();

        return response()->json([
            'success' => true,
            'message' => 'Bloged saved successfully',
        ]);
    }

    // View Favorite Items
    public function view(Request $request)
    {
        $email = $request->query('email');

        $favorites = DB::table('favorites')
            ->select('favorites.*', 'blogs.title as title')
            ->join('blogs', 'favorites.blog_id', '=', 'blogs.id')
            ->where('favorites.user_email', $email)
            ->get();

        return $favorites;
    }

    // public function view(Request $request)
    // {
    //     $email = $request->query('email');
    //     $offset = $request->query('offset', 0);
    //     $limit = $request->query('limit', 10);

    //     $query = DB::table('favorites')
    //         ->select('favorites.*', 'blogs.title as title')
    //         ->join('blogs', 'favorites.blog_id', '=', 'blogs.id')
    //         ->where('favorites.user_email', $email);

    //     $total = $query->count(); // Get the total count before applying offset and limit

    //     $favorites = $query->offset($offset)->limit($limit)->get();

    //     return [
    //         'total' => $total,
    //         'offset' => $offset,
    //         'limit' => $limit,
    //         'data' => $favorites,
    //     ];
    // }


    // Delete Favorite Items
    public function delete(Request $request)
    {
        $id = $request->query('id');

        // Delete favorites based on email
        Favorite::where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => "Removed Successfully",
        ]);
    }
}
