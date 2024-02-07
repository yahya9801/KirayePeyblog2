<?php

namespace App\Http\Controllers;

use App\Models\{Post, Category};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SitemapController extends Controller
{

    public function index($value='')
    {
        $posts = Post::latest()->get();
  
        $sitemapContent = view('sitemap', [
            'posts' => $posts
        ]);
        $sitemapPath = public_path('sitemap.xml');

        File::put($sitemapPath, $sitemapContent);

        return "Sitemap generated and saved to {$sitemapPath}";
    }
    
}
