{{-- Search Overlay --}}
<div x-show="showSearch" 
     x-cloak
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="-translate-y-full opacity-0"
     x-transition:enter-end="translate-y-0 opacity-100"
     x-transition:leave="transition ease-in duration-200 transform"
     x-transition:leave-start="translate-y-0 opacity-100"
     x-transition:leave-end="-translate-y-full opacity-0"
     class="fixed inset-x-0 top-0 z-[110] bg-white/95 dark:bg-slate-900/95 backdrop-blur-md shadow-2xl border-b-[6px] border-primary dark:border-primary/50 py-8 rounded-b-[4rem] overflow-visible">
    {{-- Cat Paw Toes (Ngón chân mèo hướng xuống) --}}
    <div class="absolute -bottom-8 left-0 right-0 flex items-start justify-center gap-2 sm:gap-4 pointer-events-none px-4">
        <div class="toe w-10 h-12 sm:w-14 sm:h-16 bg-primary/90 dark:bg-primary/70 rounded-[50%] transform -rotate-[15deg] shadow-lg border-4 border-white dark:border-slate-900"></div>
        <div class="toe w-12 h-14 sm:w-16 sm:h-20 bg-primary dark:bg-primary/80 rounded-[50%] shadow-xl border-4 border-white dark:border-slate-900"></div>
        <div class="toe w-12 h-14 sm:w-16 sm:h-20 bg-primary dark:bg-primary/80 rounded-[50%] shadow-xl border-4 border-white dark:border-slate-900"></div>
        <div class="toe w-10 h-12 sm:w-14 sm:h-16 bg-primary/90 dark:bg-primary/70 rounded-[50%] transform rotate-[15deg] shadow-lg border-4 border-white dark:border-slate-900"></div>
    </div>
    <div class="max-w-3xl mx-auto px-6 relative">
        {{-- Close Button --}}
        <button type="button" @click="showSearch = false" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-full transition-all">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>

        <form action="{{ route('shop') }}" method="GET" class="relative group mt-2">
            <div class="relative">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">
                    <span class="material-symbols-outlined text-2xl">search</span>
                </span>
                <input type="text" 
                       name="search" 
                       placeholder="Tìm kiếm sản phẩm cho thú cưng..." 
                       autofocus
                       class="w-full bg-slate-50 dark:bg-slate-800/50 border-2 border-slate-100 dark:border-slate-700 focus:border-primary rounded-full px-14 py-4 text-base font-medium outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 shadow-inner">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 bg-primary hover:bg-primary-dark text-slate-900 rounded-full px-6 py-2 text-sm font-bold transition-all hover:scale-105 active:scale-95 shadow-md">
                    TÌM KIẾM
                </button>
            </div>
        </form>
        
        {{-- Suggestion Keywords --}}
        <div class="flex flex-wrap justify-center items-center gap-2 mt-3 text-xs">
            <span class="text-slate-400 font-medium mr-1">Gợi ý:</span>
            <a href="{{ route('shop', ['search' => 'Thức ăn']) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-primary/20 hover:text-primary dark:hover:text-primary rounded-full transition-colors">Thức ăn</a>
            <a href="{{ route('shop', ['search' => 'Bánh thưởng']) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-primary/20 hover:text-primary dark:hover:text-primary rounded-full transition-colors">Bánh thưởng</a>
            <a href="{{ route('shop', ['search' => 'Cát mèo']) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-primary/20 hover:text-primary dark:hover:text-primary rounded-full transition-colors">Cát mèo</a>
            <a href="{{ route('shop', ['search' => 'Đồ chơi']) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-primary/20 hover:text-primary dark:hover:text-primary rounded-full transition-colors">Đồ chơi</a>
            <a href="{{ route('shop', ['search' => 'Phụ kiện']) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-primary/20 hover:text-primary dark:hover:text-primary rounded-full transition-colors">Phụ kiện</a>
        </div>
    </div>
</div>

{{-- Backdrop for Search --}}
<div x-show="showSearch" 
     x-cloak
     x-transition.opacity
     @click="showSearch = false"
     class="fixed inset-0 z-[90] bg-slate-900/40 backdrop-blur-sm"></div>
