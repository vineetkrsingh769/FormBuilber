import Sortable from 'sortablejs';
import {
    DRAFT_KEY,
    FORMS_LIST_KEY,
    LEGACY_KEY,
    FIELD_CATEGORIES,
    DEFAULT_SETTINGS,
    FULL_WIDTH_TYPES,
    NAV_ITEMS,
    GRID_TEMPLATES,
    THEMES,
    WIDTHS,
} from './constants';
import {
    buildFormSchema,
    createField,
    cloneField,
    snapshot,
    formRecord,
    formatDate,
    isHexTheme,
    normalizeHexColor,
    normalizeSettings,
    resolveFormWidth,
    resolveTheme,
    resetAppearanceSettings,
} from './helpers';

export function formBuilder() {
    return {
        formTitle: 'Untitled Form',
        settings: { ...DEFAULT_SETTINGS },
        activeTab: 'myforms',
        editorActive: false,
        paletteTab: 'add',
        fields: [],
        selectedFieldId: null,
        currentFormId: null,
        savedForms: [],
        previewSnapshot: null,
        isDragging: false,
        isDragOver: false,
        previewMode: false,
        schemaDialogOpen: false,
        schemaDialogJson: '',
        previewValues: {},
        previewSubmitted: false,
        deleteConfirmId: null,
        formMenuOpenId: null,
        toast: null,
        history: [],
        historyIndex: -1,
        fieldCategories: FIELD_CATEGORIES,
        sortable: null,
        isDirty: false,
        lastSavedHash: '',
        navItems: NAV_ITEMS,
        gridTemplates: GRID_TEMPLATES,
        themes: THEMES,
        widths: WIDTHS,

        init() {
            this.previewMode = false;
            this.previewSnapshot = null;
            document.body.style.overflow = 'hidden';

            this.loadSavedForms();
            this.migrateLegacyStorage();
            this.restoreSession();
            this.pushHistory();
            this.markClean();
            this.$nextTick(() => this.initSortable());

            window.addEventListener('beforeunload', () => {
                if (this.editorActive) this.persistDraft();
            });

            window.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) {
                    e.preventDefault();
                    if (this.editorActive) this.undo();
                }
                if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.key === 'z' && e.shiftKey))) {
                    e.preventDefault();
                    if (this.editorActive) this.redo();
                }
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    if (this.editorActive) this.handleSave();
                }
            });
        },

        destroySortable() {
            if (this.sortable) {
                this.sortable.destroy();
                this.sortable = null;
            }
        },

        initSortable() {
            this.destroySortable();
            const el = this.$refs.fieldsList;
            if (!el) return;

            this.sortable = Sortable.create(el, {
                animation: 200,
                draggable: '.field-card',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: (evt) => {
                    const item = this.fields.splice(evt.oldIndex, 1)[0];
                    this.fields.splice(evt.newIndex, 0, item);
                    this.persist();
                    this.pushHistory();
                },
            });
        },

        get titleLength() {
            return this.formTitle.length;
        },

        get selectedField() {
            return this.fields.find((f) => f.id === this.selectedFieldId) ?? null;
        },

        get currentTheme() {
            return resolveTheme(this.settings, this.themes);
        },

        get currentWidth() {
            return resolveFormWidth(this.settings, this.widths);
        },

        get gridClass() {
            return this.getGridClassFor(this.settings.gridColumns);
        },

        get activePreviewTitle() {
            return this.previewSnapshot?.formTitle ?? this.formTitle;
        },

        get activePreviewSettings() {
            return normalizeSettings(this.previewSnapshot?.settings ?? this.settings);
        },

        get activePreviewFields() {
            return (this.previewSnapshot?.fields ?? this.fields).map((f) => ({ colSpan: 1, ...f }));
        },

        get activePreviewWidth() {
            return resolveFormWidth(this.activePreviewSettings, this.widths);
        },

        setFormWidth(id) {
            this.settings.formWidth = id;
            this.onSettingsToggle();
        },

        onCustomWidthInput() {
            this.settings.customFormWidth = normalizeSettings({
                ...this.settings,
                customFormWidth: this.settings.customFormWidth,
            }).customFormWidth;
            this.onSettingsChange();
        },

        get activePreviewTheme() {
            return resolveTheme(this.activePreviewSettings, this.themes);
        },

        setTheme(id) {
            this.settings.theme = id;
            this.onSettingsToggle();
        },

        isCustomTheme() {
            return isHexTheme(this.settings.theme);
        },

        get customThemePickerValue() {
            return isHexTheme(this.settings.theme)
                ? this.settings.theme
                : normalizeHexColor(this.settings.theme) ?? '#6366f1';
        },

        onCustomColorInput(event) {
            const hex = normalizeHexColor(event?.target?.value);
            if (!hex) return;
            this.settings.theme = hex;
            this.onSettingsChange();
        },

        getGridClassFor(cols) {
            const map = { 1: 'grid-cols-1', 2: 'grid-cols-2', 3: 'grid-cols-3' };
            return map[cols] ?? 'grid-cols-1';
        },

        getThemeForSettings(formSettings) {
            return resolveTheme(normalizeSettings(formSettings ?? {}), this.themes);
        },

        getSavedFormGridStyle(field, form) {
            const cols = form.settings?.gridColumns ?? 1;
            const span = Math.min(field.colSpan || 1, cols);
            return `grid-column: span ${span} / span ${span}`;
        },

        getPreviewGridStyle(field) {
            const cols = this.activePreviewSettings.gridColumns;
            const span = Math.min(field.colSpan || 1, cols);
            return `grid-column: span ${span} / span ${span}`;
        },

        get colSpanOptions() {
            const max = this.settings.gridColumns;
            return Array.from({ length: max }, (_, i) => i + 1);
        },

        get schema() {
            return buildFormSchema({
                title: this.formTitle,
                settings: this.settings,
                fields: this.fields,
                formId: this.currentFormId,
            });
        },

        getFieldGridStyle(field) {
            const span = Math.min(field.colSpan || 1, this.settings.gridColumns);
            return `grid-column: span ${span} / span ${span}`;
        },

        applyGridColumns(cols, { notify = false } = {}) {
            if (this.settings.gridColumns === cols && this.settings.templateSelected) return;

            this.settings.gridColumns = cols;
            this.settings.templateSelected = true;
            this.fields.forEach((f) => {
                if (FULL_WIDTH_TYPES.includes(f.type)) {
                    f.colSpan = cols;
                } else if (f.colSpan > cols) {
                    f.colSpan = cols;
                }
            });
            this.scheduleSettingsSave();
            this.scheduleSortableRefresh();
            if (notify) {
                this.showToast(`${cols}-column layout selected`);
            }
        },

        selectTemplate(cols) {
            this.applyGridColumns(cols, { notify: true });
        },

        setGridColumns(cols) {
            this.applyGridColumns(cols, { notify: false });
        },

        scheduleSettingsSave() {
            this.markDirty();
            clearTimeout(this._settingsPersistTimer);
            this._settingsPersistTimer = setTimeout(() => this.persist(), 200);
            clearTimeout(this._settingsHistoryTimer);
            this._settingsHistoryTimer = setTimeout(() => this.pushHistory(), 500);
        },

        scheduleSortableRefresh() {
            clearTimeout(this._sortableTimer);
            this._sortableTimer = setTimeout(() => {
                this.$nextTick(() => this.initSortable());
            }, 150);
        },

        pushHistory() {
            const snap = snapshot(this);
            const current = this.history[this.historyIndex];
            if (current && JSON.stringify(current) === JSON.stringify(snap)) {
                return;
            }
            this.history = this.history.slice(0, this.historyIndex + 1);
            this.history.push(snap);
            this.historyIndex = this.history.length - 1;
            if (this.history.length > 50) {
                this.history.shift();
                this.historyIndex--;
            }
        },

        undo() {
            clearTimeout(this._settingsPersistTimer);
            clearTimeout(this._settingsHistoryTimer);
            clearTimeout(this._changeTimer);
            if (this.historyIndex <= 0) return;
            this.historyIndex--;
            this.applySnapshot(this.history[this.historyIndex]);
            this.showToast('Undone');
        },

        redo() {
            clearTimeout(this._settingsPersistTimer);
            clearTimeout(this._settingsHistoryTimer);
            clearTimeout(this._changeTimer);
            if (this.historyIndex >= this.history.length - 1) return;
            this.historyIndex++;
            this.applySnapshot(this.history[this.historyIndex]);
            this.showToast('Redone');
        },

        applySnapshot(snap) {
            this.formTitle = snap.formTitle;
            this.settings = normalizeSettings(snap.settings);
            this.fields = snap.fields.map((f) => ({ colSpan: 1, ...f }));
            this.selectedFieldId = null;
            this.paletteTab = 'add';
            this.persist();
            this.$nextTick(() => this.initSortable());
        },

        getInsertIndex(event) {
            const container = this.$refs.fieldsList;
            if (!container || this.fields.length === 0) return this.fields.length;

            const cards = container.querySelectorAll('.field-card');
            for (let i = 0; i < cards.length; i++) {
                const rect = cards[i].getBoundingClientRect();
                const midY = rect.top + rect.height / 2;
                const midX = rect.left + rect.width / 2;
                if (event.clientY < midY || (event.clientY < rect.bottom && event.clientX < midX)) {
                    return i;
                }
            }
            return this.fields.length;
        },

        initPreviewValuesFromFields(fieldList) {
            const values = {};
            fieldList.forEach((field) => {
                if (field.type === 'checkbox') {
                    values[field.id] = [];
                } else if (field.type === 'state-city') {
                    values[field.id + '_state'] = '';
                    values[field.id + '_city'] = '';
                } else if (field.type === 'radio' || field.type === 'dropdown' || field.type === 'state') {
                    values[field.id] = field.defaultValue || '';
                } else if (field.type === 'hidden') {
                    values[field.id] = field.defaultValue || '';
                } else if (!['title', 'description', 'newline', 'pagebreak'].includes(field.type)) {
                    values[field.id] = field.defaultValue || '';
                }
            });
            this.previewValues = values;
            this.previewSubmitted = false;
        },

        initPreviewValues() {
            this.initPreviewValuesFromFields(this.activePreviewFields);
        },

        toggleCheckbox(fieldId, option) {
            const current = this.previewValues[fieldId] ?? [];
            const idx = current.indexOf(option);
            if (idx === -1) {
                this.previewValues[fieldId] = [...current, option];
            } else {
                this.previewValues[fieldId] = current.filter((o) => o !== option);
            }
        },

        isCheckboxChecked(fieldId, option) {
            return (this.previewValues[fieldId] ?? []).includes(option);
        },

        onPaletteDragStart(event, type) {
            if (!this.settings.templateSelected) {
                event.preventDefault();
                this.showToast('Please select a layout template first');
                return;
            }
            event.dataTransfer.setData('field-type', type);
            event.dataTransfer.effectAllowed = 'copy';
            this.isDragging = true;
        },

        onPaletteDragEnd() {
            this.isDragging = false;
            this.isDragOver = false;
        },

        onCanvasDragOver(event) {
            if (!this.settings.templateSelected) return;
            event.preventDefault();
            event.dataTransfer.dropEffect = 'copy';
            this.isDragOver = true;
        },

        onCanvasDragLeave(event) {
            if (!event.currentTarget.contains(event.relatedTarget)) {
                this.isDragOver = false;
            }
        },

        onCanvasDrop(event) {
            event.preventDefault();
            this.isDragOver = false;
            this.isDragging = false;
            if (!this.settings.templateSelected) return;

            const type = event.dataTransfer.getData('field-type');
            if (type) {
                const index = this.getInsertIndex(event);
                this.addField(type, index);
            }
        },

        addField(type, index = null) {
            if (!this.settings.templateSelected) {
                this.showToast('Select a layout template first');
                return;
            }
            const field = createField(type, this.settings.gridColumns);
            if (index !== null && index < this.fields.length) {
                this.fields.splice(index, 0, field);
            } else {
                this.fields.push(field);
            }
            this.selectedFieldId = field.id;
            this.paletteTab = 'options';
            this.persist();
            this.pushHistory();
        },

        selectField(id) {
            this.selectedFieldId = id;
            this.paletteTab = 'options';
        },

        duplicateField(id) {
            const index = this.fields.findIndex((f) => f.id === id);
            if (index === -1) return;
            const copy = cloneField(this.fields[index]);
            this.fields.splice(index + 1, 0, copy);
            this.selectedFieldId = copy.id;
            this.paletteTab = 'options';
            this.persist();
            this.pushHistory();
            this.showToast('Field duplicated');
        },

        requestDelete(id) {
            this.deleteConfirmId = id;
        },

        cancelDelete() {
            this.deleteConfirmId = null;
        },

        confirmDelete() {
            const id = this.deleteConfirmId;
            this.fields = this.fields.filter((f) => f.id !== id);
            if (this.selectedFieldId === id) {
                this.selectedFieldId = null;
                this.paletteTab = 'add';
            }
            this.deleteConfirmId = null;
            this.persist();
            this.pushHistory();
            this.showToast('Field removed');
        },

        removeFieldFromOptions() {
            if (!this.selectedField) return;
            this.requestDelete(this.selectedField.id);
        },

        addOption() {
            if (!this.selectedField) return;
            this.selectedField.options.push(`Option ${this.selectedField.options.length + 1}`);
            this.persist();
            this.pushHistory();
        },

        removeOption(index) {
            if (!this.selectedField || this.selectedField.options.length <= 1) return;
            this.selectedField.options.splice(index, 1);
            this.persist();
            this.pushHistory();
        },

        markDirty() {
            this.isDirty = true;
        },

        markClean() {
            this.isDirty = false;
            this.lastSavedHash = JSON.stringify(snapshot(this));
        },

        onFieldChange() {
            this.markDirty();
            this.persist();
        },

        onFieldChangeDebounced() {
            this.markDirty();
            clearTimeout(this._changeTimer);
            this._changeTimer = setTimeout(() => {
                this.persist();
                this.pushHistory();
            }, 400);
        },

        onSettingsChange() {
            this.scheduleSettingsSave();
        },

        onSettingsToggle() {
            this.scheduleSettingsSave();
        },

        resetAppearance() {
            if (!confirm('Reset appearance settings to defaults? Grid layout will be kept.')) return;

            this.settings = resetAppearanceSettings(this.settings);
            clearTimeout(this._settingsPersistTimer);
            clearTimeout(this._settingsHistoryTimer);
            this.markDirty();
            this.persist();
            this.pushHistory();
            this.showToast('Appearance reset to defaults');
        },

        switchTab(tab) {
            if (tab === 'editor' && !this.editorActive) {
                this.activeTab = 'myforms';
                this.showToast('Click "New Form" to start building');
                return;
            }
            this.closeFormMenu();
            this.activeTab = tab;
            if (tab === 'myforms') this.loadSavedForms();
            if (this.editorActive) this.persistDraft();
        },

        openEditor() {
            this.editorActive = true;
            this.activeTab = 'editor';
            this.persistDraft();
        },

        exportJson() {
            const json = JSON.stringify(this.schema, null, 2);
            navigator.clipboard?.writeText(json);
            console.log('Exported:', this.schema);
            this.showToast('JSON copied to clipboard');
        },

        handleNext() {
            this.schemaDialogJson = JSON.stringify(this.schema, null, 2);
            console.log('Form schema:', this.schema);
            this.schemaDialogOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeSchemaDialog() {
            this.schemaDialogOpen = false;
            if (!this.previewMode) {
                document.body.style.overflow = '';
            }
        },

        copySchemaJson() {
            navigator.clipboard?.writeText(this.schemaDialogJson);
            this.showToast('JSON copied to clipboard');
        },

        onColSpanChange() {
            if (!this.selectedField) return;
            this.selectedField.colSpan = Math.min(this.selectedField.colSpan, this.settings.gridColumns);
            this.onFieldChangeDebounced();
        },

        handleCancel() {
            if (this.fields.length && !confirm('Discard all changes and return to My Forms?')) return;
            this.resetEditor();
            this.activeTab = 'myforms';
            this.showToast('Returned to My Forms');
        },

        resetEditor() {
            this.formTitle = 'Untitled Form';
            this.settings = { ...DEFAULT_SETTINGS };
            this.fields = [];
            this.currentFormId = null;
            this.selectedFieldId = null;
            this.paletteTab = 'add';
            this.previewMode = false;
            this.previewSnapshot = null;
            this.editorActive = false;
            localStorage.removeItem(DRAFT_KEY);
            this.history = [];
            this.historyIndex = -1;
            this.markClean();
            this.pushHistory();
            this.$nextTick(() => this.initSortable());
        },

        handleSave() {
            const data = snapshot(this);
            const existing = this.currentFormId
                ? this.savedForms.find((f) => f.id === this.currentFormId)
                : null;
            const id = this.currentFormId ?? crypto.randomUUID();
            const record = formRecord(id, {
                ...data,
                settings: normalizeSettings(data.settings),
                createdAt: existing?.createdAt,
            });

            const idx = this.savedForms.findIndex((f) => f.id === id);
            if (idx >= 0) {
                this.savedForms.splice(idx, 1, record);
            } else {
                this.savedForms.unshift(record);
            }

            this.currentFormId = id;
            this.persistSavedForms();
            this.persistDraft();
            this.markClean();
            console.log('Form saved:', record);
            this.showToast(`"${record.formTitle}" saved to My Forms!`);
        },

        toggleFormMenu(formId) {
            this.formMenuOpenId = this.formMenuOpenId === formId ? null : formId;
        },

        closeFormMenu() {
            this.formMenuOpenId = null;
        },

        loadFormForEdit(formId) {
            const form = this.savedForms.find((f) => f.id === formId);
            if (!form) return;

            this.closeFormMenu();
            this.currentFormId = form.id;
            this.formTitle = form.formTitle;
            this.settings = normalizeSettings({ ...form.settings, templateSelected: true });
            this.fields = form.fields.map((f) => ({ colSpan: 1, ...f }));
            this.selectedFieldId = null;
            this.paletteTab = 'add';
            this.openEditor();
            this.history = [];
            this.historyIndex = -1;
            this.pushHistory();
            this.persistDraft();
            this.markClean();
            this.$nextTick(() => this.initSortable());
            this.showToast(`Editing "${form.formTitle}"`);
        },

        previewSavedForm(formId) {
            const form = this.savedForms.find((f) => f.id === formId);
            if (!form) return;

            this.closeFormMenu();
            this.previewSnapshot = {
                formTitle: form.formTitle,
                settings: normalizeSettings(form.settings),
                fields: form.fields.map((f) => ({ colSpan: 1, ...f })),
            };
            this.initPreviewValues();
            this.previewMode = true;
            document.body.style.overflow = 'hidden';
        },

        deleteSavedForm(formId) {
            this.closeFormMenu();
            if (!confirm('Delete this saved form permanently?')) return;
            this.savedForms = this.savedForms.filter((f) => f.id !== formId);
            this.persistSavedForms();
            if (this.currentFormId === formId) {
                this.resetEditor();
                this.activeTab = 'myforms';
            }
            this.showToast('Form deleted');
        },

        createNewForm() {
            if (this.editorActive && this.isDirty && !confirm('Start a new form? Unsaved changes will be lost.')) return;
            this.resetEditor();
            this.openEditor();
            this.showToast('Choose a layout to begin your form');
        },

        formatDate(iso) {
            return formatDate(iso);
        },

        openPreview() {
            this.previewSnapshot = null;
            this.initPreviewValues();
            this.previewMode = true;
            document.body.style.overflow = 'hidden';
        },

        closePreview() {
            this.previewMode = false;
            this.previewSnapshot = null;
            this.previewSubmitted = false;
            document.body.style.overflow = '';
        },

        handlePreviewSubmit() {
            const data = {};
            this.activePreviewFields.forEach((field) => {
                if (['title', 'description', 'newline', 'pagebreak'].includes(field.type)) return;
                if (field.type === 'state-city') {
                    data[field.label] = {
                        state: this.previewValues[field.id + '_state'],
                        city: this.previewValues[field.id + '_city'],
                    };
                } else {
                    data[field.label] = this.previewValues[field.id];
                }
            });
            console.log('Preview submission:', data);
            this.previewSubmitted = true;
            this.showToast('Form submitted successfully!');
        },

        resetPreview() {
            this.initPreviewValues();
            this.previewSubmitted = false;
        },

        showToast(message) {
            this.toast = message;
            clearTimeout(this._toastTimer);
            this._toastTimer = setTimeout(() => { this.toast = null; }, 2800);
        },

        persist() {
            this.persistDraft();
        },

        persistDraft() {
            if (!this.editorActive) return;

            localStorage.setItem(DRAFT_KEY, JSON.stringify({
                ...snapshot(this),
                currentFormId: this.currentFormId,
                editorActive: this.editorActive,
                activeTab: this.activeTab,
                paletteTab: this.paletteTab,
                selectedFieldId: this.selectedFieldId,
            }));
        },

        persistSavedForms() {
            localStorage.setItem(FORMS_LIST_KEY, JSON.stringify(this.savedForms));
        },

        loadSavedForms() {
            try {
                const raw = localStorage.getItem(FORMS_LIST_KEY);
                this.savedForms = raw ? JSON.parse(raw) : [];
            } catch {
                this.savedForms = [];
            }
        },

        restoreSession() {
            try {
                const raw = localStorage.getItem(DRAFT_KEY);
                if (!raw) return;

                const data = JSON.parse(raw);
                if (!data.editorActive) return;

                this.formTitle = data.formTitle ?? this.formTitle;
                this.settings = normalizeSettings(data.settings);
                this.fields = (data.fields ?? []).map((f) => ({ colSpan: 1, ...f }));
                this.currentFormId = data.currentFormId ?? null;
                this.editorActive = true;
                const tab = data.activeTab === 'settings' ? 'editor' : data.activeTab;
                this.activeTab = ['editor', 'myforms'].includes(tab) ? tab : 'editor';
                const palette = data.paletteTab === 'settings' ? 'appearance' : data.paletteTab;
                this.paletteTab = ['add', 'options', 'appearance'].includes(palette) ? palette : 'add';
                this.selectedFieldId = data.selectedFieldId ?? null;

                if (this.fields.length > 0 || this.settings.templateSelected) {
                    this.settings.templateSelected = true;
                }
            } catch {
                localStorage.removeItem(DRAFT_KEY);
            }
        },

        migrateLegacyStorage() {
            try {
                const raw = localStorage.getItem(LEGACY_KEY);
                if (!raw || this.savedForms.length > 0) return;
                const data = JSON.parse(raw);
                const id = crypto.randomUUID();
                this.savedForms = [formRecord(id, {
                    formTitle: data.formTitle ?? 'Untitled Form',
                    settings: data.settings ?? DEFAULT_SETTINGS,
                    fields: data.fields ?? [],
                })];
                this.persistSavedForms();
                localStorage.removeItem(LEGACY_KEY);
            } catch {
                localStorage.removeItem(LEGACY_KEY);
            }
        },

        hasOptions(type) {
            return ['dropdown', 'radio', 'checkbox'].includes(type);
        },

        hasPlaceholder(type) {
            return ['text', 'textarea', 'number', 'email', 'phone', 'city'].includes(type);
        },

        hasMinMax(type) {
            return ['text', 'textarea'].includes(type);
        },

        hasDefaultValue(type) {
            return ['text', 'number', 'email', 'hidden'].includes(type);
        },

        canSetColSpan(type) {
            return !FULL_WIDTH_TYPES.includes(type);
        },

        getFieldMeta(type) {
            for (const cat of this.fieldCategories) {
                const found = cat.fields.find((f) => f.type === type);
                if (found) return found;
            }
            return { type, label: type, icon: '?' };
        },
    };
}
