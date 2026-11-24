<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acciones de enfermería | Ficha Clínica TP</title>

    <link rel="stylesheet" href="{{ asset('css/estudiante-admision.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-triage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-formularios.css') }}">

    <style>
        .anamnesis-container {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 12px;
            margin-top: 18px;
            align-items: stretch;
        }

        .anamnesis-card {
            grid-column: span 2;
            min-width: 0;
            background: #fff;
            border: 1px solid #dce5ef;
            border-radius: 9px;
            padding: 12px;
            box-shadow: 0 2px 9px rgba(15, 42, 76, .045);
        }

        .anamnesis-card.wide {
            grid-column: span 3;
        }

        .anamnesis-header {
            padding: 9px 12px;
            border-radius: 5px;
            margin-bottom: 12px;
        }

        .anamnesis-header h2 {
            margin: 0;
            font-size: 14px;
            font-weight: 750;
            color: #12416e;
        }

        .anamnesis-header.rosa {
            background: #fce6eb;
        }

        .anamnesis-header.azul {
            background: #e0edff;
        }

        .anamnesis-header.naranja {
            background: #fff0d8;
        }

        .anamnesis-header.lila {
            background: #eee5ff;
        }

        .anamnesis-header.verde {
            background: #ddf7eb;
        }

        .anamnesis-header.celeste {
            background: #deebff;
        }

        .anamnesis-header.rosado {
            background: #fce1e9;
        }

        .anamnesis-header.menta {
            background: #d9f5eb;
        }

        .anamnesis-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 0;
            margin-bottom: 10px;
        }

        .anamnesis-field label,
        .anamnesis-label {
            font-size: 12px;
            font-weight: 650;
            color: #25354b;
        }

        .anamnesis-field input:not([type=radio]),
        .anamnesis-field select,
        .anamnesis-field textarea {
            box-sizing: border-box;
            width: 100%;
            min-width: 0;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #fff;
            color: #172a41;
            padding: 9px 10px;
            font: 13px 'Segoe UI', Arial, sans-serif;
        }

        .anamnesis-field textarea {
            min-height: 95px;
            resize: vertical;
        }

        .anamnesis-card.wide textarea {
            min-height: 130px;
        }

        .anamnesis-field input:focus,
        .anamnesis-field select:focus,
        .anamnesis-field textarea:focus {
            outline: 2px solid #93c5fd;
            outline-offset: 1px;
        }

        .anamnesis-options {
            display: grid;
            gap: 7px;
            margin: 9px 0 12px;
        }

        .anamnesis-options label {
            display: flex;
            gap: 7px;
            align-items: center;
            font-size: 12px;
            color: #34465d;
            cursor: pointer;
        }

        .anamnesis-options input {
            accent-color: #0968bd;
        }

        .anamnesis-habits {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 9px;
        }

        .anamnesis-habits .anamnesis-field {
            margin-bottom: 0;
        }

        .anamnesis-hidden {
            display: none !important;
        }

        .anamnesis-footer {
            margin-top: 14px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .anamnesis-button {
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 11px 18px;
            background: #eef3f8;
            color: #243950;
            font-weight: 650;
            cursor: pointer;
        }

        .anamnesis-button.primary {
            background: #0968bd;
            border-color: #0968bd;
            color: white;
        }

        .anamnesis-button:hover {
            filter: brightness(.96);
        }

        .anamnesis-message {
            display: none;
            flex: 1 1 100%;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
        }

        .anamnesis-message.show {
            display: block;
        }

        .anamnesis-message.ok {
            background: #e8f7ee;
            color: #14633a;
        }

        .anamnesis-message.error {
            background: #fff0ed;
            color: #a32b25;
        }

        /* Visor de archivo clínico: vista previa a la izquierda e información a la derecha. */
        .exa-viewer-modal {
            max-width: 1000px !important;
            width: min(94vw, 1000px) !important;
            padding: 0 !important;
            overflow: hidden;
            text-align: left !important
        }

        .exa-viewer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            background: #104e80;
            color: #fff;
            padding: 16px 22px
        }

        .exa-viewer-header h2 {
            margin: 0 !important;
            color: #fff !important;
            font-size: 20px !important;
            text-align: left !important
        }

        .exa-viewer-close {
            border: 0;
            background: transparent;
            color: white;
            font-size: 30px;
            line-height: 1;
            cursor: pointer;
            padding: 0 5px
        }

        .exa-viewer-body {
            padding: 18px;
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(260px, 1fr);
            gap: 16px;
            align-items: start
        }

        .exa-viewer-image {
            background: #101d2b;
            border-radius: 9px;
            min-height: 290px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            overflow: hidden
        }

        .exa-viewer-image img {
            max-width: 100%;
            max-height: 62vh;
            object-fit: contain;
            display: block
        }

        .exa-viewer-image .exa-preview {
            margin: 0;
            max-height: 62vh
        }

        .exa-viewer-image a {
            color: #cfe7ff;
            font-weight: 650
        }

        .exa-viewer-info {
            background: #f3f7fc;
            border-radius: 9px;
            padding: 17px;
            min-width: 0;
            color: #24405e
        }

        .exa-viewer-info h3 {
            margin: 0 0 13px;
            font-size: 16px;
            color: #164b7d
        }

        .exa-viewer-info p {
            margin: 0 0 13px;
            font-size: 13px;
            line-height: 1.5;
            overflow-wrap: anywhere
        }

        .exa-viewer-info strong {
            display: block;
            color: #164b7d;
            margin-bottom: 3px
        }

        .exa-viewer-info .proc-delete {
            margin-top: 8px
        }

        @media(max-width:720px) {
            .exa-viewer-body {
                grid-template-columns: 1fr
            }

            .exa-viewer-image {
                min-height: 180px
            }

            .exa-viewer-modal {
                max-height: 90vh;
                overflow: auto
            }
        }

        /* Zona de material clínico: panel ancho y carga intuitiva */
        .exa-grid {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr)
        }

        .exa-material-fields {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px 22px;
            align-items: start
        }

        .exa-material-fields .exa-field.full {
            grid-column: 1/-1
        }

        .exa-material-fields .exa-upload-field {
            grid-column: 3;
            grid-row: 1/span 2
        }

        .exa-material-fields .exa-associated {
            grid-column: 1 / span 2
        }

        .exa-upload-zone {
            min-height: 134px;
            border: 2px dashed #b5cfee;
            border-radius: 9px;
            background: #f7faff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-align: center;
            padding: 15px;
            cursor: pointer;
            color: #145b9d;
            transition: background .15s, border-color .15s
        }

        .exa-upload-zone:hover,
        .exa-upload-zone.is-dragover,
        .exa-upload-zone:focus-visible {
            background: #eaf4ff;
            border-color: #1472c6;
            outline: none
        }

        .exa-upload-zone strong {
            font-size: 15px
        }

        .exa-upload-zone small {
            font-size: 12px;
            color: #69829d
        }

        .exa-upload-zone .exa-upload-symbol {
            font-size: 25px;
            line-height: 1
        }

        .exa-upload-zone .exa-selected-file {
            overflow-wrap: anywhere;
            color: #155a92;
            font-weight: 650
        }

        .exa-upload-field input[type=file] {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none
        }

        @media(max-width:800px) {
            .exa-material-fields {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .exa-material-fields .exa-upload-field {
                grid-column: 1/-1;
                grid-row: auto
            }

            .exa-material-fields .exa-associated {
                grid-column: 1/-1
            }
        }

        @media(max-width:1050px) {
            .anamnesis-container {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .anamnesis-card,
            .anamnesis-card.wide {
                grid-column: span 1;
            }
        }

        @media(max-width:650px) {
            .anamnesis-container {
                grid-template-columns: 1fr;
            }

            .anamnesis-card,
            .anamnesis-card.wide {
                grid-column: 1;
            }

            .anamnesis-habits {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* Distribución de examen físico general y segmentario */
        .ef-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            align-items: start;
            margin-top: 18px
        }

        .ef-panel {
            min-width: 0;
            padding: 18px;
            background: #fff;
            border: 1px solid #dce5ef;
            border-radius: 12px;
            box-shadow: 0 3px 14px rgba(15, 42, 76, .045)
        }

        .ef-title {
            margin: 0 0 18px;
            padding: 12px 14px;
            border-radius: 8px;
            background: #e6f0fc;
            color: #174c7e;
            font-size: 18px
        }

        .ef-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px
        }

        .ef-field {
            display: flex;
            flex-direction: column;
            gap: 7px;
            min-width: 0
        }

        .ef-field:last-child {
            grid-column: 1/-1
        }

        .ef-field label,
        .ef-segment-body label {
            font-size: 13px;
            font-weight: 650;
            color: #25354b
        }

        .ef-field select,
        .ef-field input[type="number"],
        .ef-field textarea,
        .ef-segment-body textarea {
            width: 100%;
            min-width: 0;
            min-height: 42px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #172a41;
            font: inherit;
            font-size: 13px
        }

        .ef-field textarea,
        .ef-segment-body textarea {
            min-height: 90px;
            resize: vertical
        }

        .ef-field select:focus,
        .ef-field input[type="number"]:focus,
        .ef-field textarea:focus,
        .ef-segment-body textarea:focus {
            outline: 2px solid #93c5fd;
            outline-offset: 1px
        }

        .ef-hint {
            margin: -6px 0 15px;
            font-size: 12px;
            color: #64748b
        }

        .ef-segment {
            margin: 0 0 10px;
            border: 1px solid #dce5ef;
            border-radius: 9px;
            overflow: hidden;
            background: #fff
        }

        .ef-segment summary {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 14px 38px 14px 15px;
            cursor: pointer;
            list-style: none;
            color: #174c7e
        }

        .ef-segment summary::-webkit-details-marker {
            display: none
        }

        .ef-segment summary:after {
            content: '+';
            position: absolute;
            right: 15px;
            top: 14px;
            font-size: 20px;
            color: #1766aa
        }

        .ef-segment[open] summary:after {
            content: '−'
        }

        .ef-segment summary span {
            font-weight: 700;
            font-size: 15px
        }

        .ef-segment summary small {
            color: #64748b;
            font-size: 11px
        }

        .ef-segment-body {
            display: flex;
            flex-direction: column;
            gap: 9px;
            padding: 14px;
            border-top: 1px solid #e7edf3;
            background: #fbfdff
        }

        .ef-segment-body textarea+label {
            margin-top: 7px
        }

        .ef-actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px
        }

        /* Zona de material clínico: panel ancho y carga intuitiva */
        .exa-grid {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr)
        }

        .exa-material-fields {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px 22px;
            align-items: start
        }

        .exa-material-fields .exa-field.full {
            grid-column: 1/-1
        }

        .exa-material-fields .exa-upload-field {
            grid-column: 3;
            grid-row: 1/span 2
        }

        .exa-material-fields .exa-associated {
            grid-column: 1 / span 2
        }

        .exa-upload-zone {
            min-height: 134px;
            border: 2px dashed #b5cfee;
            border-radius: 9px;
            background: #f7faff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-align: center;
            padding: 15px;
            cursor: pointer;
            color: #145b9d;
            transition: background .15s, border-color .15s
        }

        .exa-upload-zone:hover,
        .exa-upload-zone.is-dragover,
        .exa-upload-zone:focus-visible {
            background: #eaf4ff;
            border-color: #1472c6;
            outline: none
        }

        .exa-upload-zone strong {
            font-size: 15px
        }

        .exa-upload-zone small {
            font-size: 12px;
            color: #69829d
        }

        .exa-upload-zone .exa-upload-symbol {
            font-size: 25px;
            line-height: 1
        }

        .exa-upload-zone .exa-selected-file {
            overflow-wrap: anywhere;
            color: #155a92;
            font-weight: 650
        }

        .exa-upload-field input[type=file] {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none
        }

        @media(max-width:800px) {
            .exa-material-fields {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .exa-material-fields .exa-upload-field {
                grid-column: 1/-1;
                grid-row: auto
            }

            .exa-material-fields .exa-associated {
                grid-column: 1/-1
            }
        }

        @media(max-width:1050px) {
            .ef-columns {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:550px) {
            .ef-panel {
                padding: 12px
            }

            .ef-fields {
                grid-template-columns: 1fr
            }

            .ef-actions button {
                flex: 1
            }
        }

        /* Procedimientos: mantiene el lenguaje visual de las vistas anteriores */
        .proc-shortcuts {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px
        }

        .proc-shortcut {
            border: 1px solid #cbdff5;
            border-radius: 7px;
            background: #f4f8ff;
            color: #174c7e;
            padding: 8px 12px;
            font: 600 12px 'Segoe UI', Arial, sans-serif;
            cursor: pointer;
            transition: background .15s, border-color .15s
        }

        .proc-shortcut:hover {
            background: #e4f0ff;
            border-color: #93c5fd
        }

        .proc-shortcut[aria-pressed="true"] {
            background: #1766aa;
            color: #fff;
            border-color: #1766aa
        }

        .proc-shortcut:focus-visible {
            outline: 2px solid #2563eb;
            outline-offset: 2px
        }

        .proc-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(0, .85fr);
            gap: 16px;
            margin-top: 18px;
            align-items: start
        }

        .proc-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px
        }

        .proc-full {
            grid-column: 1/-1
        }

        .proc-fields .ef-field:last-child {
            grid-column: 1/-1
        }

        .proc-fields .ef-field input[type="text"],
        .proc-fields .ef-field input[type="date"],
        .proc-fields .ef-field input[type="time"] {
            box-sizing: border-box;
            width: 100%;
            min-width: 0;
            min-height: 42px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #172a41;
            font: inherit;
            font-size: 13px
        }

        .proc-fields .ef-field input:focus {
            outline: 2px solid #93c5fd;
            outline-offset: 1px
        }

        .proc-fields .ef-field[hidden] {
            display: none !important
        }

        .proc-actions {
            margin-top: 18px
        }

        .proc-title-secondary {
            background: #e6f6ef;
            color: #166347
        }

        .med-check-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            line-height: 1.4
        }

        .med-check-label input {
            width: 17px;
            height: 17px;
            accent-color: #0968bd;
            flex: none
        }

        /* Historial en formato tabla, acorde al estándar visual de las otras vistas */
        .proc-list {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #dce5ef;
            border-radius: 8px
        }

        .proc-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
            color: #47627e
        }

        .proc-table thead {
            background: #0d4b83;
            color: #fff
        }

        .proc-table th {
            padding: 13px 14px;
            font-weight: 700;
            white-space: nowrap
        }

        .proc-table td {
            padding: 14px;
            border-bottom: 1px solid #dce5ef;
            vertical-align: middle
        }

        .proc-table tbody tr:nth-child(odd) {
            background: #f6f9fc
        }

        .proc-table tbody tr:nth-child(even) {
            background: #fff
        }

        .proc-table tbody tr:last-child td {
            border-bottom: 0
        }

        .proc-table .proc-details-row[hidden] {
            display: none
        }

        .proc-table .proc-details-row td {
            padding: 13px 14px;
            background: #edf5ff;
            color: #334155;
            line-height: 1.6
        }

        .proc-table .proc-details-row p {
            margin: 4px 0;
            white-space: pre-wrap;
            overflow-wrap: anywhere
        }

        .proc-view {
            border: 0;
            background: none;
            color: #1766d1;
            font: inherit;
            cursor: pointer;
            padding: 4px 0
        }

        .proc-view:hover {
            text-decoration: underline
        }

        .proc-delete {
            background: #fff;
            border: 1px solid #efc4c4;
            color: #a32b25;
            border-radius: 7px;
            padding: 7px 10px;
            cursor: pointer;
            font-size: 12px;
            margin-top: 10px
        }

        .proc-empty {
            padding: 22px;
            text-align: center;
            color: #64748b;
            font-size: 13px
        }

        /* Zona de material clínico: panel ancho y carga intuitiva */
        .exa-grid {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr)
        }

        .exa-material-fields {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px 22px;
            align-items: start
        }

        .exa-material-fields .exa-field.full {
            grid-column: 1/-1
        }

        .exa-material-fields .exa-upload-field {
            grid-column: 3;
            grid-row: 1/span 2
        }

        .exa-material-fields .exa-associated {
            grid-column: 1 / span 2
        }

        .exa-upload-zone {
            min-height: 134px;
            border: 2px dashed #b5cfee;
            border-radius: 9px;
            background: #f7faff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-align: center;
            padding: 15px;
            cursor: pointer;
            color: #145b9d;
            transition: background .15s, border-color .15s
        }

        .exa-upload-zone:hover,
        .exa-upload-zone.is-dragover,
        .exa-upload-zone:focus-visible {
            background: #eaf4ff;
            border-color: #1472c6;
            outline: none
        }

        .exa-upload-zone strong {
            font-size: 15px
        }

        .exa-upload-zone small {
            font-size: 12px;
            color: #69829d
        }

        .exa-upload-zone .exa-upload-symbol {
            font-size: 25px;
            line-height: 1
        }

        .exa-upload-zone .exa-selected-file {
            overflow-wrap: anywhere;
            color: #155a92;
            font-weight: 650
        }

        .exa-upload-field input[type=file] {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none
        }

        @media(max-width:800px) {
            .exa-material-fields {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .exa-material-fields .exa-upload-field {
                grid-column: 1/-1;
                grid-row: auto
            }

            .exa-material-fields .exa-associated {
                grid-column: 1/-1
            }
        }

        @media(max-width:1050px) {
            .proc-layout {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:600px) {
            .proc-fields {
                grid-template-columns: 1fr
            }

            .proc-actions {
                justify-content: flex-start
            }
        }

        /* Medicamentos: diseño consistente con Procedimientos */
        .med-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
            gap: 16px;
            margin-top: 18px;
            align-items: start
        }

        .med-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px
        }

        .med-full {
            grid-column: 1/-1
        }

        .med-fields .ef-field:last-child {
            grid-column: 1/-1
        }

        .med-fields .ef-field input:not([type=checkbox]),
        .med-fields .ef-field select,
        .med-fields .ef-field textarea {
            box-sizing: border-box;
            width: 100%;
            min-width: 0;
            min-height: 42px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #172a41;
            font: inherit;
            font-size: 13px
        }

        .med-fields .ef-field input:focus,
        .med-fields .ef-field select:focus,
        .med-fields .ef-field textarea:focus {
            outline: 2px solid #93c5fd;
            outline-offset: 1px
        }

        .med-fields .ef-field input[readonly] {
            background: #f6f9fc
        }

        /* Ocultar Especificar vía salvo cuando se selecciona Otra. */
        .med-fields .ef-field[hidden] {
            display: none !important
        }

        .med-fields input[type="file"] {
            box-sizing: border-box;
            width: 100%;
            min-width: 0;
            padding: 9px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            font-size: 13px
        }

        .med-actions {
            margin-top: 18px
        }

        /* Zona de material clínico: panel ancho y carga intuitiva */
        .exa-grid {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr)
        }

        .exa-material-fields {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px 22px;
            align-items: start
        }

        .exa-material-fields .exa-field.full {
            grid-column: 1/-1
        }

        .exa-material-fields .exa-upload-field {
            grid-column: 3;
            grid-row: 1/span 2
        }

        .exa-material-fields .exa-associated {
            grid-column: 1 / span 2
        }

        .exa-upload-zone {
            min-height: 134px;
            border: 2px dashed #b5cfee;
            border-radius: 9px;
            background: #f7faff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-align: center;
            padding: 15px;
            cursor: pointer;
            color: #145b9d;
            transition: background .15s, border-color .15s
        }

        .exa-upload-zone:hover,
        .exa-upload-zone.is-dragover,
        .exa-upload-zone:focus-visible {
            background: #eaf4ff;
            border-color: #1472c6;
            outline: none
        }

        .exa-upload-zone strong {
            font-size: 15px
        }

        .exa-upload-zone small {
            font-size: 12px;
            color: #69829d
        }

        .exa-upload-zone .exa-upload-symbol {
            font-size: 25px;
            line-height: 1
        }

        .exa-upload-zone .exa-selected-file {
            overflow-wrap: anywhere;
            color: #155a92;
            font-weight: 650
        }

        .exa-upload-field input[type=file] {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none
        }

        @media(max-width:800px) {
            .exa-material-fields {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .exa-material-fields .exa-upload-field {
                grid-column: 1/-1;
                grid-row: auto
            }

            .exa-material-fields .exa-associated {
                grid-column: 1/-1
            }
        }

        @media(max-width:1050px) {
            .med-layout {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:600px) {
            .med-fields {
                grid-template-columns: 1fr
            }

            .med-actions {
                justify-content: flex-start
            }
        }

        /* Solicitudes y adjuntos: estándar visual de Procedimientos / Medicamentos */
        .exa-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr);
            gap: 16px;
            margin-top: 18px;
            align-items: start
        }

        .exa-stack {
            display: grid;
            gap: 16px;
            min-width: 0
        }

        .exa-panel {
            padding: 18px;
            background: #fff;
            border: 1px solid #dce5ef;
            border-radius: 12px;
            min-width: 0;
            box-shadow: 0 3px 14px rgba(15, 42, 76, .045)
        }

        .exa-head {
            padding: 12px 14px;
            background: #e6f0fc;
            color: #174c7e;
            border-radius: 8px;
            font-size: 17px;
            margin: 0 0 10px
        }

        .exa-head.green {
            background: #e6f6ef;
            color: #166347
        }

        .exa-head.purple {
            background: #eee9ff;
            color: #58449a
        }

        .exa-sub {
            font-size: 12px;
            color: #64748b;
            margin: 0 0 15px
        }

        .exa-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px
        }

        .exa-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 0
        }

        .exa-field.full {
            grid-column: 1/-1
        }

        .exa-field label {
            font-size: 12px;
            font-weight: 700;
            color: #25354b
        }

        .exa-field input,
        .exa-field select,
        .exa-field textarea {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            min-height: 42px;
            padding: 10px 12px;
            background: #fff;
            color: #172a41;
            font: 13px 'Segoe UI', Arial, sans-serif
        }

        .exa-field textarea {
            min-height: 84px;
            resize: vertical
        }

        .exa-field input:focus,
        .exa-field select:focus,
        .exa-field textarea:focus {
            outline: 2px solid #93c5fd;
            outline-offset: 1px
        }

        .exa-choices {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 9px
        }

        .exa-choice {
            display: flex;
            align-items: center;
            gap: 9px;
            border: 1px solid #d9e5f1;
            border-radius: 8px;
            padding: 10px;
            background: #f8fbff;
            color: #24496b;
            font-size: 12px;
            cursor: pointer
        }

        .exa-choice input {
            accent-color: #0968bd
        }

        .exa-actions {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            flex-wrap: wrap;
            margin-top: 15px
        }

        .exa-notice {
            margin: 12px 0 0;
            font-size: 12px;
            color: #526c86
        }

        .exa-date {
            font-weight: 750;
            color: #124c80
        }

        .exa-time {
            font-weight: 750;
            color: #124c80
        }

        .exa-file {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 7px
        }

        .exa-file a {
            color: #0968bd;
            text-decoration: underline
        }

        .exa-detail {
            padding: 13px 15px;
            background: #eef5ff;
            border-top: 1px solid #dce5ef;
            color: #34465d;
            font-size: 13px;
            line-height: 1.65
        }

        .exa-detail p {
            margin: 4px 0;
            overflow-wrap: anywhere
        }

        .exa-detail[hidden] {
            display: none
        }

        .exa-table td {
            vertical-align: top
        }

        .exa-badge {
            display: inline-block;
            background: #edf5ff;
            border-radius: 5px;
            padding: 4px 7px;
            color: #174c7e;
            font-size: 11px
        }

        .exa-file-list {
            display: grid;
            gap: 9px;
            margin-top: 9px
        }

        .exa-file-item {
            padding: 10px;
            border: 1px solid #dce5ef;
            border-radius: 8px;
            background: white
        }

        .exa-file-item small {
            display: block;
            color: #64748b;
            margin-top: 4px
        }

        .exa-preview {
            max-width: 100%;
            max-height: 400px;
            border-radius: 7px;
            margin-top: 10px;
            display: block
        }

        .exa-hidden {
            display: none !important
        }

        /* Zona de material clínico: panel ancho y carga intuitiva */
        .exa-grid {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr)
        }

        .exa-material-fields {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px 22px;
            align-items: start
        }

        .exa-material-fields .exa-field.full {
            grid-column: 1/-1
        }

        .exa-material-fields .exa-upload-field {
            grid-column: 3;
            grid-row: 1/span 2
        }

        .exa-material-fields .exa-associated {
            grid-column: 1 / span 2
        }

        .exa-upload-zone {
            min-height: 134px;
            border: 2px dashed #b5cfee;
            border-radius: 9px;
            background: #f7faff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-align: center;
            padding: 15px;
            cursor: pointer;
            color: #145b9d;
            transition: background .15s, border-color .15s
        }

        .exa-upload-zone:hover,
        .exa-upload-zone.is-dragover,
        .exa-upload-zone:focus-visible {
            background: #eaf4ff;
            border-color: #1472c6;
            outline: none
        }

        .exa-upload-zone strong {
            font-size: 15px
        }

        .exa-upload-zone small {
            font-size: 12px;
            color: #69829d
        }

        .exa-upload-zone .exa-upload-symbol {
            font-size: 25px;
            line-height: 1
        }

        .exa-upload-zone .exa-selected-file {
            overflow-wrap: anywhere;
            color: #155a92;
            font-weight: 650
        }

        .exa-upload-field input[type=file] {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none
        }

        @media(max-width:800px) {
            .exa-material-fields {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .exa-material-fields .exa-upload-field {
                grid-column: 1/-1;
                grid-row: auto
            }

            .exa-material-fields .exa-associated {
                grid-column: 1/-1
            }
        }

        @media(max-width:1050px) {
            .exa-grid {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:560px) {
            .exa-fields {
                grid-template-columns: 1fr
            }

            .exa-choices {
                grid-template-columns: 1fr
            }

            .exa-panel {
                padding: 12px
            }
        }

        /* Acciones de enfermería: dos paneles y registro cronológico */
        .ae-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.12fr) minmax(0, 1fr);
            gap: 16px;
            margin-top: 18px;
            align-items: start
        }

        .ae-panel {
            background: #fff;
            border: 1px solid #dce5ef;
            border-radius: 12px;
            padding: 18px;
            min-width: 0;
            box-shadow: 0 3px 14px rgba(15, 42, 76, .045)
        }

        .ae-heading {
            margin: 0 0 8px;
            padding: 12px 14px;
            border-radius: 8px;
            background: #e6f0fc;
            color: #174c7e;
            font-size: 17px
        }

        .ae-heading.green {
            background: #e6f6ef;
            color: #166347
        }

        .ae-sub {
            font-size: 12px;
            color: #64748b;
            margin: 0 0 16px
        }

        .ae-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px
        }

        .ae-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 0
        }

        .ae-field.full {
            grid-column: 1/-1
        }

        .ae-field label {
            font-size: 12px;
            font-weight: 700;
            color: #25354b
        }

        .ae-field input,
        .ae-field select,
        .ae-field textarea {
            box-sizing: border-box;
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            min-height: 42px;
            padding: 10px 12px;
            font: 13px 'Segoe UI', Arial, sans-serif;
            color: #172a41
        }

        .ae-field textarea {
            min-height: 110px;
            resize: vertical
        }

        .ae-field input:focus,
        .ae-field select:focus,
        .ae-field textarea:focus {
            outline: 2px solid #93c5fd;
            outline-offset: 1px
        }

        .ae-field input[readonly] {
            background: #f6f9fc
        }

        .ae-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 16px
        }

        .ae-table-wrap {
            overflow-x: auto
        }

        .ae-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px
        }

        .ae-table th {
            background: #104e80;
            color: #fff;
            text-align: left;
            padding: 12px 10px
        }

        .ae-table th:first-child {
            border-radius: 7px 0 0 0
        }

        .ae-table th:last-child {
            border-radius: 0 7px 0 0
        }

        .ae-table td {
            padding: 13px 10px;
            border-bottom: 1px solid #dce5ef;
            color: #34516e;
            vertical-align: top
        }

        .ae-table tbody tr:nth-child(odd) {
            background: #f7faff
        }

        .ae-table button {
            border: 0;
            background: #e9f2ff;
            color: #0968bd;
            border-radius: 6px;
            padding: 7px 11px;
            font-weight: 700;
            cursor: pointer
        }

        .ae-date {
            font-weight: 700;
            color: #174c7e;
            white-space: nowrap
        }

        .ae-empty {
            color: #64748b;
            font-size: 13px;
            padding: 20px 0;
            text-align: center
        }

        .ae-detail {
            margin-top: 14px;
            background: #f3f7fc;
            border: 1px solid #dce5ef;
            border-radius: 9px;
            padding: 16px;
            overflow-wrap: anywhere
        }

        .ae-detail h3 {
            margin: 0 0 12px;
            color: #174c7e
        }

        .ae-detail p {
            font-size: 13px;
            line-height: 1.5;
            color: #34465d
        }

        .ae-detail strong {
            color: #174c7e
        }

        .ae-detail-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px
        }

        .ae-delete {
            background: #fff0ed !important;
            color: #a32b25 !important;
            border: 1px solid #f5b5ae !important
        }

        .ae-status {
            font-size: 12px;
            margin-top: 12px;
            color: #14633a
        }

        .ae-hint {
            font-size: 12px;
            color: #64748b;
            margin: 12px 0 0
        }

        @media(max-width:1000px) {
            .ae-layout {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:560px) {
            .ae-fields {
                grid-template-columns: 1fr
            }

            .ae-panel {
                padding: 12px
            }
        }
    </style>
    <!-- Contexto autorizado de 4.º medio; nunca contiene datos de otros estudiantes. -->
    <script>window.__FICHA_4TO = @json($ficha4toContext ?? null);</script>
    <link rel="stylesheet" href="{{ asset('css/estudiante-confirmacion-4to.css') }}">
    <script src="{{ asset('js/estudiante/4to-backend-bridge.js') }}"></script>
</head>

<body>
    <div class="app app-shell" id="appShell">

        <!-- Menú lateral -->
        @include('estudiante.partials.sidebar-4to')

        <div class="main-area">

            <!-- Barra superior -->
            <header class="topbar">
                <div class="topbar-left">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Mostrar u ocultar menú"
                        aria-controls="sidebar" aria-expanded="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <span class="topbar-title">Acciones de enfermería</span>
                </div>

                <div class="user-info">
                    <div class="user-details">
                        <strong>Estudiante</strong>
                        <small>4.º medio</small>
                    </div>
                    <div class="user-avatar">E</div>
                </div>
            </header>

            <main class="content">

                <!-- Encabezado -->
                <div class="page-heading">
                    <div>
                        <p class="eyebrow">FICHA CLÍNICA EDUCATIVA</p>
                        <h1>Acciones de enfermería</h1>
                        <p class="muted">
                            Registro cronológico de acciones y cuidados realizados en el caso clínico simulado.
                        </p>
                    </div>

                    <a class="btn btn-secondary" id="backToCase" href="{{ route('preview.estudiante.casos') }}">
                        ← Volver al caso
                    </a>
                </div>

                <!-- Caso clínico -->
                <div class="case-banner">
                    <div>
                        <span class="case-code" id="caseCode">CAS-001</span>
                        <strong id="caseTitle">Caso clínico</strong>
                        <small>Paciente simulado · Acciones de enfermería</small>
                    </div>

                    <span class="case-status">En desarrollo</span>
                </div>

                <!-- Navegación entre etapas -->
                <nav class="tabs" aria-label="Etapas de ficha clínica">
                    <a id="tabResumen" href="#">Resumen</a>
                    <a id="tabAdmision" href="{{ route('preview.estudiante.admision') }}">Admisión</a>
                    <a id="tabTriage" href="{{ route('preview.estudiante.triage') }}">Triage</a>
                    <a id="tabSignos" href="{{ route('preview.estudiante.signos-vitales') }}">Signos vitales</a>
                    <a id="tabABCDE" href="{{ route('preview.estudiante.abcde') }}">ABCDE</a>
                    <a id="tabAnamnesis" href="{{ route('preview.estudiante.anamnesis') }}">Anamnesis</a>
                    <a id="tabExaFisico" href="{{ route('preview.estudiante.examen-fisico') }}">Examen físico</a>
                    <a id="tabProcedimientos"
                        href="{{ route('preview.estudiante.procedimientos') }}">Procedimientos</a>
                    <a id="tabMedicamentos" href="{{ route('preview.estudiante.medicamentos_4to') }}">Medicamentos</a>
                    <a id="tabExamenes" href="{{ route('preview.estudiante.examenes') }}">Exámenes</a>
                    <a class="selected" id="tabAccEnfermeria"
                        href="{{ route('preview.estudiante.acciones-enfermeria') }}" aria-current="page">Acciones de
                        enfermería</a>
                    <a id="tabDestino" href="{{ route('preview.estudiante.destino') }}">Destino</a>
                </nav>

                <!-- Vista educativa 4.º medio: Evolución renombrada a Acciones de enfermería (reunión 27/10). -->
                <div class="ae-layout">
                    <section class="ae-panel" aria-labelledby="aeFormTitle">
                        <h2 class="ae-heading" id="aeFormTitle">Registrar acción de enfermería</h2>
                        <p class="ae-sub">Documenta la acción realizada, su fecha y hora, y los cuidados u
                            observaciones del paciente simulado.</p>
                        <form id="accionForm">
                            <div class="ae-fields">
                                <div class="ae-field"><label for="fechaAccion">Fecha del registro *</label><input
                                        id="fechaAccion" type="date" required></div>
                                <div class="ae-field"><label for="horaAccion">Hora del registro *</label><input
                                        id="horaAccion" type="time" required></div>
                                <div class="ae-field full"><label for="responsableAccion">Responsable *</label><input
                                        id="responsableAccion" type="text" maxlength="100"
                                        value="{{ auth()->user()?->name ?? '' }}" placeholder="Nombre del estudiante"
                                        {{ auth()->check() ? 'readonly' : '' }} required></div>
                                <div class="ae-field full"><label for="tituloAccion">Título de la acción de enfermería
                                        *</label><input id="tituloAccion" type="text" maxlength="120"
                                        placeholder="Ej.: Control y seguimiento del paciente" required></div>
                                <div class="ae-field full"><label for="notaAccion">Descripción de la acción y cuidados
                                        realizados *</label>
                                    <textarea id="notaAccion" maxlength="3000"
                                        placeholder="Describe la intervención o cuidado realizado en el caso simulado..." required></textarea>
                                </div>
                                <div class="ae-field full"><label for="observacionesAccion">Observaciones adicionales
                                        (opcional)</label>
                                    <textarea id="observacionesAccion" maxlength="1500"
                                        placeholder="Registrar respuesta del paciente u observaciones relevantes..."></textarea>
                                </div>
                            </div>
                            <div class="ae-actions"><button type="button" class="anamnesis-button"
                                    id="limpiarAccion">Limpiar</button><button type="button"
                                    class="anamnesis-button" id="cancelarAccion">Cancelar</button><button
                                    type="submit" class="anamnesis-button primary">Guardar acción</button></div>
                        </form>
                    </section>
                    <section class="ae-panel" aria-labelledby="aeHistoryTitle">
                        <h2 class="ae-heading green" id="aeHistoryTitle">Historial de acciones de enfermería</h2>
                        <p class="ae-sub">Registros del caso clínico ordenados por fecha y hora, del más reciente al
                            más antiguo.</p>
                        <div class="ae-table-wrap">
                            <table class="ae-table">
                                <thead>
                                    <tr>
                                        <th>Fecha y hora</th>
                                        <th>Acción</th>
                                        <th>Responsable</th>
                                        <th>Detalle</th>
                                    </tr>
                                </thead>
                                <tbody id="historialAcciones"></tbody>
                            </table>
                        </div>
                        <div id="sinAcciones" class="ae-empty">Aún no se registran acciones de enfermería para este
                            caso.</div>
                        <div id="detalleAccion" class="ae-detail" hidden></div>
                        <p class="ae-hint">Las acciones registradas se guardan en la ficha clínica del caso. Puedes consultarlas cuando vuelvas a ingresar.</p>
                    </section>
                </div>
                <div id="mensajeAccion" class="anamnesis-message" role="status" aria-live="polite"
                    style="margin-top:14px"></div>
            </main>
        </div>
    </div>
    <div class="modal-overlay" id="accionModal" hidden>
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="accionModalTitulo">
            <div class="modal-icon">!</div>
            <h2 id="accionModalTitulo">Confirmar acción</h2>
            <p id="accionModalTexto"></p>
            <div class="modal-actions"><button type="button" class="modal-cancel"
                    id="accionModalCancelar">Cancelar</button><button type="button" class="modal-confirm"
                    id="accionModalConfirmar">Confirmar</button></div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            'use strict';
            const $ = id => document.getElementById(id);
            const casos = {
                'CAS-001': 'Alteración neurológica aguda',
                'CAS-002': 'Crisis convulsivas en Urgencias',
                'CAS-003': 'Dolor torácico agudo',
                'CAS-004': 'Hipoglicemia severa',
                'CAS-005': 'Politraumatismo por accidente de tránsito'
            };
            const solicitado = new URLSearchParams(location.search).get('caso');
            const casoId = window.__FICHA_4TO?.codigo || (Object.prototype.hasOwnProperty.call(casos, solicitado) ? solicitado : 'CAS-001');
            const query = '?caso=' + encodeURIComponent(casoId);
            $('caseCode').textContent = casoId;
            $('caseTitle').textContent = window.__FICHA_4TO?.titulo || casos[casoId] || 'Caso clínico';
            const rutas = {
                detalle: "{{ url('/estudiante/caso') }}/" + encodeURIComponent(casoId),
                admision: "{{ route('preview.estudiante.admision') }}" + query,
                triage: "{{ route('preview.estudiante.triage') }}" + query,
                signos: "{{ route('preview.estudiante.signos-vitales') }}" + query,
                abcde: "{{ route('preview.estudiante.abcde') }}" + query,
                anamnesis: "{{ route('preview.estudiante.anamnesis') }}" + query,
                examen: "{{ route('preview.estudiante.examen-fisico') }}" + query,
                procedimientos: "{{ route('preview.estudiante.procedimientos') }}" + query,
                medicamentos: "{{ route('preview.estudiante.medicamentos_4to') }}" + query,
                examenes: "{{ route('preview.estudiante.examenes') }}" + query,
                acciones: "{{ route('preview.estudiante.acciones-enfermeria') }}" + query
            };
            [
                ['tabResumen', 'detalle'],
                ['tabAdmision', 'admision'],
                ['tabTriage', 'triage'],
                ['tabSignos', 'signos'],
                ['tabABCDE', 'abcde'],
                ['tabAnamnesis', 'anamnesis'],
                ['tabExaFisico', 'examen'],
                ['tabProcedimientos', 'procedimientos'],
                ['tabMedicamentos', 'medicamentos'],
                ['tabExamenes', 'examenes'],
                ['tabAccEnfermeria', 'acciones'],
                ['sidebarAdmision', 'admision'],
                ['sidebarTriage', 'triage'],
                ['sidebarSignos', 'signos'],
                ['sidebarABCDE', 'abcde'],
                ['sidebarAnamnesis', 'anamnesis']
            ].forEach(([id, k]) => {
                if ($(id)) $(id).href = rutas[k]
            });
            $('backToCase').href = rutas.detalle;
            $('menuToggle').addEventListener('click', () => {
                const hidden = $('appShell').classList.toggle('sidebar-hidden');
                $('menuToggle').setAttribute('aria-expanded', String(!hidden));
            });
            // BACKEND (Laravel): asociar cada acción al caso y al usuario autenticado mediante Auth::id().
            // No confiar en el nombre del responsable enviado por el navegador; el controlador debe resolverlo desde la sesión.
            const storageKey = 'fctp_acciones_enfermeria_v1_' + casoId;
            let registros = [];
            try {
                const saved = JSON.parse(sessionStorage.getItem(storageKey) || '[]');
                if (Array.isArray(saved)) registros = saved.filter(x => x && typeof x === 'object');
            } catch (e) {
                registros = [];
            }
            let pendiente = null;
            const modal = $('accionModal');

            function cerrarModal() {
                modal.hidden = true;
                pendiente = null;
            }

            function confirmar(titulo, texto, fn) {
                $('accionModalTitulo').textContent = titulo;
                $('accionModalTexto').textContent = texto;
                pendiente = fn;
                modal.hidden = false;
                $('accionModalCancelar').focus();
            }
            $('accionModalCancelar').addEventListener('click', cerrarModal);
            $('accionModalConfirmar').addEventListener('click', () => {
                const fn = pendiente;
                cerrarModal();
                if (fn) fn();
            });
            modal.addEventListener('click', e => {
                if (e.target === modal) cerrarModal();
            });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && !modal.hidden) cerrarModal();
            });
            const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [c]));
            const fmt = (fecha, hora) => {
                if (!fecha) return 'Sin fecha';
                const [y, m, d] = fecha.split('-');
                return `${d}/${m}/${y} · ${hora||'--:--'}`;
            };

            let avisoTimer;
            function avisar(msg, error = false) {
                const el = $('mensajeAccion');
                clearTimeout(avisoTimer);
                el.textContent = msg;
                el.className = 'anamnesis-message show ' + (error ? 'error' : 'ok');
                avisoTimer = setTimeout(() => { el.className = 'anamnesis-message'; }, error ? 8000 : 4300);
            }
            $('mensajeAccion').addEventListener('click', () => {
                clearTimeout(avisoTimer);
                $('mensajeAccion').className = 'anamnesis-message';
            });

            function guardar(mensaje = 'Registro guardado correctamente.') {
                sessionStorage.setItem(storageKey, JSON.stringify(registros));
                if (!window.Ficha4toBridge?.flush) {
                    avisar('El registro no pudo confirmarse con el servidor.', true);
                    return;
                }
                avisar('Guardando registro…');
                void window.Ficha4toBridge.flush()
                    .then(() => avisar(mensaje))
                    .catch(() => avisar('No se pudo confirmar el guardado en el servidor. Revisa la conexión y vuelve a intentar.', true));
            }

            function ahora() {
                const d = new Date();
                const local = new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString();
                $('fechaAccion').value = local.slice(0, 10);
                $('horaAccion').value = local.slice(11, 16);
            }

            let detalleAbiertoId = null;
            function render() {
                const ordenados = [...registros].sort((a, b) => (b.fecha + 'T' + b.hora).localeCompare(a.fecha +
                    'T' + a.hora));
                $('sinAcciones').hidden = ordenados.length > 0;
                $('historialAcciones').innerHTML = ordenados.map(r =>
                    `<tr><td class="ae-date">${esc(fmt(r.fecha,r.hora))}</td><td>${esc(r.titulo)}</td><td>${esc(r.responsable)}</td><td><button type="button" data-ver="${esc(r.id)}" aria-expanded="${detalleAbiertoId === r.id}">${detalleAbiertoId === r.id ? 'Ocultar' : 'Ver'}</button></td></tr>`
                    ).join('');
                $('historialAcciones').querySelectorAll('[data-ver]').forEach(b => b.addEventListener('click', () =>
                    ver(b.dataset.ver)));
                if (!detalleAbiertoId) $('detalleAccion').hidden = true;
            }

            function ver(id) {
                const el = $('detalleAccion');
                if (detalleAbiertoId === id) {
                    detalleAbiertoId = null;
                    el.hidden = true;
                    render();
                    return;
                }
                const r = registros.find(x => x.id === id);
                if (!r) return;
                detalleAbiertoId = id;
                render();
                el.hidden = false;
                el.innerHTML =
                    `<h3>Detalle de la acción</h3><p><strong>Fecha y hora:</strong> ${esc(fmt(r.fecha,r.hora))}</p><p><strong>Título:</strong> ${esc(r.titulo)}</p><p><strong>Responsable:</strong> ${esc(r.responsable)}</p><p><strong>Acción y cuidados:</strong> ${esc(r.nota).replace(/\n/g,'<br>')}</p><p><strong>Observaciones adicionales:</strong> ${esc(r.observaciones||'Sin observaciones').replace(/\n/g,'<br>')}</p><div class="ae-detail-actions"><button type="button" class="anamnesis-button ae-delete" id="eliminarAccion">Eliminar registro</button><button type="button" class="anamnesis-button" id="cerrarDetalle">Cerrar</button></div>`;
                $('cerrarDetalle').addEventListener('click', () => {
                    detalleAbiertoId = null;
                    el.hidden = true;
                    render();
                });
                $('eliminarAccion').addEventListener('click', () => confirmar('Eliminar acción',
                    '¿Estás seguro de que deseas eliminar esta acción de enfermería? Esta acción no se puede deshacer.',
                    () => {
                        registros = registros.filter(x => x.id !== id);
                        detalleAbiertoId = null;
                        el.hidden = true;
                        render();
                        guardar('Registro eliminado correctamente.');
                    }));
            }
            $('accionForm').addEventListener('submit', e => {
                e.preventDefault();
                if (!$('accionForm').reportValidity()) return;
                const r = {
                    id: Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 9),
                    fecha: $('fechaAccion').value,
                    hora: $('horaAccion').value,
                    responsable: $('responsableAccion').value.trim(),
                    titulo: $('tituloAccion').value.trim(),
                    nota: $('notaAccion').value.trim(),
                    observaciones: $('observacionesAccion').value.trim()
                };
                if (!r.responsable || !r.titulo || !r.nota) {
                    avisar('Completa los campos obligatorios.', true);
                    return;
                }
                registros.push(r);
                detalleAbiertoId = null;
                render();
                $('accionForm').reset();
                ahora();
                $('detalleAccion').hidden = true;
                guardar('Acción de enfermería registrada correctamente.');
            });
            $('limpiarAccion').addEventListener('click', () => confirmar('Limpiar formulario',
                '¿Estás seguro de que deseas limpiar los datos ingresados? Se descartarán los cambios no guardados.',
                () => {
                    $('accionForm').reset();
                    ahora();
                    $('mensajeAccion').className = 'anamnesis-message';
                }));
            $('cancelarAccion').addEventListener('click', () => {
                location.href = rutas.examenes;
            });
            ahora();
            render();
        });
    </script>
<script src="{{ asset('js/estudiante/tiempo-restante.js') }}" defer></script>@include('shared.app-extras')
</body>

</html>
