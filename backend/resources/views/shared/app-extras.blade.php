{{-- Componentes de la aplicación: campana personal y pie institucional. --}}
<link rel="stylesheet" href="{{ asset('css/app-extras.css') }}">
@include('shared.notification-center')
@include('shared.app-footer')
<script src="{{ asset('js/app-extras.js') }}" defer></script>
{{-- Revisión ortográfica del navegador para campos de escritura libre, sin alterar su contenido. --}}
<script>
(() => {
    'use strict';

    const textFields = 'textarea, input[type="text"], input:not([type]), [contenteditable="true"], [contenteditable="plaintext-only"]';
    const technicalFields = /(?:^|[\s_-])(rut|dni|username|login|codigo|code|folio|token|email|correo)(?:$|[\s_-])/i;

    function enableSpellcheck(field) {
        if (field.hasAttribute('spellcheck') || field.readOnly || field.disabled) return;

        if (field instanceof HTMLInputElement && technicalFields.test(`${field.name} ${field.id}`)) return;

        field.setAttribute('spellcheck', 'true');
        if (!field.hasAttribute('lang')) field.setAttribute('lang', 'es-CL');
    }

    function enableExistingFields() {
        document.querySelectorAll(textFields).forEach(enableSpellcheck);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', enableExistingFields, { once: true });
    } else {
        enableExistingFields();
    }

    // También cubre campos que se crean dinámicamente al abrir formularios o modales.
    document.addEventListener('focusin', (event) => {
        const field = event.target;
        if (field instanceof HTMLElement && field.matches(textFields)) enableSpellcheck(field);
    }, true);
})();
</script>
