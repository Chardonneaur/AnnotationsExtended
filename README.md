# Annotations Extended

Extended annotations management with a full admin UI to view, add, edit, delete and export all annotations.

> **Warning**
>
> This plugin is experimental and was coded using [Claude Code](https://claude.ai).
> It is provided without any warranty regarding quality, stability, or performance.
> This is a community project and is not officially supported by Matomo.

## Description

Provides an enhanced admin interface for managing annotations across all your sites. View every annotation in a single unified table, add or edit annotations via a modal form, and export everything to CSV or JSON.

### Features

- **View all annotations** in a single, unified table sorted by date
- **Add new annotations** with site selection, date picker, and starred option
- **Edit existing annotations** (your own, or all if you have admin/super user access)
- **Delete annotations** with confirmation
- **Export annotations** to CSV or JSON format
- **Multi-site support** — see annotations from all sites you have access to

## FAQ

**Who can see annotations?**

All logged-in users can view annotations for sites they have access to.

**Who can edit or delete annotations?**

Users can edit/delete their own annotations. Admin users can manage any annotation for their sites. Super users can manage all annotations.

**Does this plugin make external requests?**

No. All data stays within your instance.

## Requirements

- Matomo >= 5.0
- PHP >= 8.1

## Installation

### From Matomo Marketplace
1. Go to Administration > Marketplace
2. Search for "AnnotationsExtended"
3. Click Install

### Manual Installation
1. Download the latest release from GitHub
2. Extract to your `matomo/plugins/` directory as `AnnotationsExtended`
3. Activate the plugin in Administration > Plugins

## Usage

Navigate to **Administration > Personal > Annotations** to access the annotations manager.

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## License

GPL-3.0+. See [LICENSE](LICENSE) for details.
