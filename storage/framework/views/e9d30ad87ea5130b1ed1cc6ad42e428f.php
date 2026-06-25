<section class="why-section">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">6 Reasons of assurance</div>
            <h2 class="section-title">Why DonateBazar?</h2>
            <p class="section-sub">Trusted platform for transparent, secure, and impactful donations.</p>
        </div>
        <div class="why-grid">
            <?php
            $reasons = [
                ['icon'=>'loyalty-program.png','color'=>'#fef3c7','title'=>'Product Giving',    'desc'=>'Make your impact tangible by donating products directly.'],
                ['icon'=>'verify.png',          'color'=>'#d1fae5','title'=>'Verified & Trusted','desc'=>'Support charities through strict verification processes.'],
                ['icon'=>'rotation.png',        'color'=>'#dbeafe','title'=>'Guaranteed Updates','desc'=>'Stay informed with regular campaign progress updates.'],
                ['icon'=>'easy-return.png',     'color'=>'#ede9fe','title'=>'Easy Setup',        'desc'=>'Launch your fundraiser in just a few minutes.'],
                ['icon'=>'lock.png',            'color'=>'#fee2e2','title'=>'Secure & Private',  'desc'=>'Encrypted payments and protected donor data always.'],
                ['icon'=>'support.png',         'color'=>'#e0e7ff','title'=>'24×7 Support',      'desc'=>'Our team is always here to help you succeed.'],
            ];
            ?>
            <?php $__currentLoopData = $reasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="why-card">
                <div class="why-icon" style="background:<?php echo e($r['color']); ?>;">
                    <img src="<?php echo e(asset('images/' . $r['icon'])); ?>" alt="<?php echo e($r['title']); ?>">
                </div>
                <div>
                    <div class="why-title"><?php echo e($r['title']); ?></div>
                    <div class="why-desc"><?php echo e($r['desc']); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php /**PATH C:\xampp\htdocs\fundraise\resources\views/home/sections/why.blade.php ENDPATH**/ ?>