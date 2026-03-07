@extends('layouts.shop')

@section('content')

{{-- Hero Banner --}}
<section class="w-full bg-[#fce8e8] dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm relative group">
    <div class="flex flex-col md:flex-row items-center justify-between min-h-[400px]">
        <div class="flex-1 px-10 py-12 md:py-20 flex flex-col gap-6 justify-center z-10 pl-20">
            <h1 class="text-slate-900 dark:text-white text-4xl md:text-5xl font-bold leading-tight tracking-tight">
                Ưu Đãi Đặc Biệt Cho Thú Cưng
            </h1>
            <p class="text-slate-700 dark:text-slate-300 text-lg md:text-xl max-w-xl leading-relaxed">
                Chăm sóc những người bạn nhỏ của bạn với những sản phẩm chất lượng, được tuyển chọn kỹ lưỡng từ Pink Charcoal.
            </p>
            <div>
                <button onclick="document.getElementById('products').scrollIntoView({behavior:'smooth'})"
                        class="bg-primary hover:bg-primary-dark text-slate-900 font-bold py-3 px-8 rounded-full shadow-md transition-all transform hover:scale-105">
                    Khám Phá Ngay
                </button>
            </div>
        </div>
        <div class="flex-1 w-full md:w-1/2 h-full absolute md:relative right-0 bottom-0 opacity-20 md:opacity-100">
            <img alt="Happy pets"
                 class="w-full h-full object-cover object-center rounded-r-2xl"
                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuB4StMqvJzf90V40rQ7H3vWq5wtrcKsiAE3kKU0oU8eGrmPIucbOmpesphgPQW55d4zIXQamAOx5Hqt1BI4jb18LwlD5gUbHqenXLJ4jJ2Yhj6iLC2x08GqOlbLGyoIHJtSYdBPLl55t2zbb6y4FaPQxi6moZNoEluCSRodV9fHK66mMD99H7Si8Ror0W3hyKTTpU9KERFmrmkfmpR3U_J18_rgn0nwLmM9EG4Q7-_XUY1IRHaKhKnYv2buSE4D1NBO45tqts74Cg"/>
        </div>
    </div>
</section>

{{-- Main Content: Filter + Products --}}
<div class="flex gap-8">
    {{-- Filter Sidebar --}}
    <aside class="w-64 shrink-0 flex-col gap-6 hidden lg:flex">
        <div class="flex flex-col gap-2">
            <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold">Lọc Sản Phẩm</h3>
        </div>

        {{-- Filter by Category --}}
        <details class="flex flex-col rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-[15px] py-[7px] group" open>
            <summary class="flex cursor-pointer items-center justify-between gap-6 py-2 list-none">
                <p class="text-slate-900 dark:text-slate-100 text-sm font-medium leading-normal">Theo danh mục</p>
                <span class="material-symbols-outlined text-slate-500 group-open:rotate-180 transition-transform">expand_more</span>
            </summary>
            <div class="flex flex-col gap-3 pb-3 pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 category-filter"
                           type="checkbox" value="" {{ !request('category') ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">Tất cả</span>
                </label>
                @foreach($categories as $category)
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 category-filter"
                           type="checkbox" value="{{ $category->CategoryID }}"
                           {{ in_array($category->CategoryID, explode(',', request('category', ''))) ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">{{ $category->CategoryName }}</span>
                </label>
                @endforeach
            </div>
        </details>

        {{-- Filter by Price --}}
        <details class="flex flex-col rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-[15px] py-[7px] group">
            <summary class="flex cursor-pointer items-center justify-between gap-6 py-2 list-none">
                <p class="text-slate-900 dark:text-slate-100 text-sm font-medium leading-normal">Theo giá</p>
                <span class="material-symbols-outlined text-slate-500 group-open:rotate-180 transition-transform">expand_more</span>
            </summary>
            <div class="flex flex-col gap-3 pb-3 pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 price-filter"
                           type="radio" name="price" value="all" {{ !request('price') || request('price') === 'all' ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">Tất cả</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 price-filter"
                           type="radio" name="price" value="0-100000" {{ request('price') === '0-100000' ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">Dưới 100k</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 price-filter"
                           type="radio" name="price" value="100000-500000" {{ request('price') === '100000-500000' ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">100k - 500k</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 price-filter"
                           type="radio" name="price" value="500000-9999999" {{ request('price') === '500000-9999999' ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">Trên 500k</span>
                </label>
            </div>
        </details>
    </aside>

    {{-- Products Section --}}
    <section class="flex-1 flex flex-col gap-6" id="products">
        {{-- Header & Sort --}}
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h1 class="text-slate-900 dark:text-slate-100 text-3xl font-bold leading-tight">Sản phẩm cho thú cưng</h1>
            <label class="flex items-center gap-3">
                <span class="text-slate-600 dark:text-slate-400 text-sm font-medium whitespace-nowrap">Sắp xếp theo:</span>
                <form action="{{ route('shop') }}" method="GET">
                    @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('price'))
                    <input type="hidden" name="price" value="{{ request('price') }}">
                    @endif
                    <select name="sort" onchange="this.form.submit()"
                            class="form-select rounded-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:border-primary focus:ring-primary text-sm py-2 pl-4 pr-10 cursor-pointer">
                        <option value="newest"    {{ request('sort') == 'newest'    ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến cao</option>
                        <option value="price_desc"{{ request('sort') == 'price_desc'? 'selected' : '' }}>Giá: Cao đến thấp</option>
                        <option value="popular"   {{ request('sort') == 'popular'   ? 'selected' : '' }}>Phổ biến nhất</option>
                    </select>
                </form>
            </label>
        </div>

        {{-- Mobile category filter --}}
        <div class="lg:hidden flex gap-2 overflow-x-auto py-2 no-scrollbar">
            <button class="category-btn flex-shrink-0 px-4 py-2 rounded-full bg-primary text-slate-900 text-sm font-medium" data-category="">Tất cả</button>
            @foreach($categories as $category)
            <button class="category-btn flex-shrink-0 px-4 py-2 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-primary/20"
                    data-category="{{ $category->CategoryID }}">{{ $category->CategoryName }}</button>
            @endforeach
        </div>

        {{-- Products Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                @php
                    $mainImage = $product->images->where('IsMain', 1)->first();
                    $firstImage = $product->images->first();
                    $imageUrl = $mainImage ? $mainImage->ImageUrl : ($firstImage ? $firstImage->ImageUrl : '');
                @endphp
                <div class="flex flex-col bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 dark:border-slate-800">
                    {{-- Image --}}
                    <div class="aspect-square bg-slate-100 dark:bg-slate-800 relative group">
                        @if($imageUrl)
                            <img alt="{{ $product->ProductName }}"
                                 class="w-full h-full object-cover"
                                 src="{{ str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl) }}"
                                 onerror="this.src='https://placehold.co/400x400/F4C2C3/ffffff?text={{ urlencode($product->ProductName) }}'"/>
                        @else
                            <img alt="{{ $product->ProductName }}"
                                 class="w-full h-full object-cover"
                                 src="https://placehold.co/400x400/F4C2C3/ffffff?text={{ urlencode($product->ProductName) }}"/>
                        @endif

                        {{-- Badge --}}
                        @if($product->PurchaseCount > 100)
                            <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">HOT</div>
                        @elseif($product->created_at && $product->created_at->diffInDays() < 7)
                            <div class="absolute top-3 left-3 bg-primary text-slate-900 text-xs font-bold px-2 py-1 rounded-full">MỚI</div>
                        @endif

                        {{-- Hover --}}
                        @php
                            $fullImgUrl = $imageUrl ? (str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl)) : 'https://placehold.co/800x800/F4C2C3/ffffff?text=' . urlencode($product->ProductName);
                            $priceText = number_format($product->Price, 0, ',', '.') . 'đ';
                        @endphp
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer" onclick="openLightbox('{{ $fullImgUrl }}', '{{ addslashes($product->ProductName) }}', '{{ $priceText }}')">
                            <button type="button" class="bg-white text-slate-900 rounded-full p-3 transform translate-y-4 group-hover:translate-y-0 transition-all hover:bg-primary pointer-events-none">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="p-4 flex flex-col gap-3 flex-1">
                        <div class="flex-1">
                            <span class="text-xs font-semibold text-primary uppercase tracking-wider">
                                {{ $product->category ? $product->category->CategoryName : 'Chưa phân loại' }}
                            </span>
                            <h3 class="text-slate-900 dark:text-slate-100 font-medium text-base leading-snug mt-1 line-clamp-2">
                                {{ $product->ProductName }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-between mt-auto pt-3 border-t border-slate-100 dark:border-slate-800">
                            <span class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                {{ number_format($product->Price, 0, ',', '.') }}đ
                            </span>
                            <button class="flex items-center justify-center bg-primary hover:bg-primary-dark text-slate-900 rounded-full p-2 transition-colors" title="Thêm vào giỏ">
                                <span class="material-symbols-outlined text-xl">add_shopping_cart</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="bg-primary/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-4xl text-primary">shopping_bag</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Chưa có sản phẩm nào</h3>
                    <p class="text-slate-500">Hãy quay lại sau khi admin thêm sản phẩm nhé!</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="flex justify-center mt-8">
            <nav class="flex items-center gap-2">
                @if($products->onFirstPage())
                <button class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-500" disabled>
                    <span class="material-symbols-outlined text-sm">chevron_left</span>
                </button>
                @else
                <a href="{{ $products->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <span class="material-symbols-outlined text-sm">chevron_left</span>
                </a>
                @endif

                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if($page == $products->currentPage())
                    <button class="w-10 h-10 flex items-center justify-center rounded-full bg-primary text-slate-900 font-medium">{{ $page }}</button>
                    @else
                    <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors font-medium">{{ $page }}</a>
                    @endif
                @endforeach

                @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
                @else
                <button class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-500" disabled>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </button>
                @endif
            </nav>
        </div>
        @endif
    </section>
</div>

@push('scripts')
<script>
    // Mobile category filter
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.category-btn').forEach(b => {
                b.classList.remove('bg-primary', 'text-slate-900');
                b.classList.add('bg-slate-100', 'text-slate-700');
            });
            this.classList.remove('bg-slate-100', 'text-slate-700');
            this.classList.add('bg-primary', 'text-slate-900');
        });
    });

    // Sidebar filter → apply on change
    function applyFilters() {
        const params = new URLSearchParams(window.location.search);

        const selectedCategories = Array.from(document.querySelectorAll('.category-filter:checked'))
            .map(cb => cb.value).filter(v => v);
        if (selectedCategories.length > 0) {
            params.set('category', selectedCategories.join(','));
        } else {
            params.delete('category');
        }

        const selectedPrice = document.querySelector('.price-filter:checked')?.value;
        if (selectedPrice && selectedPrice !== 'all') {
            params.set('price', selectedPrice);
        } else {
            params.delete('price');
        }

        // Keep sort & search
        window.location.href = '{{ route("shop") }}?' + params.toString();
    }

    document.querySelectorAll('.category-filter, .price-filter').forEach(input => {
        input.addEventListener('change', applyFilters);
    });
</script>
@endpush

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@endsection
