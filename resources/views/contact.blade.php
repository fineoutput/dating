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

.voho{
        display: flex;
        align-items: center;
        gap: 5px;
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
                        <a href="{{route('/')}}" style="
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
                    <a href="{{route('about')}}">About</a>
                    <a href="{{route('contact')}}">Contact</a>
                    <button class="download-btn">
                        <i data-lucide="download"></i>
                        Download App
                    </button>
                    
                </div>
            </nav>
        </div>
    </header>
    {{-- <div class="gradient-overlay"></div> --}}
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
                    <a href="mailto:contactdymm@gmail.com">contactdymm@gmail.com</a>
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
                             <a class="voho" href="{{route('/')}}">
                        <span class="logo-text">DYMM</span>
                        <span class="beta-badge">Beta</span>
                        </a>
                        </div>
                    </a>
                    <p>Connect with people through activities, dating, and our unique cupid feature.</p>
                </div>
                <div class="footer-links">
                    <h3>Company</h3>
                    <ul>
                        <li><a href="{{route('about')}}">About Us</a></li>
                        {{-- <li><a href="#careers">Careers</a></li> --}}
                        <li><a href="{{route('contact')}}">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h3>Legal</h3>
                    <ul>
                        <li><a href="{{route('terms_and_conditions')}}">Terms & conditions</a></li>
                        <li><a href="{{route('privacy_policy')}}">Privacy policy</a></li>
                        {{-- <li><a href="#guidelines">Community Guidelines</a></li> --}}
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