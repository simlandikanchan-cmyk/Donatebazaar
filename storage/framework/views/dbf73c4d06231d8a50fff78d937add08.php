
<section class="campaigns-section">
    <div class="container">

        <div class="section-header">
            <div class="section-eyebrow">Make an impact</div>

            <h2 class="section-title">
                Featured Campaigns
            </h2>

            <p class="section-sub">
                Support urgent and impactful causes across India.
            </p>
        </div>

        
        <div class="camp-filter-wrap" id="campFilterWrap">

            <button class="camp-filter-btn active" data-cat="all">
                All
            </button>

            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button
                    class="camp-filter-btn"
                    data-cat="<?php echo e($category->slug); ?>"
                >
                    <?php echo e($category->name); ?>

                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

        
        <div class="camp-grid" id="campaignContainer">

            <p class="camp-filter-empty" id="campEmpty">
                No campaigns found in this category.
            </p>

            <?php $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php
                    /*
                    |--------------------------------------------------------------------------
                    | Skip Expired Campaigns
                    |--------------------------------------------------------------------------
                    */
                    $isExpired =
                        $campaign->end_date &&
                        \Carbon\Carbon::parse($campaign->end_date)->isPast();
                ?>

                <?php if($isExpired): ?>
                    <?php continue; ?>
                <?php endif; ?>

                <?php
                    /*
                    |--------------------------------------------------------------------------
                    | Completed Donations Only
                    |--------------------------------------------------------------------------
                    */
                    $completedDonations = $campaign->donations
                        ->where('payment_status', 'completed');

                    /*
                    |--------------------------------------------------------------------------
                    | Total Raised Amount
                    |--------------------------------------------------------------------------
                    */
                    $raised = $completedDonations->sum('total_amount');

                    /*
                    |--------------------------------------------------------------------------
                    | Goal Amount
                    |--------------------------------------------------------------------------
                    */
                    $goal = $campaign->goal_amount ?? 0;

                    /*
                    |--------------------------------------------------------------------------
                    | Funding Percentage
                    |--------------------------------------------------------------------------
                    */
                    $percentage = $goal > 0
                        ? min(100, round(($raised / $goal) * 100))
                        : 0;

                    /*
                    |--------------------------------------------------------------------------
                    | Total Donors
                    |--------------------------------------------------------------------------
                    */
                    $donors = $completedDonations->count();
                ?>

                
                <div
                    class="camp-card hidden"
                    data-cat="<?php echo e($campaign->category->slug ?? 'uncategorized'); ?>"
                >

                    
                    <div class="camp-img">

                        <img
                            loading="lazy"
                            src="<?php echo e(asset('storage/' . $campaign->cover_image)); ?>"
                            alt="<?php echo e($campaign->title); ?>"
                        >

                        <div class="camp-badge">
                            <?php echo e($percentage); ?>% Funded
                        </div>

                        <div class="camp-verified">
                            Verified
                        </div>

                    </div>

                    
                    <div class="camp-body">

                        <h3 class="camp-title">
                            <?php echo e($campaign->title); ?>

                        </h3>

                        
                        <div class="camp-progress-track">
                            <div
                                class="camp-progress-fill"
                                style="width: <?php echo e($percentage); ?>%"
                            ></div>
                        </div>

                        
                        <div class="camp-meta">

                            <span>
                                <strong>
                                    ₹<?php echo e(number_format($raised)); ?>

                                </strong>
                                raised
                            </span>

                            <span>
                                Goal
                                <strong>
                                    ₹<?php echo e(number_format($goal)); ?>

                                </strong>
                            </span>

                        </div>

                        
                        <div class="camp-donors">
                            <?php echo e(number_format($donors)); ?>

                            donors · Active Campaign
                        </div>

                        
                        <a
                            href="<?php echo e(route('campaign.public', [
                                'category' => $campaign->category->slug ?? 'general',
                                'slug'     => $campaign->slug
                            ])); ?>"
                            class="camp-btn"
                        >
                            Donate Now
                        </a>

                    </div>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

        
        <div class="infinite-loader" id="infiniteLoader">

            <div class="infinite-loader-inner">

                <svg
                    class="loader-spinner"
                    id="loaderSpinner"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>

                <span id="loaderText">
                    Scroll to load more
                </span>

            </div>

        </div>

    </div>
</section><?php /**PATH C:\xampp\htdocs\fundraise\resources\views/home/sections/campaigns.blade.php ENDPATH**/ ?>