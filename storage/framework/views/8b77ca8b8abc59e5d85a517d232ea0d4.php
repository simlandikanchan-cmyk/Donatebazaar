<section class="hbs-section">
    <div class="container">
 
        
        <div class="section-header">
            <div class="section-eyebrow">From the Community</div>
            <h2 class="section-title">Stories &amp; Perspectives</h2>
            <p class="section-sub">Real voices on real causes — insights and ideas from our writers across India.</p>
        </div>
 
        
        <?php if(isset($latestBlogs) && $latestBlogs->isNotEmpty()): ?>
        <div class="hbs-carousel-wrap">
            <div class="hbs-track-outer">
                <div class="hbs-track" id="hbsTrack">
                    <?php $__currentLoopData = $latestBlogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('blogs.show', $blog->slug)); ?>" class="hbs-card">
                        <div class="hbs-card-bar"></div>
 
                        <div class="hbs-card-img">
                            <?php if($blog->cover_image): ?>
                                <img src="<?php echo e($blog->cover_image_url ?? Storage::url($blog->cover_image)); ?>" alt="<?php echo e($blog->title); ?>" loading="lazy">
                            <?php else: ?>
                                <div class="hbs-card-img-ph">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#c7d2fe" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <?php if($blog->category): ?>
                                <span class="hbs-cat-badge"><?php echo e($blog->category->name); ?></span>
                            <?php endif; ?>
                            <?php if($blog->is_featured): ?>
                                <span class="hbs-feat-badge">★ Featured</span>
                            <?php endif; ?>
                        </div>
 
                        <div class="hbs-card-body">
                            <div class="hbs-card-meta">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="11" height="11"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <?php echo e($blog->read_time_minutes ?? 1); ?> min read &middot; <?php echo e($blog->published_at?->diffForHumans() ?? 'Recently'); ?>

                            </div>
                            <div class="hbs-card-title"><?php echo e($blog->title); ?></div>
                            <p class="hbs-card-excerpt"><?php echo e(Str::limit(strip_tags($blog->excerpt ?? $blog->content ?? ''), 120)); ?></p>
                            <div class="hbs-card-footer">
                                <div class="hbs-card-author">
                                    <?php if($blog->author->avatar ?? false): ?>
                                        <img src="<?php echo e(Storage::url($blog->author->avatar)); ?>" class="hbs-avatar" alt="<?php echo e($blog->author->name); ?>">
                                    <?php else: ?>
                                        <div class="hbs-initials"><?php echo e(strtoupper(substr($blog->author->name ?? 'A', 0, 1))); ?></div>
                                    <?php endif; ?>
                                    <span class="hbs-author-name"><?php echo e($blog->author->name ?? 'Anonymous'); ?></span>
                                </div>
                                <div class="hbs-card-stats">
                                    <span class="hbs-stat">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="11" height="11"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <?php echo e(number_format($blog->views_count ?? 0)); ?>

                                    </span>
                                    <span class="hbs-stat">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="11" height="11"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                        <?php echo e(number_format($blog->likes_count ?? 0)); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
 
            <div class="hbs-controls">
                <button class="hbs-btn" id="hbsPrev" aria-label="Previous">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <div class="hbs-dots" id="hbsDots"></div>
                <button class="hbs-btn" id="hbsNext" aria-label="Next">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </div>
 
        <div class="hbs-footer-link">
            <a href="<?php echo e(route('blogs.index')); ?>" class="hbs-view-all">
                View all posts
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="13" height="13"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <?php endif; ?>
 
    </div>
</section><?php /**PATH C:\xampp\htdocs\fundraise\resources\views/home/sections/blogs.blade.php ENDPATH**/ ?>