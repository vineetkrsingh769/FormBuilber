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

- Appearance panel includes form description, theme, width, grid layout, submit/reset labels, and success message
- Theme is stored as a single value: a preset id (e.g. `"indigo"`) or a hex color (e.g. `"#6366f1"`) when custom
- Preview mode renders a fully interactive form (all fields are editable and submittable)
- State/City location fields use sample US state options for preview
- Clicking a palette tile also adds a field (in addition to drag-and-drop) for better accessibility
- Form state is persisted to `localStorage` so it survives page refresh

## Features

### Required
- Two-column layout: canvas (left) + field palette (right)
- Form title with 200-char live counter and submission URL
- My Forms / Build tabs with Appearance panel in the editor
- Draggable field palette (2-column grid, 18 field types)
- Drop canvas with empty state and dashed border
- Field cards with move, edit, duplicate, delete actions
- Field Options panel with full configuration
- Cancel and Next footer actions (Next opens a schema dialog with copyable JSON)
- All form elements rendered via Laravel Blade components

### Bonus
- Undo / Redo (Ctrl+Z / Ctrl+Y)
- Form Preview Mode toggle
- LocalStorage persistence
- Inline delete confirmation
- Drag-over visual feedback (blue border highlight)

## Sample JSON Output

Clicking **Next** (or **Export JSON** in the Appearance panel) produces JSON like this:

```json
{
  "title": "Contact Form",
  "settings": {
    "layout": {
      "gridColumns": 2,
      "formWidth": "medium"
    },
    "appearance": {
      "description": "We'd love to hear from you.",
      "showFormTitle": true,
      "showDescription": true,
      "theme": "indigo"
    },
    "actions": {
      "showSubmitButton": true,
      "submitLabel": "Submit Form",
      "showResetButton": true,
      "resetLabel": "Clear"
    },
    "submission": {
      "showSuccessMessage": true,
      "successMessage": "Thank you! Your form has been submitted."
    }
  },
  "fields": [
    {
      "order": 1,
      "id": "a1b2c3d4-e5f6-7890-abcd-ef1234567890",
      "type": "text",
      "label": "Full Name",
      "required": true,
      "colSpan": 1,
      "placeholder": "Enter your name",
      "minChars": 2,
      "maxChars": 100
    },
    {
      "order": 2,
      "id": "b2c3d4e5-f6a7-8901-bcde-f12345678901",
      "type": "email",
      "label": "Email Address",
      "required": true,
      "colSpan": 1,
      "placeholder": "name@example.com"
    },
    {
      "order": 3,
      "id": "c3d4e5f6-a7b8-9012-cdef-123456789012",
      "type": "dropdown",
      "label": "Department",
      "required": false,
      "colSpan": 2,
      "options": ["Sales", "Support", "Engineering"]
    }
  ],
  "meta": {
    "version": 1,
    "exportedAt": "2026-06-10T10:30:00.000Z",
    "fieldCount": 3,
    "formId": "f8e7d6c5-b4a3-2109-8765-432109876543"
  }
}
```

### Schema notes

| Key | Description |
|-----|-------------|
| `settings.layout.gridColumns` | `1`, `2`, or `3` — chosen layout template |
| `settings.layout.formWidth` | `narrow`, `medium`, `wide`, `full`, or `custom` |
| `settings.layout.customFormWidth` | Included only when `formWidth` is `custom` (320–1600 px) |
| `settings.appearance.theme` | Preset id (`indigo`, `emerald`, etc.) **or** hex color (`#6366f1`) when custom |
| `fields[].order` | Field position in the canvas (1-based) |
| `fields[].colSpan` | How many grid columns the field spans |
| `meta.formId` | Present only when the form has been saved to My Forms |

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
    │   │   ├── schema-dialog.blade.php
    │   │   ├── editor-footer.blade.php
    │   │   ├── sidebar.blade.php
    │   │   ├── mobile-nav.blade.php
    │   │   └── editor-header.blade.php
    │   ├── tabs/                    # Main navigation tabs
    │   │   ├── editor.blade.php
    │   │   └── my-forms.blade.php
    │   └── editor/                  # Build tab sub-components
    │       ├── template-picker.blade.php
    │       ├── canvas.blade.php
    │       ├── palette.blade.php
    │       ├── appearance.blade.php
    │       └── field-card.blade.php
    └── components/
        ├── dialog.blade.php         # Reusable modal component
        └── form-fields/             # Reusable field Blade components
            ├── input.blade.php
            ├── label.blade.php
            ├── preview.blade.php    # Canvas preview renderer
            ├── live.blade.php       # Interactive preview renderer
            └── …                    # One component per field type

routes/
└── web.php                          # GET / → FormBuilderController
```
