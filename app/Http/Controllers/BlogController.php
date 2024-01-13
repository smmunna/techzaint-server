<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    //create new Blog
    public function createBlog(Request $request)
    {
        Blog::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Blog created successfully'
        ]);
    }

    // View blogs
    public function viewBlogs(Request $request)
    {
        $offset = $request->query('offset', 0);
        $limit = $request->query('limit', 10);

        $blogs = Blog::offset($offset)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'offset' => $offset,
            'limit' => $limit,
            'total' => Blog::count(),
            'blogs' => $blogs
        ]);
    }

    // View single Blogs
    public function singleBlogs(Request $request)
    {
        $id = $request->query('id');
        $blog = Blog::where('id', $id)->first();
        return response()->json([
            'blog' => $blog
        ]);
    }

    // Month and year wise blogs;
    public function viewBlogsByMonthAndYear(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        $query = Blog::query();

        if ($month && $year) {
            // If both month and year are provided, filter by them
            $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        }

        // Get all blogs without pagination
        $blogs = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'blogs' => $blogs
        ]);
    }

    // Delete blogs
    public function DeleteBlogs(Request $request)
    {
        // Get the blog ID from the query parameter
        $blogId = $request->query('id');

        // Check if the blog ID is provided
        if (!$blogId) {
            return response()->json(['error' => 'Blog ID is missing.'], 400);
        }

        // Find the blog entry by ID
        $blog = Blog::find($blogId);

        // Check if the blog entry exists
        if (!$blog) {
            return response()->json(['error' => 'Blog not found.'], 404);
        }

        // Delete the blog entry
        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully.']);
    }

    // Edit blogs
    public function EditBlogs(Request $request)
    {
        // 1. Extract the blog ID from the query parameters
        $blogId = $request->query('id');

        // 2. Fetch the existing blog data from the database using the ID
        $blog = Blog::find($blogId);

        if (!$blog) {
            // Handle the case where the blog with the given ID is not found
            return response()->json(['message' => 'Blog not found'], 404);
        }

        // 3. Update the blog data with the values from the request
        $blog->title = $request->input('title');
        $blog->description = $request->input('description');
        $blog->category = $request->input('category');
        $blog->cover_img = $request->input('cover_img');

        // You may need additional validation and error handling here

        // 4. Save the updated data back to the database
        $blog->save();

        // Return a success message or updated blog data as needed
        return response()->json(['message' => 'Blog updated successfully', 'blog' => $blog]);
    }

    // View Individual Blogs
    public function individualBlogs(Request $request)
    {
        $offset = $request->query('offset', 0);
        $limit = $request->query('limit', 10);
        $email = $request->query('email');

        $blogsQuery = Blog::where('email', $email);
        $total = $blogsQuery->count();

        $blogs = $blogsQuery
            ->offset($offset)
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
            'blogs' => $blogs
        ]);
    }

    public function top5Blog()
    {
        $top5Blogs = Blog::orderBy('favorite', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'top5Blogs' => $top5Blogs,
        ]);
    }
}
