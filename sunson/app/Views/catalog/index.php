<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Browse Sun Son Solar panels, inverters, batteries, and installation accessories.">
  <title>Solar Products | Sun Son Solar</title>
  <link rel="stylesheet" href="<?= base_url('css/home.css') ?>">
  <link rel="stylesheet" href="<?= base_url('css/catalog.css') ?>">
</head>
<body>
  <header class="site-header catalog-header">
    <a class="brand" href="<?= site_url('/') ?>" aria-label="Sun Son Solar home">
      <span class="brand-mark" aria-hidden="true">S</span>
      <span>Sun Son <strong>Solar</strong></span>
    </a>
    <nav class="main-nav" aria-label="Main navigation">
      <a href="<?= site_url('/') ?>#services">Services</a>
      <a href="<?= site_url('products') ?>" aria-current="page">Products</a>
      <a href="<?= site_url('/') ?>#about">About</a>
      <a href="<?= site_url('/') ?>#contact">Contact</a>
      <a href="<?= site_url('login') ?>">Login</a>
      <a href="<?= site_url('register') ?>">Register</a>
    </nav>
  </header>

  <main>
    <section class="catalog-hero">
      <div>
        <p class="eyebrow">Thoughtful solar equipment</p>
        <h1>Products for a brighter home</h1>
        <p>Explore solar panels, inverters, battery storage, and the components that bring a complete system together.</p>
      </div>
    </section>

    <section class="catalog-section" aria-labelledby="catalog-title">
      <div class="catalog-heading">
        <div>
          <p class="eyebrow">Sun Son Solar collection</p>
          <h2 id="catalog-title"><?= $selectedCategory !== '' ? esc($selectedCategory) : 'Browse all products' ?></h2>
        </div>
        <p><?= esc((string) count($products)) ?> <?= count($products) === 1 ? 'product' : 'products' ?></p>
      </div>

      <?php if ($categories !== []): ?>
        <nav class="catalog-filters" aria-label="Filter products by category">
          <a class="<?= $selectedCategory === '' ? 'is-active' : '' ?>" href="<?= site_url('products') ?>" <?= $selectedCategory === '' ? 'aria-current="page"' : '' ?>>All products</a>
          <?php foreach ($categories as $category): ?>
            <a class="<?= $selectedCategory === $category ? 'is-active' : '' ?>" href="<?= site_url('products') . '?category=' . rawurlencode($category) ?>" <?= $selectedCategory === $category ? 'aria-current="page"' : '' ?>><?= esc($category) ?></a>
          <?php endforeach; ?>
        </nav>
      <?php endif; ?>

      <?php if ($products === []): ?>
        <div class="catalog-empty">
          <h3>No products to show</h3>
          <p><?= $selectedCategory !== '' ? 'There are no available products in this category right now.' : 'Our product collection will be available soon.' ?></p>
          <?php if ($selectedCategory !== ''): ?><a class="button button-primary" href="<?= site_url('products') ?>">Browse all products</a><?php endif; ?>
        </div>
      <?php else: ?>
        <div class="catalog-grid">
          <?php foreach ($products as $product): ?>
            <article class="catalog-card">
              <div class="catalog-image-wrap">
                <img
                  src="<?= esc($product['image_url'], 'attr') ?>"
                  alt="<?= esc($product['image_alt'], 'attr') ?>"
                  loading="lazy"
                  decoding="async">
                <span class="catalog-category"><?= esc($product['category']) ?></span>
              </div>
              <div class="catalog-card-content">
                <p class="catalog-sku">SKU <?= esc($product['sku']) ?></p>
                <h3><?= esc($product['name']) ?></h3>
                <p class="catalog-description"><?= esc($product['description']) ?></p>
                <div class="catalog-card-footer">
                  <?php if ($product['price'] !== null): ?>
                    <strong><?= esc(number_format((float) $product['price'], 2)) ?></strong>
                  <?php else: ?>
                    <strong class="catalog-price-contact">Request pricing</strong>
                  <?php endif; ?>
                  <a href="<?= site_url('/') ?>#quote" aria-label="<?= esc('Request a quote for ' . $product['name'], 'attr') ?>">Request a quote <span aria-hidden="true">→</span></a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <section class="catalog-cta">
      <div>
        <p class="eyebrow">Not sure what fits your home?</p>
        <h2>Get a recommendation from our solar team.</h2>
      </div>
      <a class="button button-light" href="<?= site_url('/') ?>#quote">Request a free quote</a>
    </section>
  </main>

  <footer class="catalog-footer">
    <a href="<?= site_url('/') ?>">Sun Son Solar</a>
    <span>Equipment recommendations are tailored to your property and energy goals.</span>
  </footer>
</body>
</html>
