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
      <a href="#services">Services</a>
      <a href="#about">About</a>
      <a href="#contact">Contact</a>
      <a href="<?= site_url('login') ?>">Login</a>
      <a href="<?= site_url('register') ?>">Register</a>
    </nav>
  </header>

  <main>
    <section class="hero" aria-labelledby="hero-title">
      <div class="hero-content">
        <p class="eyebrow">A brighter way to power what matters</p>
        <h1 id="hero-title">Power Your Future with Solar</h1>
        <p class="hero-copy">Make the switch to dependable, thoughtfully designed solar energy with a team that is with you at every step.</p>
        <div class="hero-actions">
          <a class="button button-primary" href="<?= site_url('register') ?>">Schedule a Service</a>
          <a class="button button-light" href="#quote">Request a Free Quote</a>
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
      <p class="service-extra"><strong>More ways we can help:</strong> system design, permitting, repairs, and performance monitoring.</p>
      <?php if ($serviceCategories !== []): ?><p class="service-extra"><strong>Available service categories:</strong> <?= esc(implode(', ', array_column($serviceCategories, 'service_type'))) ?></p><?php endif; ?>
    </section>

    <section class="section products-section" id="products">
      <div class="section-heading">
        <p class="eyebrow">Components for a complete system</p>
        <h2>Quality equipment, chosen for your home</h2>
        <p>We help you select the right components for your roof, energy needs, and goals.</p>
      </div>
      <div class="product-grid">
        <article><span aria-hidden="true">☀</span><h3>Solar panels</h3><p>Reliable modules for generating clean electricity.</p></article>
        <article><span aria-hidden="true">⌁</span><h3>Inverters</h3><p>Convert solar power into usable household energy.</p></article>
        <article><span aria-hidden="true">◉</span><h3>Batteries</h3><p>Store energy for greater flexibility and backup.</p></article>
        <article><span aria-hidden="true">⌂</span><h3>Racking &amp; mounting</h3><p>Secure, roof-ready mounting for your installation.</p></article>
        <article><span aria-hidden="true">↗</span><h3>Wires &amp; balance of system</h3><p>Essential connections specified for safe operation.</p></article>
      </div>
    </section>

    <section class="story-section" aria-labelledby="story-title">
      <div class="story-image" role="img" aria-label="Solar panels gathering energy beneath a bright sky"></div>
      <div class="story-content">
        <p class="eyebrow">A better kind of energy story</p>
        <h2 id="story-title">More confidence in every sunny day.</h2>
        <blockquote>
          Thoughtful design, clear communication, and attentive aftercare help make every solar project a lasting success.
          <cite>The Sun Son Solar approach</cite>
        </blockquote>
        <a class="button button-primary" href="#quote">Start your solar story</a>
      </div>
    </section>

    <section class="section testimonials-section" id="stories">
      <div class="section-heading">
        <p class="eyebrow">Built around real needs</p>
        <h2>Every project has its own story</h2>
        <p>From a first consultation to a system that is ready for the long term, our team keeps customers informed at every step.</p>
      </div>
      <div class="story-grid">
        <article><p class="story-tag">Consultation</p><h3>A clear plan before the work begins</h3><p>We review your property and energy goals, explain the options, and outline practical next steps.</p></article>
        <article><p class="story-tag">Installation</p><h3>Careful coordination, start to finish</h3><p>Customers have one team to coordinate installation and answer questions along the way.</p></article>
        <article><p class="story-tag">Ongoing care</p><h3>Help after your system is switched on</h3><p>Maintenance and repair support help customers keep their systems working as intended.</p></article>
      </div>
    </section>

    <section class="section quote-section" id="quote">
      <div class="section-heading">
        <p class="eyebrow">A good place to start</p>
        <h2>Request a free solar quote</h2>
        <p>Tell us a little about your project. Our team will get in touch to learn more.</p>
      </div>
      <?php if (session('success')): ?><p class="form-notice success"><?= esc(session('success')) ?></p><?php endif; ?>
      <?php if (session('error')): ?><p class="form-notice error"><?= esc(session('error')) ?></p><?php endif; ?>
      <?php if (session('errors')): foreach (session('errors') as $error): ?><p class="form-notice error"><?= esc($error) ?></p><?php endforeach; endif; ?>
      <form class="quote-form" action="<?= site_url('quotes') ?>" method="post">
        <?= csrf_field() ?>
        <label>Your name<input type="text" name="name" value="<?= esc(old('name')) ?>" maxlength="120" required></label>
        <label>Email address<input type="email" name="email" value="<?= esc(old('email')) ?>" maxlength="100" required></label>
        <label>Phone number <span>(optional)</span><input type="tel" name="phone_number" value="<?= esc(old('phone_number')) ?>" maxlength="20"></label>
        <label class="full-field">Tell us about your project<textarea name="details" rows="4" maxlength="4000" required><?= esc(old('details')) ?></textarea></label>
        <button class="button button-primary" type="submit">Request a Free Quote</button>
      </form>
    </section>

    <section class="section faq-section" id="faq">
      <div class="section-heading">
        <p class="eyebrow">Good questions, clear answers</p>
        <h2>Frequently asked questions</h2>
      </div>
      <div class="faq-list">
        <details><summary>How do I get started with solar?</summary><p>Request a quote or schedule a consultation. We will learn about your property and explain suitable next steps.</p></details>
        <details><summary>What services do you offer?</summary><p>Our team provides consultation, system design, permitting support, installation, monitoring, maintenance, and repair.</p></details>
        <details><summary>Can I request maintenance for an existing system?</summary><p>Yes. Request a maintenance or repair service and include any details that can help our team prepare.</p></details>
      </div>
    </section>

    <section class="section news-section" id="news">
      <div class="section-heading">
        <p class="eyebrow">From the solar desk</p>
        <h2>News &amp; practical guides</h2>
      </div>
      <div class="news-grid">
        <article><p class="story-tag">Getting started</p><h3>What to consider before installing solar</h3><p>Roof condition, typical energy use, and future plans all help shape a good system design.</p></article>
        <article><p class="story-tag">System care</p><h3>Simple ways to support long-term performance</h3><p>Routine inspections and monitoring can help identify when your system needs attention.</p></article>
        <article><p class="story-tag">Energy storage</p><h3>Understanding solar batteries</h3><p>Learn how storage can support backup needs and make better use of the energy your panels produce.</p></article>
      </div>
    </section>

    <section class="login-section" id="account">
      <div>
        <p class="eyebrow">Your Sun Son Solar account</p>
        <h2>Stay connected to your solar service</h2>
        <p>Customers can manage appointments and support. Employees can access their work tools and announcements.</p>
      </div>
      <div class="cta-actions">
        <a class="button button-light" href="<?= site_url('login') ?>">Customer &amp; Employee Login</a>
        <a class="button button-outline-light" href="<?= site_url('register') ?>">Customer Registration</a>
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
        <a class="button button-outline-light" href="#quote">Request a Free Quote</a>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="footer-main" id="contact">
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
        <a href="#products">Products</a>
        <a href="#faq">FAQs</a>
        <a href="<?= site_url('register') ?>">Create an account</a>
      </div>
      <div class="footer-column">
        <h2>Get in touch</h2>
        <a href="mailto:katherine.sinagaraw@sunsonsolar.com">katherine.sinagaraw@sunsonsolar.com</a>
        <a href="tel:09291230983">0929 123 0983</a>
        <a href="<?= site_url('login') ?>">Customer login</a>
        <a href="<?= site_url('register') ?>">Customer registration</a>
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
      <p>Legal notice: Solar energy information is general guidance. System performance varies by property and usage.</p>
    </div>
  </footer>
</body>
</html>
