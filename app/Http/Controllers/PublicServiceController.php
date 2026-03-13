<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('images')->get();
        return view('services.index', compact('services'));
    }

    public function show($id)
    {
        $service = Service::with('images')->findOrFail($id);
        $otherServices = Service::with('images')->where('ServiceID', '!=', $id)->take(4)->get();
        
        // Fetch reviews
        $reviews = \App\Models\Review::with(['customer' => function ($query) {
                $query->select('UserID', 'FullName', 'Avatar', 'RoleID');
            }, 'replies.staff'])
            ->where('ServiceID', $id)
            ->whereNull('ParentReviewID')
            ->where(function ($query) {
                $query->where('Deleted', 0)->orWhereNull('Deleted');
            })
            ->orderBy('CreatedAt', 'desc')
            ->get();
            
        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0 ? $reviews->avg('Rating') : 0;
        
        // Fetch suggested products (load images for display)
        $suggestedProducts = \App\Models\Product::with('images')->inRandomOrder()->take(4)->get();
        
        return view('services.show', compact('service', 'otherServices', 'reviews', 'reviewCount', 'averageRating', 'suggestedProducts'));
    }

    /**
     * Lưu đánh giá/bình luận cho dịch vụ.
     */
    public function storeReview(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để gửi đánh giá.',
            ], 401);
        }

        $request->validate([
            'ServiceID' => 'required|exists:services,ServiceID',
            'Comment' => 'required|min:3|max:1000',
            'Rating' => 'required|integer|min:1|max:5',
        ], [
            'Comment.required' => 'Vui lòng nhập nội dung đánh giá.',
            'Comment.min' => 'Đánh giá phải có ít nhất 3 ký tự.',
        ]);

        Review::create([
            'CustomerID' => Auth::id(),
            'ProductID' => null,
            'ServiceID' => $request->ServiceID,
            'StaffID' => null,
            'ParentReviewID' => null,
            'Rating' => (int) $request->Rating,
            'Comment' => $request->Comment,
            'Deleted' => 0,
            'CreatedAt' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn đã gửi đánh giá! ✨',
        ]);
    }

    /**
     * Xóa đánh giá dịch vụ.
     */
    public function destroyReview(Request $request, $reviewId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập.',
            ], 401);
        }

        $review = Review::find($reviewId);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Đánh giá không tồn tại.',
            ], 404);
        }

        $user = Auth::user();
        if ($user->RoleID != 1 && $review->CustomerID != $user->UserID) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa đánh giá này.',
            ], 403);
        }

        $review->Deleted = true;
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa đánh giá.',
        ]);
    }
}
