<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DYMM - Connect Through Activities & Dating</title>
    <meta name="description" content="Join DYMM to create or join activities, meet like-minded people, and make meaningful connections. Find your perfect match through shared interests and our unique Cupid feature.">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/svg+xml" href="/lovable-Uploads/645829d6-708c-4ab2-9a74-b01a81d3b93b.png">
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide-icons.js" rel="stylesheet">
    <style>
        @import ('~lucide-static/font/Lucide.css');
        </style>
</head>
<style>
    .haalat svg {
    color: #e5e3e8;
}
.haalat {
    display: flex;
    align-items: center;
    gap: 10px;
}

    .purple-blur-circle {
  position: absolute;
  top: -2.5rem;      /* -top-10 => -40px */
  left: -2.5rem;     /* -left-10 => -40px */
  width: 10rem;      /* w-40 => 160px */
  height: 10rem;     /* h-40 => 160px */
  background-color: #8000807a; /* Replace with actual RGB value */
  border-radius: 9999px;  /* rounded-full */
  filter: blur(64px);     /* blur-3xl */
}
.blue {
    position: absolute;
    top: 17.5rem;
    left: 17.5rem;
    width: 10rem;
    height: 10rem;
    background-color: #0000ff33;
    border-radius: 9999px;
    filter: blur(64px);
}

    .snow {
    display: flex;
    align-items: center;
    margin-top: 10px;
    gap: 10px;
}
.cupid-card {
    transform: rotate(5deg);
    transition: transform 0.3s ease;
}

.rounded-full {
    border-radius: 9999px;
}
.bg-dymm-pink\/10 {
    background-color: rgb(255 105 180 / 0.1);
}
    /* From Uiverse.io by satyamchaudharydev */ 
/* The switch - the box around the slider */
.switch {
  display: block;
  --width-of-switch: 2.9em;
  --height-of-switch: 1.5em;
  /* size of sliding icon -- sun and moon */
  --size-of-icon: 1.2em;
  /* it is like a inline-padding of switch */
  --slider-offset: 0.3em;
  position: relative;
  width: var(--width-of-switch);
  height: var(--height-of-switch);
}
.text-dymm-purple {
    --tw-text-opacity: 1;
    color: rgb(138 43 226 / var(--tw-text-opacity, 1));
}
.text-dymm-pink {
    --tw-text-opacity: 1;
    color: rgb(255 105 180 / var(--tw-text-opacity, 1));
}
.text-dymm-teal {
    --tw-text-opacity: 1;
    color: rgb(32 178 170 / var(--tw-text-opacity, 1));
}
.dedssd p {
    color: #e5e3e8cc;
    font-size: 1.25rem;
    line-height: 1.75rem;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: left;
    margin-bottom: 10px;
}
/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #6a2bbe;
  transition: .4s;
  border-radius: 30px;
}

.slider:before {
  position: absolute;
  content: "";
  height: var(--size-of-icon,1.4em);
  width: var(--size-of-icon,1.4em);
  border-radius: 20px;
  left: var(--slider-offset,0.3em);
  top: 50%;
  transform: translateY(-50%);
  background: linear-gradient(40deg, #000000, #000000 70%);
  transition: .4s;
}

input:checked + .slider {
  background-color: #303136;
}

input:checked + .slider:before {
  left: calc(100% - (var(--size-of-icon,1.4em) + var(--slider-offset,0.3em)));
  background: #303136;
  /* change the value of second inset in box-shadow to change the angle and direction of the moon  */
  box-shadow: inset -3px -2px 5px -2px #8983f7, inset -10px -4px 0 0 #a3dafb;
}
/* Base Styles */
:root {
    --primary: #9b87f5;
    --primary-dark: #7E69AB;
    --accent: #d946ef2e;
    --dark: #131019;
    /* --dark-lighter: #2a2f3c; */
    /* --dark-darker: #161922; */
    --white: #ffffff;
}

body.light-mode {
    --primary: #6B46C1;
    --primary-dark: #553C9A;
    --accent: #ED64A6;
    --dark: #F7FAFC;
    --dark-lighter: #EDF2F7;
    --dark-darker: #E2E8F0;
    --white: #1A202C;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    background-color: var(--dark);
    color: var(--white);
    line-height: 1.6;
    transition: background-color 0.3s, color 0.3s;
}

.container {
    max-width: 1340px;
    margin: 0 auto;
    /* padding: 0 2rem; */
}

/* Utility Classes */
.gradient-text {
    background: linear-gradient(to right, #8a4de1, #e84cff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-size: 4.5rem;
}

.gradient-bg {
    background: linear-gradient(to bottom right, var(--primary), var(--accent));
}

/* Header Styles */
.header {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 50;
    backdrop-filter: blur(12px);
    background: #131019;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

body.light-mode .header {
    background: rgba(247, 250, 252, 0.8);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.header nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
}
a{
    text-decoration: none;
}
.logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.logo-text {
    font-size: 1.5rem;
    font-weight: bold;
    background: linear-gradient(to right, var(--primary), #d946ef);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.beta-badge {
    padding: 0.10rem 0.5rem;
    font-size: 0.75rem;
    font-weight: bold;
    background: #ffd700;
    color: #8a2be2;
    text-decoration: none;
    border-radius: 9999px;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.nav-links a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: color 0.3s;
}

body.light-mode .nav-links a {
    color: rgba(26, 32, 44, 0.7);
}

.nav-links a:hover {
    color: var(--white);
}

.download-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #8a2be2;
    color: var(--white);
    border: none;
    border-radius: 20px;
    font-weight: 500;
    cursor: pointer;
    transition: opacity 0.3s;
}

body.light-mode .download-btn {
    color: var(--dark);
}

.download-btn:hover {
    opacity: 0.9;
}

.theme-toggle-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: transparent;
    color: var(--white);
    border: 1px solid var(--primary);
    border-radius: 0.375rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s, color 0.3s;
}

.theme-toggle-btn:hover {
    background: var(--primary);
    color: var(--dark);
}

body.light-mode .theme-toggle-btn:hover {
    color: var(--white);
}

/* Hero Section */
.hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    padding: 4rem 0;
}

.hero .container {
    text-align: center;
    position: relative;
    z-index: 10;
}

.hero h1 {
    font-size: 3rem;
    margin-bottom: 1.5rem;
}

.hero p {
    font-size: 1.5rem;
    color: rgba(255, 255, 255, 0.7);
    max-width: 46rem;
    margin: 0 auto;
}

body.light-mode .hero p {
    color: rgba(26, 32, 44, 0.7);
}

.gradient-bg {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: linear-gradient(to bottom right, var(--dark), var(--dark-darker));
}

.pattern-overlay {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-image: url('/public/pattern.svg');
    opacity: 0.02;
}

body.light-mode .pattern-overlay {
    opacity: 0.1;
}

.scroll-indicator {
    margin-top: 3rem;
    animation: bounce 2s infinite;
}

@keyframes    bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

/* Features Section */
.features {
    padding: 5rem 0;
    background: var(--dark-lighter);
}

.features h2 {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 3rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.feature-card {
    padding: 1.5rem;
    border-radius: 0.75rem;
    backdrop-filter: blur(12px);
    background: rgb(255 255 255);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.3s;
    color: #000;
}

body.light-mode .feature-card {
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-card i {
    color: var(--accent);
    font-size: 2rem;
    margin-bottom: 1rem;
}

.feature-card h3 {
    margin-bottom: 0.5rem;
}

/* Activities Section */
.activities {
    padding: 5rem 0;
    background: var(--dark);
}

.activities span {
    text-align: center;
    font-size: 3.5rem;
    margin-bottom: 1rem;
}

.activities > p {
    text-align: center;
    color: rgba(255, 255, 255, 0.7);
    max-width: 36rem;
    margin: 0 auto 3rem;
}

body.light-mode .activities > p {
    color: rgba(26, 32, 44, 0.7);
}

.activities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.activity-card {
    padding: 1.5rem;
    border-radius: 0.75rem;
    transition: transform 0.3s;
}

.activity-card:hover {
    transform: scale(1.05);
}

.activity-card.yellow { background: #fef3c7; color: #92400e; }
.activity-card.white { background: #ffffff; color: #4c1d95; }
.activity-card.pink { background: #fce7f3; color: #be185d; }
.activity-card.blue { background: #dbeafe; color: #1e40af; }
.activity-card.purple { background: #f3e8ff; color: #6b21a8; }
.activity-card.green { background: #dcfce7; color: #15803d; }
.activity-card.cyan { background: #cffafe; color: #155e75; }

body.light-mode .activity-card.yellow { background: #FFF7E6; }
body.light-mode .activity-card.white { background: #F7FAFC; }
body.light-mode .activity-card.pink { background: #FFF5F7; }
body.light-mode .activity-card.blue { background: #EBF8FF; }
body.light-mode .activity-card.purple { background: #FAF5FF; }
body.light-mode .activity-card.green { background: #F0FFF4; }
body.light-mode .activity-card.cyan { background: #E6FFFA; }

/* Cupid Feature */
.cupid-feature {
    padding: 5rem 0;
    background: var(--dark);
}

.cupid-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 3rem;
    align-items: center;
}

.cupid-content h2 {
    font-size: 2rem;
    margin-bottom: 1.5rem;
}

.cupid-content > p {
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 2rem;
}

body.light-mode .cupid-content > p {
    color: rgba(26, 32, 44, 0.7);
}

.cupid-features {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.cupid-feature-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.cupid-feature-item i {
    color: var(--accent);
}

.cupid-card {
    padding: 2rem 1rem;
    border-radius: 1rem;
    backdrop-filter: blur(12px);
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
    position: relative;
    z-index: 10;
}

body.light-mode .cupid-card {
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.match-avatars {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 2rem 0;
}

.avatar {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.avatar:first-child { background: #7C3AED; }
.avatar:last-child { background: #0D9488; }

.heart {
    font-size: 1.5rem;
}

.match-btn {
    width: 100%;
    padding: 0.75rem;
    background: linear-gradient(to right, var(--primary), #d946ef);
    color: var(--white);
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: opacity 0.3s;
}

body.light-mode .match-btn {
    color: var(--dark);
}

.match-btn:hover {
    opacity: 0.9;
}

.disclaimer {
    margin-top: 1rem;
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.5);
}

body.light-mode .disclaimer {
    color: rgba(26, 32, 44, 0.5);
}

/* Gallery */
.gallery {
    padding: 5rem 0;
    background: var(--dark-lighter);
}

.gallery-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 3rem;
}

.gallery-header h2 {
    font-size: 1.5rem;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.gallery-item {
    position: relative;
    border-radius: 0.75rem;
    overflow: hidden;
    aspect-ratio: 1;
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    opacity: 0;
    transition: opacity 0.3s;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.gallery-item:hover img {
    transform: scale(1.05);
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-stats {
    display: flex;
    gap: 1rem;
    margin-top: 0.5rem;
}

/* Footer */
.footer {
    padding: 3rem 0;
    background: var(--dark-darker);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

body.light-mode .footer {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.footer-brand p {
    color: rgba(255, 255, 255, 0.7);
    margin-top: 1rem;
}

body.light-mode .footer-brand p {
    color: rgba(26, 32, 44, 0.7);
}

.footer h3 {
    color: var(--white);
    margin-bottom: 1rem;
}

.footer-links ul {
    list-style: none;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    line-height: 2;
    transition: color 0.3s;
}

body.light-mode .footer-links a {
    color: rgba(26, 32, 44, 0.7);
}

.footer-links a:hover {
    color: var(--white);
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-links a {
    color: rgba(255, 255, 255, 0.7);
    transition: color 0.3s;
}

body.light-mode .social-links a {
    color: rgba(26, 32, 44, 0.7);
}

.social-links a:hover {
    color: var(--white);
}

.footer-bottom {
    padding-top: 2rem;
    text-align: center;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

body.light-mode .footer-bottom {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.footer-bottom p {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.875rem;
}

body.light-mode .footer-bottom p {
    color: rgba(26, 32, 44, 0.5);
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero h1 {
        font-size: 2rem;
    }
    
    .cupid-grid {
        grid-template-columns: 1fr;
    }
    
    .nav-links {
        display: none;
    }
    
    .footer-grid {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .social-links {
        justify-content: center;
    }
}
.doodle-container {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 1;
    opacity: 0.1;
}

.gradient-overlay {
    position: absolute;
    inset: 0;
    background: #131019;
    z-index: 0;
}

    .icon {
      position: absolute;
      opacity: 0.7;
      animation: pulse 4s ease-in-out infinite, float 6s ease-in-out infinite;
    }

    .icon svg {
      width: 40px;
      height: 40px;
    }

    @keyframes    pulse {
      0%, 100% { filter: brightness(1); }
      50% { filter: brightness(1.3); }
    }

    @keyframes    float {
      0% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0); }
    }
@media (max-width: 480px) {
    .container {
        padding: 0 1rem;
    }
    
    .features-grid,
    .activities-grid {
        grid-template-columns: 1fr;
    }
}
/* Section Container */
.activities-section {
  position: relative;
  min-height: 80vh;
  display: flex;
  justify-content: center;
  align-items: center;
 
  overflow: hidden;
}

/* Glowing Box */
.activities-box {
  background-color: #0e0e1a;
  border-radius: 1rem;
  padding: 2.5rem 3rem;
  /* max-width: 500px; */
  text-align: center;
  position: relative;
  z-index: 2;
}

/* Heading */
.activities-box h2 {
  font-size: 2rem;
  font-weight: 700;
}

.highlight {
  background: linear-gradient(to right, #a35aff, #ff66c4);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* Text Content */
.description {
  margin-top: 1rem;
  color: #ccc;
}

.coming-soon {
  color: #ff66c4;
  font-weight: bold;
  margin-top: 1.5rem;
}

.waitlist-info {
  margin-top: 0.5rem;
  font-size: 0.9rem;
  color: #aaa;
}

/* Button */
.waitlist-btn {
  margin-top: 1.5rem;
  background: linear-gradient(to right, #8b5cf6, #ec4899);
  border: none;
  padding: 0.75rem 2rem;
  color: #fff;
  font-size: 1rem;
  border-radius: 0.75rem;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s ease;
  width: 60%;
}

.waitlist-btn:hover {
  transform: scale(1.05);
}

.icon {
  margin-left: 0.5rem;
}

/* Footer Text */
.footer-text {
  margin-top: 2rem;
  font-size: 0.85rem;
  color: #777;
}

/* Optional: Animated floating icons */
.activities-section::before {
  content: "";
  position: absolute;
  top: -100px;
  left: -100px;
  width: 1000px;
  height: 1000px;
  background: url('your-icons-background.png') no-repeat center center;
  background-size: cover;
  opacity: 0.05;
  z-index: 0;
  pointer-events: none;
}
.activities-box:hover {
    transition: box-shadow 0.3s ease-in-out;
    box-shadow: 0 0 50px 10px rgba(163, 90, 255, 0.3);
}

</style>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav>
                
                    <div class="logo">
                        <a href="http://127.0.0.1:8000" style="
                        display: flex;
                        align-items: center;
                        gap: 5px;
                    ">
                        <span class="logo-text">DYMM</span>
                        <span class="beta-badge">Beta</span>
                    </a>
                        
                        <!-- From Uiverse.io by satyamchaudharydev --> 
                        <div class="haalat">
                            
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-moon h-4 w-4 text-foreground"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path></svg>
<label class="switch"  onclick="toggleTheme()">
    <input type="checkbox">
    <span class="slider"></span>
</label>

<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sun h-4 w-4 text-foreground"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m6.34 17.66-1.41 1.41"></path><path d="m19.07 4.93-1.41 1.41"></path></svg>
        
                        </div>
                    </div>
              
                
                <div class="nav-links">
                    <a href="http://127.0.0.1:8000/about/about">About</a>
                    <a href="http://127.0.0.1:8000/contact/contact">Contact</a>
                    <button class="download-btn">
                        <i data-lucide="download"></i>
                        Download App
                    </button>
                    
                </div>
            </nav>
        </div>
    </header>
    <div class="gradient-overlay"></div>
    <div class="doodle-container" id="doodleContainer"></div>
  
    <!-- Hero Section -->
    <section class="hero">
        <div class="gradient-bg"></div>
        <div class="pattern-overlay"></div>
        <div class="container">
            <h1>
                <span></span>
                <span class="gradient-text">Did You Meet Me?</span>
            </h1>
            <p>Create and join activities that match your interests, connect with like-minded people, and make genuine connections IRL.</p>
            <div class="scroll-indicator">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-arrow-down mx-auto h-10 w-10 text-dymm-teal opacity-80 hover:opacity-100 transition-opacity"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v8"></path><path d="m8 12 4 4 4-4"></path></svg>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles h-7 w-7 text-dymm-purple"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path><path d="M20 3v4"></path><path d="M22 5h-4"></path><path d="M4 17v2"></path><path d="M5 18H3"></path></svg>
                <span class="gradient-text" style="font-size: 2rem">How DYMM Works</span>
                <p style="font-size: 1rem; color: #ffffffc4;">Join or host activities you love and find meaningful relationships with like-minded people</p>
            </h2>
            <div class="features-grid">
                <div class="feature-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar h-12 w-12 text-dymm-purple mb-4"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                    <h3>Host Activities</h3>
                    <p>Create and host activities based on your interests, from coffee chats to sports games.</p>
                    <div class="snow"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-4 w-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg><span>Up to 9 attendees</span></div>
                </div>
                <div class="feature-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round h-7 w-7 text-dymm-purple"><path d="M18 21a8 8 0 0 0-16 0"></path><circle cx="10" cy="8" r="5"></circle><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"></path></svg>
                    <h3>Meet People</h3>
                    <p>Join activities that match your interests and connect with like-minded people.</p>
                    <div class="snow"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-4 w-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg><span>Up to 9 attendees</span></div>
                </div>
                <div class="feature-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-12 w-12 text-dymm-pink mb-4"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>
                    <h3>Dating</h3>
                    <p>Connect beyond activities. Our dating features help you find meaningful relationships.</p>
                    <div class="snow"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-4 w-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg><span>Up to 9 attendees</span></div>
                </div>
                <div class="feature-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles h-12 w-12 text-dymm-teal mb-4"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path><path d="M20 3v4"></path><path d="M22 5h-4"></path><path d="M4 17v2"></path><path d="M5 18H3"></path></svg>
                    <h3>Cupid</h3>
                    <p>Play matchmaker! Connect your friends through shared activities.</p>
                    <div class="snow"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-4 w-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg><span>Up to 9 attendees</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Activities Section -->
    <section class="activities" id="activities">
        <div class="container">
            <h2>
                <div style="
                width: 100%;
                margin-left: auto;
                margin-right: auto;
                max-width: 48rem;
            ">
            
                                <span class="gradient-text">Activities</span>
                                <span> for every interest</span> <br>
                                <span style="
                display: flex;
                /* align-items: center; */
                /* justify-content: center; */
            "> and passion </span>
                <div class="dedssd">
                    <p>Create or join activities based on your interests, meet new people, and make meaningful connections in your area.</p>
            
                </div>
                            </div>
            </h2>
            <div class="activities-grid">
                <div class="activity-card yellow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar h-8 w-8 text-amber-800 mb-3"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                    <h3>Art Therapy</h3>
                    <p>Enjoy painting, pottery, and more with art buffs</p>
                </div>
                <div class="activity-card white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-utensils h-8 w-8 text-dymm-purple mb-3"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path><path d="M7 2v20"></path><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path></svg>
                    <h3>Dinner Club</h3>
                    <p>Try new restaurants with foodies</p>
                </div>
                <div class="activity-card pink">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-8 w-8 text-dymm-pink mb-3"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>
                    <h3>Coffee Dates</h3>
                    <p>Connect over a cup of coffee</p>
                </div>
                <div class="activity-card blue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-film h-8 w-8 text-blue-700 mb-3"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M7 3v18"></path><path d="M3 7.5h4"></path><path d="M3 12h18"></path><path d="M3 16.5h4"></path><path d="M17 3v18"></path><path d="M17 7.5h4"></path><path d="M17 16.5h4"></path></svg>
                    <h3>Movie Nights</h3>
                    <p>Watch the latest films with movie buffs</p>
                </div>
                <div class="activity-card purple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-8 w-8 text-dymm-purple mb-3"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <h3>Double Dates</h3>
                    <p>Bring your date and meet another couple</p>
                </div>
                <div class="activity-card green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy h-8 w-8 text-green-700 mb-3"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path></svg>
                    <h3>Sports Events</h3>
                    <p>Bring in people to enjoy your fav sports</p>
                </div>
                <div class="activity-card yellow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mountain h-8 w-8 text-amber-800 mb-3"><path d="m8 3 4 8 5-5 5 15H2L8 3z"></path></svg>
                    <h3>Group Hikes</h3>
                    <p>Explore nature trails with fellow hikers</p>
                </div>
                <div class="activity-card cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-music h-8 w-8 text-teal-700 mb-3"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>
                    <h3>Music Jams</h3>
                    <p>Play or listen to live music sessions</p>
                </div>
                <div class="activity-card cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-film h-8 w-8 text-blue-700 mb-3"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M7 3v18"></path><path d="M3 7.5h4"></path><path d="M3 12h18"></path><path d="M3 16.5h4"></path><path d="M17 3v18"></path><path d="M17 7.5h4"></path><path d="M17 16.5h4"></path></svg>
                    <h3>Movie Nights</h3>
                    <p>Watch the latest films with movie buffs</p>
                </div>
            </div>
        </div>
    </section>
    <div class="gradient-overlay"></div>
    <div class="doodle-container" id="doodleContainer"></div>
  
    <!-- Cupid Feature -->
    <section class="cupid-feature">
        <div class="container">
            <div class="cupid-grid">
                <div class="cupid-content">
                    <h2>
                        <span>Find Your Perfect</span>
                        <span class="gradient-text" style="font-size: 2rem">Match</span>
                    </h2>
                    <p>DYMM isn't just about activities. Connect with people you meet, or explore our dating features to find someone special.</p>
                    <div class="cupid-features">
                        <div class="cupid-feature-item">
                            <div class="mt-1 bg-dymm-pink/10 p-2 rounded-full"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-5 w-5 text-dymm-pink"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg></div>
                            <div>
                                <h3>Genuine Connections</h3>
                                <p>Meet through shared interests and activities</p>
                            </div>
                        </div>
                        <div class="cupid-feature-item">
                            <div class="mt-1 bg-dymm-teal/10 p-2 rounded-full"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-5 w-5 text-dymm-teal"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
                            <div>
                                <h3>Chill Socials</h3>
                                <p>Get to know people in group settings</p>
                            </div>
                        </div>
                        <div class="cupid-feature-item">
                            <div class="mt-1 bg-dymm-purple/10 p-2 rounded-full"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles h-5 w-5 text-dymm-purple"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path><path d="M20 3v4"></path><path d="M22 5h-4"></path><path d="M4 17v2"></path><path d="M5 18H3"></path></svg></div>
                            <div>
                                <h3>Cupid Feature</h3>
                                <p>Let friends play matchmaker and introduce you</p>
                            </div>
                        </div>
                        <div class="cupid-feature-item">
                            <div class="mt-1 bg-dymm-teal/10 p-2 rounded-full"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield h-5 w-5 text-dymm-teal"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path></svg></div>
                            <div>
                                <h3>Full Control</h3>
                                <p>Easily disable dating features if you're just here for activities</p>
                            </div>
                        </div>
                        <div class="cupid-feature-item">
                            <div class="mt-1 bg-dymm-purple/10 p-2 rounded-full"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-keyhole h-5 w-5 text-dymm-purple"><circle cx="12" cy="16" r="1"></circle><rect x="3" y="10" width="18" height="12" rx="2"></rect><path d="M7 10V7a5 5 0 0 1 10 0v3"></path></svg></div>
                            <div>
                                <h3>Private Matchmaking</h3>
                                <p>Cupid matches are anonymous - no one knows who suggested the matchs</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="cupid-card">
                    <div class="purple-blur-circle absolute -top-10 -left-10 w-40 h-40 bg-dymm-purple/30 rounded-full blur-3xl"></div>
                    <i data-lucide="star"></i>
                    <h3>Cupid Mode</h3>
                    <p>Play matchmaker! Think two friends would hit it off?</p>
                    <div class="match-avatars">
                        <div class="avatar">J</div>
                        <div class="heart">❤️</div>
                        <div class="avatar">K</div>
                    </div>
                    <button class="match-btn">Make a Match</button>
                    <p class="disclaimer">Your identity stays anonymous</p>
                    <div class="blue absolute -top-10 -left-10 w-40 h-40 bg-dymm-purple/30 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery -->
    
    <section class="activities-section">
        <div class="activities-box">
          <h2>Ready to Join <span class="highlight">Activities</span>?</h2>
          <p class="description">Join DYMM today and start creating or joining activities with people who share your interests.</p>
          <p class="coming-soon">Coming Soon</p>
          <p class="waitlist-info">Join our waitlist to be notified when we launch our activities platform!</p>
          <button class="waitlist-btn">
            Join Waitlist
            <span class="icon">↗</span>
          </button>
          <p class="footer-text">Find your community through activities you love.</p>
        </div>
      </section>
      
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo">
                        <span class="logo-text">DYMM</span>
                        <span class="beta-badge">Beta</span>
                    </div>
                    <p>Connect with people through activities, dating, and our unique cupid feature.</p>
                </div>
                <div class="footer-links">
                    <h3>Company</h3>
                    <ul>
                        <li><a href="http://127.0.0.1:8000/about/about">About Us</a></li>
                        <li><a href="#careers">Careers</a></li>
                        <li><a href="http://127.0.0.1:8000/contact/contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h3>Legal</h3>
                    <ul>
                        <li><a href="#terms">Terms</a></li>
                        <li><a href="#privacy">Privacy</a></li>
                        <li><a href="#guidelines">Community Guidelines</a></li>
                    </ul>
                </div>
                <div class="footer-social">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <svg class="h-5 w-5" fill="currentColor" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-twitter h-5 w-5"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-facebook h-5 w-5"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2024 DYMM. All rights reserved.</p>
            </div>
        </div>
    </footer>
  
    <script>
      const doodles = [
      { svg: '<svg viewBox="0 0 24 24"><path d="M19 5h-2V3h-2v2h-2V3H9v2H7c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 12H7V9h12v8z" fill="#d946ef2e"/></svg>', top: '10%', left: '5%', delay: '0s', rotate: '0deg' }, // Coffee Cup
      { svg: '<svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="#FF6B6B"/></svg>', top: '15%', right: '10%', delay: '0.2s', rotate: '10deg' }, // Heart
      { svg: '<svg viewBox="0 0 24 24"><path d="M19 5h-2V3h-2v2h-2V3H9v2H7c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 12H7V9h12v8z" fill="#9b87f5"/></svg>', top: '20%', left: '15%', delay: '0.4s', rotate: '-5deg' }, // Coffee Cup (different style)
      { svg: '<svg viewBox="0 0 24 24"><path d="M12 5.69l-1.5-1.19C8.56 3.26 6.61 2 4.5 2 2.58 2 1 3.58 1 5.5c0 2.89 2.71 5.58 6.68 9.15L12 18.35l4.32-3.7C20.29 11.08 23 8.39 23 5.5c0-1.92-1.58-3.5-3.5-3.5-2.11 0-4.06 1.26-5 2.19z" fill="#4ECDC4"/></svg>', top: '25%', right: '5%', delay: '0.6s', rotate: '5deg' }, // Mountain
      { svg: '<svg viewBox="0 0 24 24"><path d="M7 14c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zm0 4c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm12-6h-8v7H3V7H1v10h22v-6c0-2.21-1.79-4-4-4z" fill="#F7D794"/></svg>', bottom: '20%', left: '10%', delay: '0.8s', rotate: '-10deg' }, // Puppy
      { svg: '<svg viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.2-.07.47.12.61l2.05 1.58c-.03.3-.06.63-.06.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.2.07-.47-.12-.61l-2.03-1.58zm-5.14 1.06c-.7 0-1.26.56-1.26 1.26s.56 1.26 1.26 1.26 1.26-.56 1.26-1.26-.56-1.26-1.26-1.26z" fill="#A3BFFA"/></svg>', bottom: '15%', right: '15%', delay: '1.0s', rotate: '8deg' }, // Paintbrush
    //   { svg: '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" fill="#E5989B"/></svg>', top: '30%', left: '20%', delay: '1.2s', rotate: '-3deg' }, // Circle (different style)
      { svg: '<svg viewBox="0 0 24 24"><path d="M12 5.69l-1.5-1.19C8.56 3.26 6.61 2 4.5 2 2.58 2 1 3.58 1 5.5c0 2.89 2.71 5.58 6.68 9.15L12 18.35l4.32-3.7C20.29 11.08 23 8.39 23 5.5c0-1.92-1.58-3.5-3.5-3.5-2.11 0-4.06 1.26-5 2.19z" fill="#6B7280"/></svg>', top: '40%', right: '10%', delay: '1.4s', rotate: '6deg' }, // Mountain (different style)
      { svg: '<svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 16H6c-.55 0-1-.45-1-1V6c0-.55.45-1 1-1h12c.55 0 1 .45 1 1v12c0 .55-.45 1-1 1z" fill="#FACC15"/></svg>', bottom: '25%', left: '25%', delay: '1.6s', rotate: '-7deg' }, // Book
    //   { svg: '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" fill="#34D399"/></svg>', bottom: '30%', right: '20%', delay: '1.8s', rotate: '4deg' }, // Leaf
    //   { svg: '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" fill="#F472B6"/></svg>', top: '50%', left: '30%', delay: '2.0s', rotate: '-2deg' }, // Star
    //   { svg: '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" fill="#60A5FA"/></svg>', top: '60%', right: '15%', delay: '2.2s', rotate: '3deg' } // Circle (different style)
      
    ];
  
      const container = document.getElementById('doodleContainer');
  
      doodles.forEach(d => {
        const el = document.createElement('div');
        el.classList.add('icon');
        el.innerHTML = d.svg;
  
        if (d.top) el.style.top = d.top;
        if (d.bottom) el.style.bottom = d.bottom;
        if (d.left) el.style.left = d.left;
        if (d.right) el.style.right = d.right;
  
        el.style.animationDelay = d.delay;
        el.style.transform = `rotate(${d.rotate})`;
  
        container.appendChild(el);
      });
    </script>
    <script src="https://unpkg.com/lucide-icons"></script>
    <script>
        function toggleTheme() {
            document.body.classList.toggle('light-mode');
            const themeBtn = document.querySelector('.theme-toggle-btn i');
            if (document.body.classList.contains('light-mode')) {
                themeBtn.setAttribute('data-lucide', 'moon');
            } else {
                themeBtn.setAttribute('data-lucide', 'sun');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>