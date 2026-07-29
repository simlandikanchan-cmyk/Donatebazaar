<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — DonateBazaar</title>
    <script>
        (function () {
            var t = localStorage.getItem('theme');
            if (!t) {
                t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite('resources/css/public/auth.css')
</head>
<body>

<button type="button" class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode" title="Toggle theme">
    <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="4"/>
        <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
    </svg>
    <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
    </svg>
</button>

<div class="page-wrapper">

    <div class="left-panel">
        @include('auth.partials._left_panel', [
            'tag' => 'Welcome back',
            'heading' => 'Good to See<br><span class="dim">You Again</span>',
            'subtitle' => 'Log back in and continue your journey of making a difference. Your campaigns are waiting for you.',
        ])

        <div class="activity-wrap">
            <div class="live-badge">
                <span class="live-dot"></span>
                Live activity
            </div>
            <div class="activity-card">
                <div class="activity-item">
                    <div class="activity-avatar" style="background:linear-gradient(135deg,#f97316,#ef4444);">R</div>
                    <div class="activity-info">
                        <div class="activity-name">Rahul M.</div>
                        <div class="activity-desc">Donated to Child Education</div>
                    </div>
                    <div class="activity-amount">+₹500</div>
                </div>
                <div class="activity-divider"></div>
                <div class="activity-item">
                    <div class="activity-avatar" style="background:linear-gradient(135deg,#06b6d4,#3b82f6);">P</div>
                    <div class="activity-info">
                        <div class="activity-name">Priya S.</div>
                        <div class="activity-desc">Supported Flood Relief Fund</div>
                    </div>
                    <div class="activity-amount">+₹1,200</div>
                </div>
                <div class="activity-divider"></div>
                <div class="activity-item">
                    <div class="activity-avatar" style="background:linear-gradient(135deg,#2563eb ,#ec4899);">A</div>
                    <div class="activity-info">
                        <div class="activity-name">Amit K.</div>
                        <div class="activity-desc">Funded Animal Rescue Drive</div>
                    </div>
                    <div class="activity-amount">+₹750</div>
                </div>
            </div>
        </div>

        @include('auth.partials._left_footer')
    </div>

    <div class="right-panel">
        @include('auth.partials._login_form')
    </div>

</div>

@vite('resources/js/public/auth.js')
</body>
</html>
