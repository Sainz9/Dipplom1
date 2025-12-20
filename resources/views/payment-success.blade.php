<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <title>Төлбөр амжилттай</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#050507] text-white h-screen flex items-center justify-center">

    <div class="text-center bg-[#0f0f13] p-10 rounded-2xl border border-white/10 shadow-2xl max-w-md w-full">
        <div class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-6 text-green-500">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <h1 class="text-2xl font-bold mb-2">Төлбөр амжилттай!</h1>
        <p class="text-gray-400 mb-8 text-sm">Таны захиалга баталгаажлаа. Доорх товч дээр дарж тоглоомоо татна уу.</p>

        @if(session('game_id'))
            <a href="{{ route('game.download', session('game_id')) }}" class="block w-full bg-[#00D4FF] text-black font-bold py-4 rounded-xl uppercase tracking-widest hover:bg-white transition-colors mb-4">
                📥 Тоглоом татах
            </a>
        @else
            <a href="/" class="block w-full bg-white/10 text-white font-bold py-4 rounded-xl hover:bg-white/20 transition-colors mb-4">
                Нүүр хуудас
            </a>
        @endif
        
        <a href="/" class="text-gray-500 text-xs hover:text-white transition-colors">Нүүр хуудас руу буцах</a>
    </div>

</body>
</html>