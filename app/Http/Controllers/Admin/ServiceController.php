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

            if ($rawFiles && is_array($rawFiles)) {
                foreach ($rawFiles as $index => $file) {
                    if ($file && $file->isValid() && $file->getSize() > 0) {
                        $filename = time() . '_service_' . uniqid() . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                        $destinationPath = storage_path('app/public/services');
                        
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        
                        $file->move($destinationPath, $filename);
                        
                        ServiceImage::create([
                            'ServiceID' => $service->ServiceID,
                            'ImageUrl' => asset('storage/services/' . $filename),
                            'IsMain' => $index === 0 ? 1 : 0,
                        ]);
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
            'DeleteImageIDs' => ['nullable', 'string'],
            'MainImageID' => ['nullable', 'integer'],
            'new_images' => ['nullable', 'array'],
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data = $request->except(['DeleteImageIDs', 'MainImageID', 'new_images', 'Images']);

        DB::transaction(function () use ($service, $data, $request) {
            $service->update($data);

            // Xóa ảnh được chọn
            $deleteIds = $request->input('DeleteImageIDs');
            if (!empty($deleteIds)) {
                $ids = array_map('intval', explode(',', $deleteIds));
                $imagesToDelete = ServiceImage::whereIn('ImageID', $ids)->get();
                
                foreach ($imagesToDelete as $img) {
                    $url = $img->ImageUrl;
                    $path = str_replace(asset('storage/'), '', $url);
                    
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                    }
                    
                    $img->delete();
                }
            }

            // Đặt ảnh chính
            $mainImageId = $request->input('MainImageID');
            if (!empty($mainImageId)) {
                ServiceImage::where('ServiceID', $service->ServiceID)->update(['IsMain' => 0]);
                ServiceImage::where('ImageID', $mainImageId)->update(['IsMain' => 1]);
            }

            // Thêm ảnh mới
            $newFiles = $request->file('new_images', []);
            if (!empty($newFiles) && is_array($newFiles)) {
                foreach ($newFiles as $index => $file) {
                    if ($file && $file->isValid() && $file->getSize() > 0) {
                        $filename = time() . '_service_new_' . uniqid() . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                        $destinationPath = storage_path('app/public/services');
                        
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        
                        $file->move($destinationPath, $filename);
                        
                        ServiceImage::create([
                            'ServiceID' => $service->ServiceID,
                            'ImageUrl' => asset('storage/services/' . $filename),
                            'IsMain' => 0,
                        ]);
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
        // Xóa ảnh vật lý khi xóa dịch vụ
        foreach ($service->images as $img) {
            $path = str_replace(asset('storage/'), '', $img->ImageUrl);
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
        }
        
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Xóa dịch vụ thành công.');
    }

    // API: Lấy danh sách ảnh của dịch vụ
    public function getImages(Service $service)
    {
        $images = $service->images()->select(['ImageID', 'ImageUrl', 'IsMain'])->get();
        return response()->json(['images' => $images]);
    }
}
