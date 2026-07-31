<?php

declare(strict_types=1);

require_once __DIR__ . '/store.php';
require_once __DIR__ . '/../templates/blog-page.php';

/**
 * Latest published posts for homepage surfaces.
 *
 * @return list<array>
 */
function blog_homepage_latest_posts(string $root, string $locale, int $limit = 6, ?array $config = null): array
{
    $config ??= blog_config();
    $posts = array_values(array_filter(
        blog_load_all_posts($root, $config),
        static fn (array $p): bool => ($p['status'] ?? '') === 'published' && blog_post_has_locale($p, $locale)
    ));

    return array_slice($posts, 0, max(1, $limit));
}

/**
 * HTML for the homepage blog section (EN/RU).
 */
function blog_homepage_render_section(string $root, string $locale, ?array $config = null, int $limit = 6): string
{
    $config ??= blog_config();
    $isRu = $locale === 'ru';
    $fromPath = $isRu ? 'ru/index.html' : 'index.html';
    $indexPath = blog_index_path($locale, $config);
    $indexHref = directory_relative_path($fromPath, $indexPath);
    $title = $isRu ? 'Блог' : 'Blog';
    $more = $isRu ? 'Все записи' : 'All posts';
    $empty = $isRu ? 'Пока нет опубликованных записей.' : 'No published posts yet.';
    $posts = blog_homepage_latest_posts($root, $locale, $limit, $config);

    $items = '';
    foreach ($posts as $post) {
        $pTitle = trim((string) ($post['locales'][$locale]['title'] ?? $post['slug']));
        $pDesc = trim((string) ($post['locales'][$locale]['description'] ?? ''));
        $href = directory_relative_path($fromPath, blog_output_path_for_locale($post, $locale));
        $date = blog_human_date((string) ($post['published_at'] ?? ''), $locale);
        $items .= '<li class="homepage-blog-item">'
            . '<a class="homepage-blog-link" href="' . directory_escape($href) . '"><strong>' . directory_escape($pTitle) . '</strong></a>'
            . '<span class="homepage-blog-date">' . directory_escape($date) . '</span>'
            . ($pDesc !== '' ? '<p class="homepage-blog-summary">' . directory_escape($pDesc) . '</p>' : '')
            . "</li>\n";
    }
    if ($items === '') {
        $items = '<li class="homepage-blog-item"><p class="homepage-blog-summary">' . directory_escape($empty) . '</p></li>';
    }

    return '<section class="directory-section homepage-blog" id="blog" data-blog-homepage-section="1">'
        . '<div class="directory-section-heading">'
        . '<h2>' . directory_section_heading($title, 'letter-check') . '</h2>'
        . '<a class="directory-section-link" href="' . directory_escape($indexHref) . '">' . directory_escape($more) . '</a>'
        . '</div>'
        . '<ul class="homepage-blog-list">' . "\n" . $items . '</ul>'
        . '</section>';
}

