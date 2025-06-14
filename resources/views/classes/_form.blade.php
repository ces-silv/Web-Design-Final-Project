
{{-- resources/views/classes/_form.blade.php --}}
@php
    // Determine if we are in edit mode
    $isEdit = isset($class) && $class !== null;

    // Prepare initial groups array: either old input, existing model data, or a single empty group
    $initialGroups = old('groups',
        $isEdit
            ? $class->groups->map(function($g) {
                return [
                    'id'            => $g->id,
                    'professor_id'  => $g->professor_id,
                    'days'          => $g->days->map(function($d) {
                        return [
                            'id'               => $d->id,
                            'weekday'          => $d->weekday,
                            'start_time'       => $d->start_time,
                            'duration_in_min'  => $d->duration_in_min,
                        ];
                    })->toArray(),
                ];
            })->toArray()
            : [
                [
                    'id'           => '',
                    'professor_id' => '',
                    'days'         => [
                        ['id'=>'','weekday'=>'','start_time'=>'','duration_in_min'=>'']
                    ],
                ],
            ]
    );
@endphp

<form action="{{ $isEdit ? route('classes.update', $class->id) : route('classes.store') }}"
      method="POST"
      class="space-y-6"
      novalidate
      autocomplete="off"
      x-data="classForm()">

    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    {{-- Class Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
            {{ __('Nombre de la clase') }}
        </label>
        <input type="text"
               name="name"
               id="name"
               value="{{ old('name', $class->name ?? '') }}"
               placeholder="e.g.: Física 101"
               class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 placeholder-[#0b545b]/50 dark:placeholder-[#ffffff]/20 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focurs:border-[#31c0d3] transition"/>
        <div class="@error('name') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>@error('name'){{ $message }}@enderror</span>
        </div>
    </div>

    {{-- Faculty --}}
    <div>
        <label for="faculty_id" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
            {{ __('Facultad') }}
        </label>
        <select name="faculty_id"
                id="faculty_id"
                class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focurs:border-[#31c0d3] transition">
            <option value="">{{ __('— Selecciona una facultad —') }}</option>
            @foreach($faculties as $faculty)
                <option value="{{ $faculty->id }}"
                        {{ old('faculty_id', $class->faculty_id ?? '') == $faculty->id ? 'selected' : '' }}>
                    {{ $faculty->name }}
                </option>
            @endforeach
        </select>
        <div class="@error('faculty_id') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>@error('faculty_id'){{ $message }}@enderror</span>
        </div>
    </div>

    {{-- Dynamic Groups and Days --}}
    <div class="space-y-4">
        <template x-for="(group, groupIndex) in groups" :key="groupIndex">
            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg space-y-4">
                {{-- Hidden Group ID --}}
                <input type="hidden"
                       :name="`groups[${groupIndex}][id]`"
                       :value="group.id">

                {{-- Professor Selector --}}
                <div>
                    <label class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
                        {{ __('Profesor') }}
                    </label>
                    <select :name="`groups[${groupIndex}][professor_id]`"
                            x-model="group.professor_id"
                            class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] transition">
                        <option value="">{{ __('— Selecciona un profesor —') }}</option>
                        @foreach($professors as $prof)
                            <option value="{{ $prof->id }}">{{ $prof->first_name }} {{ $prof->last_name }}</option>
                        @endforeach
                    </select>
                    <div class="text-red-600 dark:text-red-400 text-xs mt-1 hidden"
                         x-show="! group.professor_id">
                        {{ __('El profesor es requerido') }}
                    </div>
                </div>

                {{-- Days for this Group --}}
                <div class="space-y-3">
                    <template x-for="(day, dayIndex) in group.days" :key="dayIndex">
                        <div class="grid grid-cols-6 gap-4 items-end">
                            {{-- Hidden Day ID --}}
                            <input type="hidden"
                                   :name="`groups[${groupIndex}][days][${dayIndex}][id]`"
                                   :value="day.id">

                            {{-- Weekday --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
                                {{ __('Día') }}
                            </label>
                            <select
                                :name="`groups[${groupIndex}][days][${dayIndex}][weekday]`"
                                x-model="day.weekday"
                                class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] transition"
                            >
                                <option value="" disabled>{{ __('— Selecciona un día —') }}</option>
                                <option value="0">{{ __('Domingo') }}</option>
                                <option value="1">{{ __('Lunes') }}</option>
                                <option value="2">{{ __('Martes') }}</option>
                                <option value="3">{{ __('Miércoles') }}</option>
                                <option value="4">{{ __('Jueves') }}</option>
                                <option value="5">{{ __('Viernes') }}</option>
                                <option value="6">{{ __('Sábado') }}</option>
                            </select>
                            <div
                                class="text-red-600 dark:text-red-400 text-xs mt-1 hidden"
                                x-show="day.weekday === ''"
                            >
                                {{ __('El día es requerido') }}
                            </div>
                        </div>

                            {{-- Start Time --}}
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
                                    {{ __('Hora de Inicio') }}
                                </label>
                                <input type="time"
                                       :name="`groups[${groupIndex}][days][${dayIndex}][start_time]`"
                                       x-model="day.start_time"
                                       class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] transition"/>
                            </div>

                            {{-- Duration --}}
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
                                    {{ __('Duración (min)') }}
                                </label>
                                <input type="number"
                                       min="1"
                                       :name="`groups[${groupIndex}][days][${dayIndex}][duration_in_min]`"
                                       x-model="day.duration_in_min"
                                       class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] transition"/>
                            </div>

                            {{-- Remove Day --}}
                            <div class="col-span-6 text-right">
                                <button type="button"
                                        @click="removeDay(group, dayIndex)"
                                        class="text-sm font-medium text-red-600 hover:underline transition">
                                    {{ __('Eliminar Día') }}
                                </button>
                            </div>
                        </div>
                    </template>

                    {{-- Add Day --}}
                    <button type="button"
                            @click="addDay(group)"
                            class="inline-flex items-center gap-2 px-3 py-2 bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white text-sm font-medium rounded-lg transition">
                        <i class="fas fa-calendar-plus"></i>
                        {{ __('Agregar Día') }}
                    </button>
                </div>

                {{-- Remove Group --}}
                <div class="text-right">
                    <button type="button"
                            @click="removeGroup(groupIndex)"
                            class="text-sm font-medium text-red-600 hover:underline transition">
                        {{ __('Eliminar Grupo') }}
                    </button>
                </div>
            </div>
        </template>

        {{-- Add Group --}}
        <button type="button"
                @click="addGroup()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white font-medium rounded-lg transition">
            <i class="fas fa-layer-group"></i>
            {{ __('Agregar Grupo') }}
        </button>
    </div>

    {{-- Submit & Cancel --}}
    <div class="flex flex-col gap-3">
        <button type="submit"
                class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-[#31c0d3] dark:focus:ring-offset-gray-900 transition-all">
            <i class="fas fa-check"></i>
            {{ $isEdit ? __('Actualizar Clase') : __('Crear Clase') }}
        </button>
        <a href="{{ route('classes.index') }}"
           class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 text-[#231f20] dark:text-gray-300 hover:bg-[#31c0d3]/10 dark:hover:bg-gray-700 rounded-lg transition">
            <i class="fas fa-arrow-left"></i>
            {{ __('Cancelar') }}
        </a>
    </div>
</form>

{{-- Alpine.js Component Definition --}}
<script>
    function classForm() {
        return {
            // Seed initial groups from Blade
            groups: @json($initialGroups),

            // Add a new empty group
            addGroup() {
                this.groups.push({
                    id: '',
                    professor_id: '',
                    days: [{ id: '', weekday: '', start_time: '', duration_in_min: '' }]
                });
            },

            // Remove a group at index
            removeGroup(index) {
                this.groups.splice(index, 1);
            },

            // Add a new empty day to a specific group
            addDay(group) {
                group.days.push({ id: '', weekday: '', start_time: '', duration_in_min: '' });
            },

            // Remove a day from a group
            removeDay(group, index) {
                group.days.splice(index, 1);
            }
        }
    }
</script>
<style>
input[type="time"]::-webkit-calendar-picker-indicator {
    display: none;
}
</style>
