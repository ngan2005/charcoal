<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceImage;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $query = Service::query();

        if ($search !== '') {
            $query->where('ServiceName', 'like', '%' . $search . '%');
        }

        $services = $query->with('images')->orderByDesc('ServiceID')->paginate(10)->withQueryString();

        return view('admin.services.index', [
            'services' => $services,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ServiceName' => ['required', 'string', 'max:150'],
            'BasePrice' => ['required', 'numeric', 'min:0'],
            'Duration' => ['required', 'integer', 'min:0'],
            'Description' => ['nullable', 'string', 'max:255'],
            'Images' => ['nullable', 'array'],
            'Images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $rawFiles = $request->file('Images');

        $data = $request->except('Images');

        DB::transaction(function () use ($data, $rawFiles) {
            $service = Service::create($data);

            // Xử lý ảnh ngay cả khi không có file cũng không sao
            if ($rawFiles && is_array($rawFiles)) {
                foreach ($rawFiles as $file) {
                    if (!$file || !$file->isValid() || $file->getSize() <= 0) {
                        continue;
                    }

                    try {
                        $filename = time() . '_service_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $destinationPath = storage_path('app/public/services');
                        
                        // Đảm bảo thư mục tồn tại
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        
                        $file->move($destinationPath, $filename);
                        
                        ServiceImage::create([
                            'ServiceID' => $service->ServiceID,
                            'ImageUrl' => asset('storage/services/' . $filename),
                        ]);
                    } catch (\Throwable $e) {
                        // Log lỗi nhưng không dừng transaction
                        report($e);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Tạo dịch vụ thành công.');
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'ServiceName' => ['required', 'string', 'max:150'],
            'BasePrice' => ['required', 'numeric', 'min:0'],
            'Duration' => ['required', 'integer', 'min:0'],
            'Description' => ['nullable', 'string', 'max:255'],
            'Images' => ['nullable', 'array'],
            'Images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $rawFiles = $request->file('Images');
        $data = $request->except('Images');

        DB::transaction(function () use ($service, $data, $rawFiles) {
            $service->update($data);

            // Xử lý ảnh ngay cả khi không có file cũng không sao
            if ($rawFiles && is_array($rawFiles)) {
                foreach ($rawFiles as $file) {
                    if (!$file || !$file->isValid() || $file->getSize() <= 0) {
                        continue;
                    }

                    try {
                        $filename = time() . '_service_update_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $destinationPath = storage_path('app/public/services');
                        
                        // Đảm bảo thư mục tồn tại
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        
                        $file->move($destinationPath, $filename);
                        
                        ServiceImage::create([
                            'ServiceID' => $service->ServiceID,
                            'ImageUrl' => asset('storage/services/' . $filename),
                        ]);
                    } catch (\Throwable $e) {
                        // Log lỗi nhưng không dừng transaction
                        report($e);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Cập nhật dịch vụ thành công.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Xóa dịch vụ thành công.');
    }
}
