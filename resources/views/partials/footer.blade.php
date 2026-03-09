{{-- Footer --}}
<footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-12 mt-auto">
    <div class="max-w-[1440px] mx-auto px-10">
        <div class="flex flex-col md:flex-row justify-between items-center gap-8 border-b border-slate-100 dark:border-slate-800 pb-10 mb-8">
            {{-- Logo --}}
            <div class="flex flex-col items-center md:items-start gap-4">
                <img src="{{ asset('images/logo-pink-charcoal.png') }}" alt="Pink Charcoal" class="h-16 w-auto">
                <p class="text-slate-400 text-sm max-w-xs text-center md:text-left">
                    Pink Charcoal - Cửa hàng thú cưng cao cấp, nơi boss được yêu chiều từ những điều nhỏ nhất.
                </p>
            </div>

            {{-- Quick Links --}}
            <div class="flex gap-12 sm:gap-16">
                <div class="flex flex-col gap-4 text-center md:text-left">
                    <h5 class="font-bold text-slate-900 dark:text-white uppercase text-xs tracking-widest">Cửa hàng</h5>
                    <a href="{{ route('shop') }}" class="text-slate-400 hover:text-primary transition-colors text-sm">Sản phẩm</a>
                    <a href="{{ route('services.index') }}" class="text-slate-400 hover:text-primary transition-colors text-sm">Dịch vụ</a>
                    <a href="{{ route('about') }}" class="text-slate-400 hover:text-primary transition-colors text-sm">Về Charcoal</a>
                </div>
                <div class="flex flex-col gap-4 text-center md:text-left">
                    <h5 class="font-bold text-slate-900 dark:text-white uppercase text-xs tracking-widest">Hỗ trợ</h5>
                    <a href="#" class="text-slate-400 hover:text-primary transition-colors text-sm">Chính sách</a>
                    <a href="#" class="text-slate-400 hover:text-primary transition-colors text-sm">Vận chuyển</a>
                    <a href="#" class="text-slate-400 hover:text-primary transition-colors text-sm">Bảo mật</a>
                </div>
            </div>

            {{-- Social --}}
            <div class="flex flex-col items-center md:items-end gap-5">
                 <h5 class="font-bold text-slate-900 dark:text-white uppercase text-xs tracking-widest">Kết nối</h5>
                 <div class="flex gap-4">
                     <a href="#" class="h-10 w-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:bg-primary hover:text-white transition-all">
                         <span class="material-symbols-outlined">facebook</span>
                     </a>
                     <a href="#" class="h-10 w-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:bg-primary hover:text-white transition-all">
                         <span class="material-symbols-outlined">chat</span>
                     </a>
                 </div>
            </div>
        </div>
        
        <div class="text-center text-slate-400 text-xs">
            <p>© {{ date('Y') }} Pink Charcoal Pet Store. Designed with 💖 for all boss.</p>
        </div>
    </div>
</footer>
