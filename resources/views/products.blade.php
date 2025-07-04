<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions - DYMM</title>
    <meta name="description" content="Review the Terms and Conditions for using the DYMM Service, including didyoumeetme.com, didyoumeetme.in, and the DYMM mobile application.">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/svg+xml" href="/lovable-Uploads/645829d6-708c-4ab2-9a74-b01a81d3b93b.png">
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide-icons.js" rel="stylesheet">
    <style>
        /* Base Styles from Provided Code */
        :root {
            --primary: #9b87f5;
            --primary-dark: #7E69AB;
            --accent: #d946ef2e;
            --dark: #131019;
            --dark-lighter: #2a2f3c;
            --dark-darker: #131019;
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
.voho{
        display: flex;
        align-items: center;
        gap: 5px;
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
            padding: 0 2rem;
        }

        /* Utility Classes */
        .gradient-text {
            background: linear-gradient(to right, var(--primary), #d946ef);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
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

        a {
            text-decoration: none;
        }

        .beta-badge {
            padding: 0.10rem 0.5rem;
            font-size: 0.75rem;
            font-weight: bold;
            background: #ffd700;
            color: #8a2be2;
            border-radius: 9999px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.7);
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

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

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
            box-shadow: inset -3px -2px 5px -2px #8983f7, inset -10px -4px 0 0 #a3dafb;
        }

        .haalat {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .haalat svg {
            color: #e5e3e8;
        }

        /* Terms and Conditions Section Styles */
        .terms-conditions {
            padding: 8rem 0 5rem;
            background: var(--dark);
            position: relative;
        }

        .terms-conditions .container {
            max-width: 800px;
        }

        .terms-conditions h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--white);
            text-align: center;
        }

        .terms-conditions h2 {
            font-size: 1.8rem;
            margin: 2rem 0 1rem;
            color: var(--primary);
        }

        .terms-conditions p,
        .terms-conditions ul {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1rem;
        }

        .terms-conditions ul {
            list-style-type: disc;
            padding-left: 2rem;
        }

        .terms-conditions li {
            margin-bottom: 0.5rem;
        }

        .terms-conditions a {
            color: var(--primary);
            text-decoration: underline;
        }

        .terms-conditions a:hover {
            color: #d946ef;
        }

        body.light-mode .terms-conditions p,
        body.light-mode .terms-conditions ul {
            color: rgba(26, 32, 44, 0.8);
        }

        /* Footer Styles */
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
            .terms-conditions h1 {
                font-size: 2rem;
            }

            .terms-conditions h2 {
                font-size: 1.5rem;
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

        @media (max-width: 480px) {
            .container {
                padding: 0 1rem;
            }

            .terms-conditions h1 {
                font-size: 1.8rem;
            }

            .terms-conditions h2 {
                font-size: 1.3rem;
            }
        }
              .container {
            max-width: 1340px;
            margin: 0 auto;
            padding: 2rem;
        }
        h1 {
            text-align: center;
            color: #d946ef;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .subtitle {
            text-align: center;
            color: #e5e3e8cc;
            margin-bottom: 2rem;
        }
        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            justify-items: center;
        }
        .plan-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            width: 100%;
            max-width: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .plan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.8;
            background-size: cover;
            background-position: center;
        }
        .plan-card.free::before {
            background: linear-gradient(135deg, #16213e, #1a3c6a);
        }
        .plan-card.gold::before {
            background: linear-gradient(135deg, #8a4a0c, #6b2d00);
        }
        .plan-card.platinum::before {
            background: linear-gradient(135deg, #4a1a6e, #7b2cbf);
        }
        .plan-card:hover {
            transform: translateY(-10px);
        }
        .plan-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #fff;
        }
        .plan-title {
            color: #fff;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }
        .plan-description {
            color: #e5e3e8cc;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
       .plan-features {
    list-style: none;
    padding: 0;
    margin: 0;
    text-align: left;
}
        .plan-features li {
            margin-bottom: 0.8rem;
            color: #b0b0c0;
            font-size: 0.95rem;
        }
        .plan-features li::before {
    content: "•";
    color: #00ffcc;
    font-weight: bold;
    display: inline-block;
    width: 1em;
    margin-left: -1em;
}
.plan-card.free {
    border: 2px solid #20b2aa4d;
}
.plan-card.gold {
    border: 2px solid rgb(255 215 0 / 0.5);
}
.plan-card.platinum {
    border: 2px solid rgb(138 43 226 / 0.5);
}
          .feature-table {
      width: 100%;
      max-width: 1500px;
      margin: 0 auto;
      border-collapse: collapse;
      border-radius: 12px;
      overflow: hidden;
      background-color: #1b132a;
    }

   .feature-table th, .feature-table td {
    padding: 18px 43px;
    text-align: left;
    border-bottom: 1px solid #2c2239;
    font-size: 13px;
}

    .feature-table th {
      background-color: #1b132a;
      color: #bfb0ff;
      font-weight: 600;
      text-align: left;
    }

    .feature-table td {
      color: #e0e0e0;
    }

    .feature-table tr:hover td {
      background-color: #231633;
    }

    .feature-table td.center {
      text-align: center;
    }

    .tick {
      color: #00ffcc;
      font-size: 1.2em;
    }

    .tick.gold {
      color: #ffc107;
    }

    .tick.platinum {
      color: #b76bff;
    }

    .cross {
      color: #999;
      font-size: 1.2em;
    }

    @media (max-width: 768px) {
      .feature-table th,
      .feature-table td {
        font-size: 14px;
        padding: 12px 10px;
      }
    }
    .plan-card free{
        background: linear-gradient(135deg, #0f2e3d, #3b0a4d);
    }
    .motoSept {
    color: #fff;
    font-size: 0.875rem;
    background: #e5e3e81a;
    padding: 5px;
    border-radius: 10px;
    text-transform: capitalize;
    font-weight: 500;

    text-align: ccw;
}
.plan-card.gold {
    /* background: linear-gradient(to right, #ffd7001f, #ffc00030, #ffa500a6); */
    background: linear-gradient(112deg, #43301d42, #6f4c244d, #48165d91);
}
.plan-card.platinum {
    background: linear-gradient(135deg, #2e1049, #5a224f);
}
.cccc {
    text-align: center;
    color: #d946ef;
    font-size: 4.5rem;
    margin-bottom: 1rem;
}
.furious{
    text-align: center;
     background: linear-gradient(to right, #8a4de1, #e84cff);
     font-size: 40px;
     background-clip:text; 
     color: transparent;
}

.cta-section {
  text-align: center;
  padding: 60px 20px;
  /* background-color: #0a0514; match background */
}

.cta-title {
  font-size: 1.8rem;
  font-weight: bold;
  background: linear-gradient(90deg, #9a30ea, #ff4eb1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 10px;
}

.cta-subtext {
  font-size: 1rem;
  color: #ccc;
  margin-bottom: 30px;
}

.cta-button {
  display: inline-block;
  padding: 12px 24px;
  background-color: #a634f7;
  color: white;
  border-radius: 10px;
  font-weight: 600;
  text-decoration: none;
  transition: background 0.3s ease;
}

.cta-button:hover {
  background-color: #902de0;
}

    </style>
</head>
<body>
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
                    <a href="{{route('products')}}">Products</a>
                    <button class="download-btn">
                        <i data-lucide="download"></i>
                        Download App
                    </button>
                    
                </div>
            </nav>
        </div>
    </header>

    <!-- Terms and Conditions Section -->
   <div class="container" style="margin-top: 140px">
        <h1 class="cccc">Choose Your Plan</h1>
        <p class="subtitle">Upgrade to Gold or Platinum for an enhanced DYMM experience.</p>
        <div class="plans-grid">
            <div class="plan-card free">
                <div class="plan-icon" data-lucide="flame"></div>
                <h2 class="plan-title">🔥DYMM <span class="motoSept">Free</span></h2>
                <p class="plan-description">Get started with basic features</p>
                <ul class="plan-features">
                    <li>Match, Chat, Meet</li>
                    <li>Monthly Interests</li>
                    <li>Monthly Activities</li>
                    <li>Basic Profile Features</li>
                </ul>
            </div>
            <div class="plan-card gold">
                <div class="plan-icon" data-lucide="award"></div>
                <h2 class="plan-title">👑 DYMM <span class="motoSept">Gold</span></h2>
                <p class="plan-description">Enhanced features for active users</p>
                <ul class="plan-features">
                    <li>Everything in Free</li>
                    <li>Unlimited Likes</li>
                    <li>Unlimited Rewinds</li>
                    <li>See who likes you</li>
                    <li>Additional Monthly Activities</li>
                    <li>Additional Monthly Interests</li>
                </ul>
            </div>
            <div class="plan-card platinum">
                <div class="plan-icon" data-lucide="gem"></div>
                <h2 class="plan-title">💎 DYMM <span class="motoSept">Platinum</span></h2>
                <p class="plan-description">Premium experience with all features</p>
                <ul class="plan-features">
                    <li>Everything in Gold</li>
                    <li>Extra Monthly Activities beyond Gold</li>
                    <li>Extra Monthly Interests beyond Gold</li>
                    <li>Message with Interest in activities</li>
                </ul>
            </div>
        </div>

        <div class="feature-comparison" style="margin-top: 40px">
            <h2 style="text-align: center"><span></span><span class="furious">Feature Comparison</span></h2>
            <table class="feature-table">
    <thead>
      <tr>
        <th>Feature</th>
        <th class="center">DYMM (Free)</th>
        <th class="center">DYMM Gold</th>
        <th class="center">DYMM Platinum</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Match, Chat, Meet</td>
        <td class="center"><span class="tick">✔</span></td>
        <td class="center"><span class="tick gold">✔</span></td>
        <td class="center"><span class="tick platinum">✔</span></td>
        <td>Core functionality to connect with people</td>
      </tr>
      <tr>
        <td>Monthly Interests, Monthly Activities</td>
        <td class="center"><span class="tick">✔</span></td>
        <td class="center"><span class="tick gold">✔</span></td>
        <td class="center"><span class="tick platinum">✔</span></td>
        <td>Create activities for free using monthly allowance and show interest in joining activities using monthly interests allocation</td>
      </tr>
      <tr>
        <td>Unlimited Likes</td>
        <td class="center"><span class="cross">✖</span></td>
        <td class="center"><span class="tick gold">✔</span></td>
        <td class="center"><span class="tick platinum">✔</span></td>
        <td>Like as many profiles as you want</td>
      </tr>
      <tr>
        <td>Unlimited Rewinds</td>
        <td class="center"><span class="cross">✖</span></td>
        <td class="center"><span class="tick gold">✔</span></td>
        <td class="center"><span class="tick platinum">✔</span></td>
        <td>Take back your last Like or Nope. Bring back profiles you accidentally passed on.</td>
      </tr>
      <tr>
        <td>See who likes you</td>
        <td class="center"><span class="cross">✖</span></td>
        <td class="center"><span class="tick gold">✔</span></td>
        <td class="center"><span class="tick platinum">✔</span></td>
        <td>See who Likes You before you decide whether to Like or Nope</td>
      </tr>
      <tr>
        <td>Additional Monthly Activities</td>
        <td class="center"><span class="cross">✖</span></td>
        <td class="center"><span class="tick gold">✔</span></td>
        <td class="center"><span class="tick platinum">✔</span></td>
        <td>Create additional activities apart from what we provide on a monthly basis</td>
      </tr>
      <tr>
        <td>Additional Monthly Interests</td>
        <td class="center"><span class="cross">✖</span></td>
        <td class="center"><span class="tick gold">✔</span></td>
        <td class="center"><span class="tick platinum">✔</span></td>
        <td>Show your interests apart from what we provide on a monthly basis</td>
      </tr>
      <tr>
        <td>Message with Interest in activities</td>
        <td class="center"><span class="cross">✖</span></td>
        <td class="center"><span class="cross">✖</span></td>
        <td class="center"><span class="tick platinum">✔</span></td>
        <td>Show your interests in activities and send a short message along with it</td>
      </tr>
    </tbody>
  </table>
        </div>
    </div>


    <section class="cta-section">
  <h2 class="cta-title">Ready to upgrade your experience?</h2>
  <p class="cta-subtext">
    Join thousands of users who have found meaningful connections through DYMM.
  </p>
  <a href="#" class="cta-button">Start Your Journey</a>
</section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="/">
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
                        <li><a href="{{route('contact')}}">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h3>Legal</h3>
                    <ul>
                        <li><a href="{{route('terms_and_conditions')}}">Terms & Conditions</a></li>
                        <li><a href="{{route('privacy_policy')}}">Privacy Policy</a></li>
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
                <p>© 2025 DYMM. All rights reserved.</p>
            </div>
        </div>
    </footer>

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