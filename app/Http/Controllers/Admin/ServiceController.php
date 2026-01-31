<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceImage;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

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
        $files = $this->extractValidFiles($rawFiles ?? []);
        if ($rawFiles && empty($files)) {
            return back()->withErrors(['Images' => 'Ảnh tải lên không hợp lệ hoặc không thể lưu.']);
        }

        $data = $request->except('Images');

        DB::transaction(function () use ($data, $files) {
            $service = Service::create($data);

            foreach ($files as $file) {
                try {
                    $path = $file->store('services', 'public');
                } catch (\Throwable $e) {
                    continue;
                }
                if (!is_string($path) || $path === '') {
                    continue;
                }
                ServiceImage::create([
                    'ServiceID' => $service->ServiceID,
                    'ImageUrl' => Storage::disk('public')->url($path),
                ]);
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
        $files = $this->extractValidFiles($rawFiles ?? []);
        if ($rawFiles && empty($files)) {
            return back()->withErrors(['Images' => 'Ảnh tải lên không hợp lệ hoặc không thể lưu.']);
        }

        $data = $request->except('Images');

        DB::transaction(function () use ($service, $data, $files) {
            $service->update($data);

            foreach ($files as $file) {
                try {
                    $path = $file->store('services', 'public');
                } catch (\Throwable $e) {
                    continue;
                }
                if (!is_string($path) || $path === '') {
                    continue;
                }
                ServiceImage::create([
                    'ServiceID' => $service->ServiceID,
                    'ImageUrl' => Storage::disk('public')->url($path),
                ]);
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

    private function extractValidFiles($files): array
    {
        if (!is_array($files)) {
            $files = [$files];
        }

        $valid = [];
        foreach ($files as $file) {
            if (!$file instanceof UploadedFile || !$file->isValid()) {
                continue;
            }
            if ($file->getError() !== UPLOAD_ERR_OK) {
                continue;
            }
            if ($file->getSize() <= 0) {
                continue;
            }
            $tmpPath = $file->getPathname();
            if (!is_string($tmpPath) || $tmpPath === '' || !is_file($tmpPath)) {
                continue;
            }
            $valid[] = $file;
        }

        return $valid;
    }
}
