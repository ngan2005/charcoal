@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Thêm thú cưng mới</h1>
            <p class="text-sm text-gray-500">Nhập thông tin thú cưng của bạn</p>
        </div>
        <a href="{{ route('pets.index') }}" class="btn btn-outline-secondary flex items-center gap-2">
            <span class="material-symbols-outlined">arrow_back</span>
            Quay lại
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('pets.store') }}">
            @csrf
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="form-label">Tên thú cưng *</label>
                    <input type="text" name="PetName" class="form-control" value="{{ old('PetName') }}" required placeholder="Ví dụ: Bobby">
                </div>

                <div>
                    <label class="form-label">Loài *</label>
                    <select name="Species" class="form-select" required>
                        <option value="">Chọn loài</option>
                        <option value="Chó" {{ old('Species') === 'Chó' ? 'selected' : '' }}>Chó</option>
                        <option value="Mèo" {{ old('Species') === 'Mèo' ? 'selected' : '' }}>Mèo</option>
                        <option value="Hamster" {{ old('Species') === 'Hamster' ? 'selected' : '' }}>Hamster</option>
                        <option value="Chim" {{ old('Species') === 'Chim' ? 'selected' : '' }}>Chim</option>
                        <option value="Cá" {{ old('Species') === 'Cá' ? 'selected' : '' }}>Cá</option>
                        <option value="Thỏ" {{ old('Species') === 'Thỏ' ? 'selected' : '' }}>Thỏ</option>
                        <option value="Khác" {{ old('Species') === 'Khác' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Giống</label>
                    <input type="text" name="Breed" class="form-control" value="{{ old('Breed') }}" placeholder="Ví dụ: Husky, Persian, Golden Retriever">
                </div>

                <div>
                    <label class="form-label">Kích thước</label>
                    <select name="Size" class="form-select">
                        <option value="">Chọn kích thước</option>
                        <option value="Nhỏ" {{ old('Size') === 'Nhỏ' ? 'selected' : '' }}>Nhỏ (dưới 5kg)</option>
                        <option value="Vừa" {{ old('Size') === 'Vừa' ? 'selected' : '' }}>Vừa (5-15kg)</option>
                        <option value="Lớn" {{ old('Size') === 'Lớn' ? 'selected' : '' }}>Lớn (trên 15kg)</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Tuổi (năm)</label>
                    <input type="number" name="Age" class="form-control" value="{{ old('Age') }}" min="0" placeholder="Ví dụ: 2">
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="Notes" class="form-control" rows="3" placeholder="Thông tin thêm về thú cưng...">{{ old('Notes') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('pets.index') }}" class="btn btn-outline-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary">Thêm thú cưng</button>
            </div>
        </form>
    </div>
</div>
@endsection





