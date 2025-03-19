<div class="p-8 bg-gray-100 min-h-screen">
    <h1 class="text-4xl font-bold text-center mb-8 text-gray-800">Financial Market News</h1>

    <div class="columns-1 sm:columns-2 md:columns-3 gap-6">
        @forelse($news as $article)
            <div class="relative mb-6 bg-white shadow-lg rounded-xl overflow-hidden transition-transform transform hover:scale-105">
                <img src="{{ $article['image'] ?? 'https://via.placeholder.com/400' }}"
                     class="w-full h-auto object-cover" alt="News Image">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-800">{{ $article['headline'] }}</h2>
                    <p class="text-gray-600 text-sm mt-2">{{ Str::limit($article['summary'], 120) }}</p>
                    <a href="{{ $article['url'] }}" target="_blank"
                       class="text-blue-500 mt-2 inline-block font-medium hover:underline">Read More</a>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-600 col-span-3">No news available at the moment.</p>
        @endforelse
    </div>
</div>
