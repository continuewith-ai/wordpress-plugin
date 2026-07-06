<?php
/**
 * Plugin Name: ContinueWith
 * Plugin URI: https://continuewith.ai/docs/install
 * Description: Add the ContinueWith AI handoff widget so visitors can continue your pages in ChatGPT, Claude, Gemini, and more.
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: ContinueWith
 * Author URI: https://continuewith.ai
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: continuewith
 *
 * @package ContinueWith
 */

if (!defined('ABSPATH')) {
	exit;
}

define('CONTINUEWITH_VERSION', '1.0.0');
define('CONTINUEWITH_PLUGIN_FILE', __FILE__);
define('CONTINUEWITH_DEFAULT_SCRIPT', 'https://continuewith.ai/widget/v1.js');
define('CONTINUEWITH_OPTION_KEY', 'continuewith_site_key');
define('CONTINUEWITH_OPTION_ENABLED', 'continuewith_enabled');

/**
 * Minimal ContinueWith widget installer for WordPress.
 */
final class ContinueWith_Plugin {
	public static function init(): void {
		add_action('admin_menu', array(__CLASS__, 'register_settings_page'));
		add_action('admin_init', array(__CLASS__, 'register_settings'));
		add_action('wp_footer', array(__CLASS__, 'render_widget'), 20);
		add_action('admin_notices', array(__CLASS__, 'admin_notices'));
	}

	public static function register_settings_page(): void {
		add_options_page(
			__('ContinueWith', 'continuewith'),
			__('ContinueWith', 'continuewith'),
			'manage_options',
			'continuewith',
			array(__CLASS__, 'render_settings_page')
		);
	}

	public static function register_settings(): void {
		register_setting(
			'continuewith_settings',
			CONTINUEWITH_OPTION_KEY,
			array(
				'type'              => 'string',
				'sanitize_callback' => array(__CLASS__, 'sanitize_site_key'),
				'default'           => '',
			)
		);

		register_setting(
			'continuewith_settings',
			CONTINUEWITH_OPTION_ENABLED,
			array(
				'type'              => 'boolean',
				'sanitize_callback' => array(__CLASS__, 'sanitize_enabled'),
				'default'           => true,
			)
		);

		add_settings_section(
			'continuewith_main',
			__('Widget settings', 'continuewith'),
			array(__CLASS__, 'render_section_intro'),
			'continuewith'
		);

		add_settings_field(
			CONTINUEWITH_OPTION_KEY,
			__('Public site key', 'continuewith'),
			array(__CLASS__, 'render_site_key_field'),
			'continuewith',
			'continuewith_main'
		);

		add_settings_field(
			CONTINUEWITH_OPTION_ENABLED,
			__('Enable widget', 'continuewith'),
			array(__CLASS__, 'render_enabled_field'),
			'continuewith',
			'continuewith_main'
		);
	}

	public static function sanitize_site_key($value): string {
		$key = is_string($value) ? sanitize_text_field($value) : '';
		return preg_replace('/[^a-zA-Z0-9_-]/', '', $key);
	}

	public static function sanitize_enabled($value): bool {
		return (bool) $value;
	}

	public static function render_section_intro(): void {
		echo '<p>';
		echo esc_html__(
			'Paste your ContinueWith public site key. The widget loads on public pages before the closing body tag.',
			'continuewith'
		);
		echo ' <a href="https://continuewith.ai/dashboard" target="_blank" rel="noopener noreferrer">';
		echo esc_html__('Open dashboard', 'continuewith');
		echo '</a>';
		echo '</p>';
	}

	public static function render_site_key_field(): void {
		$key = get_option(CONTINUEWITH_OPTION_KEY, '');
		printf(
			'<input type="text" class="regular-text code" name="%1$s" id="%1$s" value="%2$s" placeholder="cw_..." autocomplete="off" />',
			esc_attr(CONTINUEWITH_OPTION_KEY),
			esc_attr($key)
		);
		echo '<p class="description">';
		echo esc_html__('Find this key in ContinueWith → your site → Install.', 'continuewith');
		echo '</p>';
	}

	public static function render_enabled_field(): void {
		$enabled = (bool) get_option(CONTINUEWITH_OPTION_ENABLED, true);
		printf(
			'<label><input type="checkbox" name="%1$s" id="%1$s" value="1" %2$s /> %3$s</label>',
			esc_attr(CONTINUEWITH_OPTION_ENABLED),
			checked($enabled, true, false),
			esc_html__('Load the ContinueWith widget on the front end', 'continuewith')
		);
	}

	public static function render_settings_page(): void {
		if (!current_user_can('manage_options')) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields('continuewith_settings');
				do_settings_sections('continuewith');
				submit_button();
				?>
			</form>
			<hr />
			<p>
				<?php
				echo wp_kses_post(
					sprintf(
						/* translators: %s: docs URL */
						__('Need help? See the <a href="%s" target="_blank" rel="noopener noreferrer">WordPress install guide</a>.', 'continuewith'),
						'https://continuewith.ai/docs/install#wordpress'
					)
				);
				?>
			</p>
			<p>
				<a href="https://ready.continuewith.ai/scan" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e('Scan your AI readiness score', 'continuewith'); ?>
				</a>
				&nbsp;·&nbsp;
				<a href="https://ready.continuewith.ai/submit" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e('Get listed in the AI Ready Index', 'continuewith'); ?>
				</a>
			</p>
		</div>
		<?php
	}

	public static function admin_notices(): void {
		if (!current_user_can('manage_options')) {
			return;
		}

		$screen = function_exists('get_current_screen') ? get_current_screen() : null;
		if ($screen && 'settings_page_continuewith' === $screen->id) {
			return;
		}

		$key = get_option(CONTINUEWITH_OPTION_KEY, '');
		if ('' !== $key) {
			return;
		}

		$url = admin_url('options-general.php?page=continuewith');
		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			wp_kses_post(
				sprintf(
					/* translators: %s: settings URL */
					__('ContinueWith is installed but missing a site key. <a href="%s">Add your public key</a>.', 'continuewith'),
					esc_url($url)
				)
			)
		);
	}

	public static function render_widget(): void {
		if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
			return;
		}

		if (!(bool) get_option(CONTINUEWITH_OPTION_ENABLED, true)) {
			return;
		}

		$key = get_option(CONTINUEWITH_OPTION_KEY, '');
		if ('' === $key) {
			return;
		}

		$script = CONTINUEWITH_DEFAULT_SCRIPT;
		printf(
			'<script src="%1$s" data-site-key="%2$s" defer></script>' . "\n",
			esc_url($script),
			esc_attr($key)
		);
	}
}

ContinueWith_Plugin::init();
