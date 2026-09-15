<?php
// gallery.php
//
// AJAX content only — no <html>, <head>, navbar, or footer here.
// This gets loaded into the customer-side #content container.

require_once __DIR__ . '/gallery_handler.php';
?>

<section class="customer-gallery">

    <!-- Gallery Header -->
    <div class="gallery-hero">

        <div class="gallery-hero-text">
            <span class="gallery-kicker">
                <i class="fa-solid fa-sparkles"></i>
                Cutesy Aesthetics
            </span>

            <h1>My Art Gallery 🎨</h1>

            <p>
                A collection of my creations, drawings and handmade pieces
                made with love and creativity.
            </p>
        </div>

        <div class="gallery-hero-icon">
            <i class="fa-solid fa-palette"></i>
        </div>

    </div>


    <!-- Category Filter -->
    <div class="gallery-filter-wrapper">

        <div class="gallery-filter">

            <button class="gallery-filter-btn active" type="button" data-category="all">
                <i class="fa-solid fa-border-all"></i>
                All
            </button>

            <button class="gallery-filter-btn" type="button" data-category="art">
                <i class="fa-solid fa-palette"></i>
                Art
            </button>

            <button class="gallery-filter-btn" type="button" data-category="crochet">
                <i class="fa-solid fa-heart"></i>
                Crochet
            </button>

            <button class="gallery-filter-btn" type="button" data-category="sketches">
                <i class="fa-solid fa-pencil"></i>
                Sketches
            </button>

        </div>

    </div>


    <!-- Gallery -->
    <div class="gallery-grid">

        <?php if (!empty($posts)): ?>

            <?php foreach ($posts as $post): ?>

                <?php
                $post_id = (int) $post['id'];

                $image = htmlspecialchars($post['media_url'], ENT_QUOTES, 'UTF-8');

                $title = htmlspecialchars(
                    gallery_resolve_title($post),
                    ENT_QUOTES,
                    'UTF-8'
                );

                $caption = htmlspecialchars($post['caption'] ?? '', ENT_QUOTES, 'UTF-8');

                $category_slug = htmlspecialchars($post['category_slug'] ?? '', ENT_QUOTES, 'UTF-8');
                $category_label = htmlspecialchars(
                    $post['category_name'] ?? 'Art',
                    ENT_QUOTES,
                    'UTF-8'
                );

                $availability = gallery_resolve_availability($post);
                $status_class = gallery_status_class($availability);

                $has_product = !empty($post['product_id']);
                $product_price = $has_product ? (float) $post['product_price'] : null;
                $product_currency = htmlspecialchars(
                    $post['product_currency'] ?? 'ZMW',
                    ENT_QUOTES,
                    'UTF-8'
                );
                $product_stock = $has_product ? (int) $post['product_stock'] : null;
                ?>

                <article
                    class="gallery-card"
                    data-category="<?= $category_slug ?>"
                    data-post-id="<?= $post_id ?>"
                    data-title="<?= $title ?>"
                    data-caption="<?= $caption ?>"
                    data-availability="<?= htmlspecialchars($availability, ENT_QUOTES, 'UTF-8') ?>"
                    data-status-class="<?= $status_class ?>"
                    data-price="<?= $product_price !== null ? number_format($product_price, 2, '.', '') : '' ?>"
                    data-currency="<?= $product_currency ?>"
                    data-stock="<?= $product_stock !== null ? $product_stock : '' ?>"
                    data-product-id="<?= $has_product ? (int) $post['product_id'] : '' ?>"
                >

                    <!-- Artwork -->
                    <div class="gallery-image-wrapper is-loading">

                        <img
                            src="<?= $image ?>"
                            alt="<?= $title ?>"
                            class="gallery-image"
                            loading="lazy"
                        >

                        <div class="gallery-image-overlay">

                            <button
                                type="button"
                                class="gallery-view-btn"
                                data-post-id="<?= $post_id ?>"
                                aria-label="View artwork"
                            >
                                <i class="fa-solid fa-expand"></i>
                            </button>

                        </div>

                    </div>


                    <!-- Information -->
                    <div class="gallery-card-info">

                        <div class="gallery-card-top">

                            <span class="gallery-category"><?= $category_label ?></span>

                            <button
                                type="button"
                                class="gallery-share-btn"
                                data-post-id="<?= $post_id ?>"
                                aria-label="Share artwork"
                            >
                                <i class="fa-solid fa-share-nodes"></i>
                            </button>

                        </div>

                        <h3 class="gallery-title"><?= $title ?></h3>

                        <span class="gallery-status gallery-status--<?= $status_class ?>">
                            <?= htmlspecialchars($availability, ENT_QUOTES, 'UTF-8') ?>
                        </span>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php else: ?>

            <!-- Empty Gallery -->
            <div class="gallery-empty">
                <div class="gallery-empty-icon">
                    <i class="fa-solid fa-palette"></i>
                </div>
                <h2>No artwork here yet ✨</h2>
                <p>New creations will appear here soon.</p>
            </div>

        <?php endif; ?>

    </div>


    <!-- Artwork Modal -->
    <div class="gallery-modal" id="gallery-modal" aria-hidden="true">

        <div class="gallery-modal-backdrop"></div>

        <div class="gallery-modal-content">

            <button type="button" class="gallery-modal-close" aria-label="Close artwork">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="gallery-modal-image-wrap">
                <img src="" alt="" id="gallery-modal-image">
            </div>

            <div class="gallery-modal-info">

                <span class="gallery-status" id="gallery-modal-status"></span>

                <h2 id="gallery-modal-title"></h2>

                <p class="gallery-modal-desc" id="gallery-modal-desc"></p>

                <div class="gallery-modal-price-row" id="gallery-modal-price-row">
                    <span class="gallery-modal-price" id="gallery-modal-price"></span>
                    <span class="gallery-modal-stock" id="gallery-modal-stock"></span>
                </div>

                <button type="button" class="gallery-modal-buy-btn" id="gallery-modal-buy-btn">
                    View in Shop
                </button>

            </div>

        </div>

    </div>

</section>
