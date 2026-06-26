@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Outfit:wght@300;400;500;600&display=swap');

:root {
    --purple-deep:  #4c1d95;
    --purple-main:  #7c3aed;
    --purple-mid:   #8b5cf6;
    --purple-light: #a78bfa;
    --purple-pale:  #ede9fe;
    --purple-mist:  #f5f3ff;
    --indigo-main:  #4f46e5;
    --indigo-light: #c7d2fe;
    --indigo-pale:  #e0e7ff;
    --ink:          #1e1b4b;
    --ink-soft:     #6d6aaf;
    --ink-muted:    #a5a3c8;
    --white:        #ffffff;
    --border:       #ddd6fe;
    --surface:      #faf9ff;
}

* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Outfit', sans-serif; color: var(--ink); }

/* ── HERO ── */

.hero-wrap {
     position: relative; 
     width: 100%; 
     height: 100vh;
      overflow: hidden;
     }


.hero-slide { 
    position: absolute;
     inset: 0; opacity: 0;
      transition: opacity 1s ease-in-out;
     }


.hero-slide.active { 

    opacity: 1; 

}
.hero-slide img {


     width: 100%; 
     height: 100%;
      object-fit: cover; 
      object-position: center;


     }


.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, rgba(0, 0, 0, .85) 0%, rgb(0 0 0 / 80%) 50%, rgb(0 0 0 / 60%) 100%);
    display: flex;
    align-items: center;
}


.hero-content { 


    max-width: 1200px; 
    margin: 0 auto; 
    padding: 0 2rem; 
    color: #fff; 


}
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3);
    backdrop-filter: blur(8px); border-radius: 100px;
    padding: 6px 18px; font-size: 12px; font-weight: 500;
    letter-spacing: .1em; text-transform: uppercase; color: #e0d7ff;
    margin-bottom: 24px;
}
.hero-eyebrow span { 

    width: 6px; height: 6px; 
    border-radius: 50%;
     background: #a78bfa; 
     display: inline-block; 
     animation: pulse-dot 2s ease infinite; 
    
    }
@keyframes pulse-dot { 
    0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(.7)} }



    .hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.8rem, 4vw, 3rem); font-weight: 700;
    line-height: 1.1; margin-bottom: 20px;
}



.hero-title em
 { 
    font-style: normal; 
    color: #c4b5fd; 

}
.hero-subtitle 
{ 
    font-size: clamp(1rem, 2vw, 1.2rem); 
    font-weight: 300; color: rgba(255,255,255,.8);
     margin-bottom: 40px; max-width: 520px; 
     line-height: 1.7; 
    
    }



.hero-btns { 
    display: flex; 
    gap: 14px; flex-wrap: wrap;

 }


.btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    background: #fff; color: var(--purple-deep);
    padding: 15px 32px; border-radius: 14px;
    font-weight: 600; font-size: 15px;
    transition: transform .2s, box-shadow .2s; text-decoration: none;
}

.btn-primary:hover { 
    transform: translateY(-3px); 
    box-shadow: 0 12px 30px rgba(0,0,0,.2); 

}


.btn-secondary {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, var(--purple-main), var(--indigo-main));
    color: #fff; padding: 15px 32px; border-radius: 14px;
    font-weight: 600; font-size: 15px;
    transition: transform .2s, box-shadow .2s, opacity .2s; text-decoration: none;
    box-shadow: 0 6px 20px rgba(124,58,237,.5);
}



.btn-secondary:hover 
{ 
    transform: translateY(-3px); 
    opacity: .92; box-shadow: 0 12px 30px rgba(124,58,237,.5);


 }


.hero-arrow {
    position: absolute; top: 50%; transform: translateY(-50%);
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3);
    backdrop-filter: blur(8px); color: #fff;
    width: 50px; height: 50px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; z-index: 10; transition: background .2s; font-size: 18px;
}
.hero-arrow:hover 
  {
     background: rgba(255,255,255,.28);

     }

#heroPrev { 

    left: 24px; 
}
#heroNext 
{ 
    right: 24px; 
}
.hero-dots { 

    position: absolute; 
    bottom: 28px; width: 100%; 
    display: flex; 
    justify-content: center;
     gap: 10px; 
     z-index: 10; 
     
    }


.hero-dot
 { 
    width: 8px; 
    height: 8px;
     border-radius: 4px;
      background: rgba(255,255,255,.4); 
      cursor: pointer; 
      transition: background .3s, width .3s;
     }
