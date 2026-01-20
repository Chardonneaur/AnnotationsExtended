# Annotations Extended for Matomo

A Matomo plugin that provides an enhanced UI for managing annotations across all your sites.

## Features

- **View all annotations** in a single, unified table
- **Add new annotations** with site selection, date picker, and starred option
- **Edit existing annotations** (your own, or all if you're a super user)
- **Delete annotations** with confirmation
- **Export annotations** to CSV or JSON format
- **Filter by site** - see annotations from all sites you have access to

## Requirements

- Matomo >= 5.0.0
- PHP >= 7.4

## Installation

### From GitHub

1. Download the latest release
2. Extract to your Matomo `plugins/` directory
3. Rename the folder to `AnnotationsExtended`
4. Activate the plugin in Matomo's Administration > Plugins

### Manual Installation

```bash
cd /path/to/matomo/plugins
git clone https://github.com/yourusername/matomo-annotations-extended.git AnnotationsExtended
```

Then activate the plugin in Matomo's Administration > Plugins.

## Usage

Once activated, navigate to **Administration > Personal > Annotations** to access the annotations manager.

### Permissions

- All logged-in users can view annotations for sites they have access to
- Users can edit/delete their own annotations
- Users with admin access to a site can edit/delete any annotation for that site
- Super users can edit/delete any annotation

## Screenshots

The plugin provides a clean interface for managing all your annotations:

- Toolbar with "Add Annotation" button and export options
- Table showing Date, Site, Note, Starred status, User, and Actions
- Modal form for adding/editing annotations

## License

GPL v3+

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

If you encounter any issues, please [open an issue](https://github.com/yourusername/matomo-annotations-extended/issues) on GitHub.
