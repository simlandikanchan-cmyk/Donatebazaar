<div class="marquee-wrap">
    <div class="marquee-track">
        <?php $items = ['Trusted by 2.5 Million Donors','10,000+ Verified NGOs','Regular Updates','Multiple Causes','Product Giving','Secure Payments','24x7 Support']; ?>
        <?php for($r=0;$r<2;$r++): ?>
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="marquee-item"><span class="marquee-dot"></span><?php echo e($item); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endfor; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\fundraise\resources\views/home/sections/marquee.blade.php ENDPATH**/ ?>