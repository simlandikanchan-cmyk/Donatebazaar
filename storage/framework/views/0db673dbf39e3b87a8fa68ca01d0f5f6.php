<!-- <section class="impact-section">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">Our reach</div>
            <h2 class="section-title">Impact Across India</h2>
            <p class="section-sub">Together with our supporters, we are transforming lives across multiple states.</p>
        </div>
        <div class="impact-grid">
            <div>
                <img src="<?php echo e(asset('images/map2.png')); ?>" alt="Impact Map" class="impact-map-img">
            </div>
            <div class="impact-stats-card">
                <div class="impact-stats-title">Lives Impacted</div>
                <?php
                $impactData = ['Uttarakhand'=>66423,'Haryana'=>65751,'Rajasthan'=>59981,'Andhra Pradesh'=>42964,'Assam'=>27549];
                ?>
                <?php $__currentLoopData = $impactData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="impact-row">
                    <span class="impact-state"><?php echo e($state); ?></span>
                    <span class="impact-count counter" data-target="<?php echo e($count); ?>">0</span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section> -->

<section class="impact-section" style="padding:88px 0;background:var(--bg)">
    <div class="container">

        
        <div style="text-align:center;margin-bottom:56px">
            <div class="eyebrow" style="justify-content:center">Our Reach</div>
            <h2 class="section-title">Impact Across <em>India</em></h2>
            <p style="font-size:15px;color:var(--text2);font-weight:300;line-height:1.75;max-width:460px;margin:0 auto">
                Together with our supporters, we are transforming lives across multiple states.
            </p>
        </div>

        
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start">

            
            <div style="display:flex;flex-direction:column;gap:16px">

                
                <div class="reveal" style="background:var(--surface);border:1.5px solid var(--border2);border-radius:var(--radius-lg);padding:28px;text-align:center">
                    <div style="font-size:10px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--text3);font-family:var(--font-mono);margin-bottom:20px;text-align:left">Coverage Map</div>
                    <img src="<?php echo e(asset('images/map2.png')); ?>" alt="Impact Map"
                         style="width:100%;max-width:280px;margin:0 auto;display:block;filter:hue-rotate(230deg) saturate(1.4)">
                </div>

                
                <div class="reveal d2" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <?php $__currentLoopData = [
                        ['val'=>'28',   'lbl'=>'States Reached'],
                        ['val'=>number_format(array_sum($impactData)),'lbl'=>'Total Lives'],
                        ['val'=>'686',  'lbl'=>'Districts Covered'],
                        ['val'=>'500+', 'lbl'=>'NGO Partners'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="background:var(--surface);border:1.5px solid var(--border2);border-radius:var(--radius);padding:18px">
                        <div style="font-family:var(--font-mono);font-size:22px;font-weight:700;color:var(--accent);margin-bottom:4px"><?php echo e($ms['val']); ?></div>
                        <div style="font-size:11px;color:var(--text3);letter-spacing:.06em;text-transform:uppercase;font-family:var(--font-mono)"><?php echo e($ms['lbl']); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

            </div>

            
            <div class="reveal d1" style="background:var(--surface);border:1.5px solid var(--border2);border-radius:var(--radius-lg);padding:28px">
                <div style="font-size:10px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--text3);font-family:var(--font-mono);margin-bottom:24px">Lives Impacted by State</div>

                <?php
                $impactData = [
                    'Uttarakhand'    => 66423,
                    'Haryana'        => 65751,
                    'Rajasthan'      => 59981,
                    'Andhra Pradesh' => 42964,
                    'Assam'          => 27549,
                    'West bengal'    => 50000,
                    'Bengaluru'      => 65000,
                    'Chennai'        => 75000,
  
                ];

                $maxVal = max($impactData);
                $rank = 1;
                ?>

                <?php $__currentLoopData = $impactData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="display:flex;align-items:center;gap:12px;padding:14px 0;border-bottom:<?php echo e(!$loop->last ? '1px solid var(--border)' : 'none'); ?>">
                    <span style="font-family:var(--font-mono);font-size:11px;font-weight:700;color:var(--text3);width:18px;flex-shrink:0"><?php echo e($rank++); ?></span>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13.5px;font-weight:600;color:var(--text);margin-bottom:7px"><?php echo e($state); ?></div>
                        <div style="height:5px;background:var(--surface3);border-radius:100px;overflow:hidden">
                            <div class="impact-bar"
                                 data-width="<?php echo e(round($count / $maxVal * 100)); ?>"
                                 style="height:100%;width:0%;border-radius:100px;background:linear-gradient(90deg,var(--accent),var(--accent2));transition:width 1.4s cubic-bezier(.4,0,.2,1)">
                            </div>
                        </div>
                    </div>
                    <span class="counter"
                          data-target="<?php echo e($count); ?>"
                          style="font-family:var(--font-mono);font-size:14px;font-weight:700;color:var(--accent);flex-shrink:0;width:68px;text-align:right">
                        0
                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

        </div>
    </div>
</section><?php /**PATH C:\xampp\htdocs\fundraise\resources\views/home/sections/impact.blade.php ENDPATH**/ ?>