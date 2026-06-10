<aside class="hidden h-full w-64 shrink-0 flex-col border-r border-violet-900/20 bg-violet-700 lg:flex">
    <div class="border-b border-white/30 px-5 py-5">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-2xl bg-white shadow-lg shadow-violet-950/30">
                <img src="{{ asset('logo.png') }}" alt="FormCraft Logo" class="h-full w-full object-cover" />
            </div>
            <div>
                <p class="text-sm font-bold text-white">FormCraft</p>
                <p class="text-[10px] font-medium text-violet-200">Drag & Drop Builder</p>
            </div>
        </div>
    </div>
    <div class="shrink-0 px-3 mt-1 pb-3 pt-1">
        <button @click="createNewForm()"
            class="flex w-full items-center justify-center gap-2 rounded-xl border border-white/20 cursor-pointer bg-white/10 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-950/20 transition hover:border-white hover:bg-white/20 hover:shadow-lg">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Form
        </button>
    </div>
    <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto p-3 pt-1">
        <template x-for="item in navItems" :key="item.id">
            <button @click="switchTab(item.id)"
                :class="[
                    activeTab === item.id ? 'bg-white text-violet-700 shadow-sm' : 'text-violet-100 hover:bg-white hover:text-violet-700',
                    item.id === 'editor' && !editorActive ? 'opacity-50' : ''
                ]"
                class="group flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left transition">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl transition"
                    :class="activeTab === item.id ? 'bg-violet-100 text-violet-600' : 'bg-white/15 text-white group-hover:bg-violet-100 group-hover:text-violet-600'">
                    <template x-if="item.id === 'myforms'">
                        @include('form-builder.partials.icon', ['name' => 'myforms'])
                    </template>
                    <template x-if="item.id === 'editor'">
                        @include('form-builder.partials.icon', ['name' => 'build'])
                    </template>
                </span>
                <div class="min-w-0 flex-1">
                    <span class="block text-sm font-bold" x-text="item.label"></span>
                    <span class="block truncate text-[10px] transition"
                        :class="activeTab === item.id ? 'text-violet-400' : 'text-violet-200 group-hover:text-violet-400'"
                        x-text="item.id === 'editor' && !editorActive ? 'Start with New Form' : item.step"></span>
                </div>
            </button>
        </template>
    </nav>
    <div class="shrink-0 border-t border-white/10 p-4">
        <div class="rounded-xl bg-white/10 p-3 text-[10px] leading-relaxed text-violet-100">
            <p class="font-bold text-white">Shortcuts</p>
            <p class="mt-1">Ctrl+S Save · Ctrl+Z Undo · Ctrl+Y Redo</p>
        </div>
    </div>
</aside>
