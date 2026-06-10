<div class="flex shrink-0 items-center gap-2 border-b border-violet-900/20 bg-violet-700 p-2 lg:hidden">
    <div class="flex flex-1 gap-1">
        <template x-for="item in navItems" :key="item.id">
            <button @click="switchTab(item.id)"
                :class="activeTab === item.id ? 'bg-white text-violet-700' : 'bg-white/15 text-white hover:bg-white hover:text-violet-700'"
                class="flex flex-1 flex-col items-center justify-center gap-1 rounded-lg py-2 text-xs font-bold transition">
                <template x-if="item.id === 'myforms'">
                    @include('form-builder.partials.icon', ['name' => 'myforms', 'class' => 'h-4 w-4'])
                </template>
                <template x-if="item.id === 'editor'">
                    @include('form-builder.partials.icon', ['name' => 'build', 'class' => 'h-4 w-4'])
                </template>
                <span x-text="item.label"></span>
            </button>
        </template>
    </div>
    <button @click="createNewForm()" title="New Form"
        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-white/60 bg-white/10 text-white shadow-sm">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    </button>
</div>
