<?php

declare(strict_types=1);

/**
 * Blog configuration. Override via environment variables for local / subdomain testing.
 *
 * BITMIXLIST_BLOG_BASE_URL  Public blog origin (canonicals), e.g. https://blog.bitmixlist.local
 * BITMIXLIST_SITE_BASE_URL  Main site origin for legacy paths + assets, e.g. https://bitmixlist.org
 * BITMIXLIST_BLOG_ASSET_MODE relative|absolute  How asset URLs are written in generated HTML
 */
function blog_config(array $overrides = []): array
{
    $defaults = [
        'blog_base_url' => rtrim((string) (getenv('BITMIXLIST_BLOG_BASE_URL') ?: 'https://blog.bitmixlist.local'), '/'),
        'site_base_url' => rtrim((string) (getenv('BITMIXLIST_SITE_BASE_URL') ?: 'https://bitmixlist.org'), '/'),
        // relative: same-tree local build; absolute: use site_base_url for wp-content (subdomain hosts)
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

    return array_merge($defaults, $overrides);
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
    ];
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