function blog_homepage_section_styles(): string
{
    return <<<'CSS'
          .homepage-blog-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 12px; }
          .homepage-blog-item { padding: 14px 16px; border: 1px solid #3a2e55; border-radius: 10px; background: #181222; }
          .homepage-blog-link { color: #f6f2ff; text-decoration: none; }
          .homepage-blog-link:hover strong, .homepage-blog-link:focus strong { color: #bb86fc; }
          .homepage-blog-date { display: block; margin-top: 4px; color: #c9c3d8; font-size: 0.88rem; }
          .homepage-blog-summary { margin: 8px 0 0; color: #e8e1f5; line-height: 1.5; }
CSS;
}

/**
 * Ensure homepage CSS includes blog list styles.
 */
function blog_homepage_ensure_styles(string $html): string
{
    if (str_contains($html, '.homepage-blog-list')) {
        return $html;
    }

    $style = blog_homepage_section_styles();
    // Prefer insert before closing of homepage style block markers we know exist
    $needles = [
        '          .homepage-directory { max-width: 1080px; margin: 0 auto; padding: 28px 20px 44px; }' . "\n",
        '          .homepage-intro p:last-child { margin-bottom: 0; }' . "\n",
    ];
    foreach ($needles as $needle) {
        if (str_contains($html, $needle)) {
            return str_replace($needle, $needle . $style . "\n", $html);
        }
    }

    // Fallback: inject before </style> nearest to homepage-directory if present
    if (preg_match('~(\.homepage-directory[^{]*\{[^}]*\})~', $html) === 1) {
        return preg_replace(
            '~(</style>)~',
            "<style>\n" . $style . "\n</style>\n$1",
            $html,
            1
        ) ?? $html;
    }

    return $html;
}

/**
 * Replace homepage sidebar menu with the shared site nav (Blog, Privacy News, …).
 */
function blog_homepage_ensure_sidebar_link(string $html, string $locale): string
{
    $isRu = $locale === 'ru';
    $fromPath = $isRu ? 'ru/index.html' : 'index.html';
    $items = '';
    foreach (directory_sidebar_nav_items($isRu) as [$label, $target]) {
        $href = directory_relative_path($fromPath, $target);
        $items .= '<li class="menu-item"><a class="nav-link" href="' . directory_escape($href) . '">' . directory_escape($label) . '</a></li>' . "\n";
    }

    $updated = preg_replace(
        '~(<ul class="nav-menu">\s*).*?(</ul>)~su',
        '$1' . $items . '$2',
        $html,
        1,
        $count
    );

    return ($count === 1 && is_string($updated)) ? $updated : $html;
}

/**
 * Inject Blog into the directory meta-nav on the homepage (first link).
 */
function blog_homepage_ensure_meta_nav_link(string $html, string $locale): string
{
    $isRu = $locale === 'ru';
    $label = $isRu ? 'Блог' : 'Blog';
    $href = 'blog/';
    $link = '<a class="directory-meta-link" href="' . $href . '">' . $label . '</a>';

    // Drop previous blog meta links
    $html = preg_replace(
        '~<a class="directory-meta-link(?: is-active)?" href="(?:\.\./)?blog/?(?:index\.html)?">(?:Blog|Блог)</a>\s*~u',
        '',
        $html
    ) ?? $html;

    $updated = preg_replace(
        '~(<nav class="directory-meta-nav"[^>]*>\s*)~',
        '$1' . $link . "\n",
        $html,
        1,
        $count
    );

    return ($count === 1 && is_string($updated)) ? $updated : $html;
}

/**
 * Replace or insert the homepage blog section inside the main article content.
 */
function blog_homepage_inject_section(string $html, string $sectionHtml): string
{
    // Replace existing marked section
    if (str_contains($html, 'data-blog-homepage-section="1"')) {
        $updated = preg_replace(
            '~<section\b[^>]*data-blog-homepage-section="1"[^>]*>.*?</section>~su',
            $sectionHtml,
            $html,
            1,
            $count
        );
        if (is_string($updated) && $count === 1) {
            return $updated;
        }
    }

    // Insert after homepage-intro if present
    if (str_contains($html, 'class="homepage-intro"')) {
        $updated = preg_replace(
            '~(</section>\s*)(?=<section class="directory-section"|<section class="directory-section homepage-blog"|$)~su',
            "$1\n" . $sectionHtml . "\n",
            $html,
            1,
            $count
        );
        // More reliable: after first </section> following homepage-intro
        $updated = preg_replace(
            '~(<section class="homepage-intro">.*?</section>)~su',
            '$1' . "\n" . $sectionHtml,
            $html,
            1,
            $count
        );
        if (is_string($updated) && $count === 1) {
            return $updated;
        }
    }

    // Insert after opening of homepage article content
    $updated = preg_replace(
        '~(<article class="page-content directory-detail homepage-directory">\s*)~',
        '$1' . $sectionHtml . "\n",
        $html,
        1,
        $count
    );

    return ($count === 1 && is_string($updated)) ? $updated : $html;
}

/**
 * Strip the homepage blog section and the styles that only served it.
 *
 * The homepage no longer carries a latest-posts block. The blog itself is
 * unaffected -- it keeps its own index, its sidebar entry, and its meta-nav
 * link; only the homepage section is dropped.
 */
function blog_homepage_remove_section(string $html): string
{
    $updated = preg_replace(
        '~<section\b[^>]*data-blog-homepage-section="1"[^>]*>.*?</section>\s*~su',
        '',
        $html
    );
    if (!is_string($updated)) {
        return $html;
    }

    // The .homepage-blog-* rules have no other consumer once the section goes.
    // Strip them rule by rule: they may have been merged into the shared
    // homepage <style> block, so deleting the enclosing block would take the
    // rest of the homepage styling with it.
    $stripped = preg_replace(
        '~^[ \t]*\.homepage-blog-[a-z-]+(?:[^{\n]*)\{[^}\n]*\}[ \t]*\r?\n~mu',
        '',
        $updated
    );

    return is_string($stripped) ? $stripped : $updated;
}

/**
 * Wire blog into index.html / ru/index.html: sidebar and meta nav only.
 *
 * @return list<string> paths written
 */
function blog_update_homepages(string $root, ?array $config = null, int $limit = 6): array
{
    $config ??= blog_config();
    $written = [];

    foreach (['en' => 'index.html', 'ru' => 'ru/index.html'] as $locale => $rel) {
        $path = $root . '/' . $rel;
        if (!is_file($path)) {
            continue;
        }
        $html = file_get_contents($path);
        if ($html === false) {
            throw new RuntimeException("Unable to read {$path}");
        }

        $html = blog_homepage_ensure_sidebar_link($html, $locale);
        $html = blog_homepage_ensure_meta_nav_link($html, $locale);
        // Removal rather than injection: rerunning the build must not bring
        // the section back.
        $html = blog_homepage_remove_section($html);

        if (file_put_contents($path, $html) === false) {
            throw new RuntimeException("Unable to write {$path}");
        }
        $written[] = $path;
    }

    return $written;
}
