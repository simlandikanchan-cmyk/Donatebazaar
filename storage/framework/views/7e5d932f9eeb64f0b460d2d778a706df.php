<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">Browse by cause</div>
            <h2 class="section-title">Explore Our Categories</h2>
            <p class="section-sub">Discover causes that need your support — find what moves you.</p>
        </div>
        <div class="cat-grid">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('campaigns.byCategory', $category->slug)); ?>" class="cat-card">
                <div class="cat-icon">
                    <i class="fa <?php echo e($category->icon ?? 'fa-heart'); ?>"></i>
                </div>
                <div class="cat-name"><?php echo e($category->name); ?></div>
                <div class="cat-count"><?php echo e($category->campaigns_count); ?> Campaigns</div>
                <div class="cat-arrow"></div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<?php /**PATH C:\xampp\htdocs\fundraise\resources\views/home/sections/categories.blade.php ENDPATH**/ ?>