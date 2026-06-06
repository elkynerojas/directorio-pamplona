<div
    x-data="{
        selected: $wire.entangle('data.icon').live,
        search: '',
        icons: [
            'store','shopping','cart','cart-outline','shopping-outline','tag','tag-outline','package','package-variant','package-variant-closed',
            'food','food-fork-drink','food-variant','food-apple','coffee','coffee-outline','pizza','cake','cake-variant','silverware-fork-knife','silverware','hamburger','cupcake','ice-cream','noodles','bread-slice','egg-fried',
            'hospital','hospital-box','medical-bag','pill','pill-multiple','stethoscope','heart','heart-pulse','heart-outline','needle','tooth','tooth-outline','eye','brain','bandage','ambulance','pharmacy',
            'school','book','book-open','book-outline','pencil','pencil-box','desk','pen','notebook','graduation-cap','library','teach',
            'car','car-outline','bus','truck','taxi','motorcycle','bicycle','airplane','train','ferry','fuel','gas-station','steering','car-wrench',
            'home','home-outline','hotel','bed','bed-outline','sofa','couch','apartment','office-building','office-building-outline','city','domain',
            'briefcase','briefcase-outline','account-tie','handshake','scale-balance','gavel','cash','currency-usd','bank','bank-outline','calculator','chart-line','file-document','printer',
            'wrench','hammer','tools','screwdriver','cog','cog-outline','pipe','broom','vacuum','washing-machine','fridge','television',
            'scissors','lipstick','hair-dryer','spa','nail-polish','face-woman','mirror','lotion-plus',
            'music','music-note','movie','gamepad','gamepad-variant','theater','soccer','basketball','tennis','swimming','dumbbell','yoga','ticket',
            'laptop','computer','phone','cellphone','tablet','printer-outline','headphones','television-play','wifi','bluetooth','chip','monitor',
            'flower','leaf','tree','nature','paw','cat','dog','horse','fish','barn',
            'image','camera','brush','palette','art','draw',
            'account','account-group','people','human','human-male-female','baby','baby-carriage',
            'recycle','earth','water','fire','snowflake','umbrella','weather-sunny','weather-night',
            'map-marker','map','compass','navigation','flag','star','star-outline','crown','shield','key','lock','unlock'
        ],
        get filtered() {
            if (!this.search) return this.icons;
            return this.icons.filter(i => i.includes(this.search.toLowerCase().trim()));
        }
    }"
    class="col-span-full"
>
    <div class="mb-3">
        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 mb-2">
            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Ícono</span>
        </label>

        {{-- Preview del ícono seleccionado --}}
        <div class="flex items-center gap-3 mb-3 p-3 rounded-lg bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-white dark:bg-white/10 border border-gray-200 dark:border-white/10">
                <i x-show="selected" :class="'mdi ' + selected" style="font-size: 1.75rem; color: #C0252D;"></i>
                <span x-show="!selected" class="text-gray-400 text-xs">—</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="selected || 'Ninguno seleccionado'"></p>
                <button
                    x-show="selected"
                    type="button"
                    @click="selected = ''"
                    class="text-xs text-red-500 hover:text-red-700 mt-0.5"
                >Quitar ícono</button>
            </div>
        </div>

        {{-- Búsqueda --}}
        <input
            type="text"
            x-model="search"
            placeholder="Buscar ícono..."
            class="w-full rounded-lg border border-gray-300 dark:border-white/20 bg-white dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 mb-3"
        />

        {{-- Grid de íconos --}}
        <div class="border border-gray-200 dark:border-white/10 rounded-lg overflow-y-auto" style="max-height: 320px;">
            <div class="grid p-2" style="grid-template-columns: repeat(auto-fill, minmax(60px, 1fr)); gap: 4px;">
                <template x-for="icon in filtered" :key="icon">
                    <button
                        type="button"
                        @click="selected = 'mdi-' + icon"
                        :title="'mdi-' + icon"
                        :class="{
                            'flex flex-col items-center justify-center p-2 rounded-lg cursor-pointer transition-all gap-1 hover:bg-primary-50 dark:hover:bg-white/10': true,
                            'ring-2 ring-primary-500 bg-primary-50 dark:bg-white/10': selected === 'mdi-' + icon,
                            'bg-transparent': selected !== 'mdi-' + icon
                        }"
                    >
                        <i :class="'mdi mdi-' + icon" style="font-size: 1.5rem; color: inherit;"></i>
                        <span class="text-gray-500 dark:text-gray-400 leading-none" style="font-size: 9px; max-width: 56px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" x-text="icon"></span>
                    </button>
                </template>
                <div x-show="filtered.length === 0" class="col-span-full py-8 text-center text-sm text-gray-400">
                    Sin resultados para "<span x-text="search"></span>"
                </div>
            </div>
        </div>
    </div>
</div>
