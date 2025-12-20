<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <title>Төлбөр шалгаж байна</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#050507] text-white h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-[#0f0f13] border border-white/10 rounded-3xl p-8 text-center">
        
        <div class="w-20 h-20 bg-yellow-500/10 rounded-full flex items-center justify-center mx-auto mb-6 text-yellow-500 animate-pulse">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>

        <h1 class="text-xl font-bold mb-2">Төлбөр шалгаж байна...</h1>
        <p class="text-sm text-gray-400 mb-6 leading-relaxed">
            Таны төлбөрийн мэдээллийг хүлээн авлаа. <br>
            Админ дансны хуулгыг шалгасны дараа (1-10 минут) таны тоглоом автоматаар идэвхжих болно.
        </p>

        <a href="{{ url('/dashboard') }}" class="block w-full bg-[#00D4FF] text-black font-bold py-3 rounded-xl hover:bg-white transition">
            Миний сан руу очих
        </a>
    </div>
</body>
</html>