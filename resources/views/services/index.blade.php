@extends('layouts.shop')

@section('content')

{{-- Hero Banner Dịch Vụ --}}
<section class="w-full bg-gradient-to-r from-primary/30 to-rose-100 dark:from-slate-800 dark:to-slate-900 rounded-[2.5rem] overflow-hidden shadow-lg relative group mb-12 border border-white/50 dark:border-slate-800/50">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-20"></div>
    <div class="flex flex-col md:flex-row items-center justify-between min-h-[340px] relative z-10">
        <div class="flex-1 px-8 py-12 md:p-16 flex flex-col gap-6 justify-center lg:pl-24">
            <h1 class="text-slate-900 dark:text-white text-4xl md:text-5xl font-extrabold font-display leading-tight tracking-tight drop-shadow-sm">
                Dịch Vụ Chăm Sóc <br/> <span class="text-primary-dark dark:text-primary">Thú Cưng Cao Cấp</span>
            </h1>
            <p class="text-slate-700 dark:text-slate-300 text-lg md:text-xl max-w-xl leading-relaxed font-medium">
                Trải nghiệm các dịch vụ spa, cắt tỉa lông, khám sức khỏe từ đội ngũ chuyên nghiệp, tận tâm của Pink Charcoal.
            </p>
        </div>
        <div class="flex-1 w-full md:w-5/12 h-full absolute md:relative right-0 bottom-0 opacity-10 md:opacity-100 flex justify-end">
            {{-- Hình ảnh minh họa dịch vụ --}}
            <div class="w-full h-full min-h-[300px] bg-gradient-to-l from-white/20 to-transparent absolute inset-0 rounded-r-[2.5rem] z-10 pointer-events-none"></div>
            <img alt="Pet spa services"
                 class="w-full h-full object-cover object-left rounded-r-[2.5rem]"
                 src="https://placehold.co/800x600/F4C2C3/ffffff?text=Spa+Relax"/>
        </div>
    </div>
</section>

{{-- Services Grid --}}
<div class="flex flex-col gap-8 w-full">
    <div class="flex items-center justify-between border-b-2 border-primary/20 pb-4">
        <h2 class="text-slate-900 dark:text-slate-100 text-3xl font-extrabold font-display leading-tight flex items-center gap-3">
            <span class="material-symbols-outlined text-primary text-4xl">volunteer_activism</span>
            Các Dịch Vụ Nổi Bật
        </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-2">
        @forelse($services as $service)
            <a href="{{ route('services.show', $service->ServiceID) }}" class="flex flex-col bg-white dark:bg-slate-900/80 rounded-[2rem] overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 border border-slate-100 dark:border-slate-800 group hover:-translate-y-2 relative">
                {{-- Floating Price Tag --}}
                <div class="absolute top-4 right-4 z-20 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md px-4 py-2 rounded-full shadow-lg border border-white/20 transform group-hover:scale-110 transition-transform">
                    <span class="text-lg font-extrabold text-primary">
                        {{ number_format($service->BasePrice, 0, ',', '.') }}đ
                    </span>
                </div>

                {{-- Image --}}
                @php
                    $serviceImgUrl = 'https://placehold.co/600x400/F4C2C3/ffffff?text=' . urlencode($service->ServiceName);
                    $servicePriceText = number_format($service->BasePrice, 0, ',', '.') . 'đ';
                @endphp
                <div class="aspect-[4/3] bg-slate-100 dark:bg-slate-800 relative overflow-hidden group/img cursor-pointer" onclick="event.preventDefault(); openLightbox('{{ $serviceImgUrl }}', '{{ addslashes($service->ServiceName) }}', '{{ $servicePriceText }}')">
                    <img alt="{{ $service->ServiceName }}"
                         class="w-full h-full object-cover transform group-hover/img:scale-110 transition-transform duration-700 ease-out"
                         src="{{ $serviceImgUrl }}"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60 group-hover/img:opacity-80 transition-opacity"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-opacity duration-300">
                        <span class="bg-white/90 text-slate-900 rounded-full p-3 shadow-[0_0_30px_rgba(244,194,195,0.6)] flex backdrop-blur-sm transform translate-y-4 group-hover/img:translate-y-0 transition-all duration-300">
                            <span class="material-symbols-outlined text-xl">zoom_in</span>
                        </span>
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-6 md:p-8 flex flex-col gap-4 flex-1 justify-between relative z-10 bg-white dark:bg-slate-900">
                    <div>
                        <h3 class="text-slate-900 dark:text-slate-100 font-bold font-display text-xl leading-snug mb-3 group-hover:text-primary transition-colors">
                            {{ $service->ServiceName }}
                        </h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm line-clamp-2 leading-relaxed font-medium">
                            {{ $service->Description ?? 'Không có mô tả chi tiết cho dịch vụ này.' }}
                        </p>
                    </div>
                    
                    <div class="flex items-center justify-between mt-2 pt-4 border-t border-dashed border-slate-200 dark:border-slate-800">
                        <div class="flex items-center gap-1 text-amber-500 text-sm">
                            <span class="material-symbols-outlined text-base fill-current">star</span>
                            <span class="material-symbols-outlined text-base fill-current">star</span>
                            <span class="material-symbols-outlined text-base fill-current">star</span>
                            <span class="material-symbols-outlined text-base fill-current">star</span>
                            <span class="material-symbols-outlined text-base fill-current">star</span>
                            <span class="text-slate-400 ml-1">(5.0)</span>
                        </div>
                        <span class="w-10 h-10 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full py-20 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-[3rem] bg-slate-50/50 dark:bg-slate-900/50">
                <div class="bg-primary/20 w-24 h-24 rounded-full flex items-center justify-center mb-6 shadow-inner">
                    <span class="material-symbols-outlined text-5xl text-primary drop-shadow-sm">spa</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-2 font-display">Chưa có dịch vụ nào</h3>
                <p class="text-slate-500 text-lg">Cửa hàng hiện chưa mở dịch vụ, vui lòng quay lại sau.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
