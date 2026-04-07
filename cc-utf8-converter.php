<?php
/**
 * Plugin Name: CC UTF8 Converter
 * Plugin URI: https://cclin.cc
 * Description: Convert uploaded plain text files (BIG5, SJIS, GB2312, etc.) to UTF-8 and download them automatically. Supports both admin and frontend, and temporary files are deleted after download.
 * Version: 1.0.0
 * Author: Chance Lin
 * Author URI: https://cclin.cc
 * Text Domain: cc-utf8-converter
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

class CC_UTF8_Converter
{
    private static $instance = null;

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('init', [$this, 'load_textdomain']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_shortcode('cc_utf8_converter', [$this, 'render_form']);
        add_action('init', [$this, 'handle_upload']);
    }

    public function load_textdomain()
    {
        load_plugin_textdomain(
            'cc-utf8-converter',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }

    public function add_admin_menu()
    {
        add_management_page(
            __('UTF-8 Converter', 'cc-utf8-converter'),
            __('UTF-8 Converter', 'cc-utf8-converter'),
            'manage_options',
            'cc-utf8-converter',
            [$this, 'render_admin_page']
        );
    }

    public function render_admin_page()
    {
        echo $this->render_form();
    }

    public function render_form()
    {
        ob_start();
        ?>
        <div class="cc-utf8-converter-wrap" style="max-width:780px;text-align:center;">
            <h2><?php echo esc_html__('File to UTF-8', 'cc-utf8-converter'); ?></h2>
            <p style="margin-top:0;font-size:14px;">
                <?php echo esc_html__('Convert any text file encoding to Unicode UTF-8.', 'cc-utf8-converter'); ?>
            </p>

            <form method="post" enctype="multipart/form-data" style="margin-top:3rem;max-width:780px;">
                <h6><?php echo esc_html__('Choose a file to convert', 'cc-utf8-converter'); ?></h6>

                <div id="cc-utf8-dropzone"
                    style="position:relative;border:2px dashed #c3c4c7;border-radius:12px;padding:40px 20px;text-align:center;background:#fafafa;cursor:pointer;transition:all .2s ease;">
                    <input type="file"
                        name="cc_convert_file"
                        id="cc_convert_file"
                        accept=".txt,.csv,.html,.htm,.md,.xml,.xhtml"
                        required
                        style="position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer;z-index:2;">

                    <div style="pointer-events:none;position:relative;z-index:1;">
                        <div id="cc-utf8-dropzone-title" style="font-size:16px;font-weight:600;margin-bottom:8px;">
                            <?php echo esc_html__('Drag a file here, or click to choose a file.', 'cc-utf8-converter'); ?>
                        </div>
                        <div id="cc-utf8-dropzone-filename" style="color:#666;margin-bottom:2rem;">
                            <?php echo esc_html__('No file selected.', 'cc-utf8-converter'); ?>
                        </div>

                        <p style="font-size:14px;margin-block-start:10px;">
                            <?php echo esc_html__('Supported formats: .txt, .htm, .html, .xhtml, .csv, .xml, .md', 'cc-utf8-converter'); ?>
                        </p>
                    </div>
                </div>

                <p style="text-align:right;margin-top:3rem;display:flex;justify-content:flex-end;gap:10px;align-items:center;">
                    <input
                        type="submit"
                        class="button button-primary"
                        value="<?php echo esc_attr__('Convert to UTF-8 and Download', 'cc-utf8-converter'); ?>">
                    <button type="button" id="cc-utf8-converter-help-toggle" class="button">
                        <?php echo esc_html__('Help', 'cc-utf8-converter'); ?>
                    </button>
                </p>
            </form>

            <div id="cc-utf8-converter-help-box" style="display:none;margin-top:2rem;text-align:left;border:1px solid #dcdcde;border-radius:8px;padding:20px;background:#fff;">
                <p style="margin-top:0;line-height:1.8;">
                    <?php echo esc_html__('Traditional Chinese text files were often encoded in Big5 in the past. If old files are not converted to UTF-8 first, they may display garbled text in different editors, systems, or browsers. This tool converts text files directly to UTF-8 without opening the original file.', 'cc-utf8-converter'); ?>
                </p>
                <p style="margin-bottom:0;line-height:1.8;">
                    <?php echo esc_html__('1. Automatically detects common encodings such as UTF-8, BIG5, SJIS, and GB2312.', 'cc-utf8-converter'); ?><br>
                    <?php echo esc_html__('2. Converts file content to UTF-8.', 'cc-utf8-converter'); ?><br>
                    <?php echo esc_html__('3. Replaces unsupported or invalid characters with ■.', 'cc-utf8-converter'); ?><br>
                    <?php echo esc_html__('4. Downloads the converted file directly and does not keep it on the server.', 'cc-utf8-converter'); ?><br>
                </p>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dropzone = document.getElementById('cc-utf8-dropzone');
            var input = document.getElementById('cc_convert_file');
            var filename = document.getElementById('cc-utf8-dropzone-filename');
            var helpToggle = document.getElementById('cc-utf8-converter-help-toggle');
            var helpBox = document.getElementById('cc-utf8-converter-help-box');
            var noFileText = <?php echo wp_json_encode(__('No file selected.', 'cc-utf8-converter')); ?>;
            var selectedPrefix = <?php echo wp_json_encode(__('Selected: ', 'cc-utf8-converter')); ?>;

            if (dropzone && input && filename) {
                function updateFileName(files) {
                    if (files && files.length > 0) {
                        filename.textContent = selectedPrefix + files[0].name;
                    } else {
                        filename.textContent = noFileText;
                    }
                }

                input.addEventListener('change', function () {
                    updateFileName(input.files);
                });

                ['dragenter', 'dragover'].forEach(function (eventName) {
                    dropzone.addEventListener(eventName, function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzone.style.borderColor = '#2271b1';
                        dropzone.style.background = '#f0f6fc';
                    });
                });

                ['dragleave', 'dragend', 'drop'].forEach(function (eventName) {
                    dropzone.addEventListener(eventName, function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzone.style.borderColor = '#c3c4c7';
                        dropzone.style.background = '#fafafa';
                    });
                });

                dropzone.addEventListener('drop', function (e) {
                    var files = e.dataTransfer.files;
                    if (!files || !files.length) {
                        return;
                    }

                    input.files = files;
                    updateFileName(files);
                });
            }

            if (helpToggle && helpBox) {
                helpToggle.addEventListener('click', function () {
                    var isOpen = helpBox.style.display === 'block';
                    helpBox.style.display = isOpen ? 'none' : 'block';
                });
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_upload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['cc_convert_file'])) {
            $file = $_FILES['cc_convert_file'];

            if ($file['error'] === UPLOAD_ERR_OK) {
                $raw = file_get_contents($file['tmp_name']);
                $encoding = $_POST['encoding'] ?? 'auto';
                $output_format = $_POST['output_format'] ?? 'original';

                if ($encoding === 'auto') {
                    if (mb_check_encoding($raw, 'UTF-8')) {
                        $converted = $raw;
                    } elseif (mb_check_encoding($raw, 'BIG5')) {
                        $converted = $this->big5_to_utf8_safe($raw);
                    } elseif (mb_check_encoding($raw, 'SJIS')) {
                        $converted = mb_convert_encoding($raw, 'UTF-8', 'SJIS');
                    } elseif (mb_check_encoding($raw, 'GB2312')) {
                        $converted = mb_convert_encoding($raw, 'UTF-8', 'GB2312');
                    } else {
                        $converted = $this->big5_to_utf8_safe($raw);
                    }
                } else {
                    $converted = ($encoding === 'BIG5')
                        ? $this->big5_to_utf8_safe($raw)
                        : mb_convert_encoding($raw, 'UTF-8', $encoding);
                }

                if (class_exists('Normalizer')) {
                    $converted = Normalizer::normalize($converted, Normalizer::FORM_KC);
                }

                $clean = mb_convert_encoding($converted, 'UTF-8', 'UTF-8');

                $filename = pathinfo(sanitize_file_name($file['name']), PATHINFO_FILENAME);
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = ($output_format === 'txt') ? $filename . '.txt' : $filename . '.' . $ext;

                $upload_dir = wp_upload_dir();
                $tmp_dir = trailingslashit($upload_dir['basedir']) . 'tmp_convert';
                wp_mkdir_p($tmp_dir);

                $output_path = trailingslashit($tmp_dir) . wp_unique_filename($tmp_dir, $new_filename);
                file_put_contents($output_path, $clean);

                if ($output_format === 'zip') {
                    $zip_path = $output_path . '.zip';
                    $zip = new ZipArchive();

                    if ($zip->open($zip_path, ZipArchive::CREATE) === true) {
                        $zip->addFile($output_path, basename($output_path));
                        $zip->close();
                        unlink($output_path);
                        $output_path = $zip_path;
                    }
                }

                if (ob_get_length()) {
                    ob_end_clean();
                }

                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . rawurlencode(basename($output_path)) . '"');
                header('Content-Length: ' . filesize($output_path));
                header('X-Content-Type-Options: nosniff');
                readfile($output_path);
                @unlink($output_path);
                exit;
            } else {
                wp_die(esc_html__('Upload error.', 'cc-utf8-converter'));
            }
        }
    }

    private function big5_to_utf8_safe($raw)
    {
        $parts = [];
        $len = strlen($raw);

        for ($i = 0; $i < $len; $i++) {
            $b1 = ord($raw[$i]);

            if ($b1 <= 0x7F) {
                $parts[] = chr($b1);
                continue;
            }

            if (isset($raw[$i + 1])) {
                $b2 = ord($raw[$i + 1]);
                $is_big5_pair = ($b1 >= 0xA1 && $b1 <= 0xF9) && (($b2 >= 0x40 && $b2 <= 0x7E) || ($b2 >= 0xA1 && $b2 <= 0xFE));

                if ($is_big5_pair) {
                    $big5 = chr($b1) . chr($b2);
                    $u = @iconv('BIG5-HKSCS', 'UTF-8//IGNORE', $big5);

                    if (!$u) {
                        $u = @iconv('BIG5', 'UTF-8//IGNORE', $big5);
                    }

                    if ($u) {
                        $cp = mb_ord($u, 'UTF-8');
                        $parts[] = ($cp >= 0xE000 && $cp <= 0xF8FF) ? '■' : $u;
                    } else {
                        $parts[] = '■';
                    }

                    $i++;
                    continue;
                }

                if ($b1 === 0xA1 && $b2 === 0x40) {
                    $parts[] = "\xE3\x80\x80";
                    $i++;
                    continue;
                }

                $i++;
            }

            $parts[] = '■';
        }

        $utf8 = implode('', $parts);
        $utf8 = str_replace("\xEF\xBF\xBD", '■', $utf8);

        if (class_exists('Normalizer')) {
            $utf8 = Normalizer::normalize($utf8, Normalizer::FORM_KC);
        }

        return $utf8;
    }
}

CC_UTF8_Converter::get_instance();