<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>laravel - Google Clone</title>
<style>
:root{
  --blue:#1a73e8;--link:#1a0dab;--text:#202124;--muted:#5f6368;
  --border:#dadce0;--light:#f1f3f4;--footer:#f2f2f2;
}
*{box-sizing:border-box}
html,body{margin:0;min-height:100%;font-family:Arial,Helvetica,sans-serif;color:var(--text);font-size:14px}
body{background:#fff}
a{color:var(--link);text-decoration:none}
a:hover{text-decoration:underline}
button,input{font:inherit}
button{cursor:pointer}
button:focus-visible,a:focus-visible,input:focus-visible{outline:2px solid var(--blue);outline-offset:2px}

/* ---------- Header ---------- */
.header{
  height:70px;display:flex;align-items:center;padding:8px 24px 8px 40px;gap:34px;
}
.logo{font-size:31px;letter-spacing:-2.5px;font-weight:500;white-space:nowrap}
.logo b:nth-child(1){color:#4285f4}.logo b:nth-child(2){color:#ea4335}.logo b:nth-child(3){color:#fbbc05}
.logo b:nth-child(4){color:#4285f4}.logo b:nth-child(5){color:#34a853}.logo b:nth-child(6){color:#ea4335}

.search{
  width:655px;height:46px;border:1px solid #dfe1e5;border-radius:24px;
  display:flex;align-items:center;padding:0 12px 0 20px;box-shadow:0 1px 6px #00000020;
}
.search:focus-within{box-shadow:0 1px 7px #0000002c}
.search input{flex:1;min-width:0;border:0;outline:0;font-size:16px;background:transparent}
.search-btn{border:0;background:transparent;color:#5f6368;height:36px;min-width:36px}
.search-btn.clear{font-size:25px;border-right:1px solid var(--border);padding-right:13px}
.search-btn.search-icon{font-size:23px;color:var(--blue)}
.header-right{margin-left:auto;display:flex;align-items:center;gap:20px}
.header-right button{border:0;background:transparent;color:#202124}
.settings{font-size:20px}
.apps{
  width:30px!important;height:30px!important;display:grid!important;grid-template-columns:repeat(3,4px);
  grid-auto-rows:4px;gap:3px;padding:5px!important
}
.apps i{width:4px;height:4px;background:#202124;border-radius:50%}
.avatar{width:34px;height:34px;border-radius:50%;display:grid;place-items:center;background:#5b20a4;color:#fff}

/* ---------- Navigation ---------- */
.nav{height:48px;border-bottom:1px solid var(--border);padding-left:180px}
.nav-inner{height:100%;display:flex;align-items:stretch;gap:27px}
.nav-link{
  position:relative;display:flex;align-items:center;color:var(--muted);white-space:nowrap;
}
.nav-link.active{color:var(--blue)}
.nav-link.active:after{content:"";position:absolute;bottom:0;left:0;right:0;height:3px;background:var(--blue)}
.nav-tools{margin-left:auto;margin-right:24px}
.more-arrow{font-size:9px;margin-left:5px}

/* ---------- Main ---------- */
.page{max-width:1240px;margin:0 auto;padding:17px 25px 0 178px}
.results-info{color:#70757a;margin-bottom:18px}
.layout{display:grid;grid-template-columns:minmax(0,600px) 416px;gap:38px}

/* ---------- Results ---------- */
.result{margin:0 0 28px}
.source{display:flex;align-items:center;gap:9px;margin-bottom:4px}
.source-icon{
  width:28px;height:28px;border-radius:50%;border:1px solid #dadce0;background:#f8f9fa;
  display:grid;place-items:center;color:#70757a;font-size:10px;flex:none
}
.source-url{font-size:12px;color:#5f6368;margin-top:2px}
.source-dots{font-size:18px;color:#5f6368;margin-left:0}
.result-title{display:block;font-size:21px;line-height:1.35;margin-bottom:4px}
.snippet{line-height:1.55;color:#4d5156}
.sitelinks{margin-top:9px}
.sitelink{
  min-height:55px;padding:9px 0;border-bottom:1px solid #e8eaed;
  display:flex;align-items:center;justify-content:space-between
}
.sitelink-title{font-size:16px;margin-bottom:2px}
.sitelink-desc{color:#4d5156;line-height:1.4}
.chevron{font-size:23px;color:#5f6368;padding-left:12px}
.more-results{display:block;font-size:12px;font-weight:600;margin-top:11px}

/* ---------- Images ---------- */
.block-title{font-size:20px;font-weight:400;margin:26px 0 14px}
.image-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:9px;position:relative}
.image-card{min-width:0}
.placeholder{
  width:100%;height:105px;border:1px solid #dadce0;border-radius:9px;background:#f1f3f4;
  display:grid;place-items:center;text-align:center;color:#80868b;font-size:11px;padding:5px
}
.placeholder strong{display:block;font-size:21px;font-weight:400}
.image-name{font-size:12px;margin-top:7px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.image-source{font-size:11px;color:#70757a;margin-top:4px}
.carousel-next{
  position:absolute;right:-18px;top:33px;width:38px;height:38px;border-radius:50%;
  border:1px solid var(--border);background:#fff;color:#5f6368;font-size:24px;box-shadow:0 1px 4px #0002
}
.view-images{
  width:250px;height:34px;border:0;border-radius:18px;background:#f1f3f4;
  display:flex;align-items:center;justify-content:center;gap:8px;margin:12px auto 27px
}

/* ---------- PAA ---------- */
.paa{border-top:1px solid var(--border);border-bottom:1px solid var(--border);margin-bottom:27px}
.paa h2{font-size:20px;font-weight:400;margin:17px 0 5px}
.question{border-top:1px solid var(--border)}
.question button{
  width:100%;min-height:47px;border:0;background:#fff;display:flex;align-items:center;justify-content:space-between;
  text-align:left;color:var(--text);font-size:16px;padding:0
}
.arrow{color:#5f6368;font-size:18px;transition:.18s}
.question.open .arrow{transform:rotate(180deg)}
.answer{display:none;color:#4d5156;line-height:1.55;padding:0 24px 14px 0}
.question.open+.answer{display:block}
.feedback{text-align:right;color:#70757a;font-size:11px;padding:0 0 8px}

/* ---------- Knowledge Panel ---------- */
.knowledge{border:1px solid var(--border);border-radius:8px;overflow:hidden;align-self:start}
.kp-head{display:flex;gap:14px;padding:17px 20px 13px;border-bottom:1px solid var(--border)}
.kp-logo{
  width:68px;height:68px;border-radius:8px;background:#f1f3f4;border:1px solid #e5e7eb;
  display:grid;place-items:center;color:#80868b;font-size:11px;text-align:center
}
.kp-title{font-size:25px;font-weight:600;margin:3px 0 5px}
.kp-subtitle{color:#5f6368}
.kp-menu{margin-left:auto;color:#5f6368;font-size:18px}
.kp-body{padding:18px 20px}
.kp-description{line-height:1.55;color:#3c4043;margin:0 0 20px}
.facts{display:grid;grid-template-columns:105px 1fr;row-gap:11px;line-height:1.35}
.kp-buttons{display:flex;flex-wrap:wrap;gap:8px;margin-top:18px}
.kp-button{
  border:1px solid var(--border);background:#fff;color:var(--blue);border-radius:20px;padding:8px 13px
}
.panel-section{border-top:1px solid var(--border);margin-top:20px;padding-top:17px}
.panel-section h3{font-size:18px;font-weight:400;margin:0 0 13px}
.related-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.related-placeholder{
  aspect-ratio:1;background:#f1f3f4;border:1px solid #e5e7eb;border-radius:8px;
  display:grid;place-items:center;text-align:center;color:#8b9196;font-size:10px
}
.related-name{margin-top:7px;line-height:1.25}
.see-more{
  width:100%;height:38px;margin-top:21px;border:0;border-radius:20px;background:#f1f3f4
}
.kp-about{border-top:1px solid var(--border);margin-top:20px;padding-top:16px;color:#5f6368;line-height:1.5}

/* ---------- More results + pagination ---------- */
.related-searches{margin-top:35px}
.related-searches h2{font-size:20px;font-weight:400;margin:0 0 12px}
.suggestion{height:48px;border-bottom:1px solid #e8eaed;display:flex;align-items:center;gap:15px}
.suggestion-icon{width:30px;height:30px;border-radius:50%;background:#f1f3f4;display:grid;place-items:center;color:#5f6368}
.pagination{display:flex;justify-content:center;margin:38px 0 45px}
.pagination-row{display:flex;align-items:flex-end;height:58px}
.glogo-mini{font-size:38px;line-height:1;font-weight:500;letter-spacing:-3px;margin-right:5px}
.glogo-mini .g{color:#4285f4}.glogo-mini .o1{color:#ea4335}.glogo-mini .o2{color:#fbbc05}
.glogo-mini .g2{color:#4285f4}.glogo-mini .l{color:#34a853}.glogo-mini .e{color:#ea4335}
.page-no{height:32px;min-width:32px;display:grid;place-items:center;color:var(--link)}
.page-no.current{color:#202124}
.next{align-self:center;margin-left:19px}

/* ---------- Footer ---------- */
.footer{background:#f2f2f2;color:#70757a}
.footer-country{min-height:45px;padding:13px 42px;border-bottom:1px solid var(--border)}
.footer-links{min-height:45px;display:flex;justify-content:center;align-items:center;gap:28px;padding:10px 20px}
.footer-links a{color:#70757a}

/* ---------- Responsive ---------- */
@media(max-width:1150px){
  .page{padding-left:100px}
  .nav{padding-left:100px}
  .layout{grid-template-columns:minmax(0,600px) 360px}
}
@media(max-width:920px){
  .header{padding:8px 20px;gap:20px}
  .header-right{display:none}
  .nav{padding-left:20px;overflow-x:auto}
  .nav-inner{min-width:max-content}
  .nav-tools{margin-left:0}
  .page{padding:17px 25px}
  .layout{display:block}
  .knowledge{display:none}
}
@media(max-width:650px){
  .header{height:62px;padding:8px 12px}
  .logo{display:none}
  .search{width:100%;height:44px}
  .search-btn.clear{display:none}
  .page{padding:14px 14px 0}
  .result-title{font-size:20px}
  .image-grid{grid-template-columns:repeat(2,1fr)}
  .carousel-next{display:none}
  .view-images{width:100%}
  .pagination-row{transform:scale(.88);transform-origin:center}
  .page-no:nth-of-type(n+8){display:none}
  .footer-links{justify-content:flex-start;flex-wrap:wrap;gap:12px 22px}
}
@media(max-width:420px){
  .nav-inner{gap:18px}
  .nav-tools{display:none}
  .source-url{max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
  .pagination{overflow:hidden}
}
</style>
</head>

<body>
<header class="header">
  <a class="logo" href="?q=laravel" aria-label="Google">
    <b>G</b><b>o</b><b>o</b><b>g</b><b>l</b><b>e</b>
  </a>

  <form class="search" id="searchForm">
    <input id="searchInput" name="q" value="laravel" autocomplete="off" aria-label="Search">
    <button class="search-btn clear" id="clearBtn" type="button" aria-label="Clear search">×</button>
    <button class="search-btn" type="button" aria-label="Voice search">🎙</button>
    <button class="search-btn" type="button" aria-label="Search by image">▣</button>
    <button class="search-btn search-icon" type="submit" aria-label="Search">⌕</button>
  </form>

  <div class="header-right">
    <button class="settings" type="button" aria-label="Settings">⚙</button>
    <button class="apps" type="button" aria-label="Google apps">
      <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
    </button>
    <div class="avatar">N</div>
  </div>
</header>

<nav class="nav">
  <div class="nav-inner">
    <a class="nav-link active" data-type="all" href="?q=laravel&type=all">All</a>
    <a class="nav-link" data-type="images" href="?q=laravel&type=images">Images</a>
    <a class="nav-link" data-type="videos" href="?q=laravel&type=videos">Videos</a>
    <a class="nav-link" data-type="news" href="?q=laravel&type=news">News</a>
    <a class="nav-link" data-type="books" href="?q=laravel&type=books">Books</a>
    <a class="nav-link" data-type="web" href="?q=laravel&type=web">Web</a>
    <a class="nav-link" data-type="maps" href="?q=laravel&type=maps">Maps</a>
    <a class="nav-link" href="#more">More <span class="more-arrow">▼</span></a>
    <a class="nav-link nav-tools" href="#tools">Tools</a>
  </div>
</nav>

<main class="page">
  <div class="results-info" id="resultsInfo">About 47,200,000 results (0.42 seconds)</div>

  <div class="layout">
    <section>

      <article class="result">
        <div class="source">
          <div class="source-icon">IMG</div>
          <div>
            <div>Laravel</div>
            <div class="source-url">https://laravel.com</div>
          </div>
          <span class="source-dots">⋮</span>
        </div>

        <a class="result-title" href="https://laravel.com" target="_blank" rel="noopener">
          Laravel - The PHP Framework for Web Artisans
        </a>
        <div class="snippet">
          Laravel is a web application framework with expressive, elegant syntax. We believe development
          must be an enjoyable, creative experience to be truly fulfilling.
        </div>

        <div class="sitelinks">
          <a class="sitelink" href="https://laravel.com/docs" target="_blank" rel="noopener">
            <span><span class="sitelink-title">Documentation</span><br>
            <span class="sitelink-desc">Laravel documentation, tutorials, and API reference.</span></span>
            <span class="chevron">›</span>
          </a>
          <a class="sitelink" href="https://laravel.com/docs/installation" target="_blank" rel="noopener">
            <span><span class="sitelink-title">Getting Started</span><br>
            <span class="sitelink-desc">Get started with Laravel by following a simple, step-by-step ...</span></span>
            <span class="chevron">›</span>
          </a>
          <a class="sitelink" href="https://laravel.com/docs/installation" target="_blank" rel="noopener">
            <span><span class="sitelink-title">Installation</span><br>
            <span class="sitelink-desc">Learn how to install Laravel on your local machine.</span></span>
            <span class="chevron">›</span>
          </a>
          <a class="sitelink" href="https://laravel.com/docs/releases" target="_blank" rel="noopener">
            <span><span class="sitelink-title">Laravel 11.x</span><br>
            <span class="sitelink-desc">The latest version of Laravel. Build modern, secure, and ...</span></span>
            <span class="chevron">›</span>
          </a>
        </div>
        <a class="more-results" href="https://laravel.com" target="_blank" rel="noopener">More results from laravel.com »</a>
      </article>

      <section>
        <h2 class="block-title">Images <span style="font-size:13px;color:#5f6368">⋮</span></h2>
        <div class="image-grid">
          <a class="image-card" href="?q=laravel&type=images">
            <div class="placeholder"><strong>IMG</strong>replace-with-local-01.jpg</div>
            <div class="image-name">Laravel Logo – Vector</div>
            <div class="image-source">Laravel</div>
          </a>
          <a class="image-card" href="?q=laravel&type=images">
            <div class="placeholder"><strong>IMG</strong>replace-with-local-02.jpg</div>
            <div class="image-name">Laravel Code Example</div>
            <div class="image-source">Unsplash</div>
          </a>
          <a class="image-card" href="?q=laravel&type=images">
            <div class="placeholder"><strong>IMG</strong>replace-with-local-03.jpg</div>
            <div class="image-name">Laravel Framework</div>
            <div class="image-source">Freepik</div>
          </a>
          <a class="image-card" href="?q=laravel&type=images">
            <div class="placeholder"><strong>IMG</strong>replace-with-local-04.jpg</div>
            <div class="image-name">Laravel Architecture</div>
            <div class="image-source">Medium</div>
          </a>
          <button class="carousel-next" id="imageNext" type="button" aria-label="More images">›</button>
        </div>
        <a class="view-images" href="?q=laravel&type=images">View more images <span>→</span></a>
      </section>

      <section class="paa">
        <h2>People also ask <span style="font-size:13px;color:#5f6368">⋮</span></h2>

        <div class="question">
          <button type="button"><span>What is Laravel used for?</span><span class="arrow">⌄</span></button>
        </div>
        <div class="answer">Laravel is a PHP framework commonly used to build modern web applications, APIs, and backend services.</div>

        <div class="question">
          <button type="button"><span>Is Laravel good for beginners?</span><span class="arrow">⌄</span></button>
        </div>
        <div class="answer">Laravel provides conventions, documentation, and tools that make PHP application development approachable.</div>

        <div class="question">
          <button type="button"><span>What are the main features of Laravel?</span><span class="arrow">⌄</span></button>
        </div>
        <div class="answer">Laravel includes routing, middleware, validation, authentication, database tools, queues, Blade, and testing support.</div>

        <div class="question">
          <button type="button"><span>How much does Laravel cost?</span><span class="arrow">⌄</span></button>
        </div>
        <div class="answer">Laravel is an open-source framework and does not require purchasing a framework license.</div>

        <div class="feedback">Feedback</div>
      </section>

      <article class="result">
        <div class="source">
          <div class="source-icon">GH</div>
          <div><div>GitHub</div><div class="source-url">https://github.com › laravel › laravel</div></div>
          <span class="source-dots">⋮</span>
        </div>
        <a class="result-title" href="https://github.com/laravel/laravel" target="_blank" rel="noopener">
          laravel/laravel: The Laravel Framework
        </a>
        <div class="snippet">A PHP framework for web artisans. Laravel is a web application framework with expressive, elegant syntax.</div>
        <div class="result-subitems">
          <a href="https://github.com/laravel/laravel/blob/11.x/README.md" target="_blank" rel="noopener">README.md</a>
          <a href="https://github.com/laravel/laravel/tree/11.x/app" target="_blank" rel="noopener">src</a>
          <a href="https://github.com/laravel/laravel/tree/11.x/routes" target="_blank" rel="noopener">routes</a>
          <a href="https://github.com/laravel/laravel/tree/11.x/tests" target="_blank" rel="noopener">tests</a>
          <a href="https://github.com/laravel/laravel/blob/11.x/composer.json" target="_blank" rel="noopener">composer.json</a>
        </div>
      </article>

      <article class="result">
        <div class="source">
          <div class="source-icon">DOC</div>
          <div><div>Laravel Documentation</div><div class="source-url">https://laravel.com › docs</div></div>
          <span class="source-dots">⋮</span>
        </div>
        <a class="result-title" href="https://laravel.com/docs" target="_blank" rel="noopener">Laravel Documentation</a>
        <div class="snippet">The Laravel documentation provides detailed guides covering installation, routing, controllers, database queries, authentication, queues, testing, and more.</div>
      </article>

      <article class="result">
        <div class="source">
          <div class="source-icon">NEWS</div>
          <div><div>Laravel News</div><div class="source-url">https://laravel-news.com</div></div>
          <span class="source-dots">⋮</span>
        </div>
        <a class="result-title" href="https://laravel-news.com" target="_blank" rel="noopener">Laravel News</a>
        <div class="snippet">Laravel tutorials, news, packages, releases, tips, and community resources for Laravel developers.</div>
      </article>

      <section class="related-searches">
        <h2>Related searches</h2>
        <a class="suggestion" href="?q=laravel+framework">
          <span class="suggestion-icon">⌕</span><span>laravel framework</span>
        </a>
        <a class="suggestion" href="?q=laravel+tutorial">
          <span class="suggestion-icon">⌕</span><span>laravel tutorial</span>
        </a>
        <a class="suggestion" href="?q=laravel+documentation">
          <span class="suggestion-icon">⌕</span><span>laravel documentation</span>
        </a>
        <a class="suggestion" href="?q=laravel+vs+php">
          <span class="suggestion-icon">⌕</span><span>laravel vs php</span>
        </a>
      </section>

      <nav class="pagination" aria-label="Search pagination">
        <div class="pagination-row">
          <span class="glogo-mini">
            <span class="g">G</span><span class="o1">o</span><span class="o2">o</span><span class="g2">g</span><span class="l">l</span><span class="e">e</span>
          </span>
          <a class="page-no current" href="?q=laravel&page=1">1</a>
          <a class="page-no" href="?q=laravel&page=2">2</a>
          <a class="page-no" href="?q=laravel&page=3">3</a>
          <a class="page-no" href="?q=laravel&page=4">4</a>
          <a class="page-no" href="?q=laravel&page=5">5</a>
          <a class="page-no" href="?q=laravel&page=6">6</a>
          <a class="page-no" href="?q=laravel&page=7">7</a>
          <a class="page-no" href="?q=laravel&page=8">8</a>
          <a class="page-no" href="?q=laravel&page=9">9</a>
          <a class="page-no" href="?q=laravel&page=10">10</a>
          <a class="next" href="?q=laravel&page=2">Next ›</a>
        </div>
      </nav>

    </section>

    <aside class="knowledge">
      <div class="kp-head">
        <div class="kp-logo">replace-with<br>logo.png</div>
        <div>
          <div class="kp-title">Laravel</div>
          <div class="kp-subtitle">Open source framework</div>
        </div>
        <div class="kp-menu">⋮</div>
      </div>

      <div class="kp-body">
        <p class="kp-description">
          Laravel is a free, open source PHP web application framework for building modern, scalable web applications.
          It follows the MVC pattern and is known for its elegant syntax, developer friendly tools, and extensive documentation.
        </p>

        <div class="facts">
          <strong>Developer(s):</strong><a href="https://en.wikipedia.org/wiki/Taylor_Otwell" target="_blank" rel="noopener">Taylor Otwell</a>
          <strong>Initial release:</strong><span>June 2011</span>
          <strong>Stable release:</strong><span>11.x</span>
          <strong>License:</strong><a href="https://opensource.org/license/mit" target="_blank" rel="noopener">MIT License</a>
          <strong>Written in:</strong><a href="https://www.php.net/" target="_blank" rel="noopener">PHP</a>
        </div>

        <div class="kp-buttons">
          <a class="kp-button" href="https://laravel.com" target="_blank" rel="noopener">◉ Official site</a>
          <a class="kp-button" href="https://github.com/laravel/laravel" target="_blank" rel="noopener">◉ GitHub</a>
          <a class="kp-button" href="https://laravel.com/docs" target="_blank" rel="noopener">↗ Documentation</a>
        </div>

        <section class="panel-section">
          <h3>Related searches</h3>
          <div class="related-grid">
            <a href="?q=laravel+forge"><div class="related-placeholder">replace-with<br>forge.png</div><div class="related-name">Laravel<br>Forge</div></a>
            <a href="?q=php"><div class="related-placeholder">replace-with<br>php.png</div><div class="related-name">PHP</div></a>
            <a href="?q=livewire"><div class="related-placeholder">replace-with<br>livewire.png</div><div class="related-name">Livewire</div></a>
            <a href="?q=inertia.js"><div class="related-placeholder">replace-with<br>inertia.png</div><div class="related-name">Inertia.js</div></a>
          </div>
        </section>

        <section class="panel-section">
          <h3>People also search for</h3>
          <div class="related-grid">
            <a href="?q=symfony"><div class="related-placeholder">replace-with<br>symfony.png</div><div class="related-name">Symfony</div></a>
            <a href="?q=codeigniter"><div class="related-placeholder">replace-with<br>codeigniter.png</div><div class="related-name">CodeIgniter</div></a>
            <a href="?q=wordpress"><div class="related-placeholder">replace-with<br>wordpress.png</div><div class="related-name">WordPress</div></a>
            <a href="?q=react"><div class="related-placeholder">replace-with<br>react.png</div><div class="related-name">React</div></a>
          </div>
          <button class="see-more" id="seeMore" type="button">See more　›</button>
        </section>

        <div class="kp-about">
          <strong>About</strong><br>
          Static demonstration of a Google-style knowledge panel.
          <a href="https://www.google.com/search?q=Laravel" target="_blank" rel="noopener">Learn more</a>
        </div>
      </div>
    </aside>
  </div>
</main>

<footer class="footer">
  <div class="footer-country">Pakistan</div>
  <div class="footer-links">
    <a href="https://about.google/" target="_blank" rel="noopener">About</a>
    <a href="https://ads.google.com/" target="_blank" rel="noopener">Advertising</a>
    <a href="https://smallbusiness.withgoogle.com/" target="_blank" rel="noopener">Business</a>
    <a href="https://www.google.com/search/howsearchworks/" target="_blank" rel="noopener">How Search works</a>
    <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Privacy</a>
    <a href="https://policies.google.com/terms" target="_blank" rel="noopener">Terms</a>
    <a href="https://www.google.com/preferences" target="_blank" rel="noopener">Settings</a>
  </div>
</footer>

<script>
(() => {
  const form=document.getElementById('searchForm');
  const input=document.getElementById('searchInput');
  const clear=document.getElementById('clearBtn');

  clear.addEventListener('click',()=>{
    input.value='';
    input.focus();
  });

  form.addEventListener('submit',e=>{
    e.preventDefault();
    const q=input.value.trim();
    if(q) window.location.href='?q='+encodeURIComponent(q);
  });

  // Preserve the current query in the search tabs.
  const params=new URLSearchParams(location.search);
  const query=params.get('q') || input.value || 'laravel';
  input.value=query;

  document.querySelectorAll('.nav-link[data-type]').forEach(link=>{
    const url=new URL(link.href,location.href);
    url.searchParams.set('q',query);
    link.href=url.pathname+'?'+url.searchParams.toString();
    if(params.get('type')===link.dataset.type){
      document.querySelectorAll('.nav-link').forEach(x=>x.classList.remove('active'));
      link.classList.add('active');
    }
  });

  // PAA accordion.
  document.querySelectorAll('.question button').forEach(button=>{
    button.addEventListener('click',()=>{
      const row=button.closest('.question');
      const wasOpen=row.classList.contains('open');
      document.querySelectorAll('.question.open').forEach(x=>x.classList.remove('open'));
      if(!wasOpen) row.classList.add('open');
    });
  });

  // Pagination links keep the current query.
  document.querySelectorAll('.page-no,.next').forEach(link=>{
    const url=new URL(link.href,location.href);
    url.searchParams.set('q',query);
    link.href=url.pathname+'?'+url.searchParams.toString();
  });

  // "See more" gives a real destination instead of a dead button.
  document.getElementById('seeMore').addEventListener('click',()=>{
    window.location.href='?q='+encodeURIComponent(query+' related searches');
  });

  // Carousel button gives visible feedback without external dependencies.
  document.getElementById('imageNext').addEventListener('click',()=>{
    document.querySelector('.image-grid').scrollBy({left:180,behavior:'smooth'});
  });

  // All internal placeholder links work as searches.
  document.querySelectorAll('a[href^="?q="]').forEach(link=>{
    link.addEventListener('click',()=>{
      const target=new URL(link.href,location.href);
      if(!target.searchParams.get('q')) return;
    });
  });
})();
</script>
</body>
</html>