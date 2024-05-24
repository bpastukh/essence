<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <!-- Include Tailwind CSS from a CDN for styling -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="pt-5">
<div class="container mx-auto px-4">
    <h1 class="mb-4 text-3xl font-bold">Articles</h1>
    @forelse ($articles as $article)
        <div class="bg-white shadow-md rounded-lg mb-5 p-5">
            <div class="mb-4">
                <h5 class="text-xl font-semibold">{{ $article->title }}</h5>
                <p class="text-gray-700">{{ $article->summary }}</p>
                <div class="mt-2">
                    @foreach ($article->tags as $tag)
                        <span class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full mr-2 text-sm">{{ $tag }}</span>
                    @endforeach
                </div>
                <a href="{{ $article->url }}" class="inline-block mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg" target="_blank">Read Article</a>
                <div class="text-gray-500 mt-2">Posted on {{ $article->created_at->format('Y-m-d') }}</div>
            </div>
        </div>
    @empty
        <p>No articles found.</p>
    @endforelse

    {{ $articles->links() }}
</div>
</body>
</html>
