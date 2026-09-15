/*
 * gallery.js
 *
 * Loaded ONCE from the main site shell (index.php), not inside the
 * AJAX fragment. customer.js does a plain `ajax.innerHTML = html`
 * swap, which means any <script> tag inside gallery.php would never
 * run — so everything here is delegated off #ajax, which persists
 * across every AJAX navigation.
 */
(function () {

    // Falls back to document if #ajax isn't present yet at
    // script-load time — either way, delegation still works.
    const root = document.getElementById("ajax") || document;

    /* --------------------------------------------------------------
     | Category filtering
     | -------------------------------------------------------------- */

    root.addEventListener("click", function (event) {
        const button = event.target.closest(".gallery-filter-btn");
        if (!button) return;

        const gallery = button.closest(".customer-gallery");
        if (!gallery) return;

        const category = button.dataset.category;

        gallery.querySelectorAll(".gallery-filter-btn").forEach(function (btn) {
            btn.classList.remove("active");
        });
        button.classList.add("active");

        gallery.querySelectorAll(".gallery-card").forEach(function (card) {
            const match = category === "all" || card.dataset.category === category;

            if (match) {
                card.style.display = "";
                requestAnimationFrame(function () {
                    card.classList.add("show");
                });
            } else {
                card.classList.remove("show");
                card.style.display = "none";
            }
        });
    });

    /* --------------------------------------------------------------
     | Artwork modal — populated straight from the card's data
     | attributes, no extra request needed
     | -------------------------------------------------------------- */

    function openModalFromCard(card) {
        const gallery = card.closest(".customer-gallery");
        if (!gallery) return;

        const modal = gallery.querySelector("#gallery-modal");
        if (!modal) return;

        const image = card.querySelector(".gallery-image");
        const modalImage = gallery.querySelector("#gallery-modal-image");
        const modalTitle = gallery.querySelector("#gallery-modal-title");
        const modalDesc = gallery.querySelector("#gallery-modal-desc");
        const modalStatus = gallery.querySelector("#gallery-modal-status");
        const modalPriceRow = gallery.querySelector("#gallery-modal-price-row");
        const modalPrice = gallery.querySelector("#gallery-modal-price");
        const modalStock = gallery.querySelector("#gallery-modal-stock");
        const modalBuyBtn = gallery.querySelector("#gallery-modal-buy-btn");

        const { title, caption, availability, statusClass, price, currency, stock, productId } = card.dataset;

        if (image && modalImage) {
            modalImage.src = image.src;
            modalImage.alt = title || image.alt;
        }

        if (modalTitle) modalTitle.textContent = title || "Untitled Artwork";
        if (modalDesc) {
            modalDesc.textContent = caption || "";
            modalDesc.style.display = caption ? "" : "none";
        }

        if (modalStatus) {
            modalStatus.textContent = availability || "";
            modalStatus.className = "gallery-status gallery-status--" + (statusClass || "not-for-sale");
        }

        const hasPrice = availability === "Available" && price;

        if (modalPriceRow) modalPriceRow.style.display = hasPrice ? "" : "none";
        if (modalPrice) modalPrice.textContent = hasPrice ? currency + " " + price : "";
        if (modalStock) {
            modalStock.textContent =
                hasPrice && stock ? (stock === "1" ? "Only 1 left" : stock + " in stock") : "";
        }

        if (modalBuyBtn) {
            modalBuyBtn.style.display = hasPrice && productId ? "" : "none";
            modalBuyBtn.dataset.productId = productId || "";
        }

        modal.classList.add("open");
        modal.setAttribute("aria-hidden", "false");
        document.body.classList.add("gallery-modal-open");
    }

    function closeModal(gallery) {
        const modal = gallery.querySelector("#gallery-modal");
        if (!modal) return;

        modal.classList.remove("open");
        modal.setAttribute("aria-hidden", "true");

        const modalImage = gallery.querySelector("#gallery-modal-image");
        if (modalImage) modalImage.src = "";

        document.body.classList.remove("gallery-modal-open");
    }

    root.addEventListener("click", function (event) {
        const viewBtn = event.target.closest(".gallery-view-btn");
        if (viewBtn) {
            const card = viewBtn.closest(".gallery-card");
            if (card) openModalFromCard(card);
            return;
        }

        const closeBtn = event.target.closest(".gallery-modal-close");
        const backdrop = event.target.closest(".gallery-modal-backdrop");
        if (closeBtn || backdrop) {
            const gallery = event.target.closest(".customer-gallery");
            if (gallery) closeModal(gallery);
            return;
        }

        const buyBtn = event.target.closest(".gallery-modal-buy-btn");
        if (buyBtn) {
            // customer.js's router only accepts a page name — it has
            // no mechanism yet to deep-link to a specific product, so
            // this just takes the person to the Shop tab.
            if (typeof window.loadPage === "function") {
                window.loadPage("shop");
                history.pushState({ page: "shop" }, "", "?page=shop");
            } else {
                window.location.href = "index.php?page=shop";
            }
            return;
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key !== "Escape") return;
        const openModal = document.querySelector(".gallery-modal.open");
        if (openModal) closeModal(openModal.closest(".customer-gallery"));
    });

    /* --------------------------------------------------------------
     | Share
     | -------------------------------------------------------------- */

    root.addEventListener("click", async function (event) {
        const button = event.target.closest(".gallery-share-btn");
        if (!button) return;

        const postId = button.dataset.postId;
        const shareUrl = window.location.origin + "/index.php?page=gallery&post=" + encodeURIComponent(postId);

        try {
            if (navigator.share) {
                await navigator.share({
                    title: "Cutesy Aesthetics",
                    text: "Check out this artwork!",
                    url: shareUrl,
                });
            } else if (navigator.clipboard) {
                await navigator.clipboard.writeText(shareUrl);
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(function () {
                    button.innerHTML = originalHTML;
                }, 1500);
            }
        } catch (error) {
            // User cancelled sharing — nothing to do.
        }
    });

    /* --------------------------------------------------------------
     | Image loading effect — skeleton shimmer until each image
     | actually finishes loading, then a soft fade-in
     | -------------------------------------------------------------- */

    function setupImageLoading(gallery) {
        gallery.querySelectorAll(".gallery-image-wrapper.is-loading").forEach(function (wrapper) {
            const img = wrapper.querySelector(".gallery-image");
            if (!img) return;

            function reveal() {
                wrapper.classList.remove("is-loading");
                img.classList.add("is-loaded");
            }

            if (img.complete && img.naturalWidth > 0) {
                reveal();
            } else {
                img.addEventListener("load", reveal, { once: true });
                img.addEventListener("error", reveal, { once: true });
            }
        });
    }

    /* --------------------------------------------------------------
     | Run setup whenever the Gallery gets inserted into the DOM —
     | works regardless of how the AJAX router injects the markup.
     | -------------------------------------------------------------- */

    function initGallery(gallery) {
        setupImageLoading(gallery);
    }

    // Catch a Gallery that's already on the page when this script runs.
    document.querySelectorAll(".customer-gallery").forEach(initGallery);

    // Catch every future AJAX-loaded Gallery.
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            mutation.addedNodes.forEach(function (node) {
                if (node.nodeType !== 1) return;

                if (node.classList && node.classList.contains("customer-gallery")) {
                    initGallery(node);
                } else if (node.querySelectorAll) {
                    node.querySelectorAll(".customer-gallery").forEach(initGallery);
                }
            });
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });

})();
