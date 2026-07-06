# ContinueWith WordPress plugin

Official thin plugin for [ContinueWith](https://continuewith.ai) — injects the AI handoff widget on every public page.

[![GitHub](https://img.shields.io/badge/GitHub-continuewith--ai%2Fwordpress--plugin-181717?style=for-the-badge&logo=github)](https://github.com/continuewith-ai/wordpress-plugin)

## Install

### From GitHub (recommended)

```bash
cd wp-content/plugins
git clone https://github.com/continuewith-ai/wordpress-plugin.git continuewith
```

Then in **WP Admin → Plugins**, activate **ContinueWith**.

### Manual ZIP

1. Download this repo as ZIP
2. Extract into `wp-content/plugins/continuewith/`
3. Activate the plugin

## Configure

1. Create a free account at [continuewith.ai/dashboard](https://continuewith.ai/dashboard)
2. Copy your **public site key**
3. Open **Settings → ContinueWith** in WordPress
4. Paste the key and save

The plugin loads `https://continuewith.ai/widget/v1.js` before `</body>` on public pages.

## Verify

- Open any public page and confirm the widget appears
- Run [install verify](https://continuewith.ai/dashboard) from your ContinueWith dashboard
- Optional: [scan AI readiness](https://ready.continuewith.ai/scan) and [get listed](https://ready.continuewith.ai/submit)

## Alternative: snippet only

Prefer not to install a plugin? Paste the script in your theme footer or use a header/footer code plugin — see the [WordPress install guide](https://continuewith.ai/docs/install#wordpress).

## Related

| Resource | Link |
|----------|------|
| CLI | `npx continuewith add` |
| Next.js starter | [starter-next](https://github.com/continuewith-ai/starter-next) |
| Curated links | [awesome-continuewith](https://github.com/continuewith-ai/awesome-continuewith) |

## License

MIT © [ContinueWith](https://continuewith.ai)
