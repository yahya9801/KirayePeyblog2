<?php

namespace App\Http\Controllers;

use App\Models\{Post, Category};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('authResource:post')->except('index', 'create', 'store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('back.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('back.posts.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
        if($request->has('image')){
            $this->uploadImage($request);
        }
        $request->user()->posts()->create($request->post());

        $postCount = Post::where('title', $request->post()['title'])->count();
        $post = Post::where('title', $request->post()['title'])->orderBy('created_at','DESC')->first();
        if($postCount - 1 > 0){
            $post->slug = $post->slug.'-'.($postCount - 1);
        }
        $post->category_id = $request->post()['category'];
        $post->update();

        $sitemapController = App::make(SitemapController::class);
        $result = $sitemapController->index();

        return redirect()->route('posts.index')->with('message', 'Post created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('back.posts.edit', compact('post','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // the update method, only fire its events when the update happens directly on the model,
        // so we will use save directly on modal instead of mass assignment
        if($request->has('image')){
            $oldImage = $post->image;
            $this->uploadImage($request);
            if(file_exists(public_path('images/'.$oldImage))){
                unlink(public_path('images/'.$oldImage));
            }
            $post->image = $request->post()['image'];
        }
        $post->title    = $request->title;
        $post->excerpt  = $request->excerpt;
        $post->body     = $request->body;
        $post->category_id     = $request->category;
        $post->save();

        return back()->with('message', 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        $sitemapController = App::make(SitemapController::class);
        $result = $sitemapController->index();
        return back();
    }

    public function uploadImage($request){
        $image = $request->file('image');
        $imageName = time().$image->getClientOriginalName();
        // add the new file 
        $image->move(public_path('images'),$imageName);
        $request->merge(['image' => $imageName]);
        // dd($request);
    }

    
}
