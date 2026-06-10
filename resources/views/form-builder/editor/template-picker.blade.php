<div x-show="!settings.templateSelected" class="mx-auto w-full max-w-3xl flex-1 overflow-y-auto py-6">
    <div class="text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-100 text-2xl">📐</div>
        <h2 class="text-2xl font-bold text-slate-900">Step 1 — Choose your layout</h2>
        <p class="mt-2 text-slate-500">Pick how many columns your form should have. Change layout anytime in the <strong>Appearance</strong> panel.</p>
    </div>
    <div class="mt-8 grid gap-4 sm:grid-cols-3">
        <template x-for="t in gridTemplates" :key="t.cols">
            <button @click="selectTemplate(t.cols)"
                class="group rounded-2xl border-2 border-slate-200 bg-white p-6 text-left shadow-sm transition hover:border-indigo-400 hover:shadow-xl">
                <div class="flex gap-1.5">
                    <template x-for="p in t.preview" :key="p">
                        <div class="h-14 flex-1 rounded-xl bg-gradient-to-b from-indigo-100 to-indigo-200 transition group-hover:from-indigo-200 group-hover:to-indigo-300"></div>
                    </template>
                </div>
                <p class="mt-5 text-base font-bold text-slate-800" x-text="t.label"></p>
                <p class="mt-1 text-xs text-slate-500" x-text="t.desc"></p>
            </button>
        </template>
    </div>
</div>
