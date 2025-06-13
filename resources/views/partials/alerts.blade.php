{{-- 
    Script centralizado para gestionar alertas y confirmaciones en todo el proyecto utilizando SweetAlert2, 
    basado en diferentes casos de uso
--}}
@push('scripts')
<script>
// Toast genérico
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    theme: 'auto',
    didOpen: t => {
        t.addEventListener('mouseenter', Swal.stopTimer);
        t.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

/**
 *  Modal de éxito al crear/editar/eliminar, 
 *  usando los mensajes especificados desde los métodos de los controladores:
 *     e.g.: ->with('alert', ['type'=>'success','message'=>'¡... creado/editado/eliminado correctamente!'])
 */
@if(session('alert'))
    Toast.fire({
        icon: '{{ session("alert.type") }}',
        title: @json(session('alert.message'))
    });
@endif

// Modales genéricas para confirmación de acciones
document.querySelectorAll('.swal-confirm').forEach(btn =>
    btn.addEventListener('click', e => {
        e.preventDefault();

        // Parámetros especificados en las vistas desde atributos data-*
        const title     = btn.dataset.title     || '¿Estás seguro?';
        const text      = btn.dataset.text      || '';
        const icon      = btn.dataset.icon      || 'warning';
        const confirm   = btn.dataset.confirm   || 'Sí'
        const cancel    = btn.dataset.cancel    || 'Cancelar';
        const formId    = btn.dataset.formId; // Id del formulario a enviar
        const form      = formId ? document.getElementById(formId) : btn.closest('form');

        Swal.fire({
            theme: 'auto',
            icon: icon,
            title: title,
            text: text,
            showCancelButton: true,
            confirmButtonText: confirm,
            cancelButtonText: cancel,
            confirmButtonColor: '#dc2626'
        }).then(res => res.isConfirmed && form.submit())
    })
);
</script>
@endpush