@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Thú cưng của tôi</h1>
            <p class="text-sm text-gray-500">Quản lý thú cưng của bạn</p>
        </div>
        <a href="{{ route('pets.create') }}" class="btn btn-primary flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Thêm thú cưng
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($pets->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-gray-400 text-3xl">pets</span>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Chưa có thú cưng nào</h3>
            <p class="text-gray-500 mb-6">Hãy thêm thú cưng đầu tiên của bạn!</p>
            <a href="{{ route('pets.create') }}" class="btn btn-primary">
                Thêm thú cưng
            </a>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($pets as $pet)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">
                                    {{ $pet->Species === 'Chó' ? 'cruelty_free' : ($pet->Species === 'Mèo' ? 'pets' : 'pets') }}
                                </span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $pet->PetName }}</h3>
                                <p class="text-sm text-gray-500">{{ $pet->Species }}</p>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <a href="{{ route('pets.edit', $pet->PetID) }}" class="p-2 text-gray-400 hover:text-primary hover:bg-gray-50 rounded-lg transition-colors" title="Sửa">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('pets.destroy', $pet->PetID) }}" method="POST" data-confirm class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        @if ($pet->Breed)
                            <div class="flex items-center gap-2 text-gray-600">
                                <span class="material-symbols-outlined text-sm">breaking_bad</span>
                                <span>{{ $pet->Breed }}</span>
                            </div>
                        @endif
                        @if ($pet->Size)
                            <div class="flex items-center gap-2 text-gray-600">
                                <span class="material-symbols-outlined text-sm">straighten</span>
                                <span>Kích thước: {{ $pet->Size }}</span>
                            </div>
                        @endif
                        @if ($pet->Age)
                            <div class="flex items-center gap-2 text-gray-600">
                                <span class="material-symbols-outlined text-sm">cake</span>
                                <span>Tuổi: {{ $pet->Age }} năm</span>
                            </div>
                        @endif
                        @if ($pet->Notes)
                            <div class="flex items-start gap-2 text-gray-600 mt-3 pt-3 border-t border-gray-100">
                                <span class="material-symbols-outlined text-sm">notes</span>
                                <span class="text-xs">{{ $pet->Notes }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const confirmWithSwal = (title, text) => {
            return Swal.fire({
                title,
                text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#dc2626',
            });
        };

        document.querySelectorAll('form[data-confirm]').forEach(form => {
            form.addEventListener('submit', async event => {
                event.preventDefault();
                const result = await confirmWithSwal('Xóa thú cưng?', 'Thao tác này không thể hoàn tác.');
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection





