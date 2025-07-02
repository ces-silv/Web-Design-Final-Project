<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#31c0d3]/20 dark:bg-[#0b545b]/30">
                    <i class="fa-solid fa-file-circle-check text-xl text-[#31c0d3] dark:text-[#31c0d3]"></i>
                </span>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">
                    {{ __('Justificaciones') }}
                </h2>
            </div>
            <a href="{{ route('justifications.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#31c0d3] dark:focus:ring-offset-gray-900 transition">
                <i class="fas fa-plus"></i>
                {{ __('Nueva Justificación') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12 bg-gradient-to-r from-[#31c0d3]/10 to-[#0b545b]/10 dark:from-gray-900 dark:to-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <form method="GET" action="{{ route('justifications.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between mb-6 gap-4">
                <div class="relative flex-1 max-w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#31c0d3] dark:text-[#31c0d3]">
                        <i class="fas fa-search"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="{{ __('Buscar justificaciones') }}"
                        class="w-full pl-10 pr-16 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focus:border-[#31c0d3] transition"
                    />
                    <button
                        type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-[#31c0d3] hover:bg-[#0b545b] text-white rounded-lg transition flex items-center gap-1 shadow"
                        title="{{ __('Buscar') }}"
                    >
                        <i class="fas fa-arrow-right"></i>
                        <span class="sr-only">{{ __('Buscar') }}</span>
                    </button>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto bg-white/80 dark:bg-gray-800/800 shadow-xl rounded-xl">
                <table class="min-w-full divide-y divide-[#0b545b]/20 dark:divide-gray-700">
                    <thead class="bg-[#31c0d3] dark:bg-[#0b545b]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                {{ __('Clase') }}
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                {{ __('Periodo') }}
                            </th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-white">
                                {{ __('Acciones') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-[#0b545b]/20 dark:divide-gray-700">
                        @forelse ($justifications as $justification)
                            <tr>
                                <td class="px-6 py-4 text-[#231f20] dark:text-gray-300">
                                    {{ $justification->class->name }}
                                    <div class="text-sm text-[#0b545b] dark:text-[#31c0d3]">
                                        {{ $justification->class->faculty->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[#231f20] dark:text-gray-300">
                                    {{ \Illuminate\Support\Carbon::parse($justification->start_date)->format('d/m/Y') }} -
                                    {{ \Illuminate\Support\Carbon::parse($justification->end_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col md:flex-row items-center justify-center gap-2">
                                        <!-- View Button -->
                                        <a href="{{ route('justifications.show', $justification) }}"
                                           title="{{ __('Ver') }}"
                                           class="w-8 h-8 flex items-center justify-center bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white rounded-full transition">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('justifications.edit', $justification) }}"
                                           title="{{ __('Editar') }}"
                                           class="w-8 h-8 flex items-center justify-center bg-yellow-500 hover:bg-yellow-400 text-white rounded-full transition">
                                            <i class="fas fa-pen text-sm"></i>
                                        </a>

                                        <!-- Delete Button -->
                    @if(Auth::user()->role === "admin")
                                        <form action="{{ route('justifications.destroy', $justification) }}"
                                              method="POST"
                                              id="delete-justification-{{ $justification->id }}"
                                              class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center bg-red-600 hover:bg-red-500 text-white rounded-full transition"
                                                    title="{{ __('Eliminar') }}">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>

                                        <div x-data="{ open{{ $justification->id }} : false }">
                                            <button @click="open{{ $justification->id }} = true" type="submit"
                                                    class="w-8 h-8 flex bg-green-700 hover:bg-green-500 items-center justify-center text-white rounded-full transition"
                                                    title="{{ __('Cambiar Estado') }}">
                                                <i class="fa  fa-wheelchair-alt"></i>
                                            </button>
                                            <div x-show="open{{ $justification->id }}" @click.outside="open{{ $justification->id }} = false" x-cloak x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
<div @click.outside="open{{ $justification->id }} = false"
                                                class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white w-full max-w-2xl mx-4 p-6 rounded shadow-lg overflow-y-auto max-h-[90vh] transition-colors">
          <form
                action="{{ route('justifications.updateStatus', $justification) }}"
                method="POST"
            >
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label for="status-{{ $justification->id }}" class="block text-sm font-medium">
                        Estado
                    </label>
                    <select
                        id="status-{{ $justification->id }}"
                        name="status"
                        class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >
<option
            value="{{ \App\Models\Justification::STATUS_PENDING }}"
            @selected($justification->status === \App\Models\Justification::STATUS_PENDING)
          >En Proceso</option>
          <option
            value="{{ \App\Models\Justification::STATUS_APPROVED }}"
            @selected($justification->status === \App\Models\Justification::STATUS_APPROVED)
          >Aceptada</option>
          <option
            value="{{ \App\Models\Justification::STATUS_REJECTED }}"
            @selected($justification->status === \App\Models\Justification::STATUS_REJECTED)
          >Rechazada</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button
                        type="button"
                        @click="open{{ $justification->id }} = false"
                        class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 rounded bg-green-600 hover:bg-green-500 text-white"
                    >
                        Guardar
                    </button>
                </div>
            </form>

                    @endif

                        <!--

                        THIS IS A BLADE.PHP FILE YOU HAVE ACCESS TO THE $justification
                        PUT A SELECT COMPONENT to update the statuses 'En Proceso' 'Aceptada' & 'Rechazada'

class Justification extends Model
{
    protected $fillable = [
        'description',
        'start_date',
        'end_date',
        'university_class_id',
        'student_id',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Constantes para los estados
    const STATUS_PENDING = 'En Proceso';
    const STATUS_APPROVED = 'Aceptada';
    const STATUS_REJECTED = 'Rechazada';
}

     */
    public function update(Request $request, Justification $justification)
    {
        if ($justification->student_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'university_class_id' => [
                'required',
                'exists:classes,id',
                function ($attribute, $value, $fail) use ($request) {
                    $start = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);

                    $days = [];
                    for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                        $days[] = $date->dayOfWeek; // 0=domingo, 6=sábado (Carbon)
                    }

                    $hasValidDays = ClassGroup::where('class_id', $value)
                        ->whereHas('days', function($q) use ($days) {
                            $q->whereIn('weekday', array_unique($days));
                        })->exists();

                    if (!$hasValidDays) {
                        $fail('La clase seleccionada no tiene horarios en las fechas indicadas.');
                    }
                }
            ],
            'documents.*' => 'sometimes|file|max:2048|mimes:pdf,jpg,png',
            'remove_documents' => 'sometimes|array',
            'remove_documents.*' => 'exists:justification_documents,id'
        ], [
            'documents.*.file' => 'El archivo debe ser válido.',
            'documents.*.max' => 'Cada documento no puede superar 2MB.',
            'documents.*.mimes' => 'Solo se permiten archivos PDF, JPG y PNG.'
        ]);

        DB::transaction(function () use ($data, $request, $justification) {
            $justification->update([
                'description' => $data['description'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'university_class_id' => $data['university_class_id']
            ]);

            // Eliminar documentos marcados para eliminar
            if ($request->has('remove_documents')) {
                foreach ($request->remove_documents as $documentId) {
                    $document = $justification->documents()->find($documentId);
                    if ($document) {
                        Storage::disk('public')->delete($document->file_path);
                        $document->delete();
                    }
                }
            }

            // Agregar nuevos documentos
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('justifications', 'public');

                    $justification->documents()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);
                }
            }
        });

        return redirect()->route('justifications.index')
            ->with('alert', [
                'type' => 'success',
                'message' => 'Justificación actualizada exitosamente.'
            ]);
    }


                        --->
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-400">
                                    {{ __('No hay justificaciones registradas.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $justifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
{{-- SweetAlert de éxito --}}
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#31c0d3'
            });
        });
    </script>
@endif
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
