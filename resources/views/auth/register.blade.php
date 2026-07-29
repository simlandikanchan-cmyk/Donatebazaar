<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — DonateBazaar</title>
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
            'tag' => 'Join the movement',
            'heading' => 'Be the Reason<br><span class="dim">Someone Smiles</span>',
            'subtitle' => 'Create your free account and start making a difference. Every donation, no matter how small, changes a life.',
        ])

        <div class="impact-wrap">
            <div class="impact-badge">
                <span class="impact-dot"></span>
                Why join us
            </div>
            <div class="impact-card">
                <div class="impact-item">
                    <div class="impact-icon-wrap" style="background:linear-gradient(135deg,rgba(126,255,196,0.18),rgba(59,130,246,0.18));">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div class="impact-info">
                        <div class="impact-title">Zero platform fees</div>
                        <div class="impact-desc">100% of your donation reaches the cause</div>
                    </div>
                    <span class="impact-tag" style="background:rgba(126,255,196,0.12);color:var(--green);">Free</span>
                </div>
                <div class="impact-divider"></div>
                <div class="impact-item">
                    <div class="impact-icon-wrap" style="background:linear-gradient(135deg,rgba(37,99,235,0.2),rgba(236,72,153,0.15));">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(37,99,235,0.9)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <div class="impact-info">
                        <div class="impact-title">Bank-grade security</div>
                        <div class="impact-desc">256-bit encrypted, fully PCI compliant</div>
                    </div>
                    <span class="impact-tag" style="background:rgba(37,99,235,0.15);color:rgba(180,170,255,0.9);">Secure</span>
                </div>
                <div class="impact-divider"></div>
                <div class="impact-item">
                    <div class="impact-icon-wrap" style="background:linear-gradient(135deg,rgba(251,191,36,0.18),rgba(249,115,22,0.15));">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(251,191,36,0.9)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                    </div>
                    <div class="impact-info">
                        <div class="impact-title">80G tax receipts</div>
                        <div class="impact-desc">Instant certificates for every donation</div>
                    </div>
                    <span class="impact-tag" style="background:rgba(251,191,36,0.12);color:rgba(251,191,36,0.9);">80G</span>
                </div>
            </div>
        </div>

        @include('auth.partials._left_footer')
    </div>

    <div class="right-panel">
        @include('auth.partials._register_form')
    </div>

</div>

@vite('resources/js/public/auth.js')
</body>
</html>
