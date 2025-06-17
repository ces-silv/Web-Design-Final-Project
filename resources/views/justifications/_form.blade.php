@php
    $isEdit = isset($justification) && $justification !== null;
    $classes = $classes ?? [];
    if (!$isEdit) {
        $classes = [];
    }
@endphp

<form action="{{ $isEdit ? route('justifications.update', $justification->id) : route('justifications.store') }}"
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
                   @change="updateEndDate(); updateDates()"
                   value="{{ old('start_date', $justification->start_date ?? '') }}"
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
                   @change="updateDates()"
                   :min="startDate"
                   value="{{ old('end_date', $justification->end_date ?? '') }}"
                   class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] transition"/>
            <div class="@error('end_date') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>@error('end_date'){{ $message }}@enderror</span>
            </div>
        </div>
    </div>

    {{-- Día de la semana seleccionado --}}
    <div x-show="selectedWeekdays && selectedWeekdays.length" class="p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
        <p class="text-sm text-[#231f20] dark:text-gray-300">
            <span class="font-medium">{{ __('Días seleccionados:') }}</span>
            <span x-text="getWeekdayNames()"></span>
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

        {{-- Mensaje de carga --}}
        <div x-show="isLoading" class="mt-2 text-sm text-[#0b545b] dark:text-[#31c0d3]">
            <i class="fas fa-spinner fa-spin mr-2"></i> {{ __('Cargando clases disponibles...') }}
        </div>

        {{-- Mensaje de error --}}
        <div x-show="error" class="mt-2 text-sm text-red-600 dark:text-red-400" x-text="error"></div>

        {{-- Aquí va el mensaje de "no se encontraron clases" --}}
        <div x-show="!isLoading && availableClasses.length === 0 && selectedWeekdays && selectedWeekdays.length" class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
            {{ __('No se encontraron clases para el rango de fechas seleccionado.') }}
        </div>

        <div class="@error('university_class_id') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>@error('university_class_id'){{ $message }}@enderror</span>
        </div>
    </div>

    {{-- Documento Adjunto --}}
    <div>
        <label for="document" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
            {{ __('Documento de justificación') }}
            <span class="text-xs text-gray-500">(PDF, JPG o PNG, máximo 2MB)</span>
        </label>
        <input type="file" name="document" id="document"
               class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#31c0d3] file:text-white hover:file:bg-[#0b545b] dark:file:bg-[#0b545b] dark:hover:file:bg-[#31c0d3] transition"
               x-ref="documentInput"
               @change="updateDocumentPreview()"/>
        
        {{-- Vista previa del documento --}}
        <template x-if="documentPreview">
            <div class="mt-2 flex items-center gap-2 text-sm text-[#0b545b] dark:text-[#31c0d3]">
                <i class="fas fa-file-alt"></i>
                <span x-text="documentPreview.name"></span>
                <button type="button" @click="clearDocumentPreview()" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </template>
        
        {{-- Documento existente en edición --}}
        @if($isEdit && $justification->document)
            <div class="mt-2 flex items-center gap-2 text-sm text-[#0b545b] dark:text-[#31c0d3]">
                <i class="fas fa-file-alt"></i>
                <span>{{ $justification->document->file_name }}</span>
            </div>
        @endif
        
        <div class="@error('document') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>@error('document'){{ $message }}@enderror</span>
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

{{-- Alpine.js Component --}}
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('justificationForm', () => ({
        // Modelos
        description: @json(old('description', $justification->description ?? '')),
        startDate: @json(old('start_date', $justification->start_date ?? '')),
        endDate: @json(old('end_date', $justification->end_date ?? '')),
        university_class_id: @json(old('university_class_id', $justification->university_class_id ?? '')),
        availableClasses: [],
        isLoading: false,
        error: null,
        documentPreview: null,
        selectedWeekdays: [],

        init() {
            if (this.startDate && this.endDate) {
                this.fetchAvailableClasses();
            }
            if (this.university_class_id && !this.availableClasses.some(c => c.id == this.university_class_id)) {
                this.fetchClassDetails(this.university_class_id);
            }
        },

        updateEndDate() {
            if (this.startDate && this.endDate && new Date(this.endDate) < new Date(this.startDate)) {
                this.endDate = this.startDate;
            }
        },

        updateDates() {
            if (this.startDate && this.endDate) {
                this.updateSelectedWeekdays();
                this.fetchAvailableClasses();
            } else {
                this.availableClasses = [];
                this.selectedWeekdays = [];
            }
        },

        updateSelectedWeekdays() {
            this.selectedWeekdays = [];
            if (!this.startDate || !this.endDate) return;

            // Parse fechas como local, no UTC
            const parseLocalDate = (str) => {
                const [year, month, day] = str.split('-').map(Number);
                return new Date(year, month - 1, day);
            };

            let current = parseLocalDate(this.startDate);
            const end = parseLocalDate(this.endDate);

            while (current <= end) {
                const day = current.getDay();
                if (!this.selectedWeekdays.includes(day)) {
                    this.selectedWeekdays.push(day);
                }
                current.setDate(current.getDate() + 1);
            }
        },

        getWeekdayNames() {
            const names = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            return this.selectedWeekdays.map(day => names[day]).join(', ');
        },

        async fetchAvailableClasses() {
            if (!this.startDate || !this.endDate) {
                this.availableClasses = [];
                return;
            }

            this.isLoading = true;
            this.error = null;

            try {
                const response = await fetch('/available-classes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        start_date: this.startDate,
                        end_date: this.endDate
                    })
                });

                if (!response.ok) {
                    throw new Error('Error al cargar las clases');
                }

                const data = await response.json();
                this.availableClasses = data;

                if (this.university_class_id && !this.availableClasses.some(c => c.id == this.university_class_id)) {
                    this.university_class_id = '';
                }
            } catch (err) {
                this.error = 'No se pudieron cargar las clases para el rango de fechas seleccionado.';
                this.availableClasses = [];
            } finally {
                this.isLoading = false;
            }
        },

        async fetchClassDetails(classId) {
            try {
                const response = await fetch(`/api/classes/${classId}`);
                if (response.ok) {
                    const classData = await response.json();
                    if (!this.availableClasses.some(c => c.id == classData.id)) {
                        this.availableClasses.push(classData);
                    }
                }
            } catch (err) {
                console.error('Error al cargar detalles de la clase:', err);
            }
        },

        updateDocumentPreview() {
            const file = this.$refs.documentInput.files[0];
            if (file) {
                this.documentPreview = {
                    name: file.name,
                    size: file.size,
                    type: file.type
                };
            }
        },

        clearDocumentPreview() {
            this.$refs.documentInput.value = '';
            this.documentPreview = null;
        }
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