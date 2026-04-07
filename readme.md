# CC UTF8 Converter

CC UTF8 Converter is a lightweight WordPress plugin that converts uploaded plain text files such as Big5, SJIS, and GB2312 into UTF-8 encoding and downloads the result automatically. This plugin is especially useful for researchers, archivists, digital humanities users, and anyone working with legacy text files that may contain non-UTF-8 encodings. The plugin automatically detects common encodings including UTF-8, BIG5, SJIS, and GB2312, converts them safely into UTF-8, and replaces unsupported characters with a visible placeholder (■) for easier proofreading. It supports both WordPress admin tools and frontend shortcode usage, allowing users to upload files directly from a page or post using `[cc_utf8_converter]`. Temporary files are automatically deleted after download to ensure security and reduce server storage usage. Supported file formats include .txt, .csv, .html, .htm, .xhtml, .xml, and .md. Installation is simple: upload the plugin to `/wp-content/plugins/`, activate it in WordPress admin, then access it via `Tools → UTF-8 Converter` or insert the shortcode into any page or post. The plugin is translation-ready using the `cc-utf8-converter` text domain and follows WordPress coding standards, making it suitable for production environments, research workflows, and digital humanities text normalization pipelines.

## Features

- Convert text files to UTF-8
- Auto detect encoding (UTF-8 / BIG5 / SJIS / GB2312)
- Replace invalid characters with ■
- Drag and drop upload interface
- Automatic download after conversion
- Temporary files auto deleted
- Admin page and shortcode support
- Translation ready (i18n)

## Shortcode

```
[cc_utf8_converter]
```

## Supported File Types

- .txt
- .csv
- .html
- .htm
- .xhtml
- .xml
- .md

## Installation

1. Upload plugin to `/wp-content/plugins/`
2. Activate plugin
3. Go to `Tools → UTF-8 Converter`
4. Or use shortcode `[cc_utf8_converter]`

## Changelog

### 1.0.0

- Initial release