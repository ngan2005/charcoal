<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class PublicServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);
        $otherServices = Service::where('ServiceID', '!=', $id)->take(4)->get();
        
        // Fetch reviews
        $reviews = \App\Models\Review::with(['customer', 'replies.staff'])
            ->where('ServiceID', $id)
            ->whereNull('ParentReviewID')
            ->where(function ($query) {
                $query->where('Deleted', 0)->orWhereNull('Deleted');
            })
            ->orderBy('CreatedAt', 'desc')
            ->get();
            
        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0 ? $reviews->avg('Rating') : 0;
        
        // Fetch suggested products
        $suggestedProducts = \App\Models\Product::inRandomOrder()->take(4)->get();
        
        return view('services.show', compact('service', 'otherServices', 'reviews', 'reviewCount', 'averageRating', 'suggestedProducts'));
    }
}
