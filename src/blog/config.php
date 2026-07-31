<?php

declare(strict_types=1);

/**
 * Blog configuration.
 *
 * Public posts live under the main site: https://bitmixlist.org/blog/ (and /ru/blog/).
 * blog.bitmixlist.org is for the admin editor only — not public content.
 *
 * BITMIXLIST_SITE_BASE_URL         Public site origin (canonicals + public blog paths). Default https://bitmixlist.org
 * BITMIXLIST_BLOG_ADMIN_BASE_URL   Admin editor origin only. Default https://blog.bitmixlist.org
 * BITMIXLIST_BLOG_ASSET_MODE       relative|absolute  How asset URLs are written in generated HTML
 *
 * BITMIXLIST_BLOG_BASE_URL is accepted as a legacy alias for SITE_BASE_URL when set (public content only).
 */
function blog_config(array $overrides = []): array
{
    $siteBase = rtrim((string) (getenv('BITMIXLIST_SITE_BASE_URL') ?: 'https://bitmixlist.org'), '/');
    // Legacy env: if someone still sets BLOG_BASE_URL, treat it as the public site origin (not admin).
    $legacyPublic = getenv('BITMIXLIST_BLOG_BASE_URL');
    if (is_string($legacyPublic) && $legacyPublic !== '') {
        $siteBase = rtrim($legacyPublic, '/');
    }

    $defaults = [
        // Public site: blog content is site_base_url + /blog/... (and legacy root article paths).
        'site_base_url' => $siteBase,
        // Alias used by older call sites; always the public site, never the admin host.
        'blog_base_url' => $siteBase,
        // Admin editor host only (blog.bitmixlist.org). Does not host public post HTML.
        'admin_base_url' => rtrim((string) (getenv('BITMIXLIST_BLOG_ADMIN_BASE_URL') ?: 'https://blog.bitmixlist.org'), '/'),
        // relative: same-tree local build; absolute: use site_base_url for wp-content
        'asset_mode' => (string) (getenv('BITMIXLIST_BLOG_ASSET_MODE') ?: 'relative'),
        'posts_dir' => 'src/blog/posts',
        'blog_index_path' => [
            'en' => 'blog/index.html',
            'ru' => 'ru/blog/index.html',
        ],
        'default_author' => 'NotATether',
        'default_author_bio' => [
            'en' => 'Bitcoin privacy researcher and maintainer of BitMixList. Focused on mixer history, enforcement timelines, and practical privacy workflows for users operating in high-friction jurisdictions.',
            'ru' => 'Исследователь приватности Bitcoin и сопровождающий BitMixList. Фокус на истории миксеров, сроках правоприменения и практических рабочих процессах приватности.',
        ],
    ];

    $config = array_merge($defaults, $overrides);
    // Keep blog_base_url aligned with site_base_url unless an override explicitly set both differently.
    if (!array_key_exists('blog_base_url', $overrides) && array_key_exists('site_base_url', $overrides)) {
        $config['blog_base_url'] = $config['site_base_url'];
    }
    if (!array_key_exists('site_base_url', $overrides) && array_key_exists('blog_base_url', $overrides)) {
        $config['site_base_url'] = $config['blog_base_url'];
    }

    return $config;
}

/**
 * Utility root HTML basenames that must never be imported as blog posts.
 *
 * @return list<string>
 */
function blog_utility_slugs(): array
{
    return [
        '404',
        'index',
        'faq',
        'terms-and-conditions',
        'scam-lookup',
        'letter-verify',
        'changelog',
        // Interactive / non-editorial tool pages (not blog posts)
        'aml-check',
        'privacy-news',
    ];
}

/**
 * Legacy hand-written site articles live at the site root (e.g. crime.html).
 * They predate the blog pipeline and often include custom HTML/CSS/JS
 * (timelines, matrices, tables). Root HTML is the source of truth.
 *
 * Only paths under blog/ (and ru/blog/) may be written by the blog builder.
 */
function blog_is_blog_managed_output_path(string $relativePath): bool
{
    $path = ltrim($relativePath, '/');
    if ($path === '') {
        return false;
    }

    return str_starts_with($path, 'blog/') || str_starts_with($path, 'ru/blog/');
}

/**
 * True when a post's output path is a legacy hardwired site page
 * (anything outside blog/ / ru/blog/).
 */
function blog_is_hardwired_output_path(string $relativePath): bool
{
    return !blog_is_blog_managed_output_path($relativePath);
}

/**
 * True for legacy root article slugs during import. Root HTML files are never
 * blog-managed; new posts must live under blog/.
 */
function blog_is_hardwired_root_slug(string $slug): bool
{
    $normalized = strtolower(trim($slug));
    $normalized = preg_replace('/[^a-z0-9\-]+/', '-', $normalized) ?? $normalized;
    $normalized = trim($normalized, '-');
    if ($normalized === '' || in_array($normalized, blog_utility_slugs(), true)) {
        return false;
    }

    // Refuse re-importing any root-level legacy article slug into the blog store.
    return true;
}

/**
 * @return list<string>
 */
function blog_reserved_root_prefixes(): array
{
    return [
        'mixers',
        'neverkyc-exchanges',
        'instant-exchanges',
        'p2p-markets',
        'coordinators',
        'privacy-tools',
        'wp-content',
        'src',
        'tools',
        'admin',
        'blog',
        'ru',
        'privacy-intel',
        'site-status-checker',
        'media-kit',
        'tests',
    ];
}
