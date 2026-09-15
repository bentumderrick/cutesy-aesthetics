<?php
// gallery_handler.php
//
// Data layer for the customer Gallery. Builds $posts (array of rows)
// for gallery.php to render. No HTML here — this file only talks to
// the database.

require_once __DIR__ . '/db.php';

// Only these three categories belong in the Gallery.
// "Art Progress" and "Personal Space" stay Feed-only content —
// these slugs are the real ones offered on the upload form.
$gallery_categories = ['art', 'crochet', 'sketches'];

$posts = [];

$placeholders = implode(',', array_fill(0, count($gallery_categories), '?'));
$types = str_repeat('s', count($gallery_categories));

$sql = "SELECT
            posts.id,
            posts.caption,
            posts.media_url,
            posts.product_id,
            categories.slug AS category_slug,
            categories.name AS category_name,
            products.name AS product_name,
            products.price AS product_price,
            products.currency AS product_currency,
            products.status AS product_status,
            products.stock AS product_stock
        FROM posts
        INNER JOIN categories ON posts.category_id = categories.id
        LEFT JOIN products ON posts.product_id = products.id
        WHERE posts.status = 'published'
          AND posts.media_type = 'image'
          AND categories.slug IN ($placeholders)
        ORDER BY posts.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$gallery_categories);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}
mysqli_stmt_close($stmt);

/**
 * Work out a display title for a post.
 *
 * posts has no title column of its own — the "title" the creator
 * types on upload is only ever saved when the artwork becomes a
 * product (as products.name). For not-for-sale artwork nothing is
 * saved under the name "title" anywhere, so we fall back to the
 * caption, then to a generic label. See the write-up above before
 * changing this — the real fix is a posts.title column, not a
 * cleverer fallback here.
 */
function gallery_resolve_title(array $post): string
{
    if (!empty($post['product_name'])) {
        return $post['product_name'];
    }

    $caption = trim($post['caption'] ?? '');
    if ($caption !== '') {
        return mb_strlen($caption) > 60
            ? mb_substr($caption, 0, 57) . '…'
            : $caption;
    }

    return 'Untitled Artwork';
}

/**
 * Work out the availability label from the real post → product
 * relationship. 'sold' / 'out_of_stock' are supported because the
 * products.status column can hold them — nothing we've seen sets
 * them yet, but the Gallery should still render correctly if/when
 * something else does.
 */
function gallery_resolve_availability(array $post): string
{
    if (empty($post['product_id'])) {
        return 'Not for sale';
    }

    $status = $post['product_status'] ?? '';
    $stock  = (int) ($post['product_stock'] ?? 0);

    if ($status === 'sold') {
        return 'Sold';
    }

    if ($status === 'out_of_stock' || ($status === 'available' && $stock <= 0)) {
        return 'Out of stock';
    }

    if ($status === 'available' && $stock > 0) {
        return 'Available';
    }

    // Unrecognised/unset status — safer to under-promise than
    // show something purchasable that might not be.
    return 'Not for sale';
}

/**
 * CSS class suffix for the status badge, so gallery.php doesn't
 * need its own copy of this mapping.
 */
function gallery_status_class(string $availability): string
{
    switch ($availability) {
        case 'Available':
            return 'available';
        case 'Sold':
            return 'sold';
        case 'Out of stock':
            return 'out-of-stock';
        default:
            return 'not-for-sale';
    }
}
