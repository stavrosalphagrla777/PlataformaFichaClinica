<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exámenes | Ficha Clínica TP</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        .exa-upload-zone > * { pointer-events: none; }

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

        .exa-upload-zone > * { pointer-events: none; }

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

        .exa-upload-zone > * { pointer-events: none; }

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

        .exa-upload-zone > * { pointer-events: none; }

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

        .exa-upload-zone > * { pointer-events: none; }

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

                    <span class="topbar-title">Exámenes</span>
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
                        <h1>Exámenes del paciente</h1>
                        <p class="muted">
                            Registro de exámenes solicitados y sus resultados en el caso clínico simulado.
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
                        <small>Paciente simulado · Exámenes</small>
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
                    <a class="selected" id="tabExamenes" href="{{ route('preview.estudiante.examenes') }}"
                        aria-current="page">Exámenes</a>
                    <a id="tabAccEnfermeria" href="{{ route('preview.estudiante.acciones-enfermeria') }}">Acciones de enfermería</a>
                    <a id="tabDestino" href="#">Destino</a>
                </nav>

                <!-- PROTOTIPO 4.º MEDIO: el docente define los exámenes y materiales permitidos para el caso. -->
                <div class="exa-grid">
                    <div class="exa-stack">
                        <section class="exa-panel" aria-labelledby="solTitle">
                            <h2 class="exa-head" id="solTitle">Solicitud de exámenes</h2>
                            <p class="exa-sub">Selecciona uno o varios exámenes para el caso simulado y registra cuándo
                                se solicitaron.</p>
                            <form id="solicitudForm" novalidate>
                                <div class="exa-choices" id="tiposExamen">
                                    <label class="exa-choice"><input type="checkbox" value="Hemograma">
                                        Hemograma</label>
                                    <label class="exa-choice"><input type="checkbox" value="PCR"> PCR</label>
                                    <label class="exa-choice"><input type="checkbox" value="Perfil bioquímico">
                                        Perfil bioquímico</label>
                                    <label class="exa-choice"><input type="checkbox" value="Troponinas">
                                        Troponinas</label>
                                    <label class="exa-choice"><input type="checkbox" value="TAC"> TAC</label>
                                    <label class="exa-choice"><input type="checkbox" value="Radiografía">
                                        Radiografía</label>
                                    <label class="exa-choice"><input type="checkbox"
                                            value="ECG - Electrocardiograma"> ECG</label>
                                    <label class="exa-choice"><input type="checkbox" value="Gases"> Gases</label>
                                </div>
                                <div class="exa-fields" style="margin-top:14px">
                                    <div class="exa-field"><label for="fechaSolicitud">Fecha de solicitud
                                            *</label><input type="date" id="fechaSolicitud" required></div>
                                    <div class="exa-field"><label for="horaSolicitud">Hora de solicitud
                                            *</label><input type="time" id="horaSolicitud" required></div>
                                    <div class="exa-field full"><label for="observacionesSolicitud">Observaciones /
                                            indicaciones de la solicitud</label>
                                        <textarea id="observacionesSolicitud" maxlength="1500" placeholder="Indicaciones para el caso clínico..."></textarea>
                                    </div>
                                </div>
                                <div class="exa-actions"><button type="button" id="limpiarSolicitud"
                                        class="anamnesis-button">Limpiar</button><button type="submit"
                                        class="anamnesis-button primary">Registrar solicitud</button></div>
                            </form>
                        </section>

                    </div>
                    <div class="exa-stack">
                        <section class="exa-panel" aria-labelledby="listadoTitle">
                            <h2 class="exa-head green" id="listadoTitle">Solicitudes de exámenes</h2>
                            <p class="exa-sub">Consulta la fecha y hora de cada solicitud y sus resultados asociados.
                            </p>
                            <div class="proc-list">
                                <table class="proc-table exa-table">
                                    <thead>
                                        <tr>
                                            <th>Fecha y hora</th>
                                            <th>Examen</th>
                                            <th>Archivos</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="solicitudesBody"></tbody>
                                </table>
                            </div>
                        </section>
                        <section class="exa-panel" aria-labelledby="archivosTitle">
                            <h2 class="exa-head" id="archivosTitle">Resultados y material adjunto</h2>
                            <p class="exa-sub">Documentos e imágenes asociados a solicitudes del caso clínico,
                                ordenados por fecha y hora.</p>
                            <div class="proc-list">
                                <table class="proc-table exa-table">
                                    <thead>
                                        <tr>
                                            <th>Fecha y hora</th>
                                            <th>Examen / archivo</th>
                                            <th>Resultado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="archivosBody"></tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="exa-material-full">
                    <section class="exa-panel" aria-labelledby="materialTitle">
                        <h2 class="exa-head purple" id="materialTitle">Agregar material clínico</h2>
                        <p class="exa-sub">Adjunta archivos JPG, PNG o PDF a una solicitud existente. La fecha y hora
                            del archivo se registran por separado.</p>
                        <form id="materialForm" novalidate>
                            <div class="exa-fields exa-material-fields">
                                <div class="exa-field exa-associated"><label for="solicitudAsociada">Solicitud y
                                        examen asociado *</label><select id="solicitudAsociada" required>
                                        <option value="">Seleccionar solicitud registrada</option>
                                    </select></div>
                                <div class="exa-field"><label for="tipoMaterial">Tipo de material *</label><select
                                        id="tipoMaterial" required>
                                        <option value="">Seleccionar</option>
                                        <option>Resultado de laboratorio</option>
                                        <option>Radiografía</option>
                                        <option>Imagen clínica</option>
                                        <option>Informe</option>
                                        <option>Otro</option>
                                    </select></div>
                                <div class="exa-field"><label for="estadoMaterial">Estado del resultado
                                        *</label><select id="estadoMaterial" required>
                                        <option value="">Seleccionar</option>
                                        <option>Pendiente</option>
                                        <option>Disponible</option>
                                        <option>Normal</option>
                                        <option>Alterado</option>
                                        <option>Crítico</option>
                                        <option>No concluyente</option>
                                    </select></div>
                                <div class="exa-field full"><label for="nombreMaterial">Nombre del documento
                                        *</label><input id="nombreMaterial" maxlength="130" required
                                        placeholder="Ej.: Radiografía de tórax"></div>
                                <div class="exa-field exa-upload-field"><label for="archivoMaterial">Archivo adjunto (opcional)
                                        · JPG, PNG o PDF</label><input type="file" id="archivoMaterial"
                                        accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf">
                                    <div class="exa-upload-zone" id="zonaArchivo" role="button" tabindex="0"
                                        aria-label="Seleccionar o arrastrar archivo JPG, PNG o PDF"><span
                                            class="exa-upload-symbol" aria-hidden="true">↑</span><strong>Seleccionar
                                            archivo</strong><small>Haz clic o arrastra un archivo aquí</small><small
                                            id="nombreArchivoSeleccionado" class="exa-selected-file">Sin archivo
                                            seleccionado</small><small>JPG, PNG o PDF · Máximo 10 MB</small></div>
                                </div>
                                <div class="exa-field"><label for="fechaMaterial">Fecha del resultado / archivo
                                        *</label><input type="date" id="fechaMaterial" required></div>
                                <div class="exa-field"><label for="horaMaterial">Hora del resultado / archivo
                                        *</label><input type="time" id="horaMaterial" required></div>
                                <div class="exa-field full"><label for="descripcionMaterial">Descripción /
                                        indicaciones</label>
                                    <textarea id="descripcionMaterial" maxlength="1500" placeholder="Contexto del material adjunto..."></textarea>
                                </div>
                            </div>
                            <p class="exa-notice">Puedes guardar el resultado sin adjuntar un archivo. Si seleccionas uno,
                                estará disponible solo durante esta sesión: actualmente la plataforma conserva los
                                datos del resultado en Laravel, pero no almacena permanentemente los archivos.</p>
                            <div class="exa-actions"><button type="button" id="cancelarMaterial"
                                    class="anamnesis-button">Cancelar</button><button type="submit"
                                    class="anamnesis-button primary">Guardar material</button></div>
                        </form>
                    </section>
                </div>
                <div id="saveMessage" class="anamnesis-message" role="status" aria-live="polite"
                    style="margin-top:14px"></div>
            </main>
        </div>
    </div>


    <!-- Los modales reutilizan las clases existentes de estudiante-triage.css -->
    <div class="modal-overlay" id="confirmModal" hidden>
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <div class="modal-icon">!</div>
            <h2 id="modalTitle">Confirmar acción</h2>
            <p id="modalText"></p>
            <div class="modal-actions"><button type="button" class="modal-cancel"
                    id="modalCancel">Cancelar</button><button type="button" class="modal-confirm"
                    id="modalConfirm">Confirmar</button></div>
        </div>
    </div>
    <div class="modal-overlay" id="archivoModal" hidden>
        <div class="modal-card exa-viewer-modal" role="dialog" aria-modal="true"
            aria-labelledby="archivoModalTitle">
            <div class="exa-viewer-header">
                <h2 id="archivoModalTitle">Visualización de archivo clínico</h2><button type="button"
                    class="exa-viewer-close" id="cerrarArchivo" aria-label="Cerrar visualización">×</button>
            </div>
            <div id="archivoModalContenido"></div>
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
                examenes: "{{ route('preview.estudiante.examenes') }}" + query
            };
            const enlaces = {
                sidebarFicha: rutas.detalle,
                sidebarAdmision: rutas.admision,
                sidebarTriage: rutas.triage,
                sidebarSignos: rutas.signos,
                sidebarABCDE: rutas.abcde,
                sidebarAnamnesis: rutas.anamnesis,
                tabResumen: rutas.detalle,
                tabAdmision: rutas.admision,
                tabTriage: rutas.triage,
                tabSignos: rutas.signos,
                tabABCDE: rutas.abcde,
                tabAnamnesis: rutas.anamnesis,
                tabExaFisico: rutas.examen,
                tabProcedimientos: rutas.procedimientos,
                tabMedicamentos: rutas.medicamentos,
                tabExamenes: rutas.examenes,
                backToCase: rutas.detalle
            };
            Object.entries(enlaces).forEach(([id, url]) => {
                if ($(id)) $(id).href = url;
            });
            $('menuToggle')?.addEventListener('click', function() {
                const oculto = $('appShell').classList.toggle('sidebar-hidden');
                this.setAttribute('aria-expanded', String(!oculto));
            });

            const key = 'fctp_solicitudes_examenes_v2_' + casoId;
            let solicitudes = [];
            try {
                const saved = JSON.parse(sessionStorage.getItem(key) || '[]');
                if (Array.isArray(saved)) solicitudes = saved.map(sol => ({...sol, materiales: Array.isArray(sol.materiales) ? [...new Map(sol.materiales.map(mat => [mat.id, mat])).values()] : []}));
            } catch (e) {
                console.warn('No fue posible cargar solicitudes', e);
            }
            // Los objetos File y las URL temporales no se almacenan en sessionStorage.
            // BACKEND LARAVEL: guardar solicitudes y adjuntos en tablas relacionadas por solicitud_id,
            // asociar usuario mediante Auth::id(), caso mediante identificador validado en servidor,
            // validar tipo/tamaño del archivo y guardar en Storage; servir con autorización.
            const archivosEnMemoria = new Map();
            const ahora = () => {
                const d = new Date();
                return {
                    fecha: [d.getFullYear(), String(d.getMonth() + 1).padStart(2, '0'), String(d.getDate())
                        .padStart(2, '0')
                    ].join('-'),
                    hora: [String(d.getHours()).padStart(2, '0'), String(d.getMinutes()).padStart(2, '0')].join(
                        ':')
                };
            };
            const fmt = (fecha, hora) => {
                if (!fecha) return '—';
                const [y, m, d] = fecha.split('-');
                return `${d}/${m}/${y} · ${hora||'--:--'}`;
            };
            const persistir = () => sessionStorage.setItem(key, JSON.stringify(solicitudes));
            const texto = (tag, value) => {
                const el = document.createElement(tag);
                el.textContent = String(value ?? '');
                return el;
            };

            const avisoElemento = $('saveMessage');
            let avisoTimer = null;
            function limpiarAviso() {
                clearTimeout(avisoTimer);
                avisoElemento.textContent = '';
                avisoElemento.className = 'anamnesis-message';
            }
            function aviso(msg, kind = 'ok') {
                clearTimeout(avisoTimer);
                avisoElemento.textContent = msg;
                avisoElemento.className = 'anamnesis-message show ' + kind;
                avisoTimer = setTimeout(limpiarAviso, kind === 'error' ? 8000 : 4300);
            }
            avisoElemento.style.cursor = 'pointer';
            avisoElemento.title = 'Haz clic para cerrar esta notificación';
            avisoElemento.addEventListener('click', limpiarAviso);
            function confirmarGuardado(texto) {
                const promesa = window.Ficha4toBridge?.flush?.();
                if (!promesa) return aviso('Los datos solo están disponibles en esta sesión.', 'error');
                Promise.resolve(promesa).then(() => aviso(texto)).catch(error =>
                    aviso('No se pudo guardar el resultado. Revisa tu conexión e inténtalo nuevamente.', 'error'));
            }

            function cargarFecha() {
                const t = ahora();
                $('fechaSolicitud').value = t.fecha;
                $('horaSolicitud').value = t.hora;
                $('fechaMaterial').value = t.fecha;
                $('horaMaterial').value = t.hora;
            }
            let accionConfirmar = null,
                focoPrevio = null;

            function abrirConfirmacion(titulo, mensaje, accion) {
                focoPrevio = document.activeElement;
                accionConfirmar = accion;
                $('modalTitle').textContent = titulo;
                $('modalText').textContent = mensaje;
                $('confirmModal').hidden = false;
                $('modalCancel').focus();
            }

            function cerrarConfirmacion() {
                $('confirmModal').hidden = true;
                accionConfirmar = null;
                focoPrevio?.focus();
            }
            $('modalCancel').addEventListener('click', cerrarConfirmacion);
            $('modalConfirm').addEventListener('click', () => {
                const fn = accionConfirmar;
                cerrarConfirmacion();
                if (fn) fn();
            });
            $('confirmModal').addEventListener('click', e => {
                if (e.target === $('confirmModal')) cerrarConfirmacion();
            });

            function cerrarArchivo() {
                $('archivoModal').hidden = true;
                $('archivoModalContenido').replaceChildren();
            }
            $('cerrarArchivo').addEventListener('click', cerrarArchivo);
            $('archivoModal').addEventListener('click', e => {
                if (e.target === $('archivoModal')) cerrarArchivo();
            });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') {
                    if (!$('archivoModal').hidden) cerrarArchivo();
                    else if (!$('confirmModal').hidden) cerrarConfirmacion();
                }
            });

            function render() {
                const tb = $('solicitudesBody'),
                    ab = $('archivosBody'),
                    sel = $('solicitudAsociada');
                tb.replaceChildren();
                ab.replaceChildren();
                sel.replaceChildren();
                sel.append(new Option('Seleccionar solicitud registrada', ''));
                const sorted = [...solicitudes].sort((a, b) => (b.fecha + 'T' + b.hora).localeCompare(a.fecha +
                    'T' + a.hora));
                if (!sorted.length) {
                    const tr = document.createElement('tr'),
                        td = texto('td', 'No hay solicitudes registradas para este caso.');
                    td.colSpan = 4;
                    td.className = 'proc-empty';
                    tr.append(td);
                    tb.append(tr);
                }
                for (const sol of sorted) {
                    sel.add(new Option(`${fmt(sol.fecha,sol.hora)} — ${sol.examen}`, sol.id));
                    const tr = document.createElement('tr');
                    tr.append(texto('td', fmt(sol.fecha, sol.hora)), texto('td', sol.examen), texto('td', String(sol
                        .materiales.filter(m => !!m.archivoNombre).length)));
                    const td = document.createElement('td'),
                        ver = document.createElement('button');
                    ver.type = 'button';
                    ver.className = 'proc-view';
                    ver.textContent = 'Ver';
                    td.append(ver);
                    tr.append(td);
                    tb.append(tr);
                    const detalle = document.createElement('tr');
                    const celda = document.createElement('td');
                    celda.colSpan = 4;
                    celda.className = 'exa-detail';
                    detalle.hidden = true;
                    const linea = (label, val) => {
                        const p = document.createElement('p'),
                            b = texto('strong', label + ': ');
                        p.append(b, document.createTextNode(val || '—'));
                        celda.append(p);
                    };
                    linea('Fecha y hora de solicitud', fmt(sol.fecha, sol.hora));
                    linea('Examen solicitado', sol.examen);
                    linea('Indicaciones', sol.observaciones || 'Sin indicaciones');
                    linea('Archivos asociados', String(sol.materiales.filter(m => !!m.archivoNombre).length));
                    const archivos = document.createElement('div');
                    archivos.className = 'exa-file-list';
                    sol.materiales.forEach(mat => {
                        const item = document.createElement('div');
                        item.className = 'exa-file-item';
                        item.append(texto('strong', mat.nombre + ' — ' + mat.estado), texto('small',
                            `Fecha y hora del resultado: ${fmt(mat.fecha,mat.hora)}`));
                        const v = document.createElement('button');
                        v.type = 'button';
                        v.className = 'proc-view';
                        v.textContent = mat.archivoNombre ? 'Ver archivo / información' : 'Ver resultado';
                        v.addEventListener('click', () => mostrarMaterial(sol, mat));
                        item.append(v);
                        archivos.append(item);
                    });
                    celda.append(archivos);
                    const borrar = document.createElement('button');
                    borrar.type = 'button';
                    borrar.className = 'proc-delete';
                    borrar.textContent = 'Eliminar solicitud';
                    borrar.addEventListener('click', () => abrirConfirmacion('Eliminar solicitud',
                        '¿Eliminar esta solicitud y todos sus materiales asociados? Esta acción no se puede deshacer.',
                        () => {
                            sol.materiales.forEach(m => {
                                const file = archivosEnMemoria.get(m.id);
                                if (file) URL.revokeObjectURL(file);
                                archivosEnMemoria.delete(m.id);
                            });
                            solicitudes = solicitudes.filter(s => s.id !== sol.id);
                            persistir();
                            render();
                            confirmarGuardado('Solicitud eliminada correctamente.');
                        }));
                    celda.append(borrar);
                    detalle.append(celda);
                    tb.append(detalle);
                    ver.addEventListener('click', () => {
                        detalle.hidden = !detalle.hidden;
                        ver.textContent = detalle.hidden ? 'Ver' : 'Ocultar';
                    });
                }
                const todos = solicitudes.flatMap(sol => sol.materiales.map(mat => ({
                    sol,
                    mat
                }))).sort((a, b) => (b.mat.fecha + 'T' + b.mat.hora).localeCompare(a.mat.fecha + 'T' + a.mat
                    .hora));
                if (!todos.length) {
                    const tr = document.createElement('tr'),
                        td = texto('td', 'Todavía no hay resultados o materiales registrados.');
                    td.colSpan = 4;
                    td.className = 'proc-empty';
                    tr.append(td);
                    ab.append(tr);
                }
                todos.forEach(({
                    sol,
                    mat
                }) => {
                    const tr = document.createElement('tr');
                    tr.append(texto('td', fmt(mat.fecha, mat.hora)));
                    const name = document.createElement('td');
                    name.append(texto('strong', sol.examen), document.createTextNode(' / '), texto('span',
                        mat.nombre));
                    tr.append(name, texto('td', mat.estado));
                    const td = document.createElement('td'),
                        v = document.createElement('button');
                    v.type = 'button';
                    v.className = 'proc-view';
                    v.textContent = mat.archivoNombre ? 'Ver archivo' : 'Ver resultado';
                    v.addEventListener('click', () => mostrarMaterial(sol, mat));
                    td.append(v);
                    tr.append(td);
                    ab.append(tr);
                });
            }

            function mostrarMaterial(sol, mat) {
                $('archivoModalTitle').textContent = 'Visualización de archivo clínico';
                const box = $('archivoModalContenido');
                box.replaceChildren();
                const layout = document.createElement('div');
                layout.className = 'exa-viewer-body';
                const vista = document.createElement('div');
                vista.className = 'exa-viewer-image';
                const info = document.createElement('div');
                info.className = 'exa-viewer-info';
                info.append(texto('h3', sol.examen + ' / ' + mat.nombre));
                const add = (label, val) => {
                    const p = document.createElement('p');
                    p.append(texto('strong', label + ':'), document.createTextNode(val || '—'));
                    info.append(p);
                };
                add('Solicitud realizada', fmt(sol.fecha, sol.hora));
                add('Resultado / archivo registrado', fmt(mat.fecha, mat.hora));
                add('Tipo de material', mat.tipo);
                add('Estado', mat.estado);
                add('Archivo', mat.archivoNombre || 'Sin archivo adjunto');
                add('Descripción', mat.descripcion || 'Sin descripción');
                const url = archivosEnMemoria.get(mat.id);
                if (url) {
                    if (mat.mime?.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = url;
                        img.alt = mat.nombre;
                        img.className = 'exa-preview';
                        vista.append(img);
                    } else if (mat.mime === 'application/pdf') {
                        const link = document.createElement('a');
                        link.href = url;
                        link.target = '_blank';
                        link.rel = 'noopener noreferrer';
                        link.textContent = 'Abrir documento PDF';
                        vista.append(link);
                    }
                } else {
                    vista.append(texto('p', mat.archivoNombre
                        ? 'No se pudo abrir este archivo. Consulta con tu docente si el problema continúa.'
                        : 'Resultado registrado sin archivo adjunto. Consulta los detalles de la solicitud.'));
                }
                const eliminar = document.createElement('button');
                eliminar.type = 'button';
                eliminar.className = 'proc-delete';
                eliminar.textContent = 'Eliminar material';
                eliminar.addEventListener('click', () => {
                    cerrarArchivo();
                    abrirConfirmacion('Eliminar material',
                        '¿Eliminar este archivo asociado a la solicitud? Esta acción no se puede deshacer.',
                        () => {
                            const url = archivosEnMemoria.get(mat.id);
                            if (url) URL.revokeObjectURL(url);
                            archivosEnMemoria.delete(mat.id);
                            sol.materiales = sol.materiales.filter(m => m.id !== mat.id);
                            persistir();
                            render();
                            confirmarGuardado('Material eliminado correctamente.');
                        });
                });
                info.append(eliminar);
                layout.append(vista, info);
                box.append(layout);
                $('archivoModal').hidden = false;
                $('cerrarArchivo').focus();
            }
            $('solicitudForm').addEventListener('submit', e => {
                e.preventDefault();
                limpiarAviso();
                const elegidos = [...$('tiposExamen').querySelectorAll('input:checked')].map(i => i.value);
                if (!elegidos.length) {
                    aviso('Selecciona al menos un examen.', 'error');
                    return;
                }
                if (!$('solicitudForm').reportValidity()) return;
                const fecha = $('fechaSolicitud').value,
                    hora = $('horaSolicitud').value,
                    obs = $('observacionesSolicitud').value.trim();
                elegidos.forEach(examen => solicitudes.push({
                    id: crypto.randomUUID(),
                    examen,
                    fecha,
                    hora,
                    observaciones: obs,
                    materiales: []
                }));
                try {
                    persistir();
                    render();
                    $('solicitudForm').reset();
                    cargarFecha();
                    confirmarGuardado(
                        `${elegidos.length} solicitud(es) guardada(s) correctamente. Los archivos son opcionales.`);
                } catch (err) {
                    aviso('No fue posible guardar la solicitud.', 'error');
                }
            });
            // Zona de carga: clic, teclado y arrastrar/soltar admiten el mismo archivo.
            // Evita que arrastrar un archivo fuera de la zona navegue a él.
            document.addEventListener('dragover', event => {
                if (event.dataTransfer?.types?.includes?.('Files')) event.preventDefault();
            });
            document.addEventListener('drop', event => {
                if (event.dataTransfer?.files?.length) event.preventDefault();
            });
            const zonaArchivo = $('zonaArchivo'),
                inputArchivo = $('archivoMaterial'),
                nombreArchivo = $('nombreArchivoSeleccionado');

            let archivoArrastrado = null;
            function obtenerArchivo() { return archivoArrastrado || inputArchivo.files[0] || null; }
            function actualizarNombreArchivo() {
                nombreArchivo.textContent = obtenerArchivo()?.name || 'Sin archivo seleccionado';
            }
            zonaArchivo.addEventListener('click', () => inputArchivo.click());
            zonaArchivo.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    inputArchivo.click();
                }
            });
            inputArchivo.addEventListener('change', () => { archivoArrastrado = null; actualizarNombreArchivo(); });
            zonaArchivo.addEventListener('dragover', e => {
                e.preventDefault();
                zonaArchivo.classList.add('is-dragover');
            });
            zonaArchivo.addEventListener('dragleave', e => {
                if (!zonaArchivo.contains(e.relatedTarget)) zonaArchivo.classList.remove('is-dragover');
            });
            zonaArchivo.addEventListener('drop', e => {
                e.preventDefault();
                zonaArchivo.classList.remove('is-dragover');
                if (!e.dataTransfer?.files?.length) return;
                const archivo = e.dataTransfer.files[0];
                if (!['image/jpeg', 'image/png', 'application/pdf'].includes(archivo.type) || archivo.size >
                    10 * 1024 * 1024) {
                    archivoArrastrado = null;
                    inputArchivo.value = '';
                    actualizarNombreArchivo();
                    aviso('Solo JPG, PNG o PDF de hasta 10 MB.', 'error');
                    return;
                }
                // Respaldar el File arrastrado: algunos navegadores bloquean asignar input.files.
                archivoArrastrado = archivo;
                try {
                    const transferencia = new DataTransfer();
                    transferencia.items.add(archivo);
                    inputArchivo.files = transferencia.files;
                } catch (error) { console.info('Se usará directamente el archivo arrastrado.', error); }
                actualizarNombreArchivo();
            });
            let cargaMaterialEnCurso = false;
            $('materialForm').addEventListener('submit', async e => {
    if (cargaMaterialEnCurso) { e.preventDefault(); return; }
    cargaMaterialEnCurso = true;
    try {
    e.preventDefault();
    limpiarAviso();
    if (!$('materialForm').reportValidity()) return;

    const sol = solicitudes.find(s => s.id === $('solicitudAsociada').value);
    if (!sol) {
        aviso('Selecciona una solicitud válida.', 'error');
        return;
    }

    const archivo = obtenerArchivo();
    if (archivo) {
        if (!['image/jpeg', 'image/png', 'application/pdf'].includes(archivo.type)) {
            aviso('Solo se permiten archivos JPG, PNG o PDF.', 'error');
            return;
        }
        if (archivo.size > 10 * 1024 * 1024) {
            aviso('El archivo debe pesar como máximo 10 MB.', 'error');
            return;
        }
    }

    // 🚀 SUBIR ARCHIVO AL SERVIDOR (si existe)
    let archivoSubido = null;
    if (archivo) {
        const formData = new FormData();
        formData.append('archivo', archivo);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        try {
            const response = await fetch(`/4to/casos/${casoId}/secciones/examenes/upload`, {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Error al subir el archivo');
            }

            archivoSubido = await response.json();
        } catch (error) {
            aviso('No se pudo adjuntar el archivo. Verifica el formato y vuelve a intentarlo.', 'error');
            return;
        }
    }

    // 🚀 GUARDAR METADATOS EN EL PAYLOAD DE LA SECCIÓN
    const id = crypto.randomUUID();
    const mat = {
        id,
        tipo: $('tipoMaterial').value,
        estado: $('estadoMaterial').value,
        nombre: $('nombreMaterial').value.trim(),
        archivoNombre: archivoSubido?.name || null,
        archivoPath: archivoSubido?.path || null,
        archivoUrl: archivoSubido?.url || null,
        mime: archivoSubido?.mime || null,
        fecha: $('fechaMaterial').value,
        hora: $('horaMaterial').value,
        descripcion: $('descripcionMaterial').value.trim()
    };

    // Un archivo pertenece exclusivamente a la solicitud elegida; nunca replicarlo
    // entre solicitudes aunque se hayan marcado varios tipos de examen.
    if (!Array.isArray(sol.materiales)) sol.materiales = [];
    if (sol.materiales.some(x => x.id === mat.id || (mat.archivoPath && x.archivoPath === mat.archivoPath))) {
        aviso('Este material ya fue incorporado a la solicitud.', 'error');
        return;
    }
    sol.materiales.push(mat);

    try {
        persistir();
        render();
        $('materialForm').reset();
        archivoArrastrado = null;
        actualizarNombreArchivo();
        cargarFecha();
        confirmarGuardado(archivoSubido
            ? 'Resultado y archivo guardados correctamente.'
            : 'Resultado guardado correctamente sin archivo adjunto.');
    } catch (err) {
        sol.materiales.pop();
        aviso('No se pudo guardar el material. Vuelve a intentarlo.', 'error');
    }
    } finally { cargaMaterialEnCurso = false; }
});
            $('limpiarSolicitud').addEventListener('click', () => abrirConfirmacion('Limpiar solicitud',
                '¿Limpiar los campos de la solicitud? Las solicitudes guardadas no se eliminarán.', () => {
                    $('solicitudForm').reset();
                    cargarFecha();
                    limpiarAviso();
                }));
            $('cancelarMaterial').addEventListener('click', () => {
                $('materialForm').reset();
                archivoArrastrado = null;
                actualizarNombreArchivo();
                cargarFecha();
                limpiarAviso();
            });
            cargarFecha();
            render();
        });
    </script>
<script src="{{ asset('js/estudiante/tiempo-restante.js') }}" defer></script>@include('shared.app-extras')
</body>

</html>
