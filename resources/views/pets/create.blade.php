@extends('layouts.shop')

@section('title', 'Thêm thú cưng - Pink Charcoal')

@section('content')
<div class="w-full max-w-[800px] mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Thêm thú cưng mới</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Nhập thông tin thú cưng của bạn</p>
        </div>
        <a href="{{ route('profile.index') }}#profileTab" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-sm font-medium">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Quay lại
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 p-4 text-red-600 dark:text-red-400 mb-6">
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-[2rem] p-6 md:p-8 shadow-xl border border-white dark:border-slate-800">
        <form method="POST" action="{{ route('pets.store') }}" enctype="multipart/form-data" class="flex flex-col gap-5">
            @csrf
            <div class="grid gap-4 md:grid-cols-2">
                <div class="flex flex-col gap-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tên thú cưng *</label>
                    <input type="text" name="PetName" value="{{ old('PetName') }}" required placeholder="Ví dụ: Bobby" class="w-full px-4 py-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none text-sm">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Loài *</label>
                    <select name="Species" required class="w-full px-4 py-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none text-sm">
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
                <div class="flex flex-col gap-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Giống</label>
                    <input type="text" name="Breed" value="{{ old('Breed') }}" placeholder="Ví dụ: Husky, Persian" class="w-full px-4 py-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none text-sm">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Kích thước</label>
                    <select name="Size" class="w-full px-4 py-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none text-sm">
                        <option value="">Chọn kích thước</option>
                        <option value="Nhỏ" {{ old('Size') === 'Nhỏ' ? 'selected' : '' }}>Nhỏ (dưới 5kg)</option>
                        <option value="Vừa" {{ old('Size') === 'Vừa' ? 'selected' : '' }}>Vừa (5-15kg)</option>
                        <option value="Lớn" {{ old('Size') === 'Lớn' ? 'selected' : '' }}>Lớn (trên 15kg)</option>
                    </select>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tuổi (năm)</label>
                    <input type="number" name="Age" value="{{ old('Age') }}" min="0" placeholder="Ví dụ: 2" class="w-full px-4 py-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none text-sm">
                </div>
                <div class="md:col-span-2 flex flex-col gap-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Ghi chú</label>
                    <textarea name="Notes" rows="3" placeholder="Thông tin thêm về thú cưng..." class="w-full px-4 py-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none text-sm resize-none">{{ old('Notes') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Ảnh thú cưng</label>
                    <div class="mt-2 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl p-6 text-center hover:border-primary/50 transition-colors cursor-pointer relative" id="dropZone">
                        <input type="file" name="images[]" id="petImages" class="hidden" accept="image/*" multiple onchange="previewPetImages(event)">
                        <label for="petImages" class="cursor-pointer flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">add_photo_alternate</span>
                            <span class="text-sm text-slate-500 dark:text-slate-400">Nhấp để chọn hoặc kéo thả ảnh vào đây</span>
                            <span class="text-xs text-slate-400">JPG, PNG, GIF, WEBP. Tối đa 5MB mỗi ảnh</span>
                        </label>
                    </div>
                    <div id="imagePreviewContainer" class="grid grid-cols-3 sm:grid-cols-4 gap-3 mt-4"></div>
                </div>
            </div>
            <div class="flex flex-wrap gap-3 pt-2">
                <a href="{{ route('profile.index') }}#profileTab" class="px-5 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-sm font-medium">Hủy</a>
                <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary-dark text-slate-900 font-bold rounded-2xl shadow-lg shadow-primary/20 transition-all text-sm">Thêm thú cưng 🐾</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
function previewPetImages(event) {
    const container = document.getElementById('imagePreviewContainer');
    const files = event.target.files;
    
    // Clear existing previews
    container.innerHTML = '';
    
    if (files.length > 0) {
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute top-1 right-1">
                            <span class="text-[10px] font-bold bg-primary text-slate-900 px-1.5 py-0.5 rounded-full">${index === 0 ? 'Chính' : ''}</span>
                        </div>
                    `;
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    }
}
</script>
@endpush
@endsection

















