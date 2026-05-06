<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('products.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    @foreach ($categories as $category)
        @if($category->slug)
        <url>
            <loc>{{ route('category.show', $category->slug) }}</loc>
            <lastmod>{{ $category->updated_at ? $category->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
        @endif
    @endforeach

    @foreach ($products as $product)
        @if($product->slug)
        <url>
            <loc>{{ route('products.show', $product->slug) }}</loc>
            <lastmod>{{ $product->updated_at ? $product->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
        @endif
    @endforeach

</urlset>