.hero-dot.active { background: #fff; width: 28px; }
.hero-stats { position: absolute; bottom: 60px; right: 6%; z-index: 10; display: flex; gap: 12px; }
.hero-stat-card {
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    backdrop-filter: blur(16px); border-radius: 16px;
    padding: 16px 22px; text-align: center; color: #fff;
}
.hero-stat-card .num { font-size: 22px; font-weight: 700; font-family: 'arial', serif; }
.hero-stat-card .lbl { font-size: 11px; opacity: .75; margin-top: 2px; }

/* ── MARQUEE ── */
.marquee-wrap { background: var(--purple-deep); overflow: hidden; padding: 14px 0; }
.marquee-track { display: flex; white-space: nowrap; animation: marquee 22s linear infinite; }
.marquee-track:hover { animation-play-state: paused; }
.marquee-item { display: inline-flex; align-items: center; gap: 10px; padding: 0 32px; font-size: 13px; font-weight: 500; color: #e0d7ff; letter-spacing: .04em; text-transform: uppercase; }
.marquee-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--purple-light); }
@keyframes marquee { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }

/* ── SECTION COMMON ── */
.section-eyebrow { font-size: 11px; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; color: var(--purple-main); margin-bottom: 10px; }
.section-title { font-family: 'Playfair Display', serif; font-size: clamp(1.8rem, 3vw, 2.6rem); font-weight: 700; color: var(--ink); margin-bottom: 12px; }
.section-sub { font-size: 15px; color: var(--ink-soft); font-weight: 300; line-height: 1.7; max-width: 520px; margin: 0 auto; }
.section-header { text-align: center; margin-bottom: 56px; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

/* ── CATEGORIES ── */
.categories-section { background: var(--white); padding: 100px 0; position: relative; overflow: hidden; }
.categories-section::before {
    content: ''; position: absolute; top: -200px; right: -200px;
    width: 500px; height: 500px; border-radius: 50%;
    background: radial-gradient(circle, rgba(139,92,246,.08) 0%, transparent 70%);
}
.cat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; }
.cat-card {
    position: relative; border-radius: 22px; overflow: hidden;
    border: 1.5px solid var(--border); background: var(--surface);
    padding: 32px 20px 28px; text-align: center; text-decoration: none; color: var(--ink);
    transition: transform .3s, box-shadow .3s, border-color .3s;
}
.cat-card::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(124,58,237,.06) 0%, rgba(79,70,229,.06) 100%);
    opacity: 0; transition: opacity .3s;
}
.cat-card:hover { transform: translateY(-6px); box-shadow: 0 20px 50px rgba(124,58,237,.15); border-color: var(--purple-light); }
.cat-card:hover::before { opacity: 1; }
.cat-icon {
    width: 62px; height: 62px; border-radius: 18px; margin: 0 auto 18px;
    background: linear-gradient(135deg, var(--purple-main), var(--indigo-main));
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 8px 20px rgba(124,58,237,.3); transition: transform .3s;
}
.cat-card:hover .cat-icon { transform: scale(1.1) rotate(-4deg); }
.cat-icon i { color: #fff; font-size: 22px; }
.cat-name { font-weight: 600; font-size: 14px; color: var(--ink); margin-bottom: 4px; }
.cat-count { font-size: 12px; color: var(--ink-muted); }
.cat-arrow {
    position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--purple-main), var(--indigo-main));
    transform: scaleX(0); transition: transform .3s; transform-origin: left;
}
.cat-card:hover .cat-arrow { transform: scaleX(1); }

/* ── CAMPAIGNS ── */
.campaigns-section { background: var(--surface); padding: 100px 0; }

/* ── Campaign Category Filter ── */
.camp-filter-wrap {
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap; margin-bottom: 36px; justify-content: center;
}
.camp-filter-btn {
    padding: 9px 22px; border-radius: 100px;
    font-size: 13px; font-weight: 500; cursor: pointer;
    border: 1.5px solid var(--border);
    background: var(--white); color: var(--ink-soft);
    font-family: 'Outfit', sans-serif;
    transition: all .2s; white-space: nowrap;
}
.camp-filter-btn:hover {
    border-color: var(--purple-light);
    color: var(--purple-main);
    background: var(--purple-mist);
}
.camp-filter-btn.active {
    background: linear-gradient(135deg, var(--purple-main), var(--indigo-main));
    color: #fff; border-color: transparent;
    box-shadow: 0 4px 14px rgba(124,58,237,.35);
}
.camp-filter-empty {
    display: none; text-align: center; padding: 60px 20px;
    color: var(--ink-muted); font-size: 14px; grid-column: 1/-1;
}

.camp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 28px; }
.camp-card {
    background: var(--white); border-radius: 24px;
    border: 1.5px solid var(--border); overflow: hidden;
    transition: transform .3s, box-shadow .3s, border-color .3s;
}
.camp-card:hover { transform: translateY(-6px); box-shadow: 0 24px 60px rgba(124,58,237,.13); border-color: var(--purple-light); }
.camp-img { position: relative; height: 220px; overflow: hidden; }
.camp-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s; }
.camp-card:hover .camp-img img { transform: scale(1.05); }
.camp-badge {
    position: absolute; top: 14px; left: 14px;
    background: rgba(255,255,255,.92); backdrop-filter: blur(8px);
    color: var(--purple-deep); font-size: 12px; font-weight: 600;
    padding: 5px 14px; border-radius: 100px; border: 1px solid rgba(124,58,237,.2);
}
.camp-verified {
    position: absolute; top: 14px; right: 14px;
    background: #ecfdf5; color: #065f46; font-size: 11px; font-weight: 600;
    padding: 4px 12px; border-radius: 100px; border: 1px solid #a7f3d0;
    display: flex; align-items: center; gap: 5px;
}
.camp-verified::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #10b981; }
.camp-body { padding: 22px 24px 24px; }
.camp-title { font-weight: 600; font-size: 16px; color: var(--ink); margin-bottom: 14px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.camp-progress-track { height: 6px; background: var(--indigo-pale); border-radius: 3px; margin-bottom: 10px; overflow: hidden; }
.camp-progress-fill { height: 100%; border-radius: 3px; background: linear-gradient(90deg, var(--purple-main), var(--indigo-main)); transition: width .8s cubic-bezier(.4,0,.2,1); }
.camp-meta { display: flex; justify-content: space-between; font-size: 13px; color: var(--ink-soft); margin-bottom: 6px; }
.camp-meta strong { color: var(--ink); font-weight: 600; }
.camp-donors { font-size: 12px; color: var(--ink-muted); margin-bottom: 18px; }
.camp-btn {
    display: block; text-align: center;
    background: linear-gradient(135deg, var(--purple-main), var(--indigo-main));
    color: #fff; padding: 13px; border-radius: 14px;
    font-weight: 600; font-size: 14px; text-decoration: none;
    transition: opacity .2s, transform .2s; box-shadow: 0 4px 14px rgba(124,58,237,.35);
}
.camp-btn:hover { opacity: .9; transform: translateY(-2px); }
.camp-card.hidden { display: none; }

/* ── Infinite scroll loader ── */
.infinite-loader { text-align: center; margin-top: 48px; padding: 20px; }
.infinite-loader-inner {
    display: inline-flex; align-items: center; gap: 10px;
    color: var(--ink-muted); font-size: 13px;
}
@keyframes spin { 0%{transform:rotate(0deg)} 100%{transform:rotate(360deg)} }
.loader-spinner { animation: spin 1s linear infinite; display: none; }

/* ── HOW IT WORKS ── */
.how-section {
    position: relative; padding: 100px 0;
    background: linear-gradient(160deg, #0f172a 0%, #1e1b4b 40%, #312e81 100%);
    overflow: hidden;
}
.how-section::before {
    content: ''; position: absolute; top: -150px; left: -150px;
    width: 500px; height: 500px; border-radius: 50%; background: rgba(255,255,255,.04);
}
.how-section::after {
    content: ''; position: absolute; bottom: -100px; right: -100px;
    width: 400px; height: 400px; border-radius: 50%; background: rgba(255,255,255,.03);
}
.how-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; }
@media(max-width:768px) { .how-grid { grid-template-columns: 1fr; } }
.how-card {
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15);
    backdrop-filter: blur(20px); border-radius: 28px; padding: 40px;
}
.how-card-title { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 600; color: #fff; margin-bottom: 8px; }
.how-divider { height: 1px; background: rgba(255,255,255,.15); margin-bottom: 32px; }
.how-step { display: flex; gap: 18px; align-items: flex-start; padding-bottom: 24px; margin-bottom: 24px; border-bottom: 1px solid rgba(255,255,255,.1); }
.how-step:last-of-type { border-bottom: none; padding-bottom: 0; margin-bottom: 24px; }
.how-step-icon {
    width: 50px; height: 50px; border-radius: 14px; flex-shrink: 0;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center; transition: background .2s;
}
.how-step:hover .how-step-icon { background: rgba(255,255,255,.2); }
.how-step-icon svg { width: 22px; height: 22px; color: #c4b5fd; }
.how-step-title { font-size: 15px; font-weight: 600; color: #fff; margin-bottom: 4px; }
.how-step-desc  { font-size: 13px; color: rgba(255,255,255,.65); line-height: 1.6; }
.how-btn { display: block; text-align: center; padding: 15px; border-radius: 14px; font-weight: 600; font-size: 14px; text-decoration: none; transition: transform .2s, opacity .2s; }
.how-btn-orange { background: linear-gradient(135deg, #f97316, #ea580c); color: #fff; box-shadow: 0 6px 20px rgba(249,115,22,.4); }
.how-btn-blue   { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); color: #fff; }
.how-btn:hover  { transform: translateY(-2px); opacity: .92; }

/* ── TESTIMONIALS ── */
.testimonials-section { background: var(--white); padding: 100px 0; }
.tab-btns { display: flex; gap: 10px; margin-bottom: 40px; }
.tab-btn {
    padding: 10px 24px; border-radius: 100px; font-size: 13px; font-weight: 600;
    border: 1.5px solid var(--border); background: var(--surface); color: var(--ink-soft);
    cursor: pointer; transition: all .2s; font-family: 'Outfit', sans-serif;
}
.tab-btn.active { background: linear-gradient(135deg, var(--purple-main), var(--indigo-main)); color: #fff; border-color: transparent; box-shadow: 0 4px 14px rgba(124,58,237,.35); }
.testi-track { overflow: hidden; }
.testi-slider { display: flex; gap: 20px; transition: transform .5s cubic-bezier(.4,0,.2,1); }
.testi-card {
    min-width: 300px; background: var(--surface);
    border: 1.5px solid var(--border); border-radius: 20px; padding: 24px;
    transition: border-color .2s, box-shadow .2s;
}
.testi-card:hover { border-color: var(--purple-light); box-shadow: 0 8px 24px rgba(124,58,237,.1); }
.testi-quote { font-size: 28px; color: var(--purple-light); line-height: 1; margin-bottom: 10px; }
.testi-badge { display: inline-block; font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 100px; margin-bottom: 12px; }
.badge-blue   { background: var(--indigo-pale); color: var(--indigo-main); }
.badge-green  { background: #d1fae5; color: #065f46; }
.badge-purple { background: var(--purple-pale); color: var(--purple-deep); }
.testi-text { font-size: 13px; color: var(--ink-soft); line-height: 1.7; margin-bottom: 18px; }
.testi-author { display: flex; align-items: center; gap: 12px; padding-top: 16px; border-top: 1px solid var(--border); }
.testi-avatar { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; }
.testi-name { font-size: 13px; font-weight: 600; color: var(--ink); }
.testi-role { font-size: 11px; color: var(--ink-muted); }

/* ── WHY US ── */
.why-section { background: var(--surface); padding: 100px 0; }
.why-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
.why-card {
    background: var(--white); border-radius: 20px;
    border: 1.5px solid var(--border); padding: 28px;
    display: flex; gap: 18px; align-items: flex-start;
    transition: transform .3s, box-shadow .3s, border-color .3s;
}
.why-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(124,58,237,.1); border-color: var(--purple-light); }
.why-icon { width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.why-icon img { width: 28px; height: 28px; object-fit: contain; }
.why-title { font-size: 15px; font-weight: 600; color: var(--ink); margin-bottom: 6px; }
.why-desc  { font-size: 13px; color: var(--ink-soft); line-height: 1.6; }

/* ── CTA BANNER ── */
.cta-section { position: relative; height: 480px; overflow: hidden; }
.cta-section img { width: 100%; height: 100%; object-fit: cover; }
.cta-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(15,23,42,.92) 0%, rgba(30,27,75,.85) 50%, rgba(49,46,129,.8) 100%);
    display: flex; align-items: center; justify-content: center; text-align: center;
}
.cta-title { font-family: 'Playfair Display', serif; font-size: clamp(2rem, 4vw, 3.2rem); font-weight: 700; color: #fff; margin-bottom: 16px; }
.cta-sub { font-size: 16px; color: rgba(255,255,255,.8); font-weight: 300; max-width: 540px; margin: 0 auto 36px; line-height: 1.7; }

/* ── IMPACT MAP ── */
.impact-section { background: var(--white); padding: 100px 0; }
.impact-grid { display: grid; grid-template-columns: 1fr 380px; gap: 48px; align-items: start; }
@media(max-width:900px) { .impact-grid { grid-template-columns: 1fr; } }
.impact-map-img { width: 100%; border-radius: 24px; transition: transform .5s; }
.impact-map-img:hover { transform: scale(1.02); }
.impact-stats-card { background: var(--surface); border: 1.5px solid var(--border); border-radius: 24px; padding: 32px; position: sticky; top: 100px; }
.impact-stats-title { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 600; color: var(--ink); margin-bottom: 24px; }
.impact-row { display: flex; justify-content: space-between; align-items: center; padding: 13px 0; border-bottom: 1px solid var(--border); }
.impact-row:last-child { border-bottom: none; }
.impact-state { font-size: 14px; color: var(--ink-soft); }
.impact-count { font-size: 15px; font-weight: 700; color: var(--purple-main); font-family: 'arial', serif; }
</style>


{{-- ═══ HERO ═══ --}}
<div class="hero-wrap">
    <div class="hero-slide active">
        <img src="{{ asset('images/2149012198.jpg') }}" alt="">
        <div class="hero-overlay">
            <div class="hero-content">
                <div class="hero-eyebrow"><span></span> Trusted Fundraising Platform</div>
                <h1 class="hero-title">Be Someone's<br><em>Hope Today</em></h1>
                <p class="hero-subtitle">Stand with people in crisis — from medical emergencies to education and disasters — every rupee can change a life.</p>
                <div class="hero-btns">
                    <a href="{{ route('all.campaigns') }}" class="btn-primary">Donate Now</a>
                    <a href="/campaign/create" class="btn-secondary">Start Fundraiser</a>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-slide">
        <img src="{{ asset('images/2149012178.jpg') }}" alt="">
        <div class="hero-overlay">
            <div class="hero-content">
                <div class="hero-eyebrow"><span></span> Change a Child's Life</div>
                <h1 class="hero-title">Together Save Children's<br><em>Precious Lives</em></h1>
                <p class="hero-subtitle">Help children access education, nutrition, and the care they deserve. Creating brighter futures through education, nutrition, and compassionate care for every child.</p>
                <div class="hero-btns">
                    <a href="{{ route('all.campaigns') }}" class="btn-primary">Donate Now</a>
                    <a href="/campaign/create" class="btn-secondary">Start Fundraiser</a>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-slide">
        <img src="{{ asset('images/18576.jpg') }}" alt="">
        <div class="hero-overlay">
            <div class="hero-content">
                <div class="hero-eyebrow"><span></span> Be The Change</div>
                <h1 class="hero-title">Be the Reason<br><em>Someone Smiles</em></h1>
                <p class="hero-subtitle">Begin your journey of giving today — make a lasting difference in someone's life.</p>
                <div class="hero-btns">
                    <a href="{{ route('all.campaigns') }}" class="btn-primary">Donate Now</a>
                    <a href="/campaign/create" class="btn-secondary">Start Fundraiser</a>
                </div>
            </div>
        </div>
    </div>

    <button class="hero-arrow" id="heroPrev">&#10094;</button>
    <button class="hero-arrow" id="heroNext">&#10095;</button>

    <div class="hero-dots">
        <span class="hero-dot active"></span>
        <span class="hero-dot"></span>
        <span class="hero-dot"></span>
    </div>

    <div class="hero-stats">
        <div class="hero-stat-card">
            <div class="num">2.5M+</div>
            <div class="lbl">Donors</div>
        </div>
        <div class="hero-stat-card">
            <div class="num">10K+</div>
            <div class="lbl">Campaigns</div>
        </div>
        <div class="hero-stat-card">
            <div class="num">₹50Cr+</div>
            <div class="lbl">Raised</div>
        </div>
    </div>
</div>


{{-- ═══ MARQUEE ═══ --}}
<div class="marquee-wrap">
    <div class="marquee-track">
        @php $items = ['Trusted by 2.5 Million Donors','10,000+ Verified NGOs','Regular Updates','Multiple Causes','Product Giving','Secure Payments','24x7 Support']; @endphp
        @for($r=0;$r<2;$r++)
            @foreach($items as $item)
                <span class="marquee-item">
                    <span class="marquee-dot"></span>{{ $item }}
                </span>
            @endforeach
        @endfor
    </div>
</div>


{{-- ═══ CATEGORIES ═══ --}}
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


{{-- ═══ CAMPAIGNS ═══ --}}
<section class="campaigns-section">
    <div class="container">

        <div class="section-header">
            <div class="section-eyebrow">Make an impact</div>
            <h2 class="section-title">Featured Campaigns</h2>
            <p class="section-sub">Support urgent and impactful causes across India.</p>
        </div>

        {{-- ── Category Filter Bar ── --}}
        <div class="camp-filter-wrap" id="campFilterWrap">
            <button class="camp-filter-btn active" data-cat="all">All</button>
            @foreach($categories as $category)
                <button class="camp-filter-btn" data-cat="{{ $category->slug }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        {{-- ── Campaign Grid ── --}}
        <div class="camp-grid" id="campaignContainer">

            {{-- Empty state (hidden by default) --}}
            <p class="camp-filter-empty" id="campEmpty">No campaigns found in this category.</p>

            @foreach($campaigns as $index => $campaign)
            @php
                $raised     = $campaign->donations->sum('amount');
                $goal       = $campaign->goal_amount;
                $percentage = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
                $donors     = $campaign->donations->count();
            @endphp
            
            <div class="camp-card {{ $index >= 6 ? 'hidden' : '' }}"
                 data-cat="{{ $campaign->category->slug ?? 'uncategorized' }}">
                <div class="camp-img">
                    <img loading="lazy"
                         src="{{ asset('storage/' . $campaign->cover_image) }}"
                         alt="{{ $campaign->title }}">
                    <div class="camp-badge">{{ $percentage }}% Funded</div>
                    <div class="camp-verified">Verified</div>
                </div>
                <div class="camp-body">
                    <h3 class="camp-title">{{ $campaign->title }}</h3>
                    <div class="camp-progress-track">
                        <div class="camp-progress-fill" style="width:{{ $percentage }}%"></div>
                    </div>
                    <div class="camp-meta">
                        <span><strong>₹{{ number_format($raised) }}</strong> raised</span>
                        <span>Goal <strong>₹{{ number_format($goal) }}</strong></span>
                    </div>
                    <div class="camp-donors">{{ $donors }} donors · Active Campaign</div>
                    <a href="{{ route('campaign.public', $campaign->slug) }}" class="camp-btn">Donate Now</a>
                </div>
            </div>
            @endforeach

        </div>{{-- /#campaignContainer --}}

        {{-- ── Infinite Scroll Loader ── --}}
        <div class="infinite-loader" id="infiniteLoader">
            <div class="infinite-loader-inner">
                <svg class="loader-spinner" id="loaderSpinner" width="20" height="20"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>

                <span id="loaderText">Scroll to load more</span>

            </div>
        </div>

    </div>
</section>


{{-- ═══ HOW IT WORKS ═══ --}}
<section class="how-section">
    <div class="container" style="position:relative;z-index:1;">
        <div class="section-header">
            <div class="section-eyebrow" style="color:#c4b5fd;">Small steps, large impact</div>
            <h2 class="section-title" style="color:#fff;">How it Works</h2>
            <p class="section-sub" style="color:rgba(255,255,255,.65);">Simple, transparent, and secure — from donation to impact.</p>
        </div>
        <div class="how-grid">

            <div class="how-card">
                <div class="how-card-title">For Donors</div>
                <div class="how-divider"></div>
                <div class="how-step">
                    <div class="how-step-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <div class="how-step-title">Choose a cause</div>
                        <div class="how-step-desc">Find a campaign or cause close to your heart.</div>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-step-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div>
                        <div class="how-step-title">Start Donating</div>
                        <div class="how-step-desc">Contribute products or money — simple and secure.</div>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-step-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg>
                    </div>
                    <div>
                        <div class="how-step-title">Get Updates</div>
                        <div class="how-step-desc">Receive regular updates on how your donation is used.</div>
                    </div>
                </div>
                <a href="{{ url('/all-campaigns') }}" class="how-btn how-btn-orange">Donate Now</a>
            </div>

            <div class="how-card">
                <div class="how-card-title">For Fundraisers</div>
                <div class="how-divider"></div>
                <div class="how-step">
                    <div class="how-step-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    </div>
                    <div>
                        <div class="how-step-title">Begin Your Fundraiser</div>
                        <div class="how-step-desc">Start in minutes and share your story with the world.</div>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-step-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92c0-1.61-1.31-2.92-2.92-2.92z"/></svg>
                    </div>
                    <div>
                        <div class="how-step-title">Spread the Word</div>
                        <div class="how-step-desc">Invite friends, family, and community to join your cause.</div>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-step-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div>
                        <div class="how-step-title">Receive Support</div>
                        <div class="how-step-desc">Withdraw funds safely and continue creating impact.</div>
                    </div>
                </div>
                <a href="{{ url('/campaign/create') }}" class="how-btn how-btn-blue">Start a Campaign</a>
            </div>

        </div>
    </div>
</section>


{{-- ═══ TESTIMONIALS ═══ --}}
<section class="testimonials-section">
    <div class="container">
        <div class="section-header" style="text-align:left;">
            <div class="section-eyebrow">Love from our community</div>
            <h2 class="section-title">Testimonials</h2>
        </div>
        <div class="tab-btns">
            <button class="tab-btn active" onclick="switchTab('donors', this)">Donors</button>
            <button class="tab-btn" onclick="switchTab('ngos', this)">NGOs</button>
            <button class="tab-btn" onclick="switchTab('celebs', this)">Celebrities</button>
        </div>

        <div id="donors" class="testi-tab">
            <div class="testi-track"><div class="testi-slider" id="slider-donors">
                @for($i=1;$i<=10;$i++)
                <div class="testi-card">
                    <div class="testi-quote">"</div>
                    <span class="testi-badge badge-blue">Contributed {{ $i+2 }} Times</span>
                    <p class="testi-text">Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.</p>
                    <div class="testi-author">
                        <img src="https://i.pravatar.cc/40?img={{ $i }}" class="testi-avatar" alt="">
                        <div><div class="testi-name">Donor {{ $i }}</div><div class="testi-role">Supporter</div></div>
                    </div>
                </div>
                @endfor
            </div></div>
        </div>

        <div id="ngos" class="testi-tab" style="display:none;">
            <div class="testi-track"><div class="testi-slider" id="slider-ngos">
                @for($i=1;$i<=10;$i++)
                <div class="testi-card">
                    <div class="testi-quote">"</div>
                    <span class="testi-badge badge-green">NGO Partner</span>
                    <p class="testi-text">This platform helps NGOs reach donors easily. The verification process builds genuine trust with supporters.</p>
                    <div class="testi-author">
                        <img src="https://i.pravatar.cc/40?img={{ $i+5 }}" class="testi-avatar" alt="">
                        <div><div class="testi-name">NGO {{ $i }}</div><div class="testi-role">Organization</div></div>
                    </div>
                </div>
                @endfor
            </div></div>
        </div>

        <div id="celebs" class="testi-tab" style="display:none;">
            <div class="testi-track"><div class="testi-slider" id="slider-celebs">
                @for($i=1;$i<=10;$i++)
                <div class="testi-card">
                    <div class="testi-quote">"</div>
                    <span class="testi-badge badge-purple">Celebrity Supporter</span>
                    <p class="testi-text">Giving back to society is important. This platform makes it easy to contribute meaningfully.</p>
                    <div class="testi-author">
                        <img src="https://i.pravatar.cc/40?img={{ $i+10 }}" class="testi-avatar" alt="">
                        <div><div class="testi-name">Celebrity {{ $i }}</div><div class="testi-role">Influencer</div></div>
                    </div>
                </div>
                @endfor
            </div></div>
        </div>
    </div>
</section>


{{-- ═══ WHY DonateBazaar ═══ --}}
<section class="why-section">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">6 Reasons of assurance</div>
            <h2 class="section-title">Why DonateBazaar?</h2>
            <p class="section-sub">Trusted platform for transparent, secure, and impactful donations.</p>
        </div>
        <div class="why-grid">
            @php
            $reasons = [
                ['icon'=>'loyalty-program.png','color'=>'#fef3c7','title'=>'Product Giving',    'desc'=>'Make your impact tangible by donating products directly.'],
                ['icon'=>'verify.png',          'color'=>'#d1fae5','title'=>'Verified & Trusted','desc'=>'Support charities through strict verification processes.'],
                ['icon'=>'rotation.png',        'color'=>'#dbeafe','title'=>'Guaranteed Updates','desc'=>'Stay informed with regular campaign progress updates.'],
                ['icon'=>'easy-return.png',     'color'=>'#ede9fe','title'=>'Easy Setup',        'desc'=>'Launch your fundraiser in just a few minutes.'],
                ['icon'=>'lock.png',            'color'=>'#fee2e2','title'=>'Secure & Private',  'desc'=>'Encrypted payments and protected donor data always.'],
                ['icon'=>'support.png',         'color'=>'#e0e7ff','title'=>'24×7 Support',      'desc'=>'Our team is always here to help you succeed.'],
            ];
            @endphp
            @foreach($reasons as $r)
            <div class="why-card">
                <div class="why-icon" style="background:{{ $r['color'] }};">
                    <img src="{{ asset('images/' . $r['icon']) }}" alt="{{ $r['title'] }}">
                </div>
                <div>
                    <div class="why-title">{{ $r['title'] }}</div>
                    <div class="why-desc">{{ $r['desc'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══ CTA BANNER ═══ --}}
<section class="cta-section">
    <img src="{{ asset('images/banner2.jpeg') }}" alt="">
    <div class="cta-overlay">
        <div>
            <h2 class="cta-title">Start Your Fundraiser Today</h2>
            <p class="cta-sub">Start raising funds for urgent needs like medical care, education, and disaster relief — it takes just a few minutes to make a difference.</p>
            <a href="/campaign/create" class="btn-secondary" style="font-size:16px;padding:18px 44px;">Start Fundraiser</a>
        </div>
    </div>
</section>


{{-- ═══ IMPACT MAP ═══ --}}
<section class="impact-section">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">Our reach</div>
            <h2 class="section-title">Impact Across India</h2>
            <p class="section-sub">Together with our supporters, we are transforming lives across multiple states.</p>
        </div>
        <div class="impact-grid">
            <div>
                <img src="{{ asset('images/map2.png') }}" alt="Impact Map" class="impact-map-img">
            </div>
            <div class="impact-stats-card">
                <div class="impact-stats-title">Lives Impacted</div>
                @php
                $impactData = ['Uttarakhand'=>66423,'Haryana'=>65751,'Rajasthan'=>59981,'Andhra Pradesh'=>42964,'Assam'=>27549];
                @endphp
                @foreach($impactData as $state => $count)
                <div class="impact-row">
                    <span class="impact-state">{{ $state }}</span>
                    <span class="impact-count counter" data-target="{{ $count }}">0</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ═══ SCRIPTS ═══ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ════════════════════════════════
    // HERO SLIDER
    // ════════════════════════════════
    var slides    = document.querySelectorAll('.hero-slide');
    var dots      = document.querySelectorAll('.hero-dot');
    var current   = 0;
    var autoTimer;

    function showSlide(idx) {
        slides.forEach(function(s, i) {
            s.classList.toggle('active', i === idx);
            dots[i].classList.toggle('active', i === idx);
        });
    }
    function nextSlide() { current = (current + 1) % slides.length; showSlide(current); }
    function prevSlide() { current = (current - 1 + slides.length) % slides.length; showSlide(current); }
    function startAuto() { autoTimer = setInterval(nextSlide, 4500); }
    function stopAuto()  { clearInterval(autoTimer); }

    document.getElementById('heroNext').onclick = function() { stopAuto(); nextSlide(); startAuto(); };
    document.getElementById('heroPrev').onclick = function() { stopAuto(); prevSlide(); startAuto(); };
    dots.forEach(function(d, i) {
        d.onclick = function() { stopAuto(); current = i; showSlide(i); startAuto(); };
    });
    showSlide(0);
    startAuto();


    // ════════════════════════════════
    // CAMPAIGNS — shared state
    // Everything lives here so filter
    // and infinite scroll share vars
    // ════════════════════════════════
    var allCards      = Array.from(document.querySelectorAll('.camp-card'));
    var loader        = document.getElementById('infiniteLoader');
    var spinner       = document.getElementById('loaderSpinner');
    var loaderTxt     = document.getElementById('loaderText');
    var campEmpty     = document.getElementById('campEmpty');

    var loading       = false;
    var visibleCount  = 0;
    var activeFilter  = 'all';
    var filteredCards = allCards.slice(); // current filtered set

    // ── Show next batch from filteredCards ──
    function showNextBatch() {
        if (loading || visibleCount >= filteredCards.length) return;
        loading = true;
        spinner.style.display = 'inline-block';
        loaderTxt.textContent = 'Loading...';

        setTimeout(function () {
            var end = Math.min(visibleCount + 6, filteredCards.length);
            for (var i = visibleCount; i < end; i++) {
                filteredCards[i].classList.remove('hidden');
            }
            visibleCount = end;
            loading      = false;
            spinner.style.display = 'none';

            if (visibleCount >= filteredCards.length) {
                loaderTxt.textContent = 'All campaigns loaded ✓';
                loader.style.opacity  = '0.5';
                observer.disconnect();
            } else {
                loaderTxt.textContent = 'Scroll to load more';
            }
        }, 600);
    }

    // ── Intersection observer for infinite scroll ──
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) showNextBatch();
        });
    }, { rootMargin: '200px' });

    // ── Apply a category filter ──
    function applyFilter(cat) {
        activeFilter = cat;

        // Hide every card first
        allCards.forEach(function (card) {
            card.classList.add('hidden');
        });

        // Build the filtered set
        filteredCards = allCards.filter(function (card) {
            return cat === 'all' || card.getAttribute('data-cat') === cat;
        });

        // Reset scroll state
        visibleCount = 0;
        loading      = false;
        observer.disconnect();

        // Show / hide empty state
        campEmpty.style.display = filteredCards.length === 0 ? 'block' : 'none';

        if (filteredCards.length === 0) {
            loader.style.display = 'none';
            return;
        }

        // Reveal first 6
        var firstBatch = Math.min(6, filteredCards.length);
        for (var i = 0; i < firstBatch; i++) {
            filteredCards[i].classList.remove('hidden');
        }
        visibleCount = firstBatch;

        // Reset loader
        if (filteredCards.length > 6) {
            loader.style.display  = '';
            loader.style.opacity  = '1';
            loaderTxt.textContent = 'Scroll to load more';
            spinner.style.display = 'none';
            observer.observe(loader);
        } else {
            loader.style.display = 'none';
        }
    }

    // ── Filter button clicks ──
    document.querySelectorAll('.camp-filter-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.camp-filter-btn').forEach(function (b) {
                b.classList.remove('active');
            });
            this.classList.add('active');
            applyFilter(this.getAttribute('data-cat'));
            document.getElementById('campaignContainer').scrollIntoView({
                behavior: 'smooth', block: 'start'
            });
        });
    });

    // ── Initial render: show first 6 of all cards ──
    applyFilter('all');


    // ════════════════════════════════
    // COUNTER ANIMATION
    // ════════════════════════════════
    document.querySelectorAll('.counter').forEach(function (counter) {
        var target = +counter.getAttribute('data-target');
        var count  = 0;
        function update() {
            count += target / 120;
            if (count < target) {
                counter.textContent = Math.ceil(count).toLocaleString('en-IN');
                requestAnimationFrame(update);
            } else {
                counter.textContent = target.toLocaleString('en-IN');
            }
        }
        update();
    });


    // ════════════════════════════════
    // TESTIMONIAL AUTO SCROLL
    // ════════════════════════════════
    setInterval(function () {
        document.querySelectorAll('.testi-slider').forEach(function (slider) {
            if (slider.closest('.testi-tab').style.display !== 'none') {
                var first = slider.firstElementChild;
                if (first) {
                    slider.appendChild(first.cloneNode(true));
                    slider.removeChild(first);
                }
            }
        });
    }, 3200);

}); // end DOMContentLoaded


// ════════════════════════════════
// TAB SWITCH — must be global for onclick=""
// ════════════════════════════════
function switchTab(tab, btn) {
    document.querySelectorAll('.testi-tab').forEach(function (el) { el.style.display = 'none'; });
    document.getElementById(tab).style.display = 'block';
    document.querySelectorAll('.tab-btn').forEach(function (b) { b.classList.remove('active'); });
    btn.classList.add('active');
}
</script>

@endsection