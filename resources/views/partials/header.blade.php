<header class="h-20 bg-white/80 dark:bg-[#1a1a1a]/80 backdrop-blur-md sticky top-0 z-20 px-6 flex items-center justify-between border-b border-gray-200 dark:border-gray-800">
    <div class="flex items-center bg-[#F3F6F8] dark:bg-white/5 px-4 py-2.5 rounded-xl w-full max-w-[400px] border border-transparent focus-within:border-primary/50 transition-all">
        <span class="material-symbols-outlined text-gray-400 mr-2">search</span>
        <input class="bg-transparent border-none outline-none text-sm w-full placeholder-gray-400 text-gray-700 dark:text-gray-200 focus:ring-0 p-0" placeholder="Tìm kiếm dịch vụ, sản phẩm..." type="text"/>
    </div>
    <div class="flex items-center gap-3 ml-4">
        <div class="hidden sm:flex items-center gap-2 bg-[#F3F6F8] dark:bg-white/5 p-1.5 rounded-full px-3 mr-2">
            <span class="text-xs font-bold text-gray-500 mr-1 uppercase tracking-wider hidden xl:block">Giao diện</span>
            <button class="w-6 h-6 rounded-full bg-white border border-gray-300 shadow-sm hover:scale-125 transition-transform" onclick="setTheme('white')" title="Trắng"></button>
            <button class="w-6 h-6 rounded-full bg-black border border-gray-600 shadow-sm hover:scale-125 transition-transform" onclick="setTheme('dark')" title="Đen (Dark Mode)"></button>
            <button class="w-6 h-6 rounded-full bg-pink-100 border border-pink-300 shadow-sm hover:scale-125 transition-transform" onclick="setTheme('pink')" title="Hồng"></button>
            <button class="w-6 h-6 rounded-full bg-blue-100 border border-blue-300 shadow-sm hover:scale-125 transition-transform" onclick="setTheme('blue')" title="Xanh"></button>
        </div>
        <button class="w-10 h-10 rounded-full bg-white dark:bg-white/10 border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 hover:text-primary hover:shadow-lg hover:shadow-primary/20 transition-all relative">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
        </button>
        <div class="flex items-center gap-3 pl-2 cursor-pointer group">
            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary to-yellow-300 p-0.5">
                <img alt="User" class="w-full h-full rounded-full object-cover border-2 border-white dark:border-[#1a1a1a]" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDIjxQp5uNoVrRYYwZkSDLY1vr6MTjD5Cm3FLYRjcVOv3g6qJKC7j6wO11PwXHiseuku4NdVlWcNwcNwbArEgvZ6YFa8c2LEHqMuEjMjr9fkEj8g0rSqDjMPOtJzXgbjtIMI2Kr2d3feRXtm-puLpj4Mss4Z1QLIK6w8_QNO9SRVSzaW1e3_F253OvPjvzfqvASSxZNry-0XJbigZu7nMiuSAdJ_maTd3q_GlMIuIzc8DfPc_11GVE8zW-gzgPpTrcc5jRGYcP-QAQ"/>
            </div>
            <div class="hidden md:block leading-tight">
                <p class="text-sm font-bold">Minh Anh</p>
                <p class="text-xs text-gray-400">Thành viên Vàng</p>
            </div>
        </div>
    </div>
</header>
