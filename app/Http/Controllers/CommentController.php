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

        $comment->load(['user' => function ($query) {
            $query->select('UserID', 'FullName', 'Avatar', 'RoleID');
        }]);

        // Thêm role_name vào user
        if ($comment->user && $comment->user->RoleID == 1) {
            $comment->user->role_name = 'Admin';
        } else {
            $comment->user->role_name = null;
        }

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
        // Lấy reviews (đánh giá gốc) cho sản phẩm
        $reviews = Review::with(['customer' => function ($query) {
                $query->select('UserID', 'FullName', 'Avatar', 'RoleID');
            }, 'replies' => function ($query) {
                $query->with(['customer' => function ($q) {
                    $q->select('UserID', 'FullName', 'Avatar', 'RoleID');
                }])->where('Deleted', 0);
            }])
            ->where('ProductID', $productId)
            ->whereNull('ParentReviewID')
            ->where('Deleted', 0)
            ->orderBy('CreatedAt', 'desc')
            ->get();

        // Thêm role name vào mỗi review và replies
        $reviews->transform(function ($review) {
            if ($review->customer && $review->customer->RoleID == 1) {
                $review->customer->role_name = 'Admin';
            } else {
                $review->customer->role_name = null;
            }

            // Xử lý replies
            $review->replies->transform(function ($reply) {
                if ($reply->customer && $reply->customer->RoleID == 1) {
                    $reply->customer->role_name = 'Admin';
                } else {
                    $reply->customer->role_name = null;
                }
                return $reply;
            });

            return $review;
        });

        return response()->json([
            'success' => true,
            'comments' => $reviews,
        ]);
    }

    /**
     * Delete a comment/review.
     */
    public function destroy(Request $request, $commentId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập.'
            ], 401);
        }

        // Thử tìm trong bảng reviews trước (cho sản phẩm)
        $review = Review::find($commentId);

        if ($review) {
            $user = Auth::user();
            if ($user->RoleID != 1 && $review->CustomerID != $user->UserID) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xóa đánh giá này.'
                ], 403);
            }

            $review->Deleted = true;
            $review->save();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa đánh giá.'
            ]);
        }

        // Nếu không có trong reviews, thử bảng comments
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Bình luận không tồn tại.'
            ], 404);
        }

        $user = Auth::user();
        if ($user->RoleID != 1 && $comment->UserID != $user->UserID) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa bình luận này.'
            ], 403);
        }

        $comment->Status = false;
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa bình luận.'
        ]);
    }
}
