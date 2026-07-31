<?php

declare(strict_types=1);

/**
 * Small dependency-free Markdown subset renderer for blog posts.
 * Supports: headings, paragraphs, fenced code, inline code, bold, italic, links, lists, hr, blockquotes.
 * Already-HTML bodies should use body_format=html and skip this.
 */
function blog_markdown_to_html(string $markdown): string
{
    $markdown = str_replace(["\r\n", "\r"], "\n", $markdown);
    $markdown = trim($markdown);
    if ($markdown === '') {
        return '';
    }

    $lines = explode("\n", $markdown);
    $html = [];
    $i = 0;
    $n = count($lines);
    $inList = false;
    $listTag = '';

    $closeList = static function () use (&$html, &$inList, &$listTag): void {
        if ($inList) {
            $html[] = "</{$listTag}>";
            $inList = false;
            $listTag = '';
        }
    };

    while ($i < $n) {
        $line = $lines[$i];

        if (preg_match('/^```/', $line) === 1) {
            $closeList();
            $i++;
            $code = [];
            while ($i < $n && preg_match('/^```/', $lines[$i]) !== 1) {
                $code[] = $lines[$i];
                $i++;
            }
            if ($i < $n) {
                $i++;
            }
            $html[] = '<pre><code>' . htmlspecialchars(implode("\n", $code), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</code></pre>';
            continue;
        }

        if (preg_match('/^(\#{1,6})\s+(.+)$/', $line, $hm) === 1) {
            $closeList();
            $level = strlen($hm[1]);
            $text = blog_markdown_inline($hm[2]);
            $id = blog_heading_id($hm[2]);
            $html[] = "<h{$level} class=\"wp-block-heading\" id=\"{$id}\">{$text}</h{$level}>";
            $i++;
            continue;
        }

        if (preg_match('/^(-{3,}|\*{3,}|_{3,})\s*$/', $line) === 1) {
            $closeList();
            $html[] = '<hr/>';
            $i++;
            continue;
        }

        if (preg_match('/^>\s?(.*)$/', $line, $qm) === 1) {
            $closeList();
            $quote = [$qm[1]];
            $i++;
            while ($i < $n && preg_match('/^>\s?(.*)$/', $lines[$i], $qm2) === 1) {
                $quote[] = $qm2[1];
                $i++;
            }
            $html[] = '<blockquote><p>' . blog_markdown_inline(implode(' ', $quote)) . '</p></blockquote>';
            continue;
        }

        if (preg_match('/^[-*+]\s+(.+)$/', $line, $lm) === 1) {
            if (!$inList || $listTag !== 'ul') {
                $closeList();
                $html[] = '<ul>';
                $inList = true;
                $listTag = 'ul';
            }
            $html[] = '<li>' . blog_markdown_inline($lm[1]) . '</li>';
            $i++;
            continue;
        }

        if (preg_match('/^\d+\.\s+(.+)$/', $line, $lm) === 1) {
            if (!$inList || $listTag !== 'ol') {
                $closeList();
                $html[] = '<ol>';
                $inList = true;
                $listTag = 'ol';
            }
            $html[] = '<li>' . blog_markdown_inline($lm[1]) . '</li>';
            $i++;
            continue;
        }

        if (trim($line) === '') {
            $closeList();
            $i++;
            continue;
        }

        $closeList();
        $para = [$line];
        $i++;
        while ($i < $n && trim($lines[$i]) !== '' && preg_match('/^(#{1,6}\s|```|[-*+]\s|\d+\.\s|>|(-{3,}|\*{3,}|_{3,})\s*$)/', $lines[$i]) !== 1) {
            $para[] = $lines[$i];
            $i++;
        }
        $html[] = '<p>' . blog_markdown_inline(implode(' ', $para)) . '</p>';
    }

    $closeList();

    return implode("\n", $html);
}

function blog_markdown_inline(string $text): string
{
    $text = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    // code
    $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text) ?? $text;
    // links
    $text = preg_replace(
        '/\[([^\]]+)\]\((https?:\/\/[^)\s]+|\/[^)\s]*)\)/',
        '<a href="$2" target="_blank" rel="noopener">$1</a>',
        $text
    ) ?? $text;
    // bold — require non-greedy pairs; underscore form must not be mid-identifier
    $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text) ?? $text;
    $text = preg_replace('/(?<![\w])__(?!_)(.+?)(?<!_)__(?![\w])/s', '<strong>$1</strong>', $text) ?? $text;
    // italic — asterisk form first; single underscores only at word boundaries (avoid SNAKE_CASE)
    $text = preg_replace('/(?<!\*)\*(?!\*)(.+?)(?<!\*)\*(?!\*)/s', '<em>$1</em>', $text) ?? $text;
    $text = preg_replace('/(?<![\w])_(?!_)([^_\n]+?)(?<!_)_(?![\w])/s', '<em>$1</em>', $text) ?? $text;

    return $text;
}

function blog_heading_id(string $text): string
{
    $text = strtolower(trim(strip_tags($text)));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';

    return trim($text, '-') ?: 'section';
}

/**
 * Render post body according to body_format.
 */
function blog_render_body_html(array $post, string $locale): string
{
    $body = (string) ($post['locales'][$locale]['body'] ?? '');
    $format = (string) ($post['body_format'] ?? 'markdown');
    if (trim($body) === '') {
        return '';
    }
    if ($format === 'html') {
        return blog_sanitize_html_fragment($body);
    }

    return blog_markdown_to_html($body);
}

/**
 * Allow a conservative HTML subset for migrated legacy bodies.
 */
function blog_sanitize_html_fragment(string $html): string
{
    $html = trim($html);
    if ($html === '') {
        return '';
    }

    // Strip scripts/styles and on* handlers
    $html = preg_replace('#<\s*(script|style|iframe|object|embed|form|input|button|textarea|select)[^>]*>.*?<\s*/\s*\1\s*>#is', '', $html) ?? $html;
    $html = preg_replace('#<\s*(script|style|iframe|object|embed|form|input|button|textarea|select)[^>]*/?\s*>#is', '', $html) ?? $html;
    $html = preg_replace('/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? $html;
    $html = preg_replace('/\s(href|src)\s*=\s*([\'"])\s*javascript:[^\'"]*\2/i', ' $1="#"', $html) ?? $html;

    return $html;
}
