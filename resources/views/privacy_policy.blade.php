<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - DYMM</title>
    <meta name="description" content="Read the DYMM Privacy Policy to understand how we collect, use, and protect your personal information when you use our services.">
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

        /* Privacy Policy Section Styles */
        .privacy-policy {
            padding: 8rem 0 5rem;
            background: var(--dark);
            position: relative;
        }

        .privacy-policy .container {
            max-width: 800px;
        }

        .privacy-policy h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--white);
            text-align: center;
        }

        .privacy-policy h2 {
            font-size: 1.8rem;
            margin: 2rem 0 1rem;
            color: var(--primary);
        }

        .privacy-policy p,
        .privacy-policy ul {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1rem;
        }

        .privacy-policy ul {
            list-style-type: disc;
            padding-left: 2rem;
        }

        .privacy-policy li {
            margin-bottom: 0.5rem;
        }

        .privacy-policy a {
            color: var(--primary);
            text-decoration: underline;
        }

        .privacy-policy a:hover {
            color: #d946ef;
        }

        body.light-mode .privacy-policy p,
        body.light-mode .privacy-policy ul {
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
            .privacy-policy h1 {
                font-size: 2rem;
            }

            .privacy-policy h2 {
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

            .privacy-policy h1 {
                font-size: 1.8rem;
            }

            .privacy-policy h2 {
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

    <!-- Privacy Policy Section -->
    <section class="privacy-policy">
        <div class="container">
            <h1>Privacy Policy</h1>

            <h2>1. Introduction</h2>
            <p>Premier App Solutions (referred to as "we", "us", or "our") operates didyoumeetme.com, didyoumeetme.in, and its mobile application didyoumeetme (also known as DYMM) (collectively, the "Service").</p>
            <p>This Privacy Policy governs your use of didyoumeetme.com, didyoumeetme.in, and the mobile application didyoumeetme (DYMM). It explains how we collect, safeguard, and disclose information resulting from your use of our Service.</p>
            <p>We use your data to provide and improve the Service. By using the Service, you agree to the collection, use, sharing, and disclosure of your information in accordance with this policy.</p>
            <p>Unless otherwise defined in this Privacy Policy, terms used herein have the same meanings as in our Terms and Conditions of Use.</p>
            <p>Our Terms and Conditions of Use ("Terms") govern all use of our Service and, together with this Privacy Policy, constitute your agreement with us ("Agreement").</p>
            <p>By accessing or using our Service or by otherwise providing us with your information, you confirm that you have the capacity to enter into a legally binding contract and have read, understood, and agreed to the practices and policies outlined in this Privacy Policy.</p>
            <p>We may modify, add to, or delete from this Privacy Policy from time to time. Your continued access to or use of the Service will be deemed an acceptance of these changes.</p>
            <p>If you do not agree with this Privacy Policy at any time, in part or as a whole, do not access or use the Service or provide us with any of your information.</p>
            <p>If you access or use the platform from any location outside India, you do so at your own risk and will be solely liable for compliance with any applicable local laws.</p>

            <h2>2. Definitions</h2>
            <ul>
                <li><strong>APPLICATION</strong> refers to didyoumeetme, the software program provided by us.</li>
                <li><strong>COOKIES</strong> are small files placed on your computer, mobile device, or any other device by a website, containing details of your browsing history on that website, among other uses.</li>
                <li><strong>DATA CONTROLLERS</strong>: For the purpose of this Privacy Policy, we are one of the Data Controllers of your data.</li>
                <li><strong>DATA PROCESSORS (OR SERVICE PROVIDERS)</strong> means any natural or legal person who processes data on behalf of the Data Controller. We may use the services of various Service Providers to process your data more effectively.</li>
                <li><strong>DATA SUBJECT</strong> is any living individual who is the subject of Personal Data.</li>
                <li><strong>SERVICE</strong> means the didyoumeetme.com website, application, mobile application, or any combination thereof, operated by us.</li>
                <li><strong>THE USER</strong> is the individual using our Service. The User corresponds to the Data Subject, the subject of Personal Data.</li>
                <li><strong>YOU</strong> means a person using our Service, including the individual accessing or using the Service, or the company or other legal entity on behalf of which such individual is accessing or using the Service, as applicable.</li>
            </ul>

            <h2>3. Information Collection</h2>
            <p>We collect several different types of information for various purposes to provide and improve our Service.</p>
            <p>While using our Service, we may ask you to provide certain personally identifiable information that can be used to contact or identify you.</p>
            <p>We may collect from you information that is not unique to you and refers to selected population characteristics, including age, gender, and current location details.</p>
            <p>We may also collect information about how you use the Service and information about your mobile device and software, including usage statistics, traffic data, your IP address, browser and operating system type, domain names, access times, locations, and details regarding the parts of the Service that you access.</p>
            <p>Your use of certain third-party services on the Service also requires us to collect information considered necessary for that purpose.</p>
            <p>Where you provide us with user information of third parties, we understand that you have obtained the consent of such third parties and have sufficient rights, approvals, and licenses to provide such information to us.</p>
            <p>While using our Application, in order to provide its features, we may collect, with your prior permission:</p>
            <ul>
                <li>Information regarding your location</li>
                <li>Information from your Device's phone book (contacts list)</li>
                <li>Pictures and other information from your Device’s camera and photo library</li>
            </ul>
            <p>We use this information to provide features of our Service and to improve and customize our Service.</p>
            <p>The information may be uploaded to the Company's servers and/or a Service Provider's server, or it may be simply stored on your device.</p>
            <p>You can enable or disable access to this information at any time through your Device settings.</p>

            <h2>4. Types of Information Collected</h2>
            <p>While using our Service, we may ask you to provide certain personally identifiable information that can be used to contact or identify you ("Personal Data").</p>
            <p>Some of the information collected by us may qualify as Personal Data. We understand that all Personal Data you provide to us is voluntary.</p>
            <p>Collection, use, and disclosure of Personal Data requires your express consent, unless other legal grounds are available to us to collect such information as further specified in this Privacy Policy.</p>
            <p>By using or accessing the Platform or otherwise providing us with your Personal Data, where applicable, you provide us with your consent to our use, collection, retention, transfer, and disclosure of the Personal Data in accordance with the terms of this Privacy Policy.</p>
            <p>In the event of a change in the law applicable to data protection in India, you hereby expressly consent to our continued use, storage, collection, and disclosure of your information, including personal information, to the fullest extent permitted under such applicable law.</p>
            <p>We may reach out to you for obtaining additional consents and approvals as required under the amended law, and you will be required to comply with such requests.</p>
            <p>Should you choose not to provide us with such additional consents and approvals, we may have to discontinue your access to the platform.</p>
            <p>You may choose not to provide us with or withdraw any or all information included under Personal Data, but if you do so, we may be unable to allow you to access the Service for the provision of which your information is being collected or processed.</p>
            <p>You may opt out of receiving any or all of these communications from us by following the unsubscribe link.</p>
            <p>This Usage Data may include information such as your computer’s Internet Protocol address (e.g., IP address), browser type, browser version, the pages of our Service that you visit, the time and date of your visit, the time spent on those pages, unique device identifiers, and other diagnostic data.</p>
            <p>We may use and store information about your location if you give us permission to do so ("Location Data"). We use this data to provide features of our Service and to improve and customize our Service.</p>
            <p>You can enable or disable location services when you use our Service at any time through your device settings.</p>
            <p>If you decide to register through or otherwise grant us access to a Third-Party Social Media Service, we may collect Personal Data already associated with your Third-Party Social Media Service account, including, but not limited to, your name, email address, activities, or contact list associated with that account.</p>
            <p>If you choose to provide such information and Personal Data, during registration or otherwise, you give us permission to use, share, and store it in a manner consistent with this Privacy Policy.</p>
            <p>We use cookies and similar tracking technologies to track activity on our Service and to hold certain information.</p>
            <p>Cookies are files with a small amount of data that may include an anonymous unique identifier.</p>
            <p>Other tracking technologies such as beacons, tags, and scripts are also used to collect and track information and to improve and analyze our Service.</p>
            <p>However, if you do not accept cookies, you may not be able to use some portions of our Service.</p>
            <p>Certain sections of our Service and our emails may contain small electronic files known as web beacons (also referred to as clear gifs, pixel tags, and single-pixel gifs) that permit the Company, for example, to count users who have visited those pages or opened an email, and for other related website statistics (e.g., recording the popularity of a certain section and verifying system and server integrity).</p>
            <ul>
                <li><strong>Session Cookies:</strong> We use session cookies to operate our Service.</li>
                <li><strong>Preference Cookies:</strong> We use preference cookies to remember your preferences and various settings.</li>
                <li><strong>Security Cookies:</strong> We use security cookies for security purposes.</li>
                <li><strong>Advertising Cookies:</strong> Advertising cookies are used to serve you with advertisements that may be relevant to you and your interests.</li>
            </ul>
            <p>To improve our website or application experience and lend stability to our Service, we may collect information or employ third-party plugins that collect information about the devices you use to access our Service, including hardware models, operating systems and versions, software, file names and versions, preferred languages, device identifiers, advertising identifiers, device motion information, mobile network information, installed applications on device, and phone state.</p>
            <p>The information collected thus will be disclosed to or collected directly by these plugins and may be used to improve the content and/or functionality of the Service offered to you.</p>
            <p>For a few of our Services, we may also collect your profile picture.</p>
            <p>In such cases, we fetch and store any information made available to us by you through these sign-in services.</p>
            <p>While using our Service, we may also collect the following information: sex, age, date of birth, place of birth, passport details, citizenship, registration at place of residence and actual address, telephone number (work, mobile), details of documents on education, qualification, professional training, employment agreements, NDA agreements, information on bonuses and compensation, information on marital status, family members, social security (or other taxpayer identification) number, office location, and other data or indirect information.</p>

            <h2>5. Use of Data</h2>
            <p>We use the collected data for various purposes:</p>
            <ul>
                <li>To provide and maintain our Service;</li>
                <li>To allow you to participate in interactive features of our Service when you choose to do so;</li>
                <li>To manage your Account, including your registration as a user of the Service;</li>
                <li>The Personal Data you provide grants you access to different functionalities of the Service available to you as a registered user;</li>
                <li>To gather analysis or valuable information to improve our Service;</li>
                <li>To fulfill any other purpose for which you provide it;</li>
                <li>To carry out our obligations and enforce our rights arising from any contracts entered into between you and us, including for billing and collection;</li>
                <li>To provide you with notices about your account and/or subscription, including expiration and renewal notices, email instructions, etc.;</li>
                <li>To provide you with news, special offers, and general information about other goods, services, and events which we offer that are similar to those you have already purchased or inquired about, unless you have opted not to receive such information;</li>
                <li>To contact you by email, telephone calls, SMS, or other equivalent forms of electronic communication (such as mobile application push notifications) regarding updates or informative communications related to the functionalities, products, or contracted services, including security updates, when necessary or reasonable for their implementation;</li>
                <li>To manage your requests, including to attend to and manage your requests to us;</li>
                <li>For business transfers, including the use of your information to evaluate or conduct a merger, divestiture, restructuring, reorganization, dissolution, or other sale or transfer of some or all of our assets, whether as a going concern or as part of bankruptcy, liquidation, or similar proceeding, in which Personal Data held by us about our Service users is among the assets transferred;</li>
                <li>For other purposes, such as data analysis, identifying usage trends, determining the effectiveness of our promotional campaigns, and to evaluate and improve our Service, products, services, marketing, and your experience;</li>
                <li>In any other way we may describe when you provide the information; or</li>
                <li>For any other purpose with your consent.</li>
            </ul>
            <p>We may share your personal information in the following situations:</p>
            <ul>
                <li><strong>With Service Providers:</strong> We may share your personal information with Service Providers to monitor and analyze the use of our Service and to contact you.</li>
                <li><strong>For business transfers:</strong> We may share or transfer your personal information in connection with, or during negotiations of, any merger, sale of Company assets, financing, or acquisition of all or a portion of our business to another company.</li>
                <li><strong>With Affiliates:</strong> We may share your information with our affiliates, in which case we will require those affiliates to honor this Privacy Policy. Affiliates include our parent company and any other subsidiaries, joint venture partners, or other companies that we control or that are under common control with us.</li>
                <li><strong>With business partners:</strong> We may share your information with our business partners to offer you certain products, services, or promotions.</li>
                <li><strong>With other users:</strong> When you share personal information or otherwise interact in public areas with other users, such information may be viewed by all users and may be publicly distributed.</li>
                <li><strong>With your consent:</strong> We may disclose your personal information for any other purpose with your consent.</li>
            </ul>

            <h2>6. Retention of Data</h2>
            <p>We will retain your Personal Data only for as long as necessary for the purposes set out in this Privacy Policy.</p>
            <p>Usage Data is generally retained for a shorter period, except when this data is used to strengthen the security or improve the functionality of our Service, or if we are legally obligated to retain this data for longer periods.</p>

            <h2>7. Transfer of Data</h2>
            <p>Your information, including Personal Data, may be transferred to and maintained on computers located outside of your area, city, state, country, or other governmental jurisdiction where data protection laws may differ from those of your jurisdiction.</p>
            <p>If you are located outside India and choose to provide information to us, please note that we transfer the data, including Personal Data, to India and process it there.</p>
            <p>Your consent to this Privacy Policy, followed by your submission of such information, represents your agreement to that transfer.</p>

            <h2>8. Disclosure of Data</h2>
            <p>Under certain circumstances, we may be required to disclose your Personal Data if compelled by law or in response to valid requests by public authorities.</p>
            <p>If we or our subsidiaries are involved in a merger, acquisition, or asset sale, your Personal Data may be transferred.</p>
            <p>We may disclose your Personal Data to:</p>
            <ul>
                <li>To our subsidiaries and affiliates;</li>
                <li>To contractors, Service Providers, and other third parties we use to support our business;</li>
                <li>To fulfill the purpose for which you provide it;</li>
                <li>For the purpose of including your company’s logo on our website;</li>
                <li>For any other purpose disclosed by us when you provide the information;</li>
                <li>With your consent in any other cases;</li>
                <li>If we believe disclosure is necessary or appropriate to protect the rights, property, or safety of the Company, our customers, or others.</li>
            </ul>

            <h2>9. Security of Data</h2>
            <p>The security of your data is important to us, but remember that no method of transmission over the Internet or method of electronic storage is 100% secure.</p>

            <h2>10. Your Data Protection Rights</h2>
            <p>In accordance with applicable law, you have the right to ask us to provide details about what we have collected, to delete it, or to exercise any other right you may have.</p>
            <p>However, requesting the deletion of your personal information may also result in a loss of access to the Service we provide.</p>
            <p>This Privacy Policy is governed by and operated in accordance with the laws of India. If any of the parties wish to seek legal recourse, they may do so by using the courts of law in New Delhi.</p>

            <h2>11. Service Providers</h2>
            <p>We may employ third-party companies and individuals to facilitate our Service ("Service Providers"), provide Service on our behalf, perform Service-related services, or assist us in analyzing how our Service is used.</p>

            <h2>12. Analytics</h2>
            <p>We may use third-party Service Providers to monitor and analyze the use of our Service.</p>

            <h2>13. CI/CD Tools</h2>
            <p>We may use third-party Service Providers to automate the development process of our Service.</p>

            <h2>14. Advertising</h2>
            <p>We may use third-party Service Providers to show advertisements to you to help support and maintain our Service.</p>

            <h2>15. Behavioral Remarketing</h2>
            <p>We may use remarketing services to advertise on third-party websites to you after you have visited our Service.</p>
            <p>We and our third-party vendors use cookies to inform, optimize, and serve ads based on your past visits to our Service.</p>

            <h2>16. Payments</h2>
            <p>We may provide paid products and/or services within the Service. In that case, we use third-party services for payment processing (e.g., payment processors).</p>
            <p>That information is provided directly to our third-party payment processors, whose use of your personal information is governed by their Privacy Policy.</p>

            <h2>17. Links to Other Sites</h2>
            <p>We have no control over such third-party links, which are provided by persons or companies other than us.</p>
            <p>We assume no responsibility for any collection or disclosure of your information by such companies or persons thereof, or for the content, privacy policies, or practices of any third-party sites or services.</p>
            <p>Furthermore, we are not liable for any loss or damage that may be incurred by you as a result of the collection and/or disclosure of your information via such third-party links.</p>
            <p>We shall not be liable for any disputes arising from or in connection with such transactions between you and the aforementioned third parties.</p>
            <p>Such third-party websites and external applications or resources accessible via the Service may have their own privacy policies governing the collection, storage, transfer, retention, and/or disclosure of your information that you may be subject to.</p>
            <p>We strongly advise that you exercise reasonable diligence, as you would in traditional online channels, and practice judgment and common sense before committing to any transaction or exchange of information, including, but not limited to, reviewing the third-party website or application’s privacy policy.</p>

            <h2>18. Children’s Privacy</h2>
            <p>Our Service is not intended for use by children under the age of 18 ("Child" or "Children").</p>
            <p>We do not knowingly collect personally identifiable information from children under 18. If you become aware that a Child has provided us with Personal Data, please contact us.</p>
            <p>If we become aware that we have collected Personal Data from children without verification of parental consent, we aim to take steps to remove that information from our servers.</p>

            <h2>19. Changes to This Privacy Policy</h2>
            <p>We will notify you of any changes by posting the new Privacy Policy on this page.</p>
            <p>We will notify you via email and/or a prominent notice on our Service prior to the change becoming effective and will update the "effective date" at the top of this Privacy Policy.</p>
            <p>You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when posted on this page.</p>

            <h2>20. Disclaimer</h2>
            <p>WE CANNOT ENSURE THAT ALL OF YOUR INFORMATION, INCLUDING PERSONAL DATA, WILL NEVER BE DISCLOSED IN WAYS NOT OTHERWISE DESCRIBED IN THIS PRIVACY POLICY.</p>
            <p>THEREFORE, ALTHOUGH WE ARE COMMITTED TO PROTECTING YOUR PRIVACY, WE DO NOT PROMISE, AND YOU SHOULD NOT EXPECT, THAT YOUR INFORMATION OR PRIVATE COMMUNICATIONS WILL ALWAYS REMAIN PRIVATE.</p>
            <p>AS A USER OF THE SERVICE, YOU ASSUME ALL RESPONSIBILITY AND RISK FOR YOUR USE OF THE PLATFORM, THE INTERNET GENERALLY, AND THE INFORMATION YOU POST OR ACCESS, AS WELL AS FOR YOUR CONDUCT ON AND OFF THE SERVICE.</p>

            <h2>21. Indemnity</h2>
            <p>YOU AGREE AND UNDERTAKE TO INDEMNIFY US IN ANY SUIT OR DISPUTE BY ANY THIRD PARTY ARISING OUT OF YOUR DISCLOSURE OF INFORMATION TO THIRD PARTIES EITHER THROUGH OUR SERVICE OR OTHERWISE, AND YOUR USE AND ACCESS OF WEBSITES, APPLICATIONS, AND RESOURCES OF THIRD PARTIES.</p>
            <p>WE ASSUME NO LIABILITY FOR ANY ACTIONS OF THIRD PARTIES WITH REGARD TO YOUR INFORMATION OR PERSONAL DATA WHICH YOU MAY HAVE DISCLOSED TO SUCH THIRD PARTIES.</p>

            <h2>22. Contact Us</h2>
            <p>If you have any questions about this Privacy Policy, please contact us by email at <a href="mailto:premierappsolutionpf@gmail.com">premierappsolutionpf@gmail.com</a>.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="/">
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
                        <li><a href="{{route('about')}}">About Us</a></li>
                        <li><a href="{{route('contact')}}">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h3>Legal</h3>
                    <ul>
                        <li><a href="{{route('terms_and_conditions')}}">Terms & conditions</a></li>
                        <li><a href="{{route('privacy_policy')}}">Privacy policy</a></li>
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