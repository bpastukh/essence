<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <!-- Include Bootstrap CSS from a CDN for styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 20px;
        }
        .article-card {
            margin-bottom: 20px;
        }
        .article-tags {
            margin-top: 10px;
        }
        .tag {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 2px 10px;
            border-radius: 20px;
            margin-right: 5px;
        }
        .article-link {
            margin-top: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="mb-4">Articles</h1>
    @forelse ($articles as $article)
        <div class="card article-card">
            <div class="card-body">
                <h5 class="card-title">{{ $article->title }}</h5>
                <p class="card-text">{{ $article->summary }}</p>
                <div class="article-tags">
                    @foreach ($article->tags as $tag)
                        <span class="tag">{{ $tag }}</span>
                    @endforeach
                </div>
                <a href="{{ $article->url }}" class="btn btn-primary article-link" target="_blank">Read Article</a>
                <div class="text-muted">Posted on {{ $article->created_at->format('Y-m-d') }}</div>
            </div>
        </div>
    @empty
        <p>No articles found.</p>
    @endforelse
</div>
<!-- Include Bootstrap JS and its dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
