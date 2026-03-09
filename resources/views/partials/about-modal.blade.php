{{-- About Modal --}}
<div id="aboutModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Overlay --}}
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('aboutModal').classList.add('hidden')"></div>
    
    {{-- Modal Panel --}}
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0 pt-24">
            <div class="relative transform overflow-visible rounded-b-3xl rounded-t-[3rem] sm:rounded-t-[4rem] bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border-4 border-primary/20">
                
                {{-- Paw Toes Decor (Ngón chân mèo) --}}
                <div class="absolute -top-16 sm:-top-20 left-1/2 transform -translate-x-1/2 flex items-end gap-3 sm:gap-6 pointer-events-none z-30">
                    <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary/40 dark:bg-primary/20 rounded-[50%] transform -rotate-[25deg] translate-y-4 sm:translate-y-6 backdrop-blur-sm border-[3px] border-white/40"></div>
                    <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary/40 dark:bg-primary/20 rounded-[50%] transform -rotate-[10deg] backdrop-blur-sm border-[3px] border-white/40"></div>
                    <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary/40 dark:bg-primary/20 rounded-[50%] transform rotate-[10deg] backdrop-blur-sm border-[3px] border-white/40"></div>
                    <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary/40 dark:bg-primary/20 rounded-[50%] transform rotate-[25deg] translate-y-4 sm:translate-y-6 backdrop-blur-sm border-[3px] border-white/40"></div>
                </div>

                {{-- Header --}}
                <div class="px-6 py-5 border-b border-primary/20 flex items-center justify-between bg-primary/10 rounded-t-[2.7rem] sm:rounded-t-[3.7rem]">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2" id="modal-title">
                        <span class="material-symbols-outlined text-primary">pets</span>
                        Giới thiệu về Shop Charcoal
                    </h3>
                    <button type="button" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300" onclick="document.getElementById('aboutModal').classList.add('hidden')">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                {{-- Body (Scrollable) --}}
                <div class="px-6 py-5 text-slate-600 dark:text-slate-400 text-sm leading-relaxed space-y-4 max-h-[60vh] overflow-y-auto">
                    <p class="font-medium text-slate-800 dark:text-slate-200 text-base">Nơi các boss được yêu chiều từ những điều nhỏ nhất</p>
                    
                    <p>Chào mừng bạn đến với Shop Charcoal – ngôi nhà chung dành cho mọi "sen" và các boss cưng đáng yêu tại Đồng Tháp cũng như khắp nơi! Với tên gọi lấy cảm hứng từ than hoạt tính – biểu tượng của sự tinh khiết, khử mùi và chăm sóc an toàn tuyệt đối, Charcoal không chỉ là một cửa hàng thú cưng thông thường, mà còn là người bạn đồng hành đáng tin cậy trên hành trình chăm sóc sức khỏe và hạnh phúc cho chó mèo, thỏ, hamster và mọi thú cưng nhỏ xinh trong gia đình bạn.</p>
                    
                    <p>Tại Charcoal, chúng tôi hiểu rằng mỗi bé cưng đều là một cá tính riêng biệt, xứng đáng được yêu thương và chăm sóc bằng những sản phẩm tốt nhất. Vì vậy, shop luôn ưu tiên nhập khẩu và chọn lọc kỹ lưỡng các dòng sản phẩm cao cấp: từ thức ăn hạt dinh dưỡng, thức ăn tươi, pate, sữa dành riêng cho từng giai đoạn tuổi và giống loài; đến tã lót than hoạt tính Charcoal, cát vệ sinh khử mùi siêu mạnh, dầu gội than tre, xịt khử mùi, đồ chơi an toàn, quần áo thời trang, phụ kiện và cả các dịch vụ tư vấn dinh dưỡng, chăm sóc sức khỏe tận tâm. Mọi sản phẩm đều được kiểm chứng về nguồn gốc, thành phần tự nhiên, an toàn 100% – giúp boss khỏe mạnh từ bên trong, sạch sẽ từ bên ngoài, và không còn lo mùi hôi khó chịu trong nhà.</p>
                    
                    <p>Đội ngũ Charcoal không chỉ bán hàng, mà còn là những "sen" thực thụ – luôn sẵn sàng lắng nghe câu chuyện về boss nhà bạn, chia sẻ kinh nghiệm nuôi dưỡng, gợi ý combo phù hợp với ngân sách và nhu cầu thực tế. Chúng tôi tin rằng, một chú cún vui vẻ tung tăng, một em mèo lười biếng cuộn tròn, hay một bé hamster năng động sẽ mang lại nguồn năng lượng tích cực và niềm hạnh phúc vô giá cho cả gia đình.</p>
                    
                    <p>Shop Charcoal – nơi than hoạt tính không chỉ khử mùi, mà còn khơi dậy tình yêu thương vô điều kiện dành cho thú cưng. Hãy ghé thăm chúng tôi để cùng nhau chăm sóc những người bạn bốn chân (hoặc ít chân hơn) của bạn nhé! Vì một ngôi nhà trọn vẹn niềm vui, bắt đầu từ việc yêu thương boss thật nhiều.</p>
                    
                    <p class="font-medium italic text-primary-dark">Cảm ơn bạn đã tin tưởng và đồng hành cùng Charcoal! 🐾✨</p>
                </div>
                
                {{-- Footer Info --}}
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 text-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <span class="material-symbols-outlined text-base text-primary">call</span>
                            <span class="font-medium">Hotline/Zalo:</span> 0367196252
                        </div>
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <span class="material-symbols-outlined text-base text-primary">location_on</span>
                            <span class="font-medium">Địa chỉ:</span> TP.HCM, Việt Nam
                        </div>
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <span class="material-symbols-outlined text-base text-primary">storefront</span>
                            <span class="font-medium">Fanpage/Shopee:</span> Shop Charcoal Thú Cưng
                        </div>
                    </div>
                    <button type="button" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 sm:mt-0 sm:w-auto transition-colors" onclick="document.getElementById('aboutModal').classList.add('hidden')">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
