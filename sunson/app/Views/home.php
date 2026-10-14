<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Discover thoughtful solar consultation, installation, and maintenance with Sun Son Solar.">
  <title>Sun Son Solar | Power Your Future</title>
  <link rel="stylesheet" href="<?= base_url('css/home.css') ?>">
</head>
<body>
  <header class="site-header">
    <a class="brand" href="<?= site_url('/') ?>" aria-label="Sun Son Solar home">
      <span class="brand-mark" aria-hidden="true">S</span>
      <span>Sun Son <strong>Solar</strong></span>
    </a>
    <nav class="main-nav" aria-label="Main navigation">
      <a href="#services">Explore Services</a>
      <a href="#about">About Us</a>
      <a href="#support">Support</a>
    </nav>
    <a class="button button-outline header-login" href="<?= site_url('login') ?>">Login</a>
  </header>

  <main>
    <section class="hero" aria-labelledby="hero-title">
      <div class="hero-content">
        <p class="eyebrow">A brighter way to power what matters</p>
        <h1 id="hero-title">Power Your Future with Solar</h1>
        <p class="hero-copy">Make the switch to dependable, thoughtfully designed solar energy with a team that is with you at every step.</p>
        <div class="hero-actions">
          <a class="button button-primary" href="<?= site_url('register') ?>">Schedule a Service</a>
          <a class="button button-light" href="mailto:katherine.sinagaraw@sunsonsolar.com?subject=Free%20Solar%20Quote">Request a Free Quote</a>
        </div>
        <p class="hero-note">Personal guidance. Professional installation. Long-term care.</p>
      </div>
      <a class="hero-scroll" href="#about">Discover the Sun Son difference <span aria-hidden="true">↓</span></a>
    </section>

    <section class="section about-section" id="about">
      <div class="section-heading">
        <p class="eyebrow">Energy with intention</p>
        <h2>A smarter way to power your home</h2>
        <p>Sun Son Solar helps homeowners move toward clean energy with practical advice, quality workmanship, and support that lasts beyond installation.</p>
      </div>
      <div class="benefit-grid">
        <article class="benefit-card">
          <span class="card-number">01</span>
          <h3>Cost Savings</h3>
          <p>Generate more of your own electricity and reduce your reliance on changing utility rates.</p>
        </article>
        <article class="benefit-card">
          <span class="card-number">02</span>
          <h3>Sustainability</h3>
          <p>Use clean, renewable energy from the sun and make a lighter footprint for the future.</p>
        </article>
        <article class="benefit-card">
          <span class="card-number">03</span>
          <h3>Energy Independence</h3>
          <p>Take greater control of how your property is powered with a system designed for your needs.</p>
        </article>
      </div>
    </section>

    <section class="section services-section" id="services">
      <div class="section-heading section-heading-row">
        <div>
          <p class="eyebrow">From first conversation to ongoing care</p>
          <h2>Solar made straightforward</h2>
        </div>
        <a class="text-link" href="<?= site_url('register') ?>">Explore your options <span aria-hidden="true">→</span></a>
      </div>
      <div class="service-grid">
        <article class="service-card">
          <div class="service-visual service-consultation" aria-hidden="true"><span>01</span></div>
          <div class="service-copy">
            <p class="service-label">Plan with confidence</p>
            <h3>Consultation</h3>
            <p>Get clear answers and a tailored recommendation based on your property, goals, and energy use.</p>
          </div>
        </article>
        <article class="service-card">
          <div class="service-visual service-installation" aria-hidden="true"><span>02</span></div>
          <div class="service-copy">
            <p class="service-label">Built with care</p>
            <h3>Installation</h3>
            <p>Our team coordinates a professional installation designed to integrate seamlessly with your home.</p>
          </div>
        </article>
        <article class="service-card">
          <div class="service-visual service-maintenance" aria-hidden="true"><span>03</span></div>
          <div class="service-copy">
            <p class="service-label">Support that stays</p>
            <h3>Maintenance</h3>
            <p>Keep your system performing with responsive support and ongoing maintenance when you need it.</p>
          </div>
        </article>
      </div>
    </section>

    <section class="story-section" aria-labelledby="story-title">
      <div class="story-image" role="img" aria-label="Solar panels gathering energy beneath a bright sky"></div>
      <div class="story-content">
        <p class="eyebrow">A better kind of energy story</p>
        <h2 id="story-title">More confidence in every sunny day.</h2>
        <blockquote>
          “To illuminate a sustainable future by merging clean energy with intelligent design.”
          <cite>Katherine “Kat” Sinagaraw, Sun Son Solar</cite>
        </blockquote>
        <a class="button button-primary" href="mailto:katherine.sinagaraw@sunsonsolar.com?subject=Share%20my%20solar%20story">Start your solar story</a>
      </div>
    </section>

    <section class="cta-section" id="support">
      <div>
        <p class="eyebrow">Your next step starts here</p>
        <h2>Ready to see what solar can do for you?</h2>
        <p>Talk with our team about a solution built around your home and your goals.</p>
      </div>
      <div class="cta-actions">
        <a class="button button-light" href="<?= site_url('register') ?>">Schedule a Service</a>
        <a class="button button-outline-light" href="mailto:katherine.sinagaraw@sunsonsolar.com?subject=Free%20Solar%20Quote">Request a Free Quote</a>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="footer-main">
      <div class="footer-brand">
        <a class="brand brand-light" href="<?= site_url('/') ?>">
          <span class="brand-mark" aria-hidden="true">S</span>
          <span>Sun Son <strong>Solar</strong></span>
        </a>
        <p>Thoughtful solar energy, designed around you.</p>
      </div>
      <div class="footer-column">
        <h2>Explore</h2>
        <a href="#services">Our services</a>
        <a href="#about">Why solar</a>
        <a href="<?= site_url('register') ?>">Create an account</a>
      </div>
      <div class="footer-column">
        <h2>Get in touch</h2>
        <a href="mailto:katherine.sinagaraw@sunsonsolar.com">katherine.sinagaraw@sunsonsolar.com</a>
        <a href="<?= site_url('login') ?>">Customer login</a>
        <a href="mailto:katherine.sinagaraw@sunsonsolar.com?subject=Sun%20Son%20Solar%20Support">Contact support</a>
      </div>
      <div class="footer-column">
        <h2>Follow along</h2>
        <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer">Facebook</a>
        <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">Instagram</a>
        <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer">LinkedIn</a>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> Sun Son Solar. All rights reserved.</p>
      <p>Solar energy information is provided for general guidance. System performance varies by property and usage.</p>
    </div>
  </footer>
</body>
</html>
