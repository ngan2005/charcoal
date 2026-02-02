<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $vouchers = Voucher::query()
            ->when($search, function($query) use ($search) {
                return $query->where('Code', 'like', "%{$search}%")
                            ->orWhere('Description', 'like', "%{$search}%");
            })
            ->when($status !== null && $status !== '', function($query) use ($status) {
                if ($status == 'active') {
                    return $query->where('IsActive', true)
                                ->where('ExpiredAt', '>', now())
                                ->where('Quantity', '>', 0);
                } else {
                    return $query->where(function($q) {
                        $q->where('IsActive', false)
                          ->orWhere('ExpiredAt', '<=', now())
                          ->orWhere('Quantity', '<=', 0);
                    });
                }
            })
            ->orderBy('CreatedAt', 'desc')
            ->paginate(10);

        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Code' => 'required|string|max:50|unique:vouchers,Code',
            'DiscountPercent' => 'required|integer|min:1|max:100',
            'ExpiredAt' => 'required|date|after:now',
            'Quantity' => 'required|integer|min:1',
            'Description' => 'nullable|string|max:255',
            'MinOrderAmount' => 'nullable|numeric|min:0',
            'MaxDiscountAmount' => 'nullable|numeric|min:0',
        ], [
            'Code.required' => 'Mã giảm giá là bắt buộc.',
            'Code.unique' => 'Mã giảm giá đã tồn tại.',
            'DiscountPercent.required' => 'Phần trăm giảm giá là bắt buộc.',
            'DiscountPercent.min' => 'Phần trăm giảm giá phải từ 1% trở lên.',
            'DiscountPercent.max' => 'Phần trăm giảm giá không được vượt quá 100%.',
            'ExpiredAt.required' => 'Ngày hết hạn là bắt buộc.',
            'ExpiredAt.after' => 'Ngày hết hạn phải lớn hơn thời gian hiện tại.',
            'Quantity.required' => 'Số lượng là bắt buộc.',
            'Quantity.min' => 'Số lượng phải lớn hơn 0.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                Voucher::create([
                    'Code' => strtoupper($request->Code),
                    'DiscountPercent' => $request->DiscountPercent,
                    'ExpiredAt' => $request->ExpiredAt,
                    'Quantity' => $request->Quantity,
                    'Description' => $request->Description,
                    'MinOrderAmount' => $request->MinOrderAmount ?? 0,
                    'MaxDiscountAmount' => $request->MaxDiscountAmount ?? 0,
                    'IsActive' => true,
                    'CreatedAt' => now(),
                ]);
            });

            return redirect()
                ->route('admin.vouchers.index')
                ->with('success', 'Tạo mã giảm giá thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $voucher = Voucher::with('orders.user')->findOrFail($id);
        return view('admin.vouchers.show', compact('voucher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'Code' => 'required|string|max:50|unique:vouchers,Code,' . $id . ',VoucherID',
            'DiscountPercent' => 'required|integer|min:1|max:100',
            'ExpiredAt' => 'required|date',
            'Quantity' => 'required|integer|min:1',
            'Description' => 'nullable|string|max:255',
            'MinOrderAmount' => 'nullable|numeric|min:0',
            'MaxDiscountAmount' => 'nullable|numeric|min:0',
        ], [
            'Code.required' => 'Mã giảm giá là bắt buộc.',
            'Code.unique' => 'Mã giảm giá đã tồn tại.',
            'DiscountPercent.required' => 'Phần trăm giảm giá là bắt buộc.',
            'DiscountPercent.min' => 'Phần trăm giảm giá phải từ 1% trở lên.',
            'DiscountPercent.max' => 'Phần trăm giảm giá không được vượt quá 100%.',
            'ExpiredAt.required' => 'Ngày hết hạn là bắt buộc.',
            'Quantity.required' => 'Số lượng là bắt buộc.',
            'Quantity.min' => 'Số lượng phải lớn hơn 0.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $voucher->update([
                'Code' => strtoupper($request->Code),
                'DiscountPercent' => $request->DiscountPercent,
                'ExpiredAt' => $request->ExpiredAt,
                'Quantity' => $request->Quantity,
                'Description' => $request->Description,
                'MinOrderAmount' => $request->MinOrderAmount ?? 0,
                'MaxDiscountAmount' => $request->MaxDiscountAmount ?? 0,
            ]);

            return redirect()
                ->route('admin.vouchers.index')
                ->with('success', 'Cập nhật mã giảm giá thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);

        try {
            $voucher->delete();
            return redirect()
                ->route('admin.vouchers.index')
                ->with('success', 'Xóa mã giảm giá thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.vouchers.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Toggle voucher status (active/inactive)
     */
    public function toggleStatus($id)
    {
        $voucher = Voucher::findOrFail($id);

        try {
            $voucher->update(['IsActive' => !$voucher->IsActive]);
            $status = $voucher->IsActive ? 'kích hoạt' : 'vô hiệu hóa';
            return redirect()
                ->route('admin.vouchers.index')
                ->with('success', "Mã giảm giá đã được {$status}!");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.vouchers.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Generate random voucher code
     */
    public function generateCode()
    {
        return Str::random(8)->upper();
    }
}

