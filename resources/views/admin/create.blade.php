@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Шинэ тоглоом нэмэх</h2>

    <form action="{{ route('games.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700">Тоглоомын нэр</label>
                <input type="text" name="title" class="w-full border p-2 rounded mt-1" required>
            </div>
            
            <div>
                <label class="block text-gray-700">Төрөл (Genre)</label>
                <input type="text" name="genre" class="w-full border p-2 rounded mt-1" placeholder="Action, RPG..." required>
            </div>

            <div>
                <label class="block text-gray-700">Үндсэн үнэ ($)</label>
                <input type="number" step="0.01" name="price" class="w-full border p-2 rounded mt-1" required>
            </div>
            <div>
                <label class="block text-gray-700">Хямдарсан үнэ ($)</label>
                <input type="number" step="0.01" name="sale_price" class="w-full border p-2 rounded mt-1">
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700">Зургийн линк (Image URL)</label>
                <input type="text" name="img" class="w-full border p-2 rounded mt-1" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-700">Баннер линк (Banner URL)</label>
                <input type="text" name="banner" class="w-full border p-2 rounded mt-1" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-700">Trailer (Youtube Embed URL)</label>
                <input type="text" name="trailer" class="w-full border p-2 rounded mt-1" placeholder="https://www.youtube.com/embed/..." required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700">Богино тайлбар</label>
                <textarea name="description" class="w-full border p-2 rounded mt-1" rows="2" required></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-700">Дэлгэрэнгүй тайлбар</label>
                <textarea name="long_description" class="w-full border p-2 rounded mt-1" rows="5"></textarea>
            </div>

            <div>
                <label class="block text-gray-700">Developer</label>
                <input type="text" name="developer" class="w-full border p-2 rounded mt-1">
            </div>
            <div>
                <label class="block text-gray-700">Publisher</label>
                <input type="text" name="publisher" class="w-full border p-2 rounded mt-1">
            </div>
            <div>
                <label class="block text-gray-700">Discount Tag (-20%)</label>
                <input type="text" name="discount" class="w-full border p-2 rounded mt-1">
            </div>
            <div>
                <label class="block text-gray-700">Status Tag (HOT, NEW)</label>
                <input type="text" name="tag" class="w-full border p-2 rounded mt-1">
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Хадгалах</button>
        </div>
    </form>
</div>
@endsection