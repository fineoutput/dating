<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About DYMM</title>
    <meta name="description" content="Learn about DYMM, our mission, values, and story of connecting people through shared experiences and meaningful relationships.">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/svg+xml" href="/lovable-Uploads/645829d6-708c-4ab2-9a74-b01a81d3b93b.png">
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide-icons.js" rel="stylesheet">
</head>
<style>
/* Base Styles */
:root {
    --primary: #9b87f5;
    --primary-dark: #7E69AB;
    --accent: #d946ef2e;
    --dark: #131019;
    --dark-lighter: #2a2f3c;
    --dark-darker: #161922;
    --white: #ffffff;
}
.value-card h3 {
    color: #8a2be2;
}
.switch {
    display: block;
    --width-of-switch: 2.9em;
    --height-of-switch: 1.5em;
    --size-of-icon: 1.2em;
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
    text-align: center;
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
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
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
a{
    text-decoration:none; 
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

/* About Section */
.about {
    padding: 5rem 0;
    min-height: calc(100vh - 200px);
    background: var(--dark);
    position: relative;
    display: flex;
    justify-content: center;
}

.about .container {
    max-width: 1000px;
}

.about h1 {
    font-size: 2.5rem;
    color: var(--primary);
    margin-bottom: 2rem;
    text-align: center;
}

.about-section {
    /* background: rgba(155, 135, 245, 0.1); */
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    /* border: 1px solid rgba(155, 135, 245, 0.3); */
}

.about-section h2 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--white);
}

.about-section p {
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.6;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.value-card {
    background: #1d1825;
    padding: 1.5rem;
    border-radius: 0.75rem;
    /* border: 1px solid rgba(217, 70, 239, 0.3); */
}

body.light-mode .about-section {
    background: rgba(107, 70, 193, 0.1);
    border-color: rgba(107, 70, 193, 0.3);
}

body.light-mode .value-card {
    background: rgba(237, 100, 166, 0.1);
    border-color: rgba(237, 100, 166, 0.3);
}

/* Footer */
.footer {
    padding: 3rem 0;
    background: var(--dark-darker);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
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

/* Doodle Styles */
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
    /* background: linear-gradient(to bottom, rgba(26, 31, 44, 0.8), rgba(22, 25, 34, 0.9)); */
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

@keyframes  pulse {
    0%, 100% { filter: brightness(1); }
    50% { filter: brightness(1.3); }
}

@keyframes  float {
    0% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0); }
}

@media (max-width: 768px) {
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

@media (max-width: 480px) {
    .container {
        padding: 0 1rem;
    }
}
.haalat svg {
    color: #e5e3e8;
}
.haalat {
    display: flex;
    align-items: center;
    gap: 10px;
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

    <!-- About Section -->
    <section class="about">
        <div class="gradient-overlay"></div>
        <div class="doodle-container" id="doodleContainerAbout"></div>
        <div class="container">
            <h1>About DYMM</h1>
            <div class="about-section">
                <p>At DYMM, we’re imagining how people connect in the digital age. Our platform brings together the best elements of social activities, dating, and matchmaking to create a holistic approach to building meaningful relationships.</p>
                <p>What you can do is only limited by your imagination — your social world is waiting to unfold with DYMM.</p>
            </div>
            <div class="about-section" style="background-color:#1d1825;">
                <h2>Our Mission</h2>
                <p>To empower people to form genuine connections through shared experiences, whether that’s finding romance, making friends, or building community.</p>
            </div>
            <div class="about-section">
                <h2>Our Values</h2>
                <div class="values-grid">
                    <div class="value-card">
                        <h3>Authenticity</h3>
                        <p>We encourage real connections based on genuine shared interests and values.</p>
                    </div>
                    <div class="value-card">
                        <h3>Inclusivity</h3>
                        <p>We’re building a platform where everyone can find their community.</p>
                    </div>
                    <div class="value-card">
                        <h3>Innovation</h3>
                        <p>We’re constantly exploring new ways to bring people together meaningfully.</p>
                    </div>
                    <div class="value-card">
                        <h3>Privacy</h3>
                        <p>We respect your data and prioritize creating a safe environment for all users.</p>
                    </div>
                </div>
            </div>
            <div class="about-section">
                <h2>Our Story</h2>
                <p>DYMM began with a simple observation: in a world more connected than ever, people were still struggling to form meaningful relationships. Dating apps focused solely on romance, social platforms lacked intentionality, and finding new communities felt overwhelming.</p>
                <p>We built DYMM to bridge these gaps, creating a space where connections can grow organically through shared activities, dating intentions, or with the help of our innovative Cupid feature. Today, we’re proud to be helping people worldwide discover new connections that truly matter.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        
        <div class="doodle-container" id="doodleContainerFooter"></div>
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

 
</body>
</html>