<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">Browse by cause</div>
            <h2 class="section-title">Explore Our Categories</h2>
            <p class="section-sub">Discover causes that need your support — find what moves you.</p>
        </div>
        <div class="cat-grid">
            @foreach($categories as $category)
            <a href="{{ route('campaigns.byCategory', $category->slug) }}" class="cat-card">
                <div class="cat-icon">
                    <i class="fa {{ $category->icon ?? 'fa-heart' }}"></i>
                </div>
                <div class="cat-name">{{ $category->name }}</div>
                <div class="cat-count">{{ $category->campaigns_count }} Campaigns</div>
                <div class="cat-arrow"></div>
            </a>
            @endforeach
        </div>
    </div>
</section>

