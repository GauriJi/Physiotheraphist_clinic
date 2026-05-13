<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FisioCare Ayla - Physiotherapy Clinic</title>
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">

    <!-- Font Awesome (iconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Inline overrides para Responsividad y Carrusel (rápido) -->
    <style>
        /* NAVBAR corregido para usar con tu header (pega directamente) */

/* Estructura principal */
.navbar {
  background: #fff;
  border-bottom: 1px solid #eee;
  position: sticky;
  top: 0;
  z-index: 999;
}

.nav-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: 1100px;
  margin: 0 auto;
  padding: .75rem 1rem;
  gap: 1rem;
  position: relative; /* referencia para el menú absolute */
}

/* Logo (derecha) */
 .logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: inherit;
    width: auto;
    height: auto;
    padding: 6px 0;
    box-shadow: none;
    border: none;
}
.logo-img {
  width: 56px;
  height: 56px;
  object-fit: cover;
  border-radius: 8px;
  display: block;
}

.logo span strong { font-family: 'Poppins', sans-serif; font-weight: 700; }
.logo span small  { font-size: 12px; color: var(--gray-text, #6b7280); }

/* Contenedor derecho agrupado (toggle + links + botones) */
.nav-right {
  display: flex;
  align-items: center;
  gap: .6rem;
  margin-left: auto;
}

/* Links principales y botones (desktop) */
.nav-links {
  display: flex;
  gap: 1rem;
  list-style: none;
  margin: 0;
  padding: 0;
  align-items: left;
}
.nav-links li a {
  text-decoration: none;
  padding: .4rem .6rem;
  color: #0f172a;
}

/* Botones de cabecera */
.nav-buttons {
  display: flex;
  gap: .5rem;
  align-items: left;
}

/* Estilos reutilizables para botones */
.btn-login, .btn-register, .btn-primary {
  text-decoration: none;
  padding: .45rem .75rem;
  border-radius: 6px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
.btn-primary { background: #0066cc; color: #fff; border: none; }
.btn-login { background: transparent; border: 2px solid #0066cc; color: #0066cc; font-weight: 600; }
.btn-register { background: #0066cc; color: #fff; font-weight: 600; box-shadow: 0 4px 12px rgba(0,102,204,.3); }

/* Toggle (móvil) */
.nav-toggle {
  display: none;
  background: transparent;
  border: 0;
  font-size: 1.35rem;
  cursor: pointer;
  position: relative;
  z-index: 2000; /* asegurar que reciba clics */
}

/* Comportamiento móvil */
@media (max-width: 860px) {
  /* Mostrar toggle en móvil */
  .nav-toggle { display: block; }

  /* Ocultar los botones de cabecera en móvil (mostramos duplicados dentro del menú) */
  .nav-buttons { display: none; }

  /* Menú desplegable (oculto por defecto) */
  .nav-links {
    display: none;
    position: absolute;
    top: 64px;
    left: 0;
    right: 0;
    background: #fff;
    flex-direction: column;
    padding: 1rem;
    box-shadow: 0 6px 18px rgba(2,6,23,.08);
    z-index: 1050;
    width: 100%;
  }

  /* Clase que el JS añade para mostrar el menú */
  .nav-links.show { display: flex !important; }

  /* Orden y alineación: logo a la izquierda, toggle a la derecha */
  .logo  { order: 1; }
  .nav-right { order: 2; display: flex; align-items: center; gap: .6rem; margin-left: 0; width: auto; }
  .nav-toggle { order: 3; margin-left: auto; }
  .nav-links  { order: 4; }

  /* Ajustes de espacio y visual para elementos dentro del menú */
  .nav-links { gap: 0.8rem; padding-top: .5rem; }

  /* Mostrar los duplicados de auth dentro del menú (li.mobile-auth) */
  .nav-links .mobile-auth { display: block; margin-top: 0.4rem; }
  .nav-links .mobile-auth a {
    display: inline-block;
    width: 100%;
    text-align: center;
    padding: .6rem .8rem;
    border-radius: 8px;
    box-sizing: border-box;
  }
  .nav-links .mobile-auth a.btn-primary { background: #0066cc; color: #fff; border: none; box-shadow: 0 6px 16px rgba(0,102,204,.25); }
  .nav-links .mobile-auth a.btn-login { background: transparent; border: 2px solid #0066cc; color: #0066cc; }
}

/* En pantallas grandes ocultar los duplicados dentro del UL */
@media (min-width: 861px) {
  .nav-links .mobile-auth { display: none; }
}

/* Asegurar visibilidad si JS agrega la clase */
.nav-links.show { display: flex !important; }
/* Asegurar visibilidad si JS agrega la clase */
.nav-links.show { display: flex !important; }


        /* SERVICES CARRUSEL */
        .services-carousel{ position:relative; overflow:hidden; }
        .services-track{ display:flex; gap:1rem; transition:transform .5s ease; }
        .service-card{ flex:0 0 260px; background:#fff; border-radius:12px; padding:1rem; box-shadow:0 6px 18px rgba(2,6,23,.06); min-height:150px; }
        .services-controls{ position:absolute; top:50%; transform:translateY(-50%); left:8px; right:8px; display:flex; justify-content:space-between; pointer-events:none; }
        .services-controls button{ pointer-events:auto; background:rgba(0,0,0,.5); color:#fff; border:0; padding:.4rem .6rem; border-radius:6px; }

        /* TESTIMONIALS (tarjetas pequeñas) */
        .testimonials-grid{ display:flex; gap:1rem; flex-wrap:wrap; }
        .testimonial-card{ width:calc(33% - .66rem); background:#fff; padding:.8rem; border-radius:10px; box-shadow:0 6px 18px rgba(2,6,23,.05); display:flex; gap:.6rem; align-items:flex-start; }
        .testimonial-card img{ width:56px; height:56px; border-radius:50%; object-fit:cover; }
        .testimonial-card p{ font-size:.95rem; margin:0; }
        @media(max-width:900px){ .testimonial-card{ width:48%; } }
        @media(max-width:560px){ .testimonial-card{ width:100%; } }

        /* Encabezados secciones alineados a la izquierda */
        .services-header{ text-align:center; margin-bottom:.8rem; }

        /* FAQ: dos columnas */
        .faq-container{ display:flex; gap:2rem; align-items:flex-start; }
        .faq-items{ flex:1 1 55%; }
        .faq-video{ flex:0 0 40%; }
        .faq-video iframe, .faq-video video{ width:100%; height:280px; border-radius:10px; }
        .faq-question{ width:100%; text-align:left; padding: .6rem 1rem; border-radius:8px; border:1px solid #e6e6e6; background:#fff; display:flex; justify-content:space-between; align-items:center; }
        .faq-answer{ display:none; padding:.6rem 1rem; color:#111; }
        .faq-item.active .faq-answer{ display:block; }

                /* Forzar que .nav-links.show se vea si alguna regla lo está sobrescribiendo */
                .nav-toggle { position: relative; z-index: 2000; cursor: pointer; }
                .nav-links.show { display: flex !important; }

                /* === Responsive general para toda la página === */
                body, html {
                    width: 100vw;
                    min-width: 320px;
                    overflow-x: hidden;
                }

                .container {
                    width: 100%;
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 0 20px;
                    box-sizing: border-box;
                }

                .nav-content {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    flex-wrap: wrap;
                    max-width: 1100px;
                    margin: 0 auto;
                    padding: .75rem 1rem;
                    gap: 1rem;
                }

                .logo {
                    display: flex;
                    align-items: center;
                    gap: .6rem;
                    text-decoration: none;
                    color: inherit;
                    margin-right: 0;

                }

                .logo-img {
                    width: 56px;
                    height: 56px;
                    object-fit: cover;
                    border-radius: 8px;
                    display: block;
                }

                .logo-text {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    line-height: 1.1;
                }

                .logo-text strong {
                    font-family: 'Poppins', sans-serif;
                    font-weight: 700;
                    font-size: 1.15rem;
                    color: #4B0082;
                }

                .logo-text small {
                    font-size: 13px;
                    color: #6b7280;
                    font-weight: 400;
                    margin-top: 2px;
                }

                /* Responsive para secciones principales */
                @media (max-width: 900px) {
                    .nav-content {
                        flex-direction: row;
                        height: auto;
                        padding: 0 8px;
                    }
                    .container {
                        padding: 0 8px;
                    }
                    .hero-content,
                    .about-content {
                        display: block;
                        gap: 2rem;
                    }
                    .hero-right,
                    .about-img {
                        height: 250px;
                        margin-top: 1rem;
                    }
                    .geometric-frame {
                        width: 180px;
                        height: 220px;
                    }
                    .services-grid,
                    .testimonials-grid {
                        flex-direction: column;
                        gap: 1rem;
                    }
                    .footer-content {
                        display: block;
                        gap: 1rem;
                    }
                    .btn,
                    .btn-primary,
                    .btn-login,
                    .btn-register {
                        width: 100%;
                        margin-bottom: 8px;
                    }
                    .logo-text small {
                        font-size: 11px;
                    }
                }
                .about-img-oval {
  position: relative;
  width: 100%;
  max-width: 360px;
  aspect-ratio: 9 / 14;
  margin: auto;
}

.about-decor,
.about-frame {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}

.about-decor {
  z-index: 0;
}

.about-frame {
  z-index: 1;
}

.about-img-photo {
  position: relative;
  z-index: 2;
  width: 100%;
  height: 100%;
  object-fit: cover;
  clip-path: url(#about-oval-clip);
}

/* Responsive */
@media (max-width: 600px) {
  .about-img-oval {
    max-width: 95vw;
  }
}
/* ===============================
   OCULTAR IMAGEN ABOUT EN MÓVIL
   =============================== */

@media (max-width: 768px) {
  /* Oculta toda la columna visual */
  .about-img {
    display: none !important;
  }

  /* Asegura que el texto ocupe todo el ancho */
  .about-content {
    grid-template-columns: 1fr !important;
    display: block;
  }
}

    </style>
</head>
<body>

    @if (session('success'))
    <div style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 1000; background: #dcfce7; color: #166534; padding: 16px 24px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); max-width: 500px; text-align: center; animation: slideDown 0.4s ease;">
        ✓ {{ session('success') }}
    </div>
    <style>
        @keyframes slideDown {
            from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
    </style>
    @endif

    <!-- NAVBAR (pública) -->
    <header class="navbar">
  <div class="nav-content">
    <!-- LOGO (izquierda) -->
        <a href="{{ url('/') }}" class="logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="FisioCare Ayla Logo" class="logo-img">
            <div class="logo-text">
                <strong>FisioCare Ayla</strong>
                <small>Physiotherapy Clinic</small>
            </div>
        </a>

    <!-- Contenedor derecho: toggle, links y botones (mantiene orden y accesibilidad) -->
    <div class="nav-right">
      <!-- Toggle (aparece en móvil) -->
      <button class="nav-toggle" aria-label="Abrir menú" aria-expanded="false">☰</button>

      <!-- Links principales (desktop) + items móviles duplicados para auth -->
      <ul class="nav-links" aria-hidden="true">
        <li><a href="#hero">Home</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#about">About Us</a></li>
        <li><a href="#testimonials">Testimonials</a></li>
        <li><a href="#faq">FAQ</a></li>
        <li><a href="{{ route('appointments.publicas.create') }}" class="btn btn-primary">Book Appointment</a></li>

        <!-- Botones duplicados para móvil (se ocultan en escritorio) -->
        <li class="mobile-auth"><a href="{{ route('login') }}" class="btn-login">Login</a></li>
        <li class="mobile-auth"><a href="{{ route('register') }}" class="btn-register">Register</a></li>
      </ul>

      <!-- Botones de autenticación (escritorio) -->
      <div class="nav-buttons">
        <a href="{{ route('login') }}" class="btn-login">Login</a>
        <a href="{{ route('register') }}" class="btn-register">Register</a>
      </div>
    </div>
  </div>
</header>

    <!-- HERO SECTION -->
    <section id="hero" class="hero">
        <div class="hero-content" style="background-image: url('{{ asset('images/portada1.png') }}'); background-size: cover; background-position: center; min-height: 100vh; width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%); display: flex; flex-direction: column; justify-content: center; align-items: flex-start; padding-left: 6vw;">
            <h1 class="hero-title" style="font-family: 'Poppins', 'Montserrat', 'Open Sans', sans-serif; color: #fff; font-weight: bold; font-size: clamp(64px, 9vw, 90px); line-height: 1.08; text-align: left; margin-bottom: 2rem; max-width: 900px;">Recover your Mobility,<br>Live Without Pain</h1>
            <p class="hero-lead" style="font-family: 'Poppins', 'Montserrat', 'Open Sans', sans-serif; color: #fff; font-weight: 400; font-size: clamp(18px, 2vw, 24px); line-height: 1.6; text-align: left; margin-bottom: 2.5rem; max-width: 600px;">Specialized care in physiotherapy, rehabilitation, and manual therapy with a personalized approach.</p>
            <form action="{{ route('appointments.publicas.create') }}" method="get" style="margin:0;">
                <button type="submit" class="btn-cta">Book Appointment</button>
            </form>
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

  <div class="about-img-oval">

    <!-- SVG DECORATIVO + ANIMACIONES -->
    <svg class="about-decor" viewBox="0 0 360 560" aria-hidden="true">
      <!-- Círculos turquesa difuminados animados -->
      <circle cx="60" cy="80" r="22" fill="#00BCD4" opacity="0.18">
        <animate attributeName="r" values="22;28;22" dur="3s" repeatCount="indefinite" />
      </circle>
      <circle cx="320" cy="120" r="14" fill="#00BCD4" opacity="0.12">
        <animate attributeName="r" values="14;20;14" dur="2.5s" repeatCount="indefinite" />
      </circle>
      <circle cx="80" cy="500" r="18" fill="#00BCD4" opacity="0.15">
        <animate attributeName="r" values="18;24;18" dur="3.5s" repeatCount="indefinite" />
      </circle>

      <!-- Líneas curvas decorativas -->
      <path d="M40,60 Q120,20 200,80" stroke="#00BCD4" stroke-width="2" fill="none" opacity="0.18">
        <animate attributeName="opacity" values="0.18;0.32;0.18" dur="2.8s" repeatCount="indefinite" />
      </path>
      <path d="M300,520 Q180,580 100,480" stroke="#00BCD4" stroke-width="2" fill="none" opacity="0.13">
        <animate attributeName="opacity" values="0.13;0.28;0.13" dur="3.2s" repeatCount="indefinite" />
      </path>

      <!-- Destellos -->
      <circle cx="110" cy="40" r="4" fill="#fff" opacity="0.7">
        <animate attributeName="opacity" values="0.7;1;0.7" dur="1.8s" repeatCount="indefinite" />
      </circle>
      <circle cx="250" cy="540" r="3" fill="#fff" opacity="0.6">
        <animate attributeName="opacity" values="0.6;1;0.6" dur="2.1s" repeatCount="indefinite" />
      </circle>
    </svg>

    <!-- SVG DEL ÓVALO -->
    <svg class="about-frame" viewBox="0 0 360 560">
      <defs>
        <clipPath id="about-oval-clip">
          <ellipse cx="180" cy="280" rx="155" ry="240" />
        </clipPath>

        <linearGradient id="about-grad" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stop-color="#00BCD4"/>
          <stop offset="100%" stop-color="#00BCD4" stop-opacity="0.7"/>
        </linearGradient>

        <filter id="about-glow">
          <feGaussianBlur stdDeviation="20"/>
        </filter>
      </defs>

      <!-- Glow -->
      <ellipse cx="180" cy="280" rx="165" ry="250"
        fill="none"
        stroke="#00BCD4"
        stroke-width="32"
        opacity="0.15"
        filter="url(#about-glow)" />

      <!-- Marco -->
      <ellipse cx="180" cy="280" rx="155" ry="240"
        fill="none"
        stroke="url(#about-grad)"
        stroke-width="12" />
    </svg>

    <!-- IMAGEN -->
    <img
      src="{{ asset('images/aboutus.webp') }}"
      alt="Equipo de Fisioterapia"
      class="about-img-photo"
    />
  </div>
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

            <div class="services-carousel">
                <div class="services-track" id="servicesTrack">
                    <div class="service-card">
                        <div class="service-icon"><i class="fa-solid fa-baby"></i></div>
                        <h3>Pediatric Physiotherapy</h3>
                        <p>Specialized treatments for children with motor and developmental needs.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fa-solid fa-wheelchair"></i></div>
                        <h3>Geriatric Physiotherapy</h3>
                        <p>Mobility, strengthening, and well-being for older adults.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fa-solid fa-lungs"></i></div>
                        <h3>Respiratory Physiotherapy</h3>
                        <p>Techniques to improve pulmonary and respiratory function.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fa-solid fa-bone"></i></div>
                        <h3>Traumatological Physiotherapy</h3>
                        <p>Recovery of bone, joint, and muscle injuries.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fa-solid fa-heart-pulse"></i></div>
                        <h3>Cardiovascular Physiotherapy</h3>
                        <p>Cardiac rehabilitation and post-operative recovery.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fa-solid fa-hand-holding-hand"></i></div>
                        <h3>Occupational Physiotherapy</h3>
                        <p>Adaptation and rehabilitation for daily life activities.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fa-solid fa-hand-holding-droplet"></i></div>
                        <h3>Palliative Care</h3>
                        <p>Complementary therapy for comfort and quality of life.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fa-solid fa-people-group"></i></div>
                        <h3>Community Physiotherapy</h3>
                        <p>Prevention and health promotion programs in communities.</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon"><i class="fa-solid fa-person-running"></i></div>
                        <h3>Sports Physiotherapy</h3>
                        <p>Treatment of sports injuries and performance improvement.</p>
                    </div>
                </div>

                <div class="services-controls">
                    <button id="prevService" aria-label=__('messages.previous')>
                        <i class="fa-solid fa-circle-chevron-left"></i>
                    </button>
                    <button id="nextService" aria-label=__('messages.next')>
                        <i class="fa-solid fa-circle-chevron-right"></i>
                    </button>
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
                    {{-- Avatar antigua: iniciales MC --}}
                    {{--<div class="author-avatar">MC</div>--}}
                    <img src="{{ asset('images/maria.jpg') }}" alt="María Consulta">
                    <div>
                        <p>"After a month of treatment, my back pain decreased significantly. The team is very professional."</p>
                        <strong>María Consulta</strong>
                        <div style="font-size:.85rem;color:#6b7280">Satisfied patient</div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <img src="{{ asset('images/juan.jpg') }}" alt="Juan García">
                    <div>
                        <p>"The therapy helped me recover from my sports injury much faster than expected."</p>
                        <strong>Juan García</strong>
                        <div style="font-size:.85rem;color:#6b7280">Professional athlete</div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <img src="{{ asset('images/carlos.jpg') }}" alt="Carlos Rodriguez">
                    <div>
                        <p>"Excellent attention to detail. Very satisfied with the results."</p>
                        <strong>Carlos Rodríguez</strong>
                        <div style="font-size:.85rem;color:#6b7280">Retired businessman</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <section id="faq" class="faq">
    <div class="container">
        <div class="services-header">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-subtitle">Resolve your doubts about our services</p>
        </div>

        <div class="faq-columns">

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
            </div> <div class="faq-video">
                <iframe src="images/video.mp4" title="Testimonio" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

        </div> </div>
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
                    <a href="tel:+18098411681">Tel: (809) 841-1681</a>
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
        <!-- Toggle mobile nav (reemplazo seguro) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // NAVBAR MOVILIDAD
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');
    if (navToggle && navLinks) {
        navToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            navLinks.classList.toggle('show');
            const open = navLinks.classList.contains('show');
            navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            navLinks.setAttribute('aria-hidden', open ? 'false' : 'true');
        });
        document.addEventListener('click', function (e) {
            if (navLinks.classList.contains('show') && !navLinks.contains(e.target) && !navToggle.contains(e.target)) {
                navLinks.classList.remove('show');
                navToggle.setAttribute('aria-expanded', 'false');
                navLinks.setAttribute('aria-hidden', 'true');
            }
        });
    }

    // FAQ MOVILIDAD
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(function(item) {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', function() {
            item.classList.toggle('active');
        });
    });

    // Colores para íconos de specialties
    const serviceIcons = document.querySelectorAll('.service-icon i');
    const colors = [
        '#4B0082', '#0066cc', '#16a34a', '#eab308', '#ef4444', '#db2777', '#0ea5e9', '#f59e42', '#a21caf', '#f43f5e'
    ];
    serviceIcons.forEach(function(icon, idx) {
        icon.style.color = colors[idx % colors.length];
        icon.style.fontSize = '2.2rem';
    });
});
</script>
    <script>
        // SERVICES CARRUSEL (simple)
        (function(){
            const track = document.getElementById('servicesTrack');
            const prev = document.getElementById('prevService');
            const next = document.getElementById('nextService');
            let index = 0;
            const cardWidth = 260 + 16; // card width + gap approx
            function update(){
                track.style.transform = `translateX(${-index * cardWidth}px)`;
            }
            next.addEventListener('click', ()=>{ index++; if(index > track.children.length-3) index = 0; update(); });
            prev.addEventListener('click', ()=>{ index--; if(index < 0) index = Math.max(0, track.children.length-3); update(); });
            // autoplay
            setInterval(()=>{ index++; if(index > track.children.length-3) index = 0; update(); }, 3800);
        })();
    </script>

</body>
</html>
