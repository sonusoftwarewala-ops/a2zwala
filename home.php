<?php
require_once __DIR__ . '/includes/db.php';

try {
    $site = s('site_name');
} catch (PDOException $ex) {
    exit('Database not ready. Open <a href="install.php">install.php</a> first.');
}

function cell($v) {
    $v = trim((string)$v);
    if (strcasecmp($v, 'yes') === 0) return '<span class="tick">✓</span>';
    if ($v === '' || strcasecmp($v, 'no') === 0) return '<span class="dash">—</span>';
    return '<span class="muted">' . e($v) . '</span>';
}

// "Label|link" per line → [[label, link], ...]
function dropdown($text) {
    $out = [];
    foreach (preg_split('/\R/', (string)$text) as $line) {
        if (trim($line) === '') continue;
        $p = explode('|', $line, 2);
        $out[] = [trim($p[0]), trim($p[1] ?? '#')];
    }
    return $out;
}

// Built-in demo graphics shown until the admin uploads real media
function mock($key) {
    switch ($key) {
        case 'banner': return '<div class="ad300"><div class="brand">FRESH BOX</div><h4>Order your Fresh Box <b>with free delivery</b></h4><div class="box"></div><div class="cta">Order now</div></div>';
        case 'edm':    return '<div class="edm"><div class="top">Having difficulty reading this email? <u>View it online</u></div><div class="hd"><b>▦ AQUA</b><p>Get conversations flowing in your office</p></div><div class="img"></div><div class="body"><i></i><i></i><i></i></div></div>';
        case 'web':    return '<div class="site"><div class="bar"><i></i><i></i><i></i></div><div class="hero-m"><b>Build. Launch. Grow.</b><span>Pixel-perfect landing pages</span></div><div class="cols"><i></i><i></i><i></i></div></div>';
        default:       return '<div class="motion"><span class="orb"></span><span class="orb"></span><span class="play"></span></div>';
    }
}

function shot_mock($key) {
    switch ($key) {
        case 'banner': return '<div class="shot-banner"><div class="sky"></div><div class="stack"><i></i><i></i><i></i></div></div>';
        case 'edm':    return '<div class="mail"><div class="t"></div><b>▦ AQUA</b><p>Get conversations flowing in your office</p><div class="ph"></div></div>';
        case 'web':    return '<div class="nav-m"><b>ECRC</b></div><div class="hero-w"></div><div class="row"><i></i><i></i><i></i></div>';
        default:       return '<div class="reel"><i></i><i></i><i></i><i></i></div>';
    }
}

$menu     = items('menu');
$showcase = items('showcase');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e(s('meta_title')) ?></title>
<meta name="description" content="<?= e(s('meta_desc')) ?>">
<script>
  try { if (localStorage.getItem('theme') === 'dark') document.documentElement.setAttribute('data-theme', 'dark'); } catch (e) {}
</script>
<style>
:root {
  --bg: #ffffff;
  --bg-alt: #f5f5f7;
  --card: #ffffff;
  --card-alt: #f5f5f7;
  --text: #1d1d1f;
  --muted: #6e6e73;
  --line: #e5e5ea;
  --accent: #0071e3;
  --accent-soft: #e8f2fd;
  --green: #1d8a3a;
  --shadow: 0 10px 30px rgba(0,0,0,.08);
  --radius: 28px;
}
:root[data-theme="dark"] {
  --bg: #000000;
  --bg-alt: #111113;
  --card: #1c1c1e;
  --card-alt: #161618;
  --text: #f5f5f7;
  --muted: #a1a1a6;
  --line: #2c2c2e;
  --accent: #2997ff;
  --accent-soft: #0b2a4a;
  --green: #30d158;
  --shadow: 0 10px 30px rgba(0,0,0,.5);
}
* { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, "Helvetica Neue", Arial, sans-serif;
  background: var(--bg); color: var(--text); line-height: 1.5;
  -webkit-font-smoothing: antialiased; transition: background .3s, color .3s;
}
a { color: inherit; text-decoration: none; }
.container { max-width: 1080px; margin: 0 auto; padding: 0 16px; }
.eyebrow { color: var(--accent); font-weight: 500; font-size: 17px; margin-bottom: 8px; }
.section { padding: 100px 0; }
.section.alt { background: var(--bg-alt); }
.section-head { text-align: center; margin-bottom: 56px; }
.section-head h2 { font-size: clamp(32px, 5vw, 52px); font-weight: 600; letter-spacing: -.02em; line-height: 1.1; }
.section-head p { color: var(--muted); font-size: 21px; margin-top: 10px; }
.link { color: var(--accent); font-size: 17px; }
.link:hover { text-decoration: underline; }
.muted { color: var(--muted); }

/* ---------- Header ---------- */
header {
  position: sticky; top: 0; z-index: 50;
  background: color-mix(in srgb, var(--bg) 85%, transparent);
  backdrop-filter: saturate(180%) blur(20px); -webkit-backdrop-filter: saturate(180%) blur(20px);
  border-bottom: 1px solid var(--line);
}
.nav { display: flex; align-items: center; justify-content: space-between; height: 66px; max-width: 1240px; margin: 0 auto; padding: 0 16px; }
.logo { display: flex; align-items: center; gap: 10px; }
.logo-mark {
  width: 38px; height: 38px; border-radius: 10px; display: grid; place-items: center;
  background: conic-gradient(from 200deg, #e53935, #fb8c00, #43a047, #1e88e5, #8e24aa, #e53935);
  color: #fff; font-weight: 800; font-size: 15px; letter-spacing: -.5px;
}
.logo-text { line-height: 1; }
.logo-text b { font-size: 22px; font-weight: 800; letter-spacing: -.5px; color: #1a3fa8; }
:root[data-theme="dark"] .logo-text b { color: #6ea8ff; }
.logo-text b span { color: #e53935; }
.logo-text small { display: block; font-size: 9px; color: var(--muted); margin-top: 3px; letter-spacing: .3px; }
.menu { display: flex; align-items: center; gap: 30px; list-style: none; font-size: 15px; }
.menu > li { position: relative; }
.menu > li > a { display: flex; align-items: center; gap: 6px; padding: 8px 0; opacity: .9; }
.menu > li > a:hover { opacity: 1; color: var(--accent); }
.caret { width: 10px; height: 10px; border: solid currentColor; border-width: 0 2px 2px 0; transform: rotate(45deg) translateY(-3px); }
.dropdown {
  position: absolute; top: 100%; left: -16px; min-width: 220px; list-style: none;
  background: var(--card); border: 1px solid var(--line); border-radius: 14px; box-shadow: var(--shadow);
  padding: 8px; opacity: 0; visibility: hidden; transform: translateY(6px); transition: .2s;
}
.menu > li:hover .dropdown, .menu > li:focus-within .dropdown { opacity: 1; visibility: visible; transform: none; }
.dropdown a { display: block; padding: 9px 12px; border-radius: 8px; font-size: 14px; }
.dropdown a:hover { background: var(--bg-alt); }
.theme-btn {
  width: 38px; height: 38px; border-radius: 50%; border: 1px solid var(--line); background: var(--card);
  color: var(--text); cursor: pointer; display: grid; place-items: center; box-shadow: 0 2px 8px rgba(0,0,0,.08);
}
.theme-btn svg { width: 16px; height: 16px; }
.burger { display: none; background: none; border: 0; color: var(--text); cursor: pointer; }
.burger svg { width: 26px; height: 26px; }

/* ---------- Hero ---------- */
.hero { text-align: center; padding: 50px 0 40px; }
.hero h1 { font-size: clamp(40px, 8vw, 84px); font-weight: 600; letter-spacing: -.03em; line-height: 1.07; margin: 6px auto 22px; max-width: 980px; }
.hero h1 .grey { color: var(--muted); }
.hero .lead { font-size: clamp(18px, 2.4vw, 22px); color: var(--muted); max-width: 640px; margin: 0 auto 30px; }
.btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.btn { padding: 12px 24px; border-radius: 999px; font-size: 17px; border: 1px solid transparent; transition: .2s; }
.btn-primary { background: var(--accent); color: #fff; }
.btn-primary:hover { filter: brightness(1.1); }
.btn-outline { border-color: var(--muted); }
.btn-outline:hover { background: var(--bg-alt); }
.trust { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px 26px; list-style: none; margin-top: 28px; font-size: 14px; color: var(--muted); }
.trust li::before { content: "✓"; color: var(--green); margin-right: 8px; font-weight: 700; }
.stats { display: flex; justify-content: center; flex-wrap: wrap; margin-top: 32px; }
.stat { padding: 0 22px; border-left: 1px solid var(--line); text-align: center; }
.stat:first-child { border-left: 0; }
.stat b { font-size: 19px; font-weight: 600; display: block; }
.stat span { font-size: 13px; color: var(--muted); }

/* ---------- Showcase tabs ---------- */
.showcase { max-width: 780px; margin: 56px auto 0; background: var(--bg-alt); border-radius: 32px; padding: 18px; }
.tabs { display: flex; background: color-mix(in srgb, var(--muted) 18%, transparent); border-radius: 999px; padding: 4px; max-width: 460px; margin: 0 auto 18px; }
.tab { flex: 1; border: 0; background: none; color: var(--text); padding: 9px 10px; border-radius: 999px; font: inherit; font-size: 15px; font-weight: 500; cursor: pointer; transition: .2s; }
.tab.active { background: var(--card); box-shadow: 0 2px 8px rgba(0,0,0,.12); }
.stage { background: var(--card); border-radius: 22px; padding: 18px; }
.panel { display: none; background: var(--bg-alt); border-radius: 16px; min-height: 300px; place-items: center; padding: 24px; }
.panel.active { display: grid; animation: fade .4s; }
@keyframes fade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }

/* mock creatives */
.ad300 { width: 300px; height: 250px; border: 1px solid #222; background: linear-gradient(180deg,#fff,#f1ece4); position: relative; overflow: hidden; text-align: center; color: #222; }
.ad300 .brand { margin-top: 14px; font-size: 11px; font-weight: 700; letter-spacing: 2px; color: #c62828; }
.ad300 h4 { font-size: 21px; font-weight: 500; line-height: 1.2; margin-top: 10px; }
.ad300 h4 b { display: block; font-weight: 800; }
.ad300 .box { position: absolute; bottom: 50px; left: 50%; width: 130px; height: 70px; transform: translateX(-50%); background: linear-gradient(#c8a06e,#a57c4b); border-radius: 4px; animation: bob 2.4s ease-in-out infinite; }
.ad300 .box::before { content: ""; position: absolute; top: -26px; left: 18px; width: 40px; height: 34px; background: #e57373; border-radius: 6px; transform: rotate(-14deg); }
.ad300 .box::after { content: ""; position: absolute; top: -30px; right: 18px; width: 44px; height: 36px; background: #ef9a9a; border-radius: 6px; transform: rotate(12deg); }
@keyframes bob { 50% { transform: translate(-50%, -6px); } }
.ad300 .cta { position: absolute; bottom: 14px; left: 50%; transform: translateX(-50%); background: #7cb342; color: #fff; padding: 6px 18px; font-size: 14px; }

.edm { width: 320px; background: #fff; color: #222; box-shadow: var(--shadow); font-size: 12px; }
.edm .top { background: #0b2545; color: #fff; text-align: center; padding: 6px; font-size: 10px; }
.edm .hd { text-align: center; padding: 16px; }
.edm .hd b { color: #1565c0; font-size: 22px; letter-spacing: 1px; }
.edm .hd p { font-size: 15px; color: #1565c0; margin-top: 8px; font-weight: 600; }
.edm .img { height: 110px; background: linear-gradient(135deg,#90caf9,#5c6bc0); }
.edm .body { padding: 14px; color: #555; }
.edm .body i { display: block; height: 8px; background: #eee; border-radius: 4px; margin-bottom: 6px; }
.edm .body i:nth-child(2) { width: 80%; } .edm .body i:nth-child(3) { width: 60%; }

.site { width: 100%; max-width: 460px; background: #fff; color: #222; border-radius: 10px; overflow: hidden; box-shadow: var(--shadow); }
.site .bar { background: #eee; padding: 8px; display: flex; gap: 5px; }
.site .bar i { width: 9px; height: 9px; border-radius: 50%; background: #ccc; }
.site .bar i:nth-child(1) { background: #ff5f57; } .site .bar i:nth-child(2) { background: #febc2e; } .site .bar i:nth-child(3) { background: #28c840; }
.site .hero-m { background: linear-gradient(120deg,#111,#2e7d32); color: #fff; padding: 34px 20px; }
.site .hero-m b { font-size: 22px; display: block; } .site .hero-m span { font-size: 12px; opacity: .8; }
.site .cols { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; padding: 14px; }
.site .cols i { height: 54px; background: #f1f1f1; border-radius: 6px; }

.motion { width: 100%; max-width: 460px; aspect-ratio: 16/9; border-radius: 14px; position: relative; overflow: hidden; background: linear-gradient(135deg,#6a11cb,#2575fc); display: grid; place-items: center; }
.motion .orb { position: absolute; width: 110px; height: 110px; border-radius: 50%; background: rgba(255,255,255,.18); animation: orbit 5s linear infinite; }
.motion .orb:nth-child(2) { width: 60px; height: 60px; animation-duration: 3.5s; animation-direction: reverse; background: rgba(255,214,0,.5); }
@keyframes orbit { from { transform: rotate(0) translateX(90px) rotate(0); } to { transform: rotate(360deg) translateX(90px) rotate(-360deg); } }
.motion .play { width: 64px; height: 64px; border-radius: 50%; background: #fff; display: grid; place-items: center; z-index: 1; box-shadow: 0 8px 24px rgba(0,0,0,.25); }
.motion .play::after { content: ""; border-left: 20px solid #2575fc; border-top: 12px solid transparent; border-bottom: 12px solid transparent; margin-left: 5px; }

/* ---------- Clients ---------- */
.clients { display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 18px 46px; padding: 50px 0 10px; }
.clients span { font-size: 22px; font-weight: 700; color: var(--muted); opacity: .7; letter-spacing: -.5px; filter: grayscale(1); }
.clients span:nth-child(2n) { font-family: Georgia, serif; font-style: italic; font-weight: 400; }
.clients em { font-size: 14px; color: var(--muted); font-style: normal; }

/* ---------- Services ---------- */
.grid2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
.svc { background: var(--card-alt); border-radius: var(--radius); padding: 40px 34px 0; overflow: hidden; display: flex; flex-direction: column; min-height: 470px; }
.svc h3 { font-size: 26px; font-weight: 600; }
.svc p { color: var(--muted); font-size: 16px; margin: 6px 0 12px; max-width: 340px; }
.svc .shot { margin-top: auto; height: 230px; border-radius: 18px 18px 0 0; background: #fff; box-shadow: var(--shadow); overflow: hidden; position: relative; }
.shot-banner { display: grid; grid-template-columns: 90px 1fr; gap: 12px; padding: 16px; height: 100%; }
.shot-banner .sky { background: linear-gradient(180deg,#ede7f6,#7e57c2); border-radius: 4px; }
.shot-banner .stack { display: grid; gap: 8px; align-content: start; }
.shot-banner .stack i { display: block; border-radius: 4px; background: linear-gradient(90deg,#f3e5f5,#9575cd); }
.shot-banner .stack i:nth-child(1) { height: 70px; } .shot-banner .stack i:nth-child(2) { height: 24px; width: 75%; } .shot-banner .stack i:nth-child(3) { height: 18px; width: 50%; }
.shot-edm { background: #f6efe6 !important; display: grid; justify-items: center; }
.shot-edm .mail { width: 75%; background: #fff; height: 100%; }
.shot-edm .mail .t { background: #0b2545; height: 16px; }
.shot-edm .mail b { display: block; text-align: center; color: #1565c0; font-size: 20px; padding: 10px 0 2px; }
.shot-edm .mail p { text-align: center; color: #1565c0; font-size: 13px; font-weight: 600; margin: 0 auto; padding: 0 10px; }
.shot-edm .mail .ph { height: 110px; margin-top: 10px; background: linear-gradient(135deg,#b0bec5,#546e7a); }
.shot-web .nav-m { background: #111; height: 28px; display: flex; align-items: center; padding: 0 12px; gap: 6px; }
.shot-web .nav-m b { color: #43a047; font-size: 13px; letter-spacing: 2px; }
.shot-web .hero-w { height: 120px; background: linear-gradient(120deg,#1b5e20,#81c784); }
.shot-web .row { display: flex; gap: 8px; padding: 12px; } .shot-web .row i { flex: 1; height: 40px; background: #eee; border-radius: 6px; }
.shot-motion { background: linear-gradient(135deg,#ff512f,#dd2476) !important; display: grid; place-items: center; }
.shot-motion .reel { display: flex; gap: 8px; animation: slide 6s linear infinite; }
.shot-motion .reel i { width: 70px; height: 110px; border-radius: 10px; background: rgba(255,255,255,.25); border: 2px solid rgba(255,255,255,.5); }
@keyframes slide { 50% { transform: translateX(-40px); } }

/* ---------- Process ---------- */
.grid4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.step { background: var(--card); border-radius: 24px; padding: 26px; }
.step .num { width: 34px; height: 34px; border-radius: 50%; background: var(--accent-soft); color: var(--accent); display: grid; place-items: center; font-weight: 600; font-size: 14px; margin-bottom: 20px; }
.step h3 { font-size: 20px; font-weight: 600; margin-bottom: 6px; }
.step p { color: var(--muted); font-size: 15px; }
.center { text-align: center; margin-top: 40px; }

/* ---------- Work ---------- */
.grid3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.work { border-radius: 24px; overflow: hidden; background: var(--card-alt); transition: transform .3s; }
.work:hover { transform: translateY(-4px); }
.work .thumb { height: 260px; position: relative; overflow: hidden; }
.w1 { background: linear-gradient(135deg,#1b1b1b,#3a3a3a); }
.w1::before { content: ""; position: absolute; left: 50%; top: 50%; width: 180px; height: 60px; transform: translate(-50%,-50%); background: #76ff03; border-radius: 40px 60px 14px 14px; box-shadow: 0 30px 40px rgba(118,255,3,.25); }
.w1::after { content: "160×600  ·  970×250  ·  728×90"; position: absolute; top: 14px; left: 14px; color: #aaa; font-size: 11px; }
.w2 { background: linear-gradient(135deg,#f5f0e6,#d7b47a); }
.w2::before { content: "OUR MISSION"; position: absolute; left: 24px; top: 40px; background: #fff; padding: 30px 20px; font-size: 13px; font-weight: 700; letter-spacing: 1px; color: #222; box-shadow: var(--shadow); }
.w2::after { content: ""; position: absolute; right: 30px; top: 40px; width: 120px; height: 140px; border-radius: 10px; background: linear-gradient(#8d6e63,#4e342e); }
.w3 { background: radial-gradient(circle at 50% 40%,#ff3b3b,#b00000); }
.w3::before { content: "Participate in the Raffle"; position: absolute; top: 70px; width: 100%; text-align: center; color: #fff; font-weight: 700; font-size: 20px; }
.w3::after { content: "Enter Your Code"; position: absolute; top: 130px; left: 50%; transform: translateX(-50%); background: #d50000; color: #fff; padding: 10px 22px; border-radius: 6px; font-size: 14px; border: 1px solid rgba(255,255,255,.3); }
.work .info { padding: 22px 24px 26px; }
.work .info small { color: var(--muted); font-size: 14px; }
.work .info h3 { font-size: 20px; font-weight: 600; margin-top: 4px; }

/* ---------- Compare ---------- */
.table-wrap { max-width: 940px; margin: 0 auto; background: var(--card); border-radius: 28px; overflow-x: auto; }
table { width: 100%; border-collapse: collapse; min-width: 600px; }
th, td { padding: 20px 26px; text-align: center; border-bottom: 1px solid var(--line); font-size: 15px; }
th:first-child, td:first-child { text-align: left; }
th { font-weight: 500; color: var(--muted); padding-top: 34px; }
tr:last-child td { border-bottom: 0; }
.rec { background: var(--accent-soft); }
th.rec { color: var(--accent); font-weight: 600; }
th.rec small { display: block; font-size: 12px; font-weight: 500; margin-bottom: 4px; }
.tick { color: var(--green); font-size: 18px; }
.dash { color: var(--muted); }
.ok { display: inline-grid; place-items: center; width: 26px; height: 26px; border-radius: 50%; background: var(--accent); color: #fff; font-size: 14px; }

/* ---------- Timeline ---------- */
.city { border-radius: 24px; overflow: hidden; background: var(--card-alt); text-align: center; }
.city .sky { height: 200px; position: relative; overflow: hidden; }
.sky.dusk { background: linear-gradient(180deg,#1e3c72 0%,#f7971e 75%,#ffd200 100%); }
.sky.night { background: linear-gradient(180deg,#020024 0%,#0b1d51 60%,#1a3a8a 100%); }
.sky.dawn { background: linear-gradient(180deg,#4facfe 0%,#ffd194 75%,#f6a96b 100%); }
.sky .sun { position: absolute; width: 50px; height: 50px; border-radius: 50%; background: #fff3b0; box-shadow: 0 0 40px #ffd54f; left: 60%; bottom: 40px; }
.sky.night .sun { width: 26px; height: 26px; background: transparent; box-shadow: -6px 4px 0 0 #fff8dc; left: 18%; top: 26px; bottom: auto; }
.sky .skyline { position: absolute; bottom: 0; left: 0; right: 0; height: 90px;
  background:
    linear-gradient(#0d1b3d,#0d1b3d) 6% 100%/9% 55% no-repeat,
    linear-gradient(#0d1b3d,#0d1b3d) 17% 100%/7% 85% no-repeat,
    linear-gradient(#0d1b3d,#0d1b3d) 27% 100%/11% 65% no-repeat,
    linear-gradient(#0d1b3d,#0d1b3d) 41% 100%/6% 100% no-repeat,
    linear-gradient(#0d1b3d,#0d1b3d) 50% 100%/10% 70% no-repeat,
    linear-gradient(#0d1b3d,#0d1b3d) 63% 100%/8% 90% no-repeat,
    linear-gradient(#0d1b3d,#0d1b3d) 74% 100%/12% 60% no-repeat,
    linear-gradient(#0d1b3d,#0d1b3d) 88% 100%/9% 80% no-repeat,
    linear-gradient(#0d1b3d,#0d1b3d) 98% 100%/6% 50% no-repeat; }
.sky.night .skyline { background-color: transparent; filter: drop-shadow(0 0 1px #ffd54f); }
.city .info { padding: 22px; }
.city b { font-size: 30px; font-weight: 600; display: block; }
.city strong { font-size: 17px; font-weight: 600; display: block; }
.city span { color: var(--muted); font-size: 14px; }

/* ---------- CTA ---------- */
.cta-band { text-align: center; }
.cta-band h2 { font-size: clamp(32px, 5vw, 52px); font-weight: 600; letter-spacing: -.02em; }
.cta-band p { color: var(--muted); font-size: 20px; margin: 10px 0 28px; }

/* ---------- Footer ---------- */
footer { background: var(--bg-alt); padding: 60px 0 30px; font-size: 14px; border-top: 1px solid var(--line); }
.foot { display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr 2fr; gap: 30px; max-width: 1180px; margin: 0 auto; padding: 0 16px; }
.foot h4 { font-size: 14px; font-weight: 600; margin-bottom: 14px; }
.foot ul { list-style: none; }
.foot li { margin-bottom: 9px; }
.foot li a { color: var(--muted); }
.foot li a:hover { color: var(--accent); }
.foot .about p { color: var(--muted); margin: 6px 0 20px; }
.foot .contact li { display: flex; gap: 10px; color: var(--muted); }
.tools { columns: 2; column-gap: 24px; }
.copy { max-width: 1180px; margin: 40px auto 0; padding: 20px 16px 0; border-top: 1px solid var(--line); color: var(--muted); font-size: 13px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; }

/* ---------- Uploaded media ---------- */
.logo-img { height: 44px; width: auto; display: block; }
.panel-media { max-width: 100%; max-height: 460px; border-radius: 10px; display: block; }
.client-logo { height: 30px; width: auto; filter: grayscale(1); opacity: .7; }
.svc .shot.has-media { background: none; }
.svc .shot img, .svc .shot video,
.work .thumb img, .work .thumb video,
.city .sky img, .city .sky video { width: 100%; height: 100%; object-fit: cover; display: block; }
.cta-band.has-bg { position: relative; overflow: hidden; color: #fff; }
.cta-band.has-bg p { color: rgba(255,255,255,.85); }
.cta-band.has-bg .btn-outline { border-color: #fff; color: #fff; }
.cta-bg { position: absolute; inset: 0; }
.cta-bg img, .cta-bg video { width: 100%; height: 100%; object-fit: cover; }
.cta-bg::after { content: ""; position: absolute; inset: 0; background: rgba(0,0,0,.55); }
.cta-band.has-bg .container { position: relative; }

/* ---------- Reveal ---------- */
.reveal { opacity: 0; transform: translateY(24px); transition: opacity .7s, transform .7s; }
.reveal.in { opacity: 1; transform: none; }

/* ---------- Responsive ---------- */
@media (max-width: 960px) {
  .grid4 { grid-template-columns: repeat(2, 1fr); }
  .grid3 { grid-template-columns: 1fr; max-width: 480px; margin: 0 auto; }
  .foot { grid-template-columns: 1fr 1fr; }
  .foot .about, .foot .tools-col { grid-column: 1 / -1; }
}
@media (max-width: 860px) {
  .burger { display: block; }
  .menu { position: fixed; top: 66px; left: 0; right: 0; flex-direction: column; align-items: stretch; gap: 0; background: var(--bg); border-bottom: 1px solid var(--line); padding: 8px 16px 16px; display: none; max-height: calc(100vh - 66px); overflow-y: auto; }
  .menu.open { display: flex; }
  .menu > li > a { padding: 14px 0; border-bottom: 1px solid var(--line); }
  .dropdown { position: static; opacity: 1; visibility: visible; transform: none; box-shadow: none; border: 0; padding: 0 0 0 12px; background: none; }
  .grid2 { grid-template-columns: 1fr; }
  .section { padding: 70px 0; }
}
@media (max-width: 560px) {
  .grid4 { grid-template-columns: 1fr; }
  .stat { padding: 8px 14px; flex: 1 1 45%; border-left: 0; }
  .ad300 { transform: scale(.9); }
  .edm { width: 100%; }
  .svc { padding: 30px 22px 0; min-height: 420px; }
  .foot { grid-template-columns: 1fr; }
  .tools { columns: 1; }
}
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation: none !important; transition: none !important; }
  .reveal { opacity: 1; transform: none; }
}
</style>
</head>
<body>

<!-- ================= HEADER ================= -->
<header>
  <nav class="nav">
    <a href="home.php" class="logo" aria-label="<?= e($site) ?> home">
      <?php if (s('logo')): ?>
        <?= media(s('logo'), $site, 'logo-img') ?>
      <?php else: ?>
        <span class="logo-mark">A2Z</span>
        <span class="logo-text"><b><?= e(mb_substr($site, 0, 3)) ?><span><?= e(mb_substr($site, 3)) ?></span></b><small><?= e(s('site_tagline')) ?></small></span>
      <?php endif; ?>
    </a>

    <ul class="menu" id="menu">
      <?php foreach ($menu as $m): $sub = dropdown($m['extra']); ?>
        <li>
          <a href="<?= e($m['link']) ?>"><?= e($m['title']) ?><?php if ($sub): ?> <i class="caret"></i><?php endif; ?></a>
          <?php if ($sub): ?>
            <ul class="dropdown">
              <?php foreach ($sub as $d): ?><li><a href="<?= e($d[1]) ?>"><?= e($d[0]) ?></a></li><?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <div style="display:flex;gap:10px;align-items:center">
      <button class="theme-btn" id="themeBtn" aria-label="Toggle dark mode">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
      </button>
      <button class="burger" id="burger" aria-label="Open menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      </button>
    </div>
  </nav>
</header>

<main>
<!-- ================= HERO ================= -->
<section class="hero">
  <div class="container">
    <p class="eyebrow"><?= e(s('hero_eyebrow')) ?></p>
    <h1><?= e(s('hero_title')) ?> <span class="grey"><?= e(s('hero_title2')) ?></span></h1>
    <p class="lead"><?= e(s('hero_lead')) ?></p>
    <div class="btns">
      <?php if (s('hero_btn1')): ?><a href="<?= e(s('hero_btn1_url')) ?>" class="btn btn-primary"><?= e(s('hero_btn1')) ?></a><?php endif; ?>
      <?php if (s('hero_btn2')): ?><a href="<?= e(s('hero_btn2_url')) ?>" class="btn btn-outline"><?= e(s('hero_btn2')) ?></a><?php endif; ?>
    </div>
    <ul class="trust">
      <?php foreach (items('trust') as $t): ?><li><?= e($t['title']) ?></li><?php endforeach; ?>
    </ul>
    <div class="stats">
      <?php foreach (items('stats') as $st): ?>
        <div class="stat"><b><?= e($st['title']) ?></b><span><?= e($st['subtitle']) ?></span></div>
      <?php endforeach; ?>
    </div>

    <?php if ($showcase): ?>
    <div class="showcase reveal">
      <div class="tabs" role="tablist">
        <?php foreach ($showcase as $i => $t): ?>
          <button class="tab<?= $i === 0 ? ' active' : '' ?>" data-tab="p-<?= (int)$t['id'] ?>"><?= e($t['title']) ?></button>
        <?php endforeach; ?>
      </div>
      <div class="stage">
        <?php foreach ($showcase as $i => $t): ?>
          <div class="panel<?= $i === 0 ? ' active' : '' ?>" id="p-<?= (int)$t['id'] ?>">
            <?= $t['media'] ? media($t['media'], $t['title'], 'panel-media') : mock($t['extra']) ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="clients">
      <?php foreach (items('clients') as $c): ?>
        <?= $c['media'] ? media($c['media'], $c['title'], 'client-logo') : '<span>' . e($c['title']) . '</span>' ?>
      <?php endforeach; ?>
      <?php if (s('clients_more')): ?><em><?= e(s('clients_more')) ?></em><?php endif; ?>
    </div>
  </div>
</section>

<!-- ================= SERVICES ================= -->
<section class="section" id="services">
  <div class="container">
    <div class="section-head reveal">
      <p class="eyebrow"><?= e(s('services_eyebrow')) ?></p>
      <h2><?= e(s('services_title')) ?></h2>
    </div>
    <div class="grid2">
      <?php foreach (items('services') as $sv): ?>
        <article class="svc reveal">
          <h3><?= e($sv['title']) ?></h3>
          <p><?= e($sv['body']) ?></p>
          <?php if ($sv['link']): ?><a href="<?= e($sv['link']) ?>" class="link">Learn more ›</a><?php endif; ?>
          <?php if ($sv['media']): ?>
            <div class="shot has-media"><?= media($sv['media'], $sv['title']) ?></div>
          <?php else: ?>
            <div class="shot shot-<?= e($sv['extra']) ?>"><?= shot_mock($sv['extra']) ?></div>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= PROCESS ================= -->
<section class="section alt">
  <div class="container">
    <div class="section-head reveal">
      <p class="eyebrow"><?= e(s('process_eyebrow')) ?></p>
      <h2><?= e(s('process_title')) ?></h2>
    </div>
    <div class="grid4">
      <?php foreach (items('process') as $i => $p): ?>
        <div class="step reveal">
          <div class="num"><?= $i + 1 ?></div>
          <h3><?= e($p['title']) ?></h3>
          <p><?= e($p['body']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if (s('process_link')): ?><div class="center"><a href="#contact" class="link"><?= e(s('process_link')) ?></a></div><?php endif; ?>
  </div>
</section>

<!-- ================= WORK ================= -->
<section class="section" id="work">
  <div class="container">
    <div class="section-head reveal">
      <p class="eyebrow"><?= e(s('work_eyebrow')) ?></p>
      <h2><?= e(s('work_title')) ?></h2>
    </div>
    <div class="grid3">
      <?php foreach (items('work') as $w): ?>
        <a href="<?= e($w['link'] ?: '#') ?>" class="work reveal">
          <?php if ($w['media']): ?>
            <div class="thumb"><?= media($w['media'], $w['title']) ?></div>
          <?php else: ?>
            <div class="thumb <?= e($w['extra']) ?>"></div>
          <?php endif; ?>
          <div class="info"><small><?= e($w['subtitle']) ?></small><h3><?= e($w['title']) ?></h3></div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= COMPARE ================= -->
<section class="section alt" id="compare">
  <div class="container">
    <div class="section-head reveal">
      <p class="eyebrow"><?= e(s('compare_eyebrow')) ?></p>
      <h2><?= e(s('compare_title')) ?></h2>
    </div>
    <div class="table-wrap reveal">
      <table>
        <thead>
          <tr><th>Capability</th><th><?= e(s('compare_col1')) ?></th><th><?= e(s('compare_col2')) ?></th><th class="rec"><small><?= e(s('compare_badge')) ?></small><?= e(s('compare_col3')) ?></th></tr>
        </thead>
        <tbody>
          <?php foreach (items('compare') as $r): ?>
            <tr>
              <td><?= e($r['title']) ?></td>
              <td><?= cell($r['subtitle']) ?></td>
              <td><?= cell($r['body']) ?></td>
              <td class="rec"><span class="ok">✓</span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- ================= TIMELINE ================= -->
<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <p class="eyebrow"><?= e(s('sleep_eyebrow')) ?></p>
      <h2><?= e(s('sleep_title')) ?></h2>
      <?php if (s('sleep_sub')): ?><p><?= e(s('sleep_sub')) ?></p><?php endif; ?>
    </div>
    <div class="grid3">
      <?php foreach (items('timeline') as $t): ?>
        <div class="city reveal">
          <?php if ($t['media']): ?>
            <div class="sky"><?= media($t['media'], $t['subtitle']) ?></div>
          <?php else: ?>
            <div class="sky <?= e($t['extra']) ?>"><span class="sun"></span><span class="skyline"></span></div>
          <?php endif; ?>
          <div class="info"><b><?= e($t['title']) ?></b><strong><?= e($t['subtitle']) ?></strong><span><?= e($t['body']) ?></span></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= CTA ================= -->
<section class="section alt cta-band<?= s('cta_media') ? ' has-bg' : '' ?>" id="contact">
  <?php if (s('cta_media')): ?><div class="cta-bg"><?= media(s('cta_media')) ?></div><?php endif; ?>
  <div class="container reveal">
    <h2><?= e(s('cta_title')) ?></h2>
    <p><?= e(s('cta_text')) ?></p>
    <div class="btns">
      <?php if (s('cta_btn')): ?><a href="<?= e(s('cta_btn_url')) ?>" class="btn btn-primary"><?= e(s('cta_btn')) ?></a><?php endif; ?>
      <?php if (s('hero_btn2')): ?><a href="<?= e(s('hero_btn2_url')) ?>" class="btn btn-outline"><?= e(s('hero_btn2')) ?></a><?php endif; ?>
    </div>
  </div>
</section>
</main>

<!-- ================= FOOTER ================= -->
<footer>
  <div class="foot">
    <div class="about">
      <h4><?= e($site) ?></h4>
      <p><?= e(s('footer_about')) ?></p>
      <ul class="contact">
        <?php if (s('email')): ?><li>✉ <a href="mailto:<?= e(s('email')) ?>"><?= e(s('email')) ?></a></li><?php endif; ?>
        <?php if (s('phone')): ?><li>☎ <a href="tel:<?= e(preg_replace('/[^\d+]/', '', s('phone'))) ?>"><?= e(s('phone')) ?></a></li><?php endif; ?>
        <?php if (s('address')): ?><li>📍 <span><?= nl2br(e(s('address'))) ?></span></li><?php endif; ?>
      </ul>
    </div>
    <?php foreach (['footer_company' => 'Company', 'footer_services' => 'Services', 'footer_resources' => 'Resources'] as $sec => $label): ?>
      <div>
        <h4><?= e($label) ?></h4>
        <ul>
          <?php foreach (items($sec) as $l): ?><li><a href="<?= e($l['link'] ?: '#') ?>"><?= e($l['title']) ?></a></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
    <div class="tools-col">
      <h4>Free Tools</h4>
      <ul class="tools">
        <?php foreach (items('footer_tools') as $l): ?><li><a href="<?= e($l['link'] ?: '#') ?>"><?= e($l['title']) ?></a></li><?php endforeach; ?>
      </ul>
    </div>
  </div>
  <div class="copy">
    <span>© <?= date('Y') ?> <?= e($site) ?>. <?= e(s('copyright')) ?></span>
    <span><?= e(s('footer_right')) ?></span>
  </div>
</footer>

<script>
// Theme toggle
document.getElementById('themeBtn').addEventListener('click', function () {
  var root = document.documentElement;
  var dark = root.getAttribute('data-theme') === 'dark';
  if (dark) root.removeAttribute('data-theme'); else root.setAttribute('data-theme', 'dark');
  try { localStorage.setItem('theme', dark ? 'light' : 'dark'); } catch (e) {}
});

// Mobile menu
var menu = document.getElementById('menu');
document.getElementById('burger').addEventListener('click', function () { menu.classList.toggle('open'); });
menu.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { menu.classList.remove('open'); }); });

// Showcase tabs
document.querySelectorAll('.tab').forEach(function (tab) {
  tab.addEventListener('click', function () {
    document.querySelectorAll('.tab').forEach(function (t) { t.classList.remove('active'); });
    document.querySelectorAll('.panel').forEach(function (p) { p.classList.remove('active'); });
    tab.classList.add('active');
    document.getElementById(tab.dataset.tab).classList.add('active');
  });
});

// Scroll reveal
var io = new IntersectionObserver(function (entries) {
  entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
</script>
</body>
</html>
