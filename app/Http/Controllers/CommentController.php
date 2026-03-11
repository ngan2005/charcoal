<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để bình luận.'
            ], 401);
        }

        $request->validate([
            'ProductID' => 'required|exists:products,ProductID',
            'Content' => 'required|min:3|max:1000',
            'Rating' => 'required|integer|min:1|max:5',
        ]);

        $product = Product::find($request->ProductID);
        if (!$product || $product->StatusID != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại hoặc đã bị ẩn.'
            ], 404);
        }

        $comment = Comment::create([
            'ProductID' => $request->ProductID,
            'UserID' => Auth::id(),
            'Content' => $request->Content,
            'Rating' => $request->Rating,
            'Status' => true,
        ]);

        // Đồng bộ sang bảng reviews để hiển thị trong Quản lý đánh giá (admin)
        Review::create([
            'CustomerID' => Auth::id(),
            'ProductID' => $request->ProductID,
            'ServiceID' => null,
            'StaffID' => null,
            'ParentReviewID' => null,
            'Rating' => (int) $request->Rating,
            'Comment' => mb_substr($request->Content, 0, 500),
            'Deleted' => 0,
            'CreatedAt' => now(),
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Bình luận của bạn đã được đăng!',
            'comment' => $comment,
        ]);
    }

    /**
     * Get comments for a product (AJAX).
     */
    public function getComments($productId)
    {
        $comments = Comment::with('user')
            ->where('ProductID', $productId)
            ->where('Status', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments,
        ]);
    }
}
