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
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav>
                <div class="logo">
                    <a href="{{route('/')}}" style="display: flex; align-items: center; gap: 5px;">
                        <span class="logo-text">DYMM</span>
                        <span class="beta-badge">Beta</span>
                    </a>
                    <div class="haalat">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-moon h-4 w-4 text-foreground"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path></svg>
                        <label class="switch" onclick="toggleTheme()">
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

    <!-- Terms and Conditions Section -->
    <section class="terms-conditions">
        <div class="container">
            <h1>Terms and Conditions</h1>

            <h2>1. Introduction</h2>
            <p>Welcome to <strong>Premier App Solutions</strong>.</p>
            <p>These Terms of Service (“Terms”) govern your use of our website located at <a href="https://didyoumeetme.com">didyoumeetme.com</a>, <a href="https://didyoumeetme.in">didyoumeetme.in</a>, and our mobile application <strong>didyoumeetme</strong> or <strong>DYMM</strong> (collectively, the “Service”) operated by <strong>Premier App Solutions</strong> or its successors (“Company”, “we”, “our”, “us”).</p>
            <p>For the purpose of these Terms, “you” and “your” refer to any person who uses or accesses the Service, including those browsing, sharing information, advertising, posting comments, or interacting with content on the Service. By using the Service, you agree to comply with these Terms and any applicable guidelines, which may be updated at our discretion. You are responsible for reviewing these Terms periodically. If you disagree with any part of these Terms or become dissatisfied with the Service, you must discontinue use immediately.</p>
            <p>Our <a href="{{route('privacy_policy')}}">Privacy Policy</a> also governs your use of the Service and explains how we collect, safeguard, and disclose information resulting from your use of our web pages. Your agreement with us includes these Terms and our Privacy Policy (“Agreements”). If you do not agree with the Agreements, you may not use the Service. Please contact us at <a href="mailto:contactdymm@gmail.com">contactdymm@gmail.com</a> if you have concerns.</p>
            <p>We may update these Terms at our discretion and notify you of changes. The English version of these Terms prevails in case of inconsistencies with any translations.</p>

            <h2>2. Communications</h2>
            <p>By using our Service, you agree to receive newsletters, marketing, or promotional materials. You may opt out by following the unsubscribe link or emailing <a href="mailto:contactdymm@gmail.com">contactdymm@gmail.com</a>.</p>

            <h2>3. Content</h2>
            <p>Our Service allows you to post, link, store, share, and make available information, text, graphics, videos, or other material (“Content”). You are responsible for the legality, reliability, and appropriateness of your Content.</p>
            <p>By posting Content, you represent that you own it or have the right to use it and grant us a license to use, modify, perform, display, reproduce, and distribute it on the Service. This license allows us to make your Content available to other users, subject to these Terms. You retain your rights to your Content and are responsible for protecting those rights.</p>
            <p>We reserve the right to terminate accounts found infringing on copyrights. We may monitor and edit user Content but are not obligated to do so. Content on the Service is our property or used with permission and may not be used for commercial purposes without our written consent.</p>

            <h2>4. Accounts</h2>
            <p>When creating an account, you guarantee that you are over 18 and that your information is accurate, complete, and current. Inaccurate information may lead to account termination.</p>
            <p>You are responsible for maintaining the confidentiality of your account and password and for all activities under your account. Notify us immediately of any security breach or unauthorized use at <a href="mailto:contactdymm@gmail.com">contactdymm@gmail.com</a>.</p>
            <p>You may not use a username that is offensive, vulgar, or belongs to another person or entity without authorization. We reserve the right to refuse service, terminate accounts, or remove content at our discretion.</p>

            <h2>5. No Use By Minors</h2>
            <p>The Service is intended for users at least 18 years old. If you are under 18, you are prohibited from accessing or using the Service. Minors under Indian law must not use the Service or enter transactions. Legal guardians cannot contract on behalf of minors.</p>
            <p><strong>Notice to Minors:</strong> Do not send us your personal information. Contact us only through a parent or legal guardian.</p>

            <h2>6. User Submission</h2>
            <p>You may be exposed to Content from various sources on the Service. We are not responsible for the accuracy, safety, or intellectual property rights of such Content. You waive any legal rights against us for Content that is inaccurate, offensive, or objectionable.</p>

            <h2>7. Posting Agents</h2>
            <p>“Posting Agent” refers to third-party agents posting Content on behalf of others. We prohibit the use of Posting Agents and are not liable for any breaches caused by them.</p>

            <h2>8. Access to Services</h2>
            <p>We grant you a limited, revocable, non-exclusive license to access the Service for personal use. This license does not permit data mining, robots, or similar tools, except by general-purpose internet search engines complying with our robots.txt file. You may link to individual postings for non-commercial purposes, subject to our limits. Commercial use requires our express permission.</p>

            <h2>9. Dealing with Organisations, Individuals, or Third Parties</h2>
            <p>We are not liable for your interactions with organizations, individuals, or third parties on the Service, including payment or delivery disputes. Any disputes are between you and the third party, and we are not obligated to intervene. You release us from claims arising from such disputes.</p>

            <h2>10. Purchases</h2>
            <p>For purchases, you may need to provide payment information. You warrant that you have the legal right to use the payment method and that the information is accurate. We use third-party services for payment processing, subject to our Privacy Policy.</p>
            <p>We may refuse or cancel orders due to availability, errors, or suspected fraud. We are not involved in transactions with third-party vendors and are not liable for them.</p>

            <h2>11. Contests, Sweepstakes, and Promotions</h2>
            <p>Promotions on the Service may have separate rules. Review these rules and our Privacy Policy before participating. Promotion rules prevail if they conflict with these Terms.</p>

            <h2>12. Subscriptions</h2>
            <p>Some parts of the Service are subscription-based, billed on a recurring basis (“Billing Cycle”). You can cancel your subscription through your account or by contacting <a href="mailto:contactdymm@gmail.com">contactdymm@gmail.com</a>.</p>
            <p>Provide accurate billing information, including a valid payment method. You authorize us to charge subscription fees to your payment method. Failed billing may result in immediate termination of access. Prices include applicable taxes unless stated otherwise.</p>

            <h2>13. Fee Changes</h2>
            <p>We may modify subscription fees at our discretion, effective at the end of the current Billing Cycle. Continued use after a fee change constitutes agreement to the new fees.</p>

            <h2>14. Refunds</h2>
            <p>We do not issue refunds for any purchases.</p>

            <h2>15. Conduct and Prohibited Uses</h2>
            <p>You agree to use the Service lawfully and in accordance with these Terms. You must not:</p>
            <ul>
                <li>Violate any national or international law.</li>
                <li>Exploit or harm minors.</li>
                <li>Send unsolicited advertising or spam.</li>
                <li>Impersonate any person or entity.</li>
                <li>Infringe on others’ rights or engage in illegal, threatening, or harmful activities.</li>
                <li>Disrupt others’ use of the Service.</li>
                <li>Use misleading identifiers to disguise Content origin.</li>
                <li>Post harmful, false, or misleading Content.</li>
                <li>Threaten India’s unity, security, or public order.</li>
                <li>Share personal information without consent.</li>
                <li>Disable, damage, or impair the Service.</li>
                <li>Use automated tools to access or monitor the Service.</li>
                <li>Introduce malicious software.</li>
                <li>Gain unauthorized access to our systems.</li>
                <li>Harass or stalk users.</li>
                <li>Collect personal data for unlawful purposes.</li>
                <li>Post irrelevant or repetitive Content.</li>
                <li>Use automated flagging or posting tools.</li>
                <li>Damage our rating or interfere with the Service.</li>
            </ul>
            <p>Non-compliant Content may be disabled or investigated, and we may terminate your account or remove such Content.</p>

            <h2>16. Changes to Service</h2>
            <p>We may withdraw or amend the Service without notice and are not liable for any unavailability. We may restrict access to parts or all of the Service at our discretion.</p>

            <h2>17. Analytics</h2>
            <p>We may use third-party providers to monitor and analyze Service usage.</p>

            <h2>18. Intellectual Property</h2>
            <p>The Service and its original content (excluding user Content), features, and functionality are owned by <strong>Premier App Solutions</strong> and its licensors, protected by Indian and international copyright, trademark, and other laws. Our trademarks may not be used without written consent.</p>
            <p>Materials on the Service, including text, software, and graphics, are licensed to us and protected by law. You may not use, copy, or distribute these Materials for commercial purposes without permission. You must retain copyright notices on any downloaded Materials for personal use.</p>

            <h2>19. Copyright Policy</h2>
            <p>We respect intellectual property rights. If you believe Content infringes your copyright, submit a claim to <a href="mailto:contactdymm@gmail.com">contactdymm@gmail.com</a> with the subject “Copyright Infringement” and a detailed description. Misrepresentation may result in liability for damages.</p>

            <h2>20. Error Reporting and Feedback</h2>
            <p>You may provide feedback at <a href="mailto:contactdymm@gmail.com">contactdymm@gmail.com</a>. You grant us an exclusive, perpetual right to use your feedback without obligation. Feedback is not confidential, and you waive intellectual property rights to it.</p>

            <h2>21. Links to Other Websites</h2>
            <p>Our Service may contain third-party links. We are not responsible for their content, privacy policies, or practices. Review their terms and policies before use. We are not liable for any damage caused by third-party sites.</p>

            <h2>22. Indemnity</h2>
            <p>You agree to indemnify and hold us harmless from claims arising from your use of the Service, violation of these Terms, infringement of third-party rights, or negligence in complying with laws or third-party terms. This obligation survives termination of these Terms.</p>

            <h2>23. Assignment</h2>
            <p>You may not transfer or assign these Terms, but we may do so without restriction. Any assignment by you is void.</p>

            <h2>24. Termination</h2>
            <p>We may terminate or suspend your account without notice for any reason, including breach of these Terms. You may terminate your account by discontinuing use. Provisions such as indemnity and liability limitations survive termination.</p>

            <h2>25. Disclaimer of Warranty</h2>
            <p>The Service is provided “AS IS” without warranties of any kind. We do not guarantee the accuracy, reliability, or availability of the Service or its content. We are not liable for errors, interruptions, or damages resulting from your use of the Service or third-party content.</p>
            <p>We operate as an intermediary platform under Section 2(w) of the Information Technology Act, 2000, and do not guarantee specific relief or performance of contracts entered through the Service.</p>

            <h2>26. Limitation of Liability</h2>
            <p>We are not liable for indirect, punitive, or consequential damages arising from your use of the Service, even if advised of the possibility. Our liability is limited to the amount paid for products or services. Users accessing the Service from outside India do so at their own risk and must comply with local laws.</p>

            <h2>27. Governing Law</h2>
            <p>These Terms are governed by Indian law, without regard to conflict of law provisions. Our failure to enforce any right is not a waiver. These Terms constitute the entire agreement between us regarding the Service.</p>

            <h2>28. Dispute Resolution</h2>
            <p>Disputes shall be resolved through mutual discussions. If unresolved within 15 days, disputes will be settled by arbitration under the Arbitration and Conciliation Act, 1996, with a sole arbitrator in Gurugram, Haryana, India. Courts in Gurugram have exclusive jurisdiction.</p>

            <h2>29. Amendments to Terms</h2>
            <p>We may amend these Terms by posting updates on the Service. Your continued use after amendments constitutes acceptance. Review these Terms periodically.</p>

            <h2>30. Waiver and Severability</h2>
            <p>No waiver of any Term constitutes a continuing waiver. If any provision is invalid, it will be limited to the minimum extent, and the remaining provisions remain in effect.</p>

            <h2>31. Violation of These Terms and Liquidated Damages</h2>
            <p>Report violations to <a href="mailto:contactdymm@gmail.com">contactdymm@gmail.com</a>. If damages cannot be quantified, you agree to pay the following liquidated damages:</p>
            <ul>
                <li>For exceeding access limits or unauthorized access: ₹10,000 per message or day, whichever is higher.</li>
                <li>For Posting Agent violations: ₹10,000 per engagement, plus ₹10,000 for the engaging party.</li>
                <li>For unsolicited emails: ₹2,500 per email.</li>
                <li>For other violations: ₹10,000 per message.</li>
                <li>For unauthorized Content exploitation: ₹10,00,000 per day.</li>
            </ul>
            <p>If these clauses don’t apply, you’ll pay actual damages if calculable. We may seek equitable remedies without posting a bond.</p>

            <h2>32. Acknowledgement</h2>
            <p>By using the Service, you acknowledge that you have read and agree to be bound by these Terms.</p>

            <h2>33. Contact Us</h2>
            <p>For feedback or support, contact us at <a href="mailto:contactdymm@gmail.com">contactdymm@gmail.com</a>.</p>
        </div>
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