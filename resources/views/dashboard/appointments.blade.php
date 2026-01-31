<div class="max-w-7xl mx-auto space-y-8 pb-20 view-content hidden" id="view-appointments">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">Quản lý Lịch hẹn</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Theo dõi và quản lý lịch trình chăm sóc thú cưng của bạn</p>
        </div>
        <div class="flex gap-3">
            <button class="px-6 py-3 bg-white dark:bg-white/10 border border-gray-200 dark:border-gray-700 rounded-xl font-bold text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-white/20 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">filter_list</span> Lọc
            </button>
            <button class="px-6 py-3 bg-primary text-white rounded-xl font-bold shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-105 active:scale-95 transition-all duration-300 flex items-center gap-2 bounce-hover">
                <span class="material-symbols-outlined">add</span> Đặt lịch mới
            </button>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-2xl">calendar_month</span>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold">Tổng số lịch hẹn</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">24</p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-2xl">today</span>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold">Lịch hẹn hôm nay</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">03</p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-2xl">pending_actions</span>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold">Chờ xác nhận</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">01</p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-2xl">check_circle</span>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold">Hoàn thành</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">20</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                Lịch hẹn sắp tới
            </h3>
            <div class="bg-white dark:bg-[#1e1e1e] rounded-3xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group cursor-pointer">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="material-symbols-outlined text-base">schedule</span>
                            <span>Hôm nay, 14:00 - 15:30</span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Đã xác nhận</span>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/10 overflow-hidden flex-shrink-0">
                            <img alt="Pet" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA74wrZy6fNLXTqAf4MGX9Sd0k2hAdtYBu9EV1WgeO03w5zCA_BK6vTcJd9eWNIHwqN7GknKtwIHeo7U02XGUvcsDktXWJA9K1K3CT_zFAk17yJiEOyvJpPC08IhAm9z0OJqOinx92FsWhfxrnY_1UZSjz10Z5RGDoZ7emj1syG46J2JicpU5syHDd6563IgFelOIkub9QbEWM85eMpDNL0zJ5Js-JsjInwRbZYoKYo3fp_GTL3njPyd59sHXfYK9ExMpMfIBRq9-Y"/>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors">Spa &amp; Grooming trọn gói</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Bé Mochi (Corgi)</span>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span class="text-sm text-gray-500">Gói cao cấp</span>
                            </div>
                        </div>
                        <button class="w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 hover:text-primary hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                    </div>
                </div>
                <div class="p-6 border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group cursor-pointer">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="material-symbols-outlined text-base">schedule</span>
                            <span>Ngày mai, 09:00 - 10:00</span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">Chờ xác nhận</span>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/10 overflow-hidden flex-shrink-0">
                            <img alt="Pet" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA--Re6Aa1Td8DUTc2qfRmfXEDAoomI1huxJ1NEmkZhDUMFgN2lJ_M121JTDTTlXfipqKq7l3x4V9FdlHIP0aLf7be30lx62ORk21hQe-A5k9iRU-Ljpmi4wGB5SelFHMluJS-97j8vOWh_a7Hq2MbQ-WOzVxZrW1Qlz0nZ3nxB4gYnUbV-p2cUvCKa7MpRwSH6Cs2TJiD0Kxh2HqQXs736G8uetCGGa--wjg01PXdQMd3Z8c4n2cAGbd9VgjUa-FtgQU1oQPkiEx8"/>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors">Khám sức khỏe định kỳ</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Bé Lu (Golden)</span>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span class="text-sm text-gray-500">BS. Nguyễn Văn A</span>
                            </div>
                        </div>
                        <button class="w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 hover:text-primary hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                    </div>
                </div>
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group cursor-pointer">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="material-symbols-outlined text-base">schedule</span>
                            <span>15/10/2023, 16:30</span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Đã xác nhận</span>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/10 overflow-hidden flex-shrink-0">
                            <img alt="Pet" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAxbdVcL3PRlIXobDDRB7iTunXtuKqu-MWSnYkFYVRj5q5TVNcJlDKa8kWLNj6biJY2pxTDO5Erxgm0BR8ecYcrLDvEs1oHTXWtpRVBSAGFSvD0shgPZzZeGmhd-6we_vk92wQPhdcXPlqiXVjwf9vzDlIUMzJ5HMzyQfAcIA46h7qAHoVDI-H6aqnslqVdHAL42BNOyQTYzRNNAbS_-15LYoH9gUWe_90Dpf1Lx0MWhEITlFo5OGJdwiTdUH9Jy3bgF23gBvJwtU0"/>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors">Buổi huấn luyện cơ bản</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Bé Ken (Poodle)</span>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span class="text-sm text-gray-500">HLV. Trần B</span>
                            </div>
                        </div>
                        <button class="w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 hover:text-primary hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-y-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="w-1.5 h-6 bg-secondary rounded-full"></span>
                Lịch tháng 10
            </h3>
            <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="font-bold text-lg dark:text-white">Tháng 10, 2023</h4>
                    <div class="flex gap-2">
                        <button class="w-8 h-8 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </button>
                        <button class="w-8 h-8 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-7 text-center text-xs font-semibold text-gray-400 mb-4">
                    <div>T2</div>
                    <div>T3</div>
                    <div>T4</div>
                    <div>T5</div>
                    <div>T6</div>
                    <div>T7</div>
                    <div>CN</div>
                </div>
                <div class="grid grid-cols-7 gap-y-4 gap-x-2 text-center text-sm">
                    <div class="text-gray-300 py-1"></div>
                    <div class="text-gray-300 py-1"></div>
                    <div class="text-gray-300 py-1">1</div>
                    <div class="text-gray-300 py-1">2</div>
                    <div class="text-gray-300 py-1">3</div>
                    <div class="text-gray-300 py-1">4</div>
                    <div class="text-gray-300 py-1">5</div>
                    <div class="text-gray-900 dark:text-white py-1 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg">6</div>
                    <div class="text-gray-900 dark:text-white py-1 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg">7</div>
                    <div class="text-gray-900 dark:text-white py-1 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg">8</div>
                    <div class="bg-primary text-white rounded-lg shadow-lg shadow-primary/30 py-1 font-bold cursor-pointer">9</div>
                    <div class="text-gray-900 dark:text-white py-1 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg relative">10
                        <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-500 rounded-full"></span>
                    </div>
                    <div class="text-gray-900 dark:text-white py-1 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg">11</div>
                    <div class="text-gray-900 dark:text-white py-1 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg">12</div>
                    <div class="text-gray-900 dark:text-white py-1 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg">13</div>
                    <div class="text-gray-900 dark:text-white py-1 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg">14</div>
                    <div class="text-gray-900 dark:text-white py-1 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg relative">15
                        <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-green-500 rounded-full"></span>
                    </div>
                </div>
            </div>
            <div class="bg-blue-50 dark:bg-blue-900/10 p-6 rounded-3xl border border-blue-100 dark:border-blue-900/20">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">notifications_active</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">Nhắc nhở</h4>
                        <p class="text-xs text-gray-500">Đừng quên lịch hẹn nhé!</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                    Bạn có một lịch hẹn tiêm phòng cho <span class="font-bold">Bé Lu</span> vào ngày mai lúc 09:00 sáng.
                </p>
                <button class="w-full py-2 bg-blue-500 text-white rounded-xl text-sm font-bold hover:bg-blue-600 transition-colors">Xem chi tiết</button>
            </div>
        </div>
    </div>
</div>
