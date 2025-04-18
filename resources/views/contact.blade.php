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
</head>
<style>
/* Base Styles */
:root {
    --primary: #9b87f5;
    --primary-dark: #7E69AB;
    --accent: #d946ef2e;
    --dark: #131019;
    --dark-lighter: #2a2f3c;
    --dark-darker: #131019;
    --white: #ffffff;
}
.switch {
  display: block;
  --width-of-switch: 3.5em;
  --height-of-switch: 2em;
  /* size of sliding icon -- sun and moon */
  --size-of-icon: 1.4em;
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
  background-color: #f4f4f5;
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
  background: linear-gradient(40deg,#ff0080,#ff8c00 70%);
  ;
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

/* Utility Classes */
.gradient-text {
    background: linear-gradient(to right, var(--primary), #d946ef);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
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
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: bold;
    background: #ffd700;
    color: var(--primary);
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

.contact-info,
    .contact-form {
      flex: 1 1 45%;
      box-sizing: border-box;
    }

    .contact-info-item {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
    }

    .contact-form input,
    .contact-form textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      box-sizing: border-box;
    }

    .contact-form button {
      padding: 10px 20px;
      background: #007bff;
      color: white;
      border: none;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .contact-info,
      .contact-form {
        flex: 1 1 100%;
      }
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
@media (max-width: 480px) {
    .container {
        padding: 0 1rem;
    }
    
    .features-grid,
    .activities-grid {
        grid-template-columns: 1fr;
    }
}
.contact {
    padding: 5rem 0;
    background: var(--dark);
    position: relative;
    display: flex;
    justify-content: center;
}

.contact .container {
    display: flex;
    gap: 2rem;
    max-width: 1000px;
}

.contact-info {
    flex: 1;
    padding: 2rem;
    background: rgba(155, 135, 245, 0.1);
    border-radius: 1rem;
    border: 1px solid rgba(155, 135, 245, 0.3);
}

.contact-info h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--white);
}

.contact-info p {
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 1.5rem;
}

.contact-info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.contact-info-item i {
    color: var(--primary);
}

.contact-info-item a {
    color: var(--white);
    text-decoration: none;
}

.contact-form {
    flex: 1;
    padding: 2rem;
    background: #1b1721;
    border-radius: 1rem;
    border: 1px solid rgba(217, 70, 239, 0.3);
}

.contact-form h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--white);
}

.contact-form p {
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 1.5rem;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 0.75rem;
    margin-bottom: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.5rem;
    color: var(--white);
    font-size: 1rem;
}

.contact-form input:focus,
.contact-form textarea:focus {
    outline: none;
    border-color: var(--primary);
}

.contact-form button {
    width: 100%;
    padding: 0.75rem;
    background: var(--primary);
    color: var(--white);
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: opacity 0.3s;
}

.contact-form button:hover {
    opacity: 0.9;
}

body.light-mode .contact-info {
    background: rgba(107, 70, 193, 0.1);
    border-color: rgba(107, 70, 193, 0.3);
}

body.light-mode .contact-form {
    background: rgba(237, 100, 166, 0.1);
    border-color: rgba(237, 100, 166, 0.3);
}

body.light-mode .contact-form input,
body.light-mode .contact-form textarea {
    background: rgba(0, 0, 0, 0.05);
    color: var(--dark);
}

body.light-mode .contact-form button {
    color: var(--dark);
}
</style>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav>
                
                    <div class="logo">
                        <a href="{{route('/')}}">
                        <span class="logo-text">DYMM</span>
                        <span class="beta-badge">Beta</span>
                    </a>
                        {{-- <button class="theme-toggle-btn">
                            <i data-lucide="sun"></i>
                            Toggle Theme
                        </button> --}}
                        <!-- From Uiverse.io by satyamchaudharydev --> 
<label class="switch"  onclick="toggleTheme()">
    <input type="checkbox">
    <span class="slider"></span>
</label>
                    </div>
              
                
                <div class="nav-links">
                    <a href="{{route('about')}}">About</a>
                    <a href="{{route('contact')}}">Contact</a>
                    <button class="download-btn">
                        <i data-lucide="download"></i>
                        Download App
                    </button>
                    {{-- <button class="theme-toggle-btn" onclick="toggleTheme()">
                        <i data-lucide="sun"></i>
                        Toggle Theme
                    </button> --}}
                </div>
            </nav>
        </div>
    </header>
    <div class="gradient-overlay"></div>
    <div class="doodle-container" id="doodleContainer"></div>   
  
    <!-- Hero Section -->
    <section class="contact" id="contact">
        
        <div class="doodle-container" id="doodleContainerContact"></div>
        <div class="container">
            <div class="contact-info">
                <h2>Get in Touch</h2>
                <p>We’d love to hear from you! Whether you have questions about DYMM, need support, or want to share your feedback, our team is here to help.</p>
                <div class="contact-info-item">
                    <i data-lucide="phone"></i>
                    <span>Contact Information</span>
                </div>
                <div class="contact-info-item">
                    <i data-lucide="mail"></i>
                    <a href="mailto:support@dymm.app">support@dymm.app</a>
                </div>
            </div>
            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <p>Tell us what you’d like to know.</p>
                <input type="text" placeholder="Your name" required>
                <input type="email" placeholder="Your email@example.com" required>
                <input type="text" placeholder="Subject" required>
                <textarea placeholder="What’s this about?" rows="4" required></textarea>
                <button type="submit">Send Message</button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="http://127.0.0.1:8000">
                        <div class="logo">
                            <span class="logo-text">DYMM</span>
                            <span class="beta-badge">Beta</span>
                        </div>
                    </a>
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
                        <a href="#"><i data-lucide="instagram"></i></a>
                        <a href="#"><i data-lucide="twitter"></i></a>
                        <a href="#"><i data-lucide="facebook"></i></a>
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
      { svg: '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" fill="#E5989B"/></svg>', top: '30%', left: '20%', delay: '1.2s', rotate: '-3deg' }, // Circle (different style)
      { svg: '<svg viewBox="0 0 24 24"><path d="M12 5.69l-1.5-1.19C8.56 3.26 6.61 2 4.5 2 2.58 2 1 3.58 1 5.5c0 2.89 2.71 5.58 6.68 9.15L12 18.35l4.32-3.7C20.29 11.08 23 8.39 23 5.5c0-1.92-1.58-3.5-3.5-3.5-2.11 0-4.06 1.26-5 2.19z" fill="#6B7280"/></svg>', top: '40%', right: '10%', delay: '1.4s', rotate: '6deg' }, // Mountain (different style)
      { svg: '<svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 16H6c-.55 0-1-.45-1-1V6c0-.55.45-1 1-1h12c.55 0 1 .45 1 1v12c0 .55-.45 1-1 1z" fill="#FACC15"/></svg>', bottom: '25%', left: '25%', delay: '1.6s', rotate: '-7deg' }, // Book
      { svg: '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" fill="#34D399"/></svg>', bottom: '30%', right: '20%', delay: '1.8s', rotate: '4deg' }, // Leaf
      { svg: '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" fill="#F472B6"/></svg>', top: '50%', left: '30%', delay: '2.0s', rotate: '-2deg' }, // Star
      { svg: '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" fill="#60A5FA"/></svg>', top: '60%', right: '15%', delay: '2.2s', rotate: '3deg' } // Circle (different style)
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