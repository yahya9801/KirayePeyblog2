<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\CategoryPost;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(){
        $categories_present = Post::selectRaw('category_id, COUNT(*) as post_count')
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->get();

        // foreach category get the latest 3 posts
        foreach ($categories_present as $key => $value) {
            $value->posts = Post::where('category_id',$value->category_id)->latest()->take(3)->get();
            $value->image = $value->posts[0]->image;
            $value->title = Category::find($value->category_id)->title;
        }
        $posts = Post::where('featured', false)
                    ->with('user', 'categories')
                    ->get();
        $categories = Category::all();
        $featured = Post::featured()->take(3)->get();
        // dd($featured);
        return view('front.index', [
            'posts' => $posts,
            'featured' => $featured,
            'categories' => $categories,
            'categories_present' => $categories_present
        ]);
    }

    public function posts(){
        return view('posts.index');
    }
    
    public function showPost($slug){
        $post = Post::where('slug',$slug)->latest()->first();
        $categories = Category::all();
        return view('front.posts.show', compact('post', 'categories'));
    }

    public function showCategory(Category $category){
        $posts = Post::where('category_id', $category->id)->orderBy('created_at','DESC')->get();
        $categories = Category::all();
        return view('front.categories.show', compact('category', 'posts', 'categories'));
    }

    public function searchPost(Request $request){
        $queryParam = $request->query('q');
        $posts = Post::where('title', 'Like', "%$queryParam%")
            ->orWhere('slug', 'Like', "%$queryParam%")
            ->orWhere('excerpt', 'Like', "%$queryParam%")
            ->orWhere('body', 'Like', "%$queryParam%")
            ->orderBy('created_at', 'desc') // Replace 'desc' with 'asc' if you want ascending order
            ->get();

        return $posts;
    }
}
