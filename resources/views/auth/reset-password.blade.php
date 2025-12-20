<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Шинэ нууц үг тохируулах | PlayVision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0a0a0f; color: #e5e5e5; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-[#121218] border border-white/10 rounded-3xl p-8 shadow-2xl">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-white">Шинэ нууц үг</h2>
            <p class="text-gray-400 text-sm mt-2">Та шинэ нууц үгээ оруулаад баталгаажуулна уу.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase ml-1">Имэйл</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" required readonly
                    class="w-full bg-black/20 border border-white/5 text-gray-400 text-sm rounded-xl p-3 focus:outline-none cursor-not-allowed">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-400 uppercase ml-1">Шинэ нууц үг</label>
                <input type="password" name="password" required autofocus
                    class="w-full bg-black/40 border border-white/10 text-white text-sm rounded-xl p-3 focus:border-cyan-500 focus:outline-none transition-all">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-400 uppercase ml-1">Нууц үг баталгаажуулах</label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-black/40 border border-white/10 text-white text-sm rounded-xl p-3 focus:border-cyan-500 focus:outline-none transition-all">
            </div>

            <button type="submit" class="w-full bg-[#00D4FF] hover:bg-white text-black font-black uppercase py-4 rounded-xl transition-all shadow-[0_0_20px_rgba(0,212,255,0.3)]">
                Нууц үг шинэчлэх
            </button>
        </form>
    </div>
</body>
</html>