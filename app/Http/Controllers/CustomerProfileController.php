<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class CustomerProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Load recent orders
        $orders = \Illuminate\Support\Facades\DB::table('orders')
            ->where('UserID', $user->UserID)
            ->orderByDesc('CreatedAt')
            ->take(5)
            ->get();

        // Load pets of current user (for "Thú cưng của tôi" in profile)
        $pets = Pet::where('OwnerID', $user->UserID)->with('images')->orderBy('PetName')->get();

        // Lịch sử chăm sóc dịch vụ (appointments)
        $appointments = \App\Models\Appointment::with(['pet', 'services'])
            ->where('CustomerID', $user->UserID)
            ->orderByDesc('AppointmentTime')
            ->take(30)
            ->get();

        // Tính tổng giá dịch vụ cho mỗi appointment
        $appointments->each(function ($apt) {
            $totalPrice = 0;
            if ($apt->services && $apt->services->count() > 0) {
                foreach ($apt->services as $service) {
                    // Kiểm tra user có membership không để lấy giá phù hợp
                    $price = $service->MemberPrice ?? $service->BasePrice;
                    $totalPrice += $price;
                }
            }
            $apt->total_price = $totalPrice;
        });
            
        return view('profile.index', compact('user', 'orders', 'pets', 'appointments'));
    }

    public function update(Request $request)
    {
        $user = User::find(auth()->id());
        
        $request->validate([
            'FullName' => 'required|string|max:100',
            'Phone' => 'nullable|string|max:15',
            'Address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = [
            'FullName' => $request->FullName,
            'Phone' => $request->Phone,
            'Address' => $request->Address,
        ];

        // Handle Avatar Upload - dùng get() thay vì storeAs để tránh lỗi "Path must not be empty" trên Windows
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->isValid() && $file->getSize() > 0) {
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                if (!$filename || strpos($filename, '_.') !== false) {
                    $ext = $file->getClientOriginalExtension() ?: 'jpg';
                    $filename = time() . '_avatar.' . strtolower($ext);
                }
                Storage::disk('public')->makeDirectory('avatars');
                $relativePath = 'avatars/' . $filename;
                Storage::disk('public')->put($relativePath, $file->get());
                if ($user->Avatar && Storage::disk('public')->exists($user->Avatar)) {
                    Storage::disk('public')->delete($user->Avatar);
                }
                $user->Avatar = $relativePath;
                $user->save();
            }
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::find(auth()->id());

        if (!Hash::check($request->current_password, $user->Password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        $user->update([
            'Password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công!');
    }

    /**
     * Hủy đơn hàng (chỉ đơn hàng ở trạng thái pending hoặc confirmed)
     */
    public function cancelOrder(Request $request, $id)
    {
        $user = auth()->user();
        
        $order = Order::with('details')
            ->where('OrderID', $id)
            ->where('UserID', $user->UserID)
            ->first();

        if (!$order) {
            return redirect()->route('profile.index')->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Chỉ cho phép hủy đơn hàng ở trạng thái pending hoặc confirmed
        if (!in_array($order->Status, ['pending', 'confirmed'])) {
            return redirect()->route('profile.index')->with('error', 'Không thể hủy đơn hàng ở trạng thái "' . $this->getOrderStatusText($order->Status) . '".');
        }

        // Hoàn lại tồn kho cho các sản phẩm trong đơn
        foreach ($order->details as $detail) {
            if ($detail->ProductID && $detail->Quantity > 0) {
                DB::table('products')
                    ->where('ProductID', $detail->ProductID)
                    ->increment('Stock', $detail->Quantity);

                // Giảm số lượng đã bán (PurchaseCount)
                DB::table('products')
                    ->where('ProductID', $detail->ProductID)
                    ->decrement('PurchaseCount', $detail->Quantity);
            }
        }

        $order->update(['Status' => 'cancelled']);

        return redirect()->route('profile.index')->with('success', 'Đơn hàng #' . str_pad($order->OrderID, 5, '0', STR_PAD_LEFT) . ' đã được hủy thành công. Tồn kho đã được hoàn lại.');
    }

    /**
     * Hủy lịch hẹn (chỉ lịch hẹn ở trạng thái pending hoặc confirmed)
     */
    public function cancelAppointment(Request $request, $id)
    {
        $user = auth()->user();
        
        $appointment = Appointment::with(['order.details'])
            ->where('AppointmentID', $id)
            ->where('CustomerID', $user->UserID)
            ->first();

        if (!$appointment) {
            return redirect()->route('profile.index')->with('error', 'Không tìm thấy lịch hẹn.');
        }

        // Chỉ cho phép hủy lịch hẹn ở trạng thái pending hoặc confirmed
        if (!in_array($appointment->Status, ['pending', 'confirmed'])) {
            return redirect()->route('profile.index')->with('error', 'Không thể hủy lịch hẹn ở trạng thái "' . $this->getAppointmentStatusText($appointment->Status) . '".');
        }

        // Hoàn lại tồn kho khi hủy lịch hẹn (nếu có đơn hàng gốc)
        if ($appointment->OrderID && $appointment->order && $appointment->order->details) {
            foreach ($appointment->order->details as $detail) {
                if ($detail->ProductID && $detail->Quantity > 0) {
                    DB::table('products')
                        ->where('ProductID', $detail->ProductID)
                        ->increment('Stock', $detail->Quantity);

                    // Giảm số lượng đã bán (PurchaseCount)
                    DB::table('products')
                        ->where('ProductID', $detail->ProductID)
                        ->decrement('PurchaseCount', $detail->Quantity);
                }
            }
        }

        $appointment->update(['Status' => 'cancelled']);

        return redirect()->route('profile.index')->with('success', 'Lịch hẹn ngày ' . \Carbon\Carbon::parse($appointment->AppointmentTime)->format('d/m/Y H:i') . ' đã được hủy thành công. Tồn kho đã được hoàn lại.');
    }

    /**
     * Chuyển đổi trạng thái đơn hàng sang tiếng Việt
     */
    private function getOrderStatusText($status)
    {
        return match($status) {
            'pending' => 'chờ xác nhận',
            'confirmed' => 'đã xác nhận',
            'processing' => 'đang xử lý',
            'shipping' => 'đang giao hàng',
            'delivered' => 'đã giao hàng',
            'completed' => 'hoàn thành',
            'cancelled' => 'đã hủy',
            'refunded' => 'đã hoàn tiền',
            default => $status,
        };
    }

    /**
     * Chuyển đổi trạng thái lịch hẹn sang tiếng Việt
     */
    private function getAppointmentStatusText($status)
    {
        return match($status) {
            'pending' => 'chờ xác nhận',
            'confirmed' => 'đã xác nhận',
            'in_progress' => 'đang thực hiện',
            'completed' => 'hoàn thành',
            'cancelled' => 'đã hủy',
            'no_show' => 'vắng mặt',
            default => $status,
        };
    }
}
