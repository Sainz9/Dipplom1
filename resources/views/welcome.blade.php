<!DOCTYPE html>
<html lang="mn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayVision - Ultimate Gaming Store</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                extend: {
                    colors: {
                        brand: '#00D4FF',
                        dark: '#0f0f0f',
                        surface: '#18181b',
                        surfaceHighlight: '#27272a'
                    }
                }
            }
        }
    </script>

    <style>
        .swiper-button-next, .swiper-button-prev { color: white !important; width: 3rem !important; height: 3rem !important; background: rgba(0,0,0,0.5); border-radius: 50%; backdrop-filter: blur(4px); }
        .swiper-button-disabled { opacity: 0 !important; }
        .mobile-menu-overlay { transition: opacity 0.3s ease; }
        .mobile-menu-panel { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .hi { transition: color 0.2s ease; }
        .hi:hover { color: rgb(207, 205, 205); }
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #18181b; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #00D4FF; }
    </style>
</head>
<body class="bg-dark text-white antialiased selection:bg-brand selection:text-black">

    <nav class="fixed w-full z-50 top-0 bg-dark/90 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">

                <a href="/" class="text-2xl font-black tracking-tighter uppercase italic">
                    Play<span class="text-brand">Vision</span>
                </a>

                <div class="hidden md:flex items-center space-x-10">
                    <a href="#" class="text-sm font-bold hover:text-brand transition-colors">GAMES</a>
                    <a href="#" class="text-sm font-bold hover:text-brand transition-colors">NEWS</a>
                    <a href="#" class="text-sm font-bold hover:text-brand transition-colors">STORE</a>
                    <a href="#" class="text-sm font-bold hover:text-brand transition-colors">SUPPORT</a>

                    <div class="relative group z-50">
                        <input type="text" id="desktopSearchInput" placeholder="Search games..." class="bg-white/10 text-sm rounded-full py-2 px-4 pl-10 w-64 focus:outline-none focus:ring-2 focus:ring-brand focus:bg-black transition-all" autocomplete="off">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        
                        <div id="desktopSearchResults" class="absolute top-full left-0 w-80 mt-2 bg-surfaceHighlight border border-white/10 rounded-xl shadow-2xl overflow-hidden hidden transform origin-top transition-all">
                            <div id="desktopResultsContainer" class="max-h-80 overflow-y-auto custom-scroll"></div>
                            <div id="desktopNoResults" class="hidden p-4 text-center text-gray-400 text-sm">No games found.</div>
                        </div>
                    </div>
                    <a href="{{ route('login') }}" class="hi font-bold text-sm">Нэвтрэх</a>
                </div>

                <button id="menu-btn" class="md:hidden p-2 text-white hover:text-brand focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <div id="mobile-menu" class="fixed inset-0 z-[60] hidden">
        <div id="menu-overlay" class="absolute inset-0 bg-black/80 backdrop-blur-sm opacity-0 mobile-menu-overlay" onclick="toggleMenu()"></div>

        <div id="menu-panel" class="absolute right-0 top-0 h-full w-[85%] max-w-sm bg-surface border-l border-white/10 shadow-2xl transform translate-x-full mobile-menu-panel flex flex-col">
            <div class="flex justify-between items-center p-6 border-b border-white/5">
                <span class="text-xl font-bold text-white">Menu</span>
                <button id="menu-close-btn" class="text-gray-400 hover:text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 border-b border-white/5">
                <div class="relative group">
                    <input type="text" id="mobileSearchInput" placeholder="Search games..." class="w-full bg-white/10 text-sm rounded-full py-3 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-brand focus:bg-black transition-all text-white" autocomplete="off">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    
                    <div id="mobileSearchResults" class="absolute top-full left-0 w-full mt-2 bg-surfaceHighlight border border-white/10 rounded-xl shadow-2xl overflow-hidden hidden z-50">
                        <div id="mobileResultsContainer" class="max-h-60 overflow-y-auto custom-scroll"></div>
                        <div id="mobileNoResults" class="hidden p-4 text-center text-gray-400 text-sm">No games found.</div>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto py-6 px-6 flex flex-col gap-6">
                <a href="#" class="text-2xl font-bold text-white hover:text-brand transition-colors">GAMES</a>
                <a href="#" class="text-2xl font-bold text-white hover:text-brand transition-colors">NEWS</a>
                <a href="#" class="text-2xl font-bold text-white hover:text-brand transition-colors">STORE</a>
                <a href="#" class="text-2xl font-bold text-white hover:text-brand transition-colors">SUPPORT</a>
            </nav>

            <div class="p-6 border-t border-white/5 bg-black/20">
                <button class="w-full bg-brand text-black font-bold py-3 rounded uppercase tracking-wide hover:bg-white transition-colors">Sign In</button>
            </div>
        </div>
    </div>

    <main class="pt-16 md:pt-20">
        <section class="relative h-[500px] md:h-[700px] w-full group">
            <div class="swiper heroSwiper h-full w-full">
                <div class="swiper-wrapper">
                    <div class="swiper-slide relative">
                        <img src="https://images5.alphacoders.com/139/1397346.jpg" class="w-full h-full object-cover" alt="Banner">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/40 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6 md:p-20 max-w-3xl">
                            <span class="inline-block py-1 px-3 bg-brand text-black text-xs font-bold rounded mb-4 uppercase">New Release</span>
                            <h1 class="text-4xl md:text-7xl font-black uppercase leading-none mb-6">Anno 117<br>Pax Romana</h1>
                            <div class="flex gap-4">
                                <button class="bg-brand text-black px-8 py-3 font-bold rounded hover:bg-white transition-colors">BUY NOW</button>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide relative">
                        <img src="https://wallpapercat.com/w/full/0/e/f/885-3840x2160-desktop-4k-the-last-of-us-game-background.jpg" class="w-full h-full object-cover" alt="Banner">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/40 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6 md:p-20 max-w-3xl">
                            <span class="inline-block py-1 px-3 bg-red-600 text-white text-xs font-bold rounded mb-4 uppercase">Best Seller</span>
                            <h1 class="text-4xl md:text-7xl font-black uppercase leading-none mb-6">The Last of Us</h1>
                            <button class="bg-white text-black px-8 py-3 font-bold rounded hover:bg-brand transition-colors">PLAY NOW</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 md:py-20 relative overflow-hidden">
            <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-white">Featured Games</h2>
                        <p class="text-gray-400 text-sm mt-2">Top rated games curated for you</p>
                    </div>
                    <div class="flex gap-3">
                        <button class="game-prev w-10 h-10 md:w-12 md:h-12 border border-white/10 rounded-full flex items-center justify-center hover:bg-brand hover:text-black hover:border-brand transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                        <button class="game-next w-10 h-10 md:w-12 md:h-12 border border-white/10 rounded-full flex items-center justify-center hover:bg-brand hover:text-black hover:border-brand transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                    </div>
                </div>
                <div class="swiper gameSwiper !overflow-visible">
                    <div class="swiper-wrapper" id="game-slider-wrapper">
                        </div>
                </div>
            </div>
        </section>

        <footer class="bg-surface border-t border-white/5 pt-16 pb-8">
            <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-xs">
                &copy; 2025 PlayVision Entertainment. All Rights Reserved.
            </div>
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // ---------------------------------------------------------
            // 1. DATA FROM PHP
            // ---------------------------------------------------------
            const games = @json($games); 

            // ---------------------------------------------------------
            // 2. RENDER SLIDER (LINKED)
            // ---------------------------------------------------------
            const sliderWrapper = document.getElementById('game-slider-wrapper');
            games.forEach(game => {
                let priceHtml = '';
                if (game.tag === "COMING SOON" || game.price === 0) {
                    priceHtml = `<span class="text-white font-bold">Pre-Order</span>`;
                } else if (game.sale_price) {
                    priceHtml = `
                        <div class="flex flex-col text-right">
                            <span class="text-xs text-gray-500 line-through font-medium">$${game.price}</span>
                            <span class="text-white font-bold text-lg">$${game.sale_price}</span>
                        </div>`;
                } else {
                    priceHtml = `<span class="text-white font-bold text-lg">$${game.price}</span>`;
                }

                let badgeHtml = '';
                if(game.discount) badgeHtml = `<div class="absolute top-3 right-3 bg-brand text-black text-xs font-black px-2 py-1 rounded shadow-lg">${game.discount}</div>`;
                else if(game.tag) badgeHtml = `<div class="absolute top-3 right-3 bg-white text-black text-xs font-black px-2 py-1 rounded shadow-lg">${game.tag}</div>`;

                const slide = `
                    <div class="swiper-slide">
                        <div class="group relative aspect-[3/4] rounded-xl overflow-hidden cursor-pointer bg-surface border border-white/5 hover:border-brand/50 transition-all duration-300">
                            <img src="${game.img}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-90"></div>
                            ${badgeHtml}
                            <div class="absolute bottom-0 p-4 w-full translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-brand mb-1 block">${game.genre}</span>
                                <h3 class="font-bold text-lg leading-tight text-white mb-2 line-clamp-1">${game.title}</h3>
                                <div class="flex items-center justify-between border-t border-white/10 pt-3">
                                    <a href="/games/${game.id}" class="bg-white/10 hover:bg-brand hover:text-black text-white p-2 rounded-full transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    </a>
                                    ${priceHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                sliderWrapper.innerHTML += slide;
            });

            // ----------------------------------------------------
            // 3. UNIVERSAL SEARCH FUNCTION (Desktop & Mobile)
            // ----------------------------------------------------
            function setupSearch(inputId, resultsId, containerId, noResultsId) {
                const searchInput = document.getElementById(inputId);
                const searchResults = document.getElementById(resultsId);
                const resultsContainer = document.getElementById(containerId);
                const noResults = document.getElementById(noResultsId);

                searchInput.addEventListener('input', function(e) {
                    const query = e.target.value.toLowerCase();
                    if (query.length === 0) {
                        searchResults.classList.add('hidden');
                        return;
                    }

                    const filteredGames = games.filter(game => game.title.toLowerCase().includes(query));
                    resultsContainer.innerHTML = '';

                    if (filteredGames.length > 0) {
                        noResults.classList.add('hidden');
                        filteredGames.forEach(game => {
                            let priceDisplay = '';
                            if (game.sale_price) {
                                priceDisplay = `
                                    <div class="text-right">
                                        <span class="text-[10px] text-gray-500 line-through block leading-none">$${game.price}</span>
                                        <span class="text-sm text-brand font-bold">$${game.sale_price}</span>
                                    </div>`;
                            } else if (game.price === 0) {
                                priceDisplay = `<span class="text-sm text-white font-bold">Soon</span>`;
                            } else {
                                priceDisplay = `<span class="text-sm text-white font-bold">$${game.price}</span>`;
                            }

                            const item = `
                                <a href="/games/${game.id}" class="flex items-center justify-between p-3 hover:bg-white/10 rounded-lg cursor-pointer transition-colors border-b border-white/5 last:border-0 block">
                                    <div class="flex items-center gap-3">
                                        <img src="${game.img}" class="w-10 h-14 object-cover rounded bg-gray-800">
                                        <div>
                                            <h4 class="font-bold text-sm text-white hover:text-brand transition-colors line-clamp-1">${game.title}</h4>
                                            <span class="text-[10px] text-gray-400 uppercase">${game.genre}</span>
                                        </div>
                                    </div>
                                    ${priceDisplay}
                                </a>
                            `;
                            resultsContainer.innerHTML += item;
                        });
                    } else {
                        noResults.classList.remove('hidden');
                    }
                    searchResults.classList.remove('hidden');
                });

                // Close search on outside click
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                        searchResults.classList.add('hidden');
                    }
                });
            }

            // Setup Desktop Search
            setupSearch('desktopSearchInput', 'desktopSearchResults', 'desktopResultsContainer', 'desktopNoResults');
            
            // Setup Mobile Search
            setupSearch('mobileSearchInput', 'mobileSearchResults', 'mobileResultsContainer', 'mobileNoResults');


            // ----------------------------------------------------
            // 4. MOBILE MENU & SWIPER
            // ----------------------------------------------------
            const menuBtn = document.getElementById('menu-btn');
            const closeBtn = document.getElementById('menu-close-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuOverlay = document.getElementById('menu-overlay');
            const menuPanel = document.getElementById('menu-panel');

            function toggleMenu() {
                const isHidden = mobileMenu.classList.contains('hidden');
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    setTimeout(() => {
                        menuOverlay.classList.remove('opacity-0');
                        menuPanel.classList.remove('translate-x-full');
                    }, 10);
                    document.body.style.overflow = 'hidden';
                } else {
                    menuOverlay.classList.add('opacity-0');
                    menuPanel.classList.add('translate-x-full');
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                        document.body.style.overflow = '';
                    }, 300);
                }
            }

            if (menuBtn) menuBtn.addEventListener('click', toggleMenu);
            if (closeBtn) closeBtn.addEventListener('click', toggleMenu);
            if (menuOverlay) menuOverlay.addEventListener('click', toggleMenu);

            new Swiper('.heroSwiper', {
                loop: true, effect: 'fade', autoplay: { delay: 5000 },
                pagination: { el: '.swiper-pagination', clickable: true },
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            });

            new Swiper('.gameSwiper', {
                slidesPerView: 2, spaceBetween: 12, loop: true,
                navigation: { nextEl: '.game-next', prevEl: '.game-prev' },
                breakpoints: { 640: { slidesPerView: 3, spaceBetween: 16 }, 1024: { slidesPerView: 4, spaceBetween: 20 }, 1280: { slidesPerView: 6, spaceBetween: 24 } }
            });
        });
    </script>
</body>
</html>