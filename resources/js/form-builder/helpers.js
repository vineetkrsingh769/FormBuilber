import { DEFAULT_CUSTOM_HEX, DEFAULT_LABELS, DEFAULT_SETTINGS, FULL_WIDTH_TYPES, THEMES } from './constants';

const FIELD_OPTION_TYPES = ['dropdown', 'radio', 'checkbox'];
const FIELD_PLACEHOLDER_TYPES = ['text', 'textarea', 'number', 'email', 'phone', 'city'];
const FIELD_MINMAX_TYPES = ['text', 'textarea'];
const FIELD_DEFAULT_TYPES = ['text', 'number', 'email', 'hidden'];

export function normalizeHexColor(hex) {
    if (!hex || typeof hex !== 'string') return null;
    let value = hex.trim();
    if (!value.startsWith('#')) value = `#${value}`;
    if (/^#[0-9A-Fa-f]{3}$/.test(value)) {
        value = `#${value[1]}${value[1]}${value[2]}${value[2]}${value[3]}${value[3]}`;
    }
    if (!/^#[0-9A-Fa-f]{6}$/.test(value)) return null;
    return value.toLowerCase();
}

export function isHexTheme(theme) {
    return normalizeHexColor(theme) !== null;
}

export function darkenHex(hex, amount = 0.14) {
    const normalized = normalizeHexColor(hex);
    if (!normalized) return DEFAULT_CUSTOM_HEX;
    const channel = (start) => parseInt(normalized.slice(start, start + 2), 16);
    const dim = (c) => Math.max(0, Math.min(255, Math.round(c * (1 - amount))));
    const r = dim(channel(1));
    const g = dim(channel(3));
    const b = dim(channel(5));
    return `#${[r, g, b].map((n) => n.toString(16).padStart(2, '0')).join('')}`;
}

export function normalizeSettings(raw = {}) {
    const settings = { ...DEFAULT_SETTINGS, ...raw };
    const px = Number(settings.customFormWidth);
    settings.customFormWidth = Number.isFinite(px)
        ? Math.min(Math.max(Math.round(px), 320), 1600)
        : DEFAULT_SETTINGS.customFormWidth;

    if (settings.theme === 'custom') {
        settings.theme = normalizeHexColor(raw.customThemeColor) ?? DEFAULT_CUSTOM_HEX;
    } else if (isHexTheme(settings.theme)) {
        settings.theme = normalizeHexColor(settings.theme);
    }

    delete settings.customThemeColor;

    return settings;
}

const APPEARANCE_KEYS = [
    'description',
    'showFormTitle',
    'showDescription',
    'theme',
    'formWidth',
    'customFormWidth',
    'showSubmitButton',
    'submitLabel',
    'showResetButton',
    'resetLabel',
    'showSuccessMessage',
    'successMessage',
];

export function resetAppearanceSettings(current = {}) {
    const defaults = normalizeSettings({});
    const next = { ...current };

    APPEARANCE_KEYS.forEach((key) => {
        next[key] = defaults[key];
    });

    return normalizeSettings(next);
}

export function resolveTheme(settings, themes = THEMES) {
    const hex = normalizeHexColor(settings.theme);
    if (hex) {
        const darker = darkenHex(hex);
        return {
            id: 'custom',
            label: 'Custom',
            class: '',
            style: `background: linear-gradient(to right, ${hex}, ${darker})`,
            swatchStyle: `background: linear-gradient(to bottom right, ${hex}, ${darker})`,
        };
    }
    const preset = themes.find((t) => t.id === settings.theme) ?? themes[0];
    return {
        ...preset,
        class: `bg-gradient-to-r ${preset.class}`,
        style: '',
        swatchStyle: '',
    };
}

export function resolveFormWidth(settings, widths) {
    if (settings.formWidth === 'custom') {
        const px = settings.customFormWidth ?? DEFAULT_SETTINGS.customFormWidth;
        return {
            id: 'custom',
            label: 'Custom',
            class: 'w-full',
            style: `max-width: ${px}px`,
        };
    }
    return widths.find((w) => w.id === settings.formWidth) ?? widths[1];
}

export function createField(type, gridColumns = 1) {
    const isFullWidth = FULL_WIDTH_TYPES.includes(type);

    return {
        id: crypto.randomUUID(),
        type,
        label: DEFAULT_LABELS[type] ?? 'Field',
        placeholder: '',
        required: false,
        cssClass: '',
        defaultValue: '',
        minChars: null,
        maxChars: null,
        options: ['Option 1', 'Option 2', 'Option 3'],
        colSpan: isFullWidth ? gridColumns : 1,
    };
}

export function cloneField(field) {
    return {
        ...JSON.parse(JSON.stringify(field)),
        id: crypto.randomUUID(),
        label: `${field.label} (Copy)`,
    };
}

export function serializeSettingsForExport(raw = {}) {
    const settings = normalizeSettings(raw);

    const layout = {
        gridColumns: settings.gridColumns,
        formWidth: settings.formWidth,
    };

    if (settings.formWidth === 'custom') {
        layout.customFormWidth = settings.customFormWidth;
    }

    return {
        layout,
        appearance: {
            description: settings.description,
            showFormTitle: settings.showFormTitle,
            showDescription: settings.showDescription,
            theme: settings.theme,
        },
        actions: {
            showSubmitButton: settings.showSubmitButton,
            submitLabel: settings.submitLabel,
            showResetButton: settings.showResetButton,
            resetLabel: settings.resetLabel,
        },
        submission: {
            showSuccessMessage: settings.showSuccessMessage,
            successMessage: settings.successMessage,
        },
    };
}

export function serializeFieldForExport(field, index = 0) {
    const exported = {
        order: index + 1,
        id: field.id,
        type: field.type,
        label: field.label,
        required: Boolean(field.required),
        colSpan: field.colSpan ?? 1,
    };

    if (FIELD_PLACEHOLDER_TYPES.includes(field.type)) {
        exported.placeholder = field.placeholder ?? '';
    }

    if (FIELD_OPTION_TYPES.includes(field.type)) {
        exported.options = Array.isArray(field.options) ? [...field.options] : [];
    }

    if (FIELD_MINMAX_TYPES.includes(field.type)) {
        if (field.minChars != null && field.minChars !== '') {
            exported.minChars = Number(field.minChars);
        }
        if (field.maxChars != null && field.maxChars !== '') {
            exported.maxChars = Number(field.maxChars);
        }
    }

    if (FIELD_DEFAULT_TYPES.includes(field.type) && field.defaultValue) {
        exported.defaultValue = field.defaultValue;
    }

    if (field.cssClass) {
        exported.cssClass = field.cssClass;
    }

    return exported;
}

export function buildFormSchema({ title, settings, fields, formId = null }) {
    const schema = {
        title: title?.trim() || 'Untitled Form',
        settings: serializeSettingsForExport(settings),
        fields: fields.map((field, index) => serializeFieldForExport(field, index)),
        meta: {
            version: 1,
            exportedAt: new Date().toISOString(),
            fieldCount: fields.length,
        },
    };

    if (formId) {
        schema.meta.formId = formId;
    }

    return schema;
}

export function snapshot(state) {
    return JSON.parse(JSON.stringify({
        formTitle: state.formTitle,
        settings: normalizeSettings(state.settings),
        fields: state.fields,
    }));
}

export function formRecord(id, data) {
    const now = new Date().toISOString();

    return {
        id,
        formTitle: data.formTitle,
        settings: data.settings,
        fields: data.fields,
        createdAt: data.createdAt ?? now,
        updatedAt: now,
    };
}

export function formatDate(iso) {
    if (!iso) return '';

    return new Date(iso).toLocaleDateString(undefined, {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}
