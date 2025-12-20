"Бидний тухай" хуудасны тоон үзүүлэлтүүдийг (Stats) зүгээр нэг текст биш, **0-ээс эхлэн гүйж тоологддог (Counter Animation)** болгож өөрчиллөө.

Үүнийг хийхийн тулд HTML дээр `data-target` гэсэн утга оноож, доор нь багахан хэмжээний JavaScript бичсэн.

Энэ кодыг **`about.blade.php`** файлдаа бүтнээр нь хуулж тавиарай.

```html
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бидний тухай | PlayVision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                extend: {
                    colors: {
                        brand: '#00D4FF',
                        dark: '#0a0a0f', 
                        surface: '#121218', 
                    }
                }
            }
        }
    </script>
    <style>body { background-color: #0a0a0f; color: #e5e5e5; }</style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="fixed w-full z-50 top-0 bg-dark/80 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="text-2xl font-black italic text-white tracking-tighter">Play<span class="text-brand">Vision</span></a>
            <a href="/" class="text-sm font-bold text-gray-400 hover:text-white">БУЦАХ</a>
        </div>
    </nav>

    <main class="pt-32 pb-20 flex-grow">
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- HERO SECTION --}}
            <div class="text-center mb-20">
                <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight">
                    БИДНИЙ <span class="text-brand">ТУХАЙ</span>
                </h1>
                <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                    PlayVision бол Монголын геймерүүдэд зориулсан дижитал тоглоомын тэргүүлэгч платформ юм. Бид тоглоомын ертөнцийг танд ойртуулна.
                </p>
            </div>

            {{-- MISSION & VISION GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-24">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-brand to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative bg-surface rounded-2xl p-8 border border-white/10 h-full">
                        <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Бидний Эрхэм Зорилго</h3>
                        <p class="text-gray-400 leading-relaxed">
                            Бидний зорилго бол Монголын залууст дэлхийн шилдэг видео тоглоомуудыг албан ёсны эрхтэйгээр, хамгийн хямд үнээр, хурдан шуурхай хүргэх явдал юм. Бид И-Спорт болон гейминг соёлыг хөгжүүлэхэд хувь нэмрээ оруулахыг зорьдог.
                        </p>
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-green-400 to-brand rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative bg-surface rounded-2xl p-8 border border-white/10 h-full">
                        <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Яагаад PlayVision?</h3>
                        <ul class="space-y-3 text-gray-400">
                            <li class="flex items-center gap-3">
                                <span class="w-1.5 h-1.5 bg-brand rounded-full"></span> 100% Албан ёсны эрхтэй тоглоомууд
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-1.5 h-1.5 bg-brand rounded-full"></span> Шинэ болон Эрэлттэй тоглоомын сан
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-1.5 h-1.5 bg-brand rounded-full"></span> Төлбөрийн уян хатан шийдэл
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-1.5 h-1.5 bg-brand rounded-full"></span> 24/7 Хэрэглэгчийн дэмжлэг
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- STATS (COUNTER ANIMATION ADDED) --}}
            <div class="border-y border-white/10 py-12 mb-20" id="stats-section">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        {{-- 500+ --}}
                        <div class="text-4xl font-black text-white mb-2 flex justify-center items-baseline">
                            <span class="count-up" data-target="100">0</span>+
                        </div>
                        <div class="text-sm text-gray-100 uppercase tracking-widest font-bold">Games</div>
                    </div>
                    <div>
                        {{-- 10k+ --}}
                        <div class="text-4xl font-black text-brand mb-2 flex justify-center items-baseline">
                            <span class="count-up" data-target="10">0</span>k+
                        </div>
                        <div class="text-sm text-gray-500 uppercase tracking-widest font-bold">Gamers</div>
                    </div>
                    <div>
                        {{-- 24/7 --}}
                        <div class="text-4xl font-black text-white mb-2 flex justify-center items-baseline">
                            <span class="count-up" data-target="24">0</span>/7
                        </div>
                        <div class="text-sm text-gray-500 uppercase tracking-widest font-bold">Support</div>
                    </div>
                    <div>
                        {{-- 100% --}}
                        <div class="text-4xl font-black text-purple-500 mb-2 flex justify-center items-baseline">
                            <span class="count-up" data-target="100">0</span>%
                        </div>
                        <div class="text-sm text-gray-500 uppercase tracking-widest font-bold">Secure</div>
                    </div>
                </div>
            </div>

            {{-- CONTACT CTA --}}
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white mb-6">Хамтран ажиллах уу?</h2>
                <p class="text-gray-400 mb-8 max-w-xl mx-auto">Танд асуулт байна уу? Эсвэл манай платформ дээр тоглоомоо байршуулахыг хүсч байна уу? Бидэнтэй шууд холбогдоорой.</p>
                
                <a href="https://www.facebook.com/GILAKSAINZ" target="_blank" class="inline-flex items-center gap-3 bg-[#1877F2] text-white font-black uppercase tracking-wider px-8 py-4 rounded-xl hover:bg-[#166fe5] transition-all transform hover:scale-105 shadow-[0_0_20px_rgba(24,119,242,0.4)]">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Facebook-ээр холбогдох
                </a>
            </div>

        </div>
    </main>

    <footer class="border-t border-white/5 py-12 text-center bg-dark">
        <div class="text-2xl font-black italic text-gray-700 tracking-tighter mb-4">Play<span class="text-gray-800">Vision</span></div>
        <p class="text-gray-600 text-sm">&copy; 2025 PlayVision Entertainment. All Rights Reserved.</p>
    </footer>

    {{-- COUNTER ANIMATION SCRIPT --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const counters = document.querySelectorAll(".count-up");
            const speed = 200; // Тоолох хурд (Бага бол удаан)

            const startCounting = (counter) => {
                const updateCount = () => {
                    const target = +counter.getAttribute("data-target");
                    const count = +counter.innerText;
                    
                    // Тоолох алхам
                    const inc = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 20);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
            };

            // Scroll хийх үед тухайн хэсэгт ирмэгц тоолж эхэлнэ (Intersection Observer)
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        startCounting(counter);
                        observer.unobserve(counter); // Нэг удаа л тоолно
                    }
                });
            }, { threshold: 0.5 }); // 50% харагдах үед эхэлнэ

            counters.forEach(counter => {
                observer.observe(counter);
            });
        });
    </script>

</body>
</html>

```