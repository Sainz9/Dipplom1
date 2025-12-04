@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Add New Game</h2>
        <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-900">Back to Dashboard</a>
    </div>

    <form action="{{ route('games.store') }}" method="POST">
        @csrf <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Game Title</label>
                <input type="text" name="title" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:border-indigo-500" placeholder="E.g. The Last of Us" required>
            </div>
            
            <div>
                <label class="block text-gray-700 font-bold mb-2">Genre</label>
                <input type="text" name="genre" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:border-indigo-500" placeholder="Action, RPG..." required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Original Price ($)</label>
                <input type="number" step="0.01" name="price" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:border-indigo-500" placeholder="59.99" required>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Sale Price ($) (Optional)</label>
                <input type="number" step="0.01" name="sale_price" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:border-indigo-500" placeholder="49.99">
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Cover Image URL</label>
                <input type="text" name="img" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:border-indigo-500" placeholder="https://..." required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Banner Image URL</label>
                <input type="text" name="banner" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:border-indigo-500" placeholder="https://..." required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">YouTube Embed URL</label>
                <input type="text" name="trailer" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:border-indigo-500" placeholder="https://www.youtube.com/embed/..." required>
                <p class="text-xs text-gray-500 mt-1">Must include 'embed/' not 'watch?v='</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Short Description</label>
                <textarea name="description" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:border-indigo-500" rows="2" required></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Long Description</label>
                <textarea name="long_description" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:border-indigo-500" rows="5"></textarea>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded transition-colors">
                Save Game
            </button>
        </div>
    </form>
    <form action="{{ route('games.store') }}" method="POST">
</div>
@endsection