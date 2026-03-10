@extends('layouts.shop')

@section('title', 'Thú cưng của tôi - Pink Charcoal')

@section('content')
<div class="w-full max-w-[900px] mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Thú cưng của tôi</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Quản lý thú cưng của bạn</p>
        </div>
        <a href="{{ route('pets.create') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-slate-900 font-bold py-2.5 px-5 rounded-2xl shadow-md transition-all text-sm">
            <span class="material-symbols-outlined text-[18px]">add</span> Thêm thú cưng
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 p-4 text-emerald-600 dark:text-emerald-400 mb-6 flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 p-4 text-red-600 dark:text-red-400 mb-6">
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($pets->isEmpty())
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-[2rem] p-12 text-center border border-white dark:border-slate-800 shadow-xl">
            <div class="mx-auto w-16 h-16 bg-primary/20 rounded-2xl flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-primary text-3xl">pets</span>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Chưa có thú cưng nào</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-6">Hãy thêm thú cưng đầu tiên của bạn!</p>
            <a href="{{ route('pets.create') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-slate-900 font-bold py-2.5 px-5 rounded-2xl shadow-md transition-all text-sm">
                <span class="material-symbols-outlined text-[18px]">add</span> Thêm thú cưng
            </a>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($pets as $pet)
                @php
                    $petImageUrl = null;
                    if ($pet->images && $pet->images->count() > 0) {
                        $mainImage = $pet->images->firstWhere('IsMain', 1);
                        if (!$mainImage) {
                            $mainImage = $pet->images->first();
                        }
                        if ($mainImage && $mainImage->ImageUrl) {
                            $petImageUrl = str_starts_with($mainImage->ImageUrl, 'http') ? $mainImage->ImageUrl : asset('storage/' . $mainImage->ImageUrl);
                        }
                    }
                @endphp
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-100 dark:border-slate-800 p-6 hover:border-primary/50 transition-all shadow-lg">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            @if($petImageUrl)
                                <img src="{{ $petImageUrl }}" alt="{{ $pet->PetName }}" class="w-12 h-12 rounded-xl object-cover">
                            @else
                                <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-2xl">pets</span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white">{{ $pet->PetName }}</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $pet->Species ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <a href="{{ route('pets.edit', $pet) }}" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-xl transition-colors" title="Sửa">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            <form action="{{ route('pets.destroy', $pet) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa thú cưng này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors" title="Xóa">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                        @if ($pet->Breed)
                            <div class="flex items-center gap-2"><span class="material-symbols-outlined text-base">pets</span> {{ $pet->Breed }}</div>
                        @endif
                        @if ($pet->Size)
                            <div class="flex items-center gap-2">Kích thước: {{ $pet->Size }}</div>
                        @endif
                        @if ($pet->Age !== null)
                            <div class="flex items-center gap-2">Tuổi: {{ $pet->Age }} năm</div>
                        @endif
                        @if ($pet->Notes)
                            <div class="pt-3 mt-3 border-t border-slate-100 dark:border-slate-700 text-xs text-slate-500 dark:text-slate-400">{{ $pet->Notes }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection

















