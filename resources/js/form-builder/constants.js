export const DRAFT_KEY = 'form_builder_draft';
export const FORMS_LIST_KEY = 'form_builder_forms';
export const LEGACY_KEY = 'form_builder_state';

export const FIELD_CATEGORIES = [
    {
        name: 'Basic Inputs',
        fields: [
            { type: 'text', label: 'Text Input', icon: 'Aa' },
            { type: 'textarea', label: 'Text Area', icon: '¶' },
            { type: 'number', label: 'Number', icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5"/></svg>' },
            { type: 'email', label: 'Email', icon: '@' },
            { type: 'phone', label: 'Phone', icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>' },
            { type: 'date', label: 'Date', icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5M8.25 12h.008v.008H8.25V12zm0 3h.008v.008H8.25V15zm3-3h.008v.008H11.25V12zm0 3h.008v.008H11.25V15zm3-3h.008v.008H14.25V12zm0 3h.008v.008H14.25V15z"/></svg>' },
            { type: 'file', label: 'File Upload', icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>' },
        ],
    },
    {
        name: 'Selection',
        fields: [
            { type: 'dropdown', label: 'Dropdown', icon: '▾' },
            { type: 'radio', label: 'Radio', icon: '◎' },
            { type: 'checkbox', label: 'Checkboxes', icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25L15 9.75M4.5 6.75A2.25 2.25 0 016.75 4.5h10.5a2.25 2.25 0 012.25 2.25v10.5a2.25 2.25 0 01-2.25 2.25H6.75a2.25 2.25 0 01-2.25-2.25V6.75z"/></svg>' },
        ],
    },
    {
        name: 'Layout',
        fields: [
            { type: 'title', label: 'Title', icon: 'H' },
            { type: 'description', label: 'Description', icon: '≡' },
            { type: 'newline', label: 'New Line', icon: '↵' },
            { type: 'pagebreak', label: 'Page Break', icon: '—' },
            { type: 'hidden', label: 'Hidden', icon: '◌' },
        ],
    },
    {
        name: 'Location',
        fields: [
            { type: 'state', label: 'State', icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/></svg>' },
            { type: 'city', label: 'City', icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>' },
            { type: 'state-city', label: 'State & City', icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zm0 0c2.485 0 4.5-4.03 4.5-9s-2.015-9-4.5-9-4.5 4.03-4.5 9 2.015 9 4.5 9zM3 12h18"/></svg>' },
        ],
    },
];

export const DEFAULT_LABELS = {
    text: 'Text Input',
    textarea: 'Text Area',
    number: 'Number Input',
    email: 'Email Address',
    phone: 'Phone Number',
    dropdown: 'Dropdown',
    radio: 'Radio Buttons',
    checkbox: 'Checkboxes',
    date: 'Date',
    file: 'File Upload',
    title: 'Section Title',
    description: 'Description text goes here.',
    newline: 'New Line',
    pagebreak: 'Page Break',
    hidden: 'Hidden Field',
    state: 'State',
    city: 'City',
    'state-city': 'State & City',
};

export const FULL_WIDTH_TYPES = ['title', 'description', 'newline', 'pagebreak', 'state-city'];

export const DEFAULT_SETTINGS = {
    // Layout
    gridColumns: 1,
    templateSelected: false,
    formWidth: 'medium',
    customFormWidth: 720,
    // Header (preview / published form)
    description: '',
    showFormTitle: true,
    showDescription: true,
    // Theme — preset id (e.g. "indigo") or hex (e.g. "#6366f1")
    theme: 'indigo',
    // Action buttons
    showSubmitButton: true,
    submitLabel: 'Submit Form',
    showResetButton: true,
    resetLabel: 'Clear',
    // Post-submit
    showSuccessMessage: true,
    successMessage: 'Thank you! Your form has been submitted.',
};

export const NAV_ITEMS = [
    { id: 'myforms', label: 'My Forms', step: 'View saved forms' },
    { id: 'editor', label: 'Build', step: 'Create & edit forms' },
];

export const GRID_TEMPLATES = [
    { cols: 1, label: 'Single Column', desc: 'Stacked layout, best for simple forms', preview: ['████████████████'] },
    { cols: 2, label: 'Two Columns', desc: 'Side-by-side fields, great for compact forms', preview: ['████████', '████████'] },
    { cols: 3, label: 'Three Columns', desc: 'Multi-column grid for dense layouts', preview: ['██████', '██████', '██████'] },
];

export const DEFAULT_CUSTOM_HEX = '#6366f1';

export const THEMES = [
    { id: 'indigo', label: 'Indigo', class: 'from-indigo-600 to-violet-600' },
    { id: 'emerald', label: 'Emerald', class: 'from-emerald-600 to-teal-600' },
    { id: 'rose', label: 'Rose', class: 'from-rose-600 to-pink-600' },
    { id: 'amber', label: 'Amber', class: 'from-amber-500 to-orange-600' },
    { id: 'sky', label: 'Sky', class: 'from-sky-600 to-blue-600' },
];

export const WIDTHS = [
    { id: 'narrow', label: 'Narrow', class: 'max-w-lg' },
    { id: 'medium', label: 'Medium', class: 'max-w-2xl' },
    { id: 'wide', label: 'Wide', class: 'max-w-4xl' },
    { id: 'full', label: 'Full', class: 'max-w-full' },
];
