<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('./css/landing.css') }}">
    <style>
        #home{
            background-image: url("images/homepic.jpg");
        }
    </style>

</head>
<body>

    <header class="header">
        <div>
            <a href="#contact" class="contact-us">Contact Us</a>
            <a href="#services" class="services">Services</a>
        </div>
        <div class="logo-container">
            <img class="logo" src="{{ asset('images/LOGO.png') }}" alt="logo">
        </div>
        <div class="auth-buttons">
            <a href="/login">Log In</a>
            <a href="/signup">Sign Up</a>
        </div>
    </header>

    <section id="home" class="section">
        <div class="home-content">
            <h1>Welcome to Hemlock Village!</h1>
            <p>Providing personalized care and support, where dignity and comfort come first.</p>
        </div>
    </section>

    <section id="services" class="section">
        <h1>Services we offer</h1>
        <div class="service-item reverse">
            <div class="service-image">
                <img src="images/247care.jpg" alt="Service 1">
            </div>
            <div class="service-text">
                <h3>24/7 Medical Care</h3>
                <p>Our dedicated team of medical professionals provides round-the-clock care to ensure the health and well-being of our residents. From managing chronic conditions to offering emergency medical assistance, we prioritize our residents' health with compassion and expertise.</p>
            </div>
        </div>
        <div class="service-item">
            <div class="service-image">
                <img src="images/pt.jpg" alt="Service 2">
            </div>
            <div class="service-text">
                <h3>Physical Therapy</h3>
                <p>Our licensed physical therapists offer rehabilitation services to help residents regain strength, mobility, and independence after illness, surgery, or injury. Through personalized treatment plans, we support the recovery process and improve overall quality of life.</p>
            </div>
        </div>
        <div class="service-item reverse">
            <div class="service-image">
                <img src="images/assistedliving.png" alt="Service 3">
            </div>
            <div class="service-text">
                <h3>Assisted Living</h3>
                <p>We offer assisted living services for residents who need help with daily activities such as bathing, dressing, and eating, but still enjoy some level of independence. Our team ensures that each resident receives personalized care tailored to their needs while maintaining their dignity and comfort.</p>
            </div>
        </div>
        <div class="service-item">
            <div class="service-image">
                <img src="images/nutrition.jpg" alt="Service 2">
            </div>
            <div class="service-text">
                <h3>Nutritional Support</h3>
                <p>We offer a personalized nutrition plan tailored to each resident’s health needs, including diet modification for specific medical conditions such as diabetes, hypertension, and heart disease. Our professional chefs prepare nutritious and delicious meals that promote health and well-being.</p>
            </div>
        </div>
        <div class="service-item reverse">
            <div class="service-image">
                <img src="images/counseling.jpg" alt="Service 3">
            </div>
            <div class="service-text">
                <h3>Family Support & Counseling </h3>
                <p>We offer family support services to help families navigate the challenges of caring for a loved one in a nursing home. Our counseling and guidance provide emotional support, helping families understand the care process and make informed decisions about their loved one’s well-being.</p>
            </div>
        </div>
    </section>

    <section id="contact" class="section">
        <h1>Contact Us</h1>
        <div class="form-container">
            <form>
                <input type="text" placeholder="Your Name" required><br><br>
                <input type="email" placeholder="Your Email" required><br><br>
                <textarea placeholder="Your Message" required></textarea><br><br>
                <button type="submit">Send Message</button>
            </form>
            <h1>Or contact us through:</h1>
            <p>phone number: (888) 888-8888</p>
            <p>email: hemlockvillage@email.com</p>
        </div>
    </section>

    <footer id="footer">
        <div class="footer-content">
            <p>&copy; 2024 Hemlock Village. All Rights Reserved.</p>
            <div class="social-links">
                <a href="https://facebook.com">Facebook</a>
                <a href="https://twitter.com" >Twitter</a>
                <a href="https://instagram.com" >Instagram</a>
            </div>
        </div>
    </footer>
</body>
</html>
