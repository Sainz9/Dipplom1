<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders - PlayVision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: { 
                    colors: { brand: '#00D4FF', dark: '#0a0a0a', surface: '#121212', card: '#1c1c1c' },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #121212; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #00D4FF; }
        body { background-color: #0a0a0a; color: #e5e7eb; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col font-sans">

    <nav class="border-b border-white/5 bg-surface/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="text-2xl font-black italic text-white tracking-tighter">Play<span class="text-brand">Vision</span></div>
                <span class="bg-white/5 border border-white/5 text-gray-400 text-xs px-2 py-0.5 rounded uppercase tracking-widest font-bold">Orders</span>
            </div>
            
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-xs font-bold text-white bg-brand/10 border border-brand/20 px-4 py-2 rounded-lg hover:bg-brand hover:text-black transition-colors uppercase tracking-wide">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="flex-1 max-w-[1600px] mx-auto w-full p-6">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-white/5 pb-6 mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight flex items-center gap-3">
                    <span class="text-brand">///</span> Захиалгын түүх
                </h1>
                <p class="text-gray-500 text-sm mt-1">Бүх хэрэглэгчийн худалдан авалтын мэдээлэл.</p>
            </div>
       <div class="bg-card px-6 py-3 rounded-xl border border-white/10 flex items-center gap-4">
    <div class="text-right">
        <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Нийт орлого</div>
        
        <div class="text-2xl font-mono text-green-400 font-bold">
            {{ number_format($totalRevenue) }}₮
        </div>
        
    </div>
    <div class="h-8 w-8 rounded-full bg-green-500/20 flex items-center justify-center text-green-500">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
</div>
        </div>

        <div class="bg-card rounded-2xl border border-white/5 shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-black/40 border-b border-white/5">
                            <th class="py-4 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">ID</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Хэрэглэгч (User)</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Тоглоом (Game)</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Төлбөр</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Төлөв</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-right">Огноо</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($orders as $order)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="py-4 px-6 font-mono text-gray-500 text-xs">#{{ $order->id }}</td>
                            
                            <td class="py-4 px-6 align-middle">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-xs font-bold text-gray-400">
                                        {{ substr($order->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-sm text-white">
                                            {{ $order->user->name ?? 'Устгагдсан хэрэглэгч' }}
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            {{ $order->user->email ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-6 align-middle">
                                <div class="text-sm font-medium text-brand group-hover:text-white transition-colors">
                                    {{ $order->game->title ?? 'Устгагдсан тоглоом' }}
                                </div>
                            </td>

                            <td class="py-4 px-6 align-middle">
                                <div class="font-mono text-white font-bold">{{ number_format($order->amount) }}₮</div>
                                <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mt-0.5">
                                    {{ $order->payment_method }}
                                </div>
                            </td>

     <td class="px-6 py-4">
    <div class="flex items-center gap-2">
        
        {{-- 1. ТӨЛӨВ ХАРУУЛАХ / ЗӨВШӨӨРӨХ ТОВЧ --}}
        @if($order->status == 'paid')
            <span class="bg-green-500/10 text-green-500 px-2 py-1 rounded text-xs font-bold uppercase border border-green-500/20">
                Paid
            </span>
        @else
            <span class="text-xs font-bold text-yellow-500 mr-2">Checking...</span>
            
            <form action="{{ route('admin.order.approve', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-500 hover:bg-green-400 text-black p-1.5 rounded transition" title="Зөвшөөрөх">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
            </form>
        @endif

        {{-- 2. УСТГАХ ТОВЧ (ШИНЭЭР НЭМСЭН) --}}
        <form action="{{ route('admin.order.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Та энэ захиалгыг устгахдаа итгэлтэй байна уу?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500/10 border border-red-500/50 text-red-500 hover:bg-red-500 hover:text-white p-1.5 rounded transition" title="Устгах">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </form>

    </div>
</td>

                            <td class="py-4 px-6 text-right text-xs text-gray-500 font-mono">
                                {{ $order->created_at->format('Y-m-d H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center text-gray-600">
                                <div class="text-4xl mb-4 opacity-20">📂</div>
                                <p>Одоогоор ямар ч захиалга хийгдээгүй байна.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-white/5 flex justify-end">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

</body>
</html>