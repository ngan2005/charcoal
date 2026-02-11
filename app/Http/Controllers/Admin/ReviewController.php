<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Hiển thị danh sách đánh giá
     */
    public function index(Request $request)
    {
        $type = $request->input('type'); // product, service, staff
        $rating = $request->input('rating');
        $search = $request->input('search');

        $reviews = Review::with(['customer', 'product', 'service', 'staff'])
            ->when($type === 'product', function($query) {
                return $query->whereNotNull('ProductID')->whereNull('StaffID');
            })
            ->when($type === 'service', function($query) {
                return $query->whereNotNull('ServiceID')->whereNull('StaffID');
            })
            ->when($type === 'staff', function($query) {
                return $query->whereNotNull('StaffID');
            })
            ->when($rating, function($query) use ($rating) {
                return $query->where('Rating', $rating);
            })
            ->when($search, function($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('Comment', 'like', "%{$search}%")
                      ->orWhereHas('customer', function($q2) use ($search) {
                          $q2->where('FullName', 'like', "%{$search}%");
                      });
                });
            })
            ->where('Deleted', 0)
            ->orderBy('CreatedAt', 'desc')
            ->paginate(15);

        // Thống kê
        $stats = [
            'total' => Review::where('Deleted', 0)->count(),
            'avg_rating' => Review::where('Deleted', 0)->avg('Rating'),
            'five_star' => Review::where('Deleted', 0)->where('Rating', 5)->count(),
            'one_star' => Review::where('Deleted', 0)->where('Rating', 1)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Ẩn đánh giá (soft delete)
     */
    public function hide($id)
    {
        Review::where('ReviewID', $id)->update(['Deleted' => 1]);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Đã ẩn đánh giá thành công!');
    }

    /**
     * Hiển thị đánh giá
     */
    public function show($id)
    {
        $review = Review::with(['customer', 'product', 'service', 'staff', 'replies'])
            ->where('ReviewID', $id)
            ->firstOrFail();

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Trả lời đánh giá
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'Comment' => 'required|string|max:500',
        ]);

        // Tìm review gốc để lấy thông tin
        $parentReview = Review::where('ReviewID', $id)->first();

        // Tạo review trả lời
        Review::create([
            'CustomerID' => auth()->id(), // Admin hoặc staff trả lời
            'ProductID' => $parentReview->ProductID,
            'ServiceID' => $parentReview->ServiceID,
            'StaffID' => $parentReview->StaffID,
            'ParentReviewID' => $id,
            'Rating' => 5, // Mặc định
            'Comment' => $request->Comment,
            'Deleted' => 0,
            'CreatedAt' => now(),
        ]);

        return redirect()
            ->route('admin.reviews.show', $id)
            ->with('success', 'Đã trả lời đánh giá!');
    }

    /**
     * Thống kê đánh giá
     */
    public function statistics()
    {
        // Đánh giá theo sản phẩm
        $productReviews = DB::table('reviews')
            ->select([
                'ProductID',
                DB::raw('COUNT(*) as total_reviews'),
                DB::raw('AVG(Rating) as avg_rating')
            ])
            ->whereNotNull('ProductID')
            ->where('Deleted', 0)
            ->groupBy('ProductID')
            ->orderBy('total_reviews', 'desc')
            ->limit(10)
            ->get();

        // Đánh giá theo dịch vụ
        $serviceReviews = DB::table('reviews')
            ->select([
                'ServiceID',
                DB::raw('COUNT(*) as total_reviews'),
                DB::raw('AVG(Rating) as avg_rating')
            ])
            ->whereNotNull('ServiceID')
            ->where('Deleted', 0)
            ->groupBy('ServiceID')
            ->orderBy('total_reviews', 'desc')
            ->limit(10)
            ->get();

        // Đánh giá theo nhân viên
        $staffReviews = DB::table('reviews')
            ->select([
                'StaffID',
                DB::raw('COUNT(*) as total_reviews'),
                DB::raw('AVG(Rating) as avg_rating')
            ])
            ->whereNotNull('StaffID')
            ->where('Deleted', 0)
            ->groupBy('StaffID')
            ->orderBy('avg_rating', 'desc')
            ->limit(10)
            ->get();

        // Phân bố sao
        $ratingDistribution = Review::where('Deleted', 0)
            ->select('Rating', DB::raw('COUNT(*) as count'))
            ->groupBy('Rating')
            ->orderBy('Rating', 'desc')
            ->get();

        return view('admin.reviews.statistics', compact('productReviews', 'serviceReviews', 'staffReviews', 'ratingDistribution'));
    }
}












