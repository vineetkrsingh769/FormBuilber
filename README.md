# Form Builder — Laravel Blade Assignment

A drag-and-drop Form Builder UI built with Laravel Blade, Alpine.js, Tailwind CSS, and SortableJS.

## Setup

```bash
composer install
npm install
npm run build
php artisan serve
```

Open either URL:

- **http://localhost:5173** (Vite — recommended for development)
- **http://127.0.0.1:8000** (Laravel directly)

For development with hot reload (starts both Laravel + Vite):

```bash
npm run dev
```

Then open **http://localhost:5173**

No extra configuration is required. SQLite is used by default.

## DnD Library Choice

| Library | Purpose | Rationale |
|---------|---------|-----------|
| **HTML5 Drag & Drop API** | Palette → Canvas drops | Native, zero dependencies for adding new field types from the palette |
| **SortableJS** | Canvas reordering | Reliable drag-handle reordering with smooth animations; widely used and lightweight (~7kb) |
| **Alpine.js** | State management | Pairs naturally with Blade templates; keeps logic colocated without a separate SPA framework |

## Assumptions

- Settings tab includes form description, submit label, theme, width, and success message
- Preview mode renders a fully interactive form (all fields are editable and submittable)
- State/City location fields use sample US state options for preview
- Clicking a palette tile also adds a field (in addition to drag-and-drop) for better accessibility
- Form state is persisted to `localStorage` so it survives page refresh

## Features

### Required
- Two-column layout: canvas (left) + field palette (right)
- Form title with 200-char live counter and submission URL
- Form Editor / Settings tabs
- Draggable field palette (2-column grid, 18 field types)
- Drop canvas with empty state and dashed border
- Field cards with move, edit, duplicate, delete actions
- Field Options panel with full configuration
- Cancel and Next footer actions (Next outputs JSON via alert + console)
- All form elements rendered via Laravel Blade components

### Bonus
- Undo / Redo (Ctrl+Z / Ctrl+Y)
- Form Preview Mode toggle
- LocalStorage persistence
- Inline delete confirmation
- Drag-over visual feedback (blue border highlight)

## Sample JSON Output

Clicking **Next** produces JSON like this:

```json
{
  "title": "Contact Form",
  "settings": {
    "description": "We'd love to hear from you.",
    "submitLabel": "Submit Form",
    "successMessage": "Thank you! Your form has been submitted.",
    "theme": "indigo",
    "formWidth": "medium",
    "showFieldCount": true
  },
  "fields": [
    {
      "id": "a1b2c3d4-e5f6-7890-abcd-ef1234567890",
      "type": "text",
      "label": "Full Name",
      "placeholder": "Enter your name",
      "required": true,
      "cssClass": "",
      "defaultValue": "",
      "minChars": 2,
      "maxChars": 100,
      "options": ["Option 1", "Option 2", "Option 3"]
    },
    {
      "id": "b2c3d4e5-f6a7-8901-bcde-f12345678901",
      "type": "email",
      "label": "Email Address",
      "placeholder": "name@example.com",
      "required": true,
      "cssClass": "",
      "defaultValue": "",
      "minChars": null,
      "maxChars": null,
      "options": ["Option 1", "Option 2", "Option 3"]
    },
    {
      "id": "c3d4e5f6-a7b8-9012-cdef-123456789012",
      "type": "dropdown",
      "label": "Department",
      "placeholder": "",
      "required": false,
      "cssClass": "",
      "defaultValue": "",
      "minChars": null,
      "maxChars": null,
      "options": ["Sales", "Support", "Engineering"]
    }
  ]
}
```

## Project Structure

```
app/
└── Http/Controllers/
    └── FormBuilderController.php    # Serves the form builder page

resources/
├── css/
│   ├── app.css                      # Tailwind entry + global styles
│   └── form-builder.css             # Form builder–specific styles
├── js/
│   ├── app.js                       # Alpine.js bootstrap
│   └── form-builder/
│       ├── index.js                 # Alpine component (main logic)
│       ├── constants.js             # Field types, themes, defaults
│       └── helpers.js               # Field factory, snapshot, dates
└── views/
    ├── layouts/
    │   └── form-builder.blade.php   # App shell layout
    ├── form-builder/
    │   ├── index.blade.php          # Page entry (includes partials)
    │   ├── partials/                # Shared UI pieces
    │   │   ├── toast.blade.php
    │   │   ├── preview-modal.blade.php
    │   │   ├── sidebar.blade.php
    │   │   ├── mobile-nav.blade.php
    │   │   └── editor-header.blade.php
    │   ├── tabs/                    # Main navigation tabs
    │   │   ├── editor.blade.php
    │   │   ├── my-forms.blade.php
    │   │   └── settings.blade.php
    │   └── editor/                  # Build tab sub-components
    │       ├── template-picker.blade.php
    │       ├── canvas.blade.php
    │       ├── palette.blade.php
    │       └── field-card.blade.php
    └── components/
        └── form-fields/             # Reusable field Blade components
            ├── input.blade.php
            ├── label.blade.php
            ├── preview.blade.php    # Canvas preview renderer
            ├── live.blade.php       # Interactive preview renderer
            └── …                    # One component per field type

routes/
└── web.php                          # GET / → FormBuilderController
```
