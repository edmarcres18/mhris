<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Carbon;
use App\Models\MedicalProduct;
use App\Models\Category;

class WelcomeController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->take(6)->get();
        $todayPostsCount = $this->countTodayPosts();
        return view('welcome', compact('posts', 'todayPostsCount'));
    }

    public function showPost($id)
    {
        $post = Post::findOrFail($id);
        $relatedPosts = $this->getRelatedPosts($post);
        return view('posts', compact('post', 'relatedPosts'));
    }

    private function countTodayPosts()
    {
        return Post::whereDate('created_at', Carbon::today())->count();
    }

    private function getRelatedPosts($post)
    {
        return Post::where('id', '!=', $post->id)
                    ->whereDate('created_at', Carbon::today())
                    ->take(3)
                    ->get();
    }
    /**
     * Display the MHRPCI page.
     */
    public function showMhrpci()
    {
        return view('mhrpci');
    }

    public function showBgpdi()
    {
        return view('bgpdi');
    }

    public function showCio()
    {
        return view('cio');
    }

    public function showVhi()
    {
        return view('vhi');
    }

    public function showMhrhci()
    {   
        $featuredProducts = MedicalProduct::where('is_featured', true)
            ->with('category')
            ->latest()
            ->take(5)
            ->get();
            
        return view('mhrhci', compact('featuredProducts'));
    }
    public function showMax()
    {
        return view('max');
    }

    public function showRcg()
    {
        return view('rcg');
    }
    public function showLus()
    {
        return view('lus');
    }

    public function showMhrcons()
    {
        return view('mhrcons');
    }

    public function showMedicalEquipment()
    {
        return view('medical_equipment');
    }

    public function allSubsidiaries()
    {
        return view('all_subsidiaries');
    }
    public function showMedicalProducts()
    {
        $categories = Category::all();
        $medicalProducts = MedicalProduct::orderBy('category_id')->get();
        return view('medical_products', compact('categories', 'medicalProducts'));
    }
}
