@vite(['resources/css/app.css', 'resources/js/app.js'])
@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto mt-10">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">
        Bienvenido a Fisiocare
    </h1>

    <p class="text-lg text-gray-600">
        Esta será tu página de inicio completamente personalizada.
    </p>

</div>
@endsection
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FisioCare Ayla - Physiotherapy Clinic</title>
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">>
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="nav-content">
            <a href="/" class="logo">
                <div class="logo-box">FC</div>
                <span>
                    <strong>FisioCare Ayla</strong>
                    <small>Physiotherapy Clinic</small>
                </span>
            </a>

            <ul class="nav-links">
                <li><a href="#hero">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#testimonials">Testimonials</a></li>
                <li><a href="{{ route('appointments.create') }}" class="btn btn-primary">Book Appointment</a></li>
            </ul>

            <div class="nav-buttons">
                <a href="{{ route('login') }}" class="btn-login">Login</a>
                <a href="{{ route('register') }}" class="btn-register">Register</a>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section id="hero" class="hero">
        <div class="container hero-content">
            <div class="hero-left">
                <h1 class="hero-title">Recupera tu Movilidad, Vive Sin Dolor</h1>
                <p class="hero-lead">Atención especializada en fisioterapia, rehabilitación y terapia manual. Profesionales certificados y planes personalizados para cada patient.</p>

                <div class="hero-buttons">
                    <a href="{{ route('appointments.create') }}" class="btn btn-primary">Book Appointment</a>
                </div>
            </div>

            <div class="hero-right">
                <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=600&h=600&fit=crop" alt="Profesional de Fisioterapia">
            </div>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section id="about" class="about">
        <div class="container about-content">
            <div class="about-text">
                <h2>About Us</h2>
                <p>FisioCare Ayla is a modern clinic specialized in physiotherapy and rehabilitation, with certified professionals and state-of-the-art equipment.</p>
                <p>Our team is dedicated to providing personalized care, comprehensive evaluation, and evidence-based treatments.</p>

                <div class="about-highlights">
                    <div class="highlight-item">
                        <div class="highlight-icon">✓</div>
                        <div class="highlight-text">
                            <strong>Certified Professionals</strong>
                            <small>Team with extensive training and experience</small>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon">✓</div>
                        <div class="highlight-text">
                            <strong>Modern Equipment</strong>
                            <small>State-of-the-art technology for better care</small>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon">✓</div>
                        <div class="highlight-text">
                            <strong>Personalized Plans</strong>
                            <small>Treatment adapted to your needs</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-img">
                <div class="geometric-frame">
                    <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=400&h=500&fit=crop" alt="Equipo de Fisioterapia">
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION -->
    <section id="services" class="services">
        <div class="container">
            <div class="services-header">
                <h2 class="section-title">Nuestros Services Especializados</h2>
                <p class="section-subtitle">Physiotherapy specialties for your complete well-being</p>
            </div>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">👶</div>
                    <h3>Pediatric Physiotherapy</h3>
                    <p>Specialized treatments for children with motor and developmental needs.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">👴</div>
                    <h3>Geriatric Physiotherapy</h3>
                    <p>Mobility, strengthening, and well-being for older adults.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🫁</div>
                    <h3>Respiratory Physiotherapy</h3>
                    <p>Techniques to improve pulmonary and respiratory function.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🦴</div>
                    <h3>Traumatological Physiotherapy</h3>
                    <p>Recovery of bone, joint, and muscle injuries.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">❤️</div>
                    <h3>Cardiovascular Physiotherapy</h3>
                    <p>Cardiac rehabilitation and post-operative recovery.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🤲</div>
                    <h3>Occupational Physiotherapy</h3>
                    <p>Adaptation and rehabilitation for daily life activities.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🕊️</div>
                    <h3>Palliative Care</h3>
                    <p>Complementary therapy for comfort and quality of life.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🏘️</div>
                    <h3>Community Physiotherapy</h3>
                    <p>Prevention and health promotion programs in communities.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">⚽</div>
                    <h3>Sports Physiotherapy</h3>
                    <p>Treatment of sports injuries and performance improvement.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🧠</div>
                    <h3>Fisioterapia Neurológica</h3>
                    <p>Rehabilitación de trastornos neuromotores y neurológicos.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section id="testimonials" class="testimonials">
        <div class="container">
            <div class="services-header">
                <h2 class="section-title">What Our Patients Say</h2>
                <p class="section-subtitle">Stories of recovery and well-being</p>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p>"Después de un mes de tratamiento, mi dolor de espalda disminuyó significativamente. El equipo es muy profesional y atento."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">MC</div>
                        <div class="author-info">
                            <strong>María Consulta</strong>
                            <small>Satisfied patient</small>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p>"La terapia me ayudó a recuperarme de mi lesión deportiva mucho más rápido de lo que esperaba. ¡Recomendado!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">JG</div>
                        <div class="author-info">
                            <strong>Juan García</strong>
                            <small>Professional athlete</small>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p>"Excelente atención al detalle. Los physiotherapists me explicaron cada paso del proceso. Muy satisfecho con los resultados."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">CR</div>
                        <div class="author-info">
                            <strong>Carlos Rodríguez</strong>
                            <small>Retired businessman</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section id="faq" class="faq">
        <div class="container faq-container">
            <div class="services-header">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <p class="section-subtitle">Resolve your doubts about our services</p>
            </div>

            <div class="faq-items">
                <div class="faq-item">
                    <button class="faq-question">
                        How many sessions will I need?
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        The number of sessions depends on your specific condition. In the initial evaluation, we determine a personalized plan that can vary between 4-20 sessions depending on the assessment.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Do you accept health insurance?
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        Yes, we work with most health insurances in the country. We recommend checking your physiotherapy coverage directly with your insurance company.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        What should I bring to my first appointment?
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        Bring ID, medical history (if you have one), recent relevant exams, comfortable clothing, and any previous diagnostic documentation.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        How long does a typical session last?
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        Physiotherapy sessions usually last between 45-60 minutes, including evaluation, treatment, and rehabilitation exercises.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Are there schedules available on weekends?
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        Yes, we have limited availability on Saturdays. Contact us directly to check availability and book your appointment.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Do you offer home therapy?
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        Yes, we offer home physiotherapy services for patients with limited mobility. Check availability and special rates.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container footer-content">
            <div class="footer-section">
                <h4>FisioCare Ayla</h4>
                <p>Specialized clinic in physiotherapy and rehabilitation with certified professionals and modern equipment.</p>
                <div class="social-links">
                    <a href="#" class="social-link">f</a>
                    <a href="#" class="social-link">𝕏</a>
                    <a href="#" class="social-link">IG</a>
                    <a href="#" class="social-link">📺</a>
                </div>
            </div>

            <div class="footer-section">
                <h4>Navigation</h4>
                <div class="footer-links">
                    <a href="#hero">Home</a>
                    <a href="#services">Services</a>
                    <a href="#about">About Us</a>
                    <a href="#testimonials">Testimonials</a>
                    <a href="#faq">Frequently Asked Questions</a>
                </div>
            </div>

            <div class="footer-section">
                <h4>Contact</h4>
                <div class="footer-links">
                    <a href="tel:+18095550000">Tel: (809) 555-0000</a>
                    <a href="mailto:info@fisiocare.com">info@fisiocare.com</a>
                    <a href="#">Barahona, República Dominicana</a>
                </div>
            </div>

            <div class="footer-section">
                <h4>Legal</h4>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Book Appointments</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} FisioCare Ayla. All rights reserved. | Designed with dedication to your well-being.</p>
        </div>
    </footer>

    <!-- FAQ INTERACTIVITY -->
    <script>
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', function() {
                const faqItem = this.parentElement;
                faqItem.classList.toggle('active');
            });
        });
    </script>

</body>
</html>
