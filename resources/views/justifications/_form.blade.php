@php
    $isEdit = isset($justification) && $justification && $justification->exists;
    $classes = $classes ?? [];
@endphp

<form action="{{ $isEdit ? route('justifications.update', $justification) : route('justifications.store') }}"
      method="POST"
      class="space-y-6"
      enctype="multipart/form-data"
      novalidate
      autocomplete="off"
      x-data="justificationForm()">

    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    {{-- Descripción --}}
    <div>
        <label for="description" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
            {{ __('Descripción de la justificación') }}
        </label>
        <textarea name="description" id="description" rows="4"
                  class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 placeholder-[#0b545b]/50 dark:placeholder-[#ffffff]/20 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focurs:border-[#31c0d3] transition"
                  placeholder="{{ __('Describe el motivo de tu justificación...') }}"
                  x-model="description">{{ old('description', $justification->description ?? '') }}</textarea>
        <div class="@error('description') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>@error('description'){{ $message }}@enderror</span>
        </div>
    </div>

    {{-- Rango de Fechas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Fecha de Inicio --}}
        <div>
            <label for="start_date" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
                {{ __('Fecha de inicio') }}
            </label>
            <input type="date" name="start_date" id="start_date"
                   x-model="startDate"
                   @change="updateEndDate(); updateWeekday()"
                   value="{{ old('start_date', $justification->start_date ? $justification->start_date->format('Y-m-d') : '') }}"
                   class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] transition"/>
            <div class="@error('start_date') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>@error('start_date'){{ $message }}@enderror</span>
            </div>
        </div>

        {{-- Fecha de Fin --}}
        <div>
            <label for="end_date" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
                {{ __('Fecha de fin') }}
            </label>
            <input type="date" name="end_date" id="end_date"
                   x-model="endDate"
                   @change="updateWeekday()"
                   :min="startDate"
                   value="{{ old('end_date', $justification->end_date ? $justification->end_date->format('Y-m-d') : '') }}"
                   class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] transition"/>
            <div class="@error('end_date') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>@error('end_date'){{ $message }}@enderror</span>
            </div>
        </div>
    </div>

    {{-- Día de la semana seleccionado --}}
    <div x-show="weekday !== null" class="p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
        <p class="text-sm text-[#231f20] dark:text-gray-300">
            <span class="font-medium">{{ __('Días seleccionados:') }}</span>
            <span x-text="getWeekdayNames(weekday)"></span>
        </p>
    </div>

    {{-- Selector de Clase --}}
    <div>
        <label for="university_class_id" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
            {{ __('Clase') }}
        </label>
        <select name="university_class_id" id="university_class_id" x-model="university_class_id"
                class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] transition"
                required>
            <option value="">{{ __('— Selecciona una clase —') }}</option>
            <template x-for="classItem in availableClasses" :key="classItem.id">
                <option :value="classItem.id" x-text="classItem.name + ' (' + classItem.faculty.name + ')'"></option>
            </template>
        </select>

        {{-- Mensajes de estado --}}
        <div x-show="isLoading" class="mt-2 text-sm text-[#0b545b] dark:text-[#31c0d3]">
            <i class="fas fa-spinner fa-spin mr-2"></i> {{ __('Cargando clases disponibles...') }}
        </div>
        <div x-show="error" class="mt-2 text-sm text-red-600 dark:text-red-400" x-text="error"></div>
        <div x-show="!isLoading && availableClasses.length === 0 && weekday !== null" class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
            {{ __('No se encontraron clases para el día seleccionado.') }}
        </div>

        <div class="@error('university_class_id') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>@error('university_class_id'){{ $message }}@enderror</span>
        </div>
    </div>

    {{-- Documentos Adjuntos --}}
    <div>
        <label for="documents" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
            {{ __('Documentos de justificación') }}
            <span class="text-xs text-gray-500">(PDF, JPG o PNG, máximo 2MB cada uno)</span>
        </label>
        <input type="file" name="documents[]" id="documents" multiple
               class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#31c0d3] file:text-white hover:file:bg-[#0b545b] dark:file:bg-[#0b545b] dark:hover:file:bg-[#31c0d3] transition"
               x-ref="documentsInput"
               @change="updateDocumentsPreview()"/>

        {{-- Vista previa de documentos nuevos --}}
        <template x-if="documentsPreview.length > 0">
            <div class="mt-2 space-y-1">
                <template x-for="(doc, index) in documentsPreview" :key="index">
                    <div class="flex items-center gap-2 text-sm text-[#0b545b] dark:text-[#31c0d3]">
                        <i class="fas fa-file-alt"></i>
                        <span x-text="doc.name"></span>
                        <button type="button" @click="removeDocumentPreview(index)" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </template>
            </div>
        </template>

        {{-- Documentos existentes en edición --}}
        @if($isEdit && $justification->documents->count() > 0)
            <div class="mt-3">
                <label class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-2">
                    {{ __('Documentos existentes') }}
                </label>
                <div class="space-y-2">
                    @foreach($justification->documents as $document)
                        <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-file text-[#31c0d3] dark:text-[#31c0d3]"></i>
                                <span class="text-sm text-[#231f20] dark:text-gray-100">{{ $document->file_name }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">({{ number_format($document->size / 1024, 1) }} KB)</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('justifications.download', ['justification' => $justification, 'document' => $document]) }}"
                                   class="text-[#31c0d3] hover:text-[#0b545b] dark:text-[#31c0d3] dark:hover:text-[#0b545b] transition"
                                   title="{{ __('Descargar') }}">
                                    <i class="fas fa-download text-sm"></i>
                                </a>
                                <label class="flex items-center gap-1 text-red-500 hover:text-red-700 cursor-pointer">
                                    <input type="checkbox" name="remove_documents[]" value="{{ $document->id }}" class="sr-only">
                                    <i class="fas fa-trash text-sm"></i>
                                    <span class="text-xs">{{ __('Eliminar') }}</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="@error('documents.*') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>@error('documents.*'){{ $message }}@enderror</span>
        </div>
    </div>

    {{-- Botones de Envío y Cancelar --}}
    <div class="flex flex-col gap-3">
        <button type="submit"
                class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-[#31c0d3] dark:focus:ring-offset-gray-900 transition-all"
                :disabled="isLoading">
            <i class="fas fa-check"></i>
            <span x-show="!isLoading">{{ $isEdit ? __('Actualizar Justificación') : __('Crear Justificación') }}</span>
            <span x-show="isLoading">{{ __('Procesando...') }}</span>
        </button>
        <a href="{{ route('justifications.index') }}"
           class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 text-[#231f20] dark:text-gray-300 hover:bg-[#31c0d3]/10 dark:hover:bg-gray-700 rounded-lg transition">
            <i class="fas fa-arrow-left"></i>
            {{ __('Cancelar') }}
        </a>
    </div>
</form>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('justificationForm', () => ({
        // 1) Raw data never changes
        allClasses: @json($classes),

        // 2) What the <select> shows
        availableClasses: [],

        // form state
        description: @json(old('description', $justification->description ?? '')),
        startDate:   @json(old('start_date',   $justification->start_date?->format('Y-m-d') ?? '')),
        endDate:     @json(old('end_date',     $justification->end_date?->format('Y-m-d')   ?? '')),
        university_class_id: @json(old('university_class_id', (string)($justification->university_class_id ?? ''))),
        weekday: null,
        documentsPreview: [],

        init() {
            // On edit, seed the initial filter
            if (this.startDate && this.endDate) {
                this.updateWeekday();
            }
        },

        updateEndDate() {
            if (this.startDate && this.endDate
                && new Date(this.endDate) < new Date(this.startDate)) {
                this.endDate = this.startDate;
            }
        },

        updateWeekday() {
            if (!this.startDate || !this.endDate) {
                this.weekday = null;
                this.availableClasses = [];
                return;
            }

            // 3) Build a Set of all weekdays in the range
            const [y1,m1,d1] = this.startDate.split('-').map(Number);
            const [y2,m2,d2] = this.endDate.split('-').map(Number);
            let s = new Date(y1, m1 - 1, d1),
                e = new Date(y2, m2 - 1, d2),
                days = new Set();

            while (s <= e) {
                days.add(s.getDay());
                s.setDate(s.getDate() + 1);
            }

            this.weekday = Array.from(days);

            // 4) Filter allClasses by group.days ∩ selected weekdays
            this.availableClasses = this.allClasses.filter(c =>
                c.groups.some(g =>
                    g.days.some(d => days.has(Number(d.weekday)))
                )
            );

            // 5) Preserve existing selection when editing
            if (this.university_class_id) {
                const kept = this.allClasses.find(c => c.id == this.university_class_id);
                if (kept && !this.availableClasses.some(c => c.id == kept.id)) {
                    this.availableClasses.push(kept);
                }
            }
        },

        getWeekdayNames(arr) {
            const names = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
            return arr.map(i => names[i]).join(', ');
        },

        updateDocumentsPreview() {
            const files = this.$refs.documentsInput.files;
            this.documentsPreview = Array.from(files).map(f => ({
                name: f.name, size: f.size, type: f.type
            }));
        },

        removeDocumentPreview(i) {
            this.documentsPreview.splice(i, 1);
        },
    }));
});
</script>

<style>
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(0.5);
}
.dark input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
}
</style>
