{{-- ============================================================ --}}
{{-- resources/views/agente/chat.blade.php                       --}}
{{-- Agente Atlas CAPCEE — Vista Blade lista para HostGator      --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agente Atlas — CAPCEE</title>

    {{-- jsPDF desde CDN (no necesitas npm ni build) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    {{-- marked.js para renderizar markdown --}}
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --azul:       #005A9E;
            --azul-dark:  #003f6e;
            --azul-light: #e8f1fa;
            --verde:      #087f5b;
            --rojo:       #c0392b;
            --gris:       #f0f4f8;
            --texto:      #1a1a2e;
            --borde:      #dde4ec;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--gris);
            color: var(--texto);
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* HEADER */
        #chat-header {
            background: var(--azul);
            color: white;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.25);
            flex-shrink: 0;
        }
        #chat-header .avatar {
            width: 42px; height: 42px;
            background: rgba(255,255,255,.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }
        #chat-header h1  { font-size: 15px; font-weight: 700; }
        #chat-header p   { font-size: 11px; opacity: .8; margin-top: 2px; }
        .badge-online {
            margin-left: auto;
            background: #00c853;
            border-radius: 12px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 600;
        }

        /* MENSAJES */
        #mensajes {
            flex: 1;
            overflow-y: auto;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .mensaje-wrap          { display: flex; align-items: flex-end; gap: 8px; }
        .mensaje-wrap.usuario  { flex-direction: row-reverse; }

        .avatar-bot {
            width: 32px; height: 32px;
            background: var(--azul); color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; flex-shrink: 0;
        }

        .burbuja {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.65;
            box-shadow: 0 1px 3px rgba(0,0,0,.1);
        }
        .burbuja.bot     { background: white;       border-bottom-left-radius: 4px; }
        .burbuja.usuario { background: var(--azul); color: white; border-bottom-right-radius: 4px; }

        /* Markdown dentro de burbujas */
        .burbuja table   { border-collapse: collapse; width: 100%; font-size: 12px; margin: 8px 0; }
        .burbuja th      { background: var(--azul); color: white; padding: 6px 8px; text-align: left; }
        .burbuja td      { padding: 5px 8px; border-bottom: 1px solid var(--borde); }
        .burbuja tr:nth-child(even) td { background: var(--azul-light); }
        .burbuja code    { background: #f0f4f8; padding: 1px 5px; border-radius: 3px; font-size: 12px; }
        .burbuja ul, .burbuja ol { padding-left: 18px; }
        .burbuja p       { margin-bottom: 6px; }
        .burbuja p:last-child { margin-bottom: 0; }

        /* Botón PDF */
        .btn-pdf {
            margin-top: 8px;
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            background: var(--rojo); color: white;
            border: none; border-radius: 8px;
            cursor: pointer; font-size: 13px; font-weight: 600;
            box-shadow: 0 2px 6px rgba(192,57,43,.4);
            transition: background .2s;
        }
        .btn-pdf:hover { background: #a93226; }

        /* Typing */
        .typing          { display: flex; gap: 5px; align-items: center; padding: 12px 16px; }
        .typing span     { width: 8px; height: 8px; background: var(--azul); border-radius: 50%; animation: bounce 1.2s ease-in-out infinite; }
        .typing span:nth-child(2) { animation-delay: .2s; }
        .typing span:nth-child(3) { animation-delay: .4s; }
        @keyframes bounce { 0%,80%,100%{transform:translateY(0)} 40%{transform:translateY(-8px)} }

        /* Input */
        #chat-input-area {
            padding: 14px 16px; background: white;
            border-top: 1px solid var(--borde);
            display: flex; gap: 10px; flex-shrink: 0;
        }
        #user-input {
            flex: 1; padding: 10px 14px;
            border: 2px solid var(--borde); border-radius: 12px;
            font-size: 14px; font-family: inherit;
            resize: none; outline: none;
            transition: border-color .2s; line-height: 1.5;
        }
        #user-input:focus { border-color: var(--azul); }
        #btn-enviar {
            padding: 0 20px; background: var(--azul); color: white;
            border: none; border-radius: 12px; font-size: 20px;
            cursor: pointer; min-width: 52px; transition: background .2s;
        }
        #btn-enviar:hover    { background: var(--azul-dark); }
        #btn-enviar:disabled { background: #bbb; cursor: not-allowed; }

        @media (max-width: 600px) {
            .burbuja { max-width: 92%; font-size: 13px; }
        }
    </style>
</head>
<body>

    <div id="chat-header">
        <div class="avatar">🏫</div>
        <div>
            <h1>Agente Atlas CAPCEE</h1>
            <p>Atlas de Infraestructura Escolar · Puebla, México</p>
        </div>
        <span class="badge-online">● En línea</span>
    </div>

    <div id="mensajes">
        <div class="mensaje-wrap">
            <div class="avatar-bot">🤖</div>
            <div class="burbuja bot" id="msg-bienvenida"></div>
        </div>
    </div>

    <div id="chat-input-area">
        <textarea id="user-input" rows="2"
            placeholder="Escribe tu consulta... (Enter para enviar, Shift+Enter = nueva línea)"
        ></textarea>
        <button id="btn-enviar" title="Enviar">➤</button>
    </div>

<script>
// ── Configuración ──────────────────────────────
const API_URL = '{{ route("agente.atlas.chat") }}';
const CSRF    = document.querySelector('meta[name="csrf-token"]').content;

// Configura marked para que las tablas funcionen
marked.setOptions({ breaks: true, gfm: true });

// ── Estado ─────────────────────────────────────
let historial = [];

// ── Bienvenida ─────────────────────────────────
document.getElementById('msg-bienvenida').innerHTML = marked.parse(
`¡Hola! Soy el **Agente Atlas CAPCEE** 🏫

Tengo acceso en tiempo real a la base de datos del Atlas de Infraestructura Escolar de Puebla.

Puedes preguntarme cosas como:
- _¿Cuántas escuelas sin energía eléctrica hay en Tehuacán?_
- _Muéstrame las obras de sanitarios iniciadas en 2023_
- _Dame un resumen general del Atlas_
- _¿Cómo va la macroregión Sierra Norte?_
- _Genera un PDF con el listado de obras del municipio de Atlixco_`
);

// ── Render de mensajes ─────────────────────────
function agregarMensaje(role, contenido, pdfData) {
    const wrap = document.createElement('div');
    wrap.className = 'mensaje-wrap' + (role === 'user' ? ' usuario' : '');

    if (role !== 'user') {
        const av = document.createElement('div');
        av.className = 'avatar-bot';
        av.textContent = '🤖';
        wrap.appendChild(av);
    }

    const grupo = document.createElement('div');

    const burbuja = document.createElement('div');
    burbuja.className = 'burbuja ' + (role === 'user' ? 'usuario' : 'bot');
    burbuja.innerHTML = role === 'user'
        ? escapeHtml(contenido)
        : marked.parse(contenido);
    grupo.appendChild(burbuja);

    if (pdfData) {
        const btn = document.createElement('button');
        btn.className = 'btn-pdf';
        btn.innerHTML = '📄 Descargar PDF — ' + escapeHtml(pdfData.titulo || 'Reporte');
        btn.onclick = () => generarPDF(pdfData);
        grupo.appendChild(btn);
    }

    wrap.appendChild(grupo);
    const cont = document.getElementById('mensajes');
    cont.appendChild(wrap);
    cont.scrollTop = cont.scrollHeight;
}

function escapeHtml(t) {
    return t.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function mostrarTyping() {
    const wrap = document.createElement('div');
    wrap.id = 'typing-wrap';
    wrap.className = 'mensaje-wrap';

    const av = document.createElement('div');
    av.className = 'avatar-bot';
    av.textContent = '🤖';

    const burbuja = document.createElement('div');
    burbuja.className = 'burbuja bot typing';
    burbuja.innerHTML = '<span></span><span></span><span></span>';

    wrap.appendChild(av);
    wrap.appendChild(burbuja);

    const cont = document.getElementById('mensajes');
    cont.appendChild(wrap);
    cont.scrollTop = cont.scrollHeight;
}

function quitarTyping() {
    const el = document.getElementById('typing-wrap');
    if (el) el.remove();
}

// ── Parser del bloque PDF ──────────────────────
function parsearRespuesta(texto) {
    const regex = /@@PDF_REPORT@@\s*([\s\S]*?)\s*@@END_PDF@@/;
    const m = texto.match(regex);
    if (!m) return { texto, pdfData: null };
    try {
        return {
            texto:   texto.replace(regex, '').trim(),
            pdfData: JSON.parse(m[1]),
        };
    } catch(e) {
        console.warn('PDF parse error:', e);
        return { texto, pdfData: null };
    }
}

// ── Enviar mensaje ─────────────────────────────
async function enviar() {
    const input = document.getElementById('user-input');
    const btn   = document.getElementById('btn-enviar');
    const texto = input.value.trim();
    if (!texto || btn.disabled) return;

    agregarMensaje('user', texto, null);
    historial.push({ role: 'user', content: texto });
    input.value  = '';
    btn.disabled = true;
    mostrarTyping();

    try {
        const resp = await fetch(API_URL, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':  CSRF,
                'Accept':        'application/json',
            },
            body: JSON.stringify({ messages: historial }),
        });

        if (!resp.ok) throw new Error('HTTP ' + resp.status);

        const data = await resp.json();
        const raw  = data.reply || 'No se recibió respuesta.';
        const { texto: limpio, pdfData } = parsearRespuesta(raw);

        historial.push({ role: 'assistant', content: raw });
        quitarTyping();
        agregarMensaje('assistant', limpio, pdfData);

    } catch (err) {
        console.error(err);
        quitarTyping();
        agregarMensaje('assistant',
            '⚠️ Error de conexión con el servidor. Revisa la consola del navegador o intenta de nuevo.',
            null
        );
    } finally {
        btn.disabled = false;
        input.focus();
    }
}

document.getElementById('user-input').addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); enviar(); }
});
document.getElementById('btn-enviar').addEventListener('click', enviar);

// ── Generación de PDF ──────────────────────────
function generarPDF(report) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'letter' });
    const { tipo, titulo, subtitulo, fecha_generacion, datos } = report;
    const fecha = fecha_generacion || new Date().toLocaleDateString('es-MX');

    // Encabezado azul institucional
    doc.setFillColor(0, 90, 158);
    doc.rect(0, 0, 280, 22, 'F');
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(13);
    doc.setFont('helvetica', 'bold');
    doc.text('CAPCEE — Atlas de Infraestructura Escolar de Puebla', 14, 10);
    doc.setFontSize(8);
    doc.setFont('helvetica', 'normal');
    doc.text('Comité Administrador Poblano para la Construcción de Espacios Educativos', 14, 17);

    doc.setTextColor(0, 0, 0);
    doc.setFontSize(12);
    doc.setFont('helvetica', 'bold');
    doc.text(titulo || 'Reporte Atlas CAPCEE', 14, 32);
    doc.setFontSize(8);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(100);
    if (subtitulo) doc.text(subtitulo, 14, 38);
    doc.text('Fecha de generación: ' + fecha, 14, subtitulo ? 44 : 38);

    let y = subtitulo ? 50 : 44;

    // RESUMEN ESTADÍSTICO
    if (tipo === 'resumen_estadistico') {
        doc.autoTable({
            startY: y,
            head: [['Indicador', 'Valor']],
            body: [
                ['Total de planteles registrados',    datos.total_planteles || 0],
                ['Total de proyectos de inversión',   datos.total_proyectos_inversion || 0],
                ['Avance físico promedio',             datos.promedio_avance_fisico_pct || '0%'],
                ['Avance financiero promedio',         datos.promedio_avance_financiero_pct || '0%'],
                ['Monto total contratado',             datos.monto_total_contratado || '$0'],
            ],
            theme: 'grid',
            headStyles: { fillColor: [0, 90, 158], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [232, 241, 250] },
        });
        if (datos.proyectos_por_estatus) {
            doc.autoTable({
                startY: doc.lastAutoTable.finalY + 8,
                head: [['Estatus de Obra', 'Cantidad de Proyectos']],
                body: Object.entries(datos.proyectos_por_estatus),
                theme: 'grid',
                headStyles: { fillColor: [8, 127, 91], textColor: 255 },
            });
        }
    }

    // FICHA DE PLANTEL
    if (tipo === 'ficha_plantel' && datos.plantel) {
        const pl = datos.plantel;
        doc.autoTable({
            startY: y,
            head: [['Campo', 'Valor']],
            body: [
                ['CCT',              pl.cct            || 'N/D'],
                ['Nombre escuela',   pl.nombre_escuela || 'N/D'],
                ['Nivel educativo',  pl.nivel_educativo || 'N/D'],
                ['Municipio',        pl.nombre_municipio || 'N/D'],
                ['Macroregión',      pl.nombre_macroregion || 'N/D'],
                ['Estatus plantel',  pl.estatus_plantel || 'N/D'],
                ['Total obras',      datos.total_obras || 0],
                ['Inversión total',  datos.inversion_total || '$0'],
            ],
            theme: 'grid',
            headStyles: { fillColor: [0, 90, 158], textColor: 255 },
        });
        doc.autoTable({
            startY: doc.lastAutoTable.finalY + 8,
            head: [['Servicio Básico', 'Estado']],
            body: [
                ['Red eléctrica contratada', pl.energia_red_contrato    ? '✓ Cuenta con el servicio' : '✗ No disponible'],
                ['Paneles solares',          pl.energia_paneles_solares ? '✓ Instalados'             : '✗ No instalados'],
                ['Sin ninguna energía',      pl.sin_energia             ? '⚠ Sin servicio eléctrico' : '✓ Tiene energía'],
                ['Agua red pública',         pl.agua_red_publica        ? '✓ Conectada'              : '✗ Sin conexión'],
                ['Cisterna',                 pl.cisterna                ? '✓ Cuenta con cisterna'    : '✗ Sin cisterna'],
                ['Drenaje público',          pl.drenaje_publico         ? '✓ Conectado'              : '✗ Sin drenaje'],
                ['Fosa séptica',             pl.fosa_septica            ? '✓ Instalada'              : '✗ No instalada'],
                ['Acceso a internet',        pl.internet_acceso         ? '✓ Con acceso'             : '✗ Sin internet'],
            ],
            theme: 'grid',
            headStyles: { fillColor: [8, 127, 91], textColor: 255 },
        });
        if (datos.obras_en_plantel && datos.obras_en_plantel.length) {
            doc.autoTable({
                startY: doc.lastAutoTable.finalY + 8,
                head: [['Folio', 'Proyecto', 'Módulo', 'Av. Físico', 'Av. Financiero', 'Monto', 'Estatus']],
                body: datos.obras_en_plantel.map(o => [
                    o.folio_ppi || '', o.nombre_proyecto || '', o.modulo || '',
                    (o.av_fis_real || 0) + '%', (o.av_fin_real || 0) + '%',
                    o.monto_contratado ? '$' + Number(o.monto_contratado).toLocaleString('es-MX') : '',
                    o.estatus_general || '',
                ]),
                theme: 'striped',
                headStyles: { fillColor: [150, 50, 0], textColor: 255 },
                styles: { fontSize: 7 },
            });
        }
    }

    // REPORTE / LISTADO DE OBRAS
    if (tipo === 'reporte_obras' || tipo === 'listado_obras') {
        const obras = datos.obras_ejemplo || (Array.isArray(datos) ? datos : []);
        if (obras.length) {
            doc.autoTable({
                startY: y,
                head: [['CCT','Proyecto','Municipio','Módulo','Av. Fís.','Av. Fin.','Monto Contratado','Estatus']],
                body: obras.map(o => [
                    o.cct || '',
                    o.nombre_proyecto || '',
                    o.municipio || '',
                    o.modulo || '',
                    (o.avance_fisico_pct || o.av_fis_real || 0) + '%',
                    (o.avance_financiero_pct || o.av_fin_real || 0) + '%',
                    o.monto_contratado ? '$' + Number(o.monto_contratado).toLocaleString('es-MX') : '',
                    o.estatus_general || '',
                ]),
                theme: 'striped',
                headStyles: { fillColor: [0, 90, 158], textColor: 255 },
                styles: { fontSize: 7 },
                columnStyles: { 1: { cellWidth: 45 } },
            });
        }
        if (datos.resumen_agregado) {
            doc.autoTable({
                startY: doc.lastAutoTable.finalY + 8,
                head: [['Métrica de Resumen', 'Valor']],
                body: Object.entries(datos.resumen_agregado).map(([k, v]) => [k.replace(/_/g,' '), v]),
                theme: 'grid',
                headStyles: { fillColor: [8, 127, 91], textColor: 255 },
            });
        }
    }

    // Pie de página en todas las hojas
    const totalPags = doc.internal.getNumberOfPages();
    for (let i = 1; i <= totalPags; i++) {
        doc.setPage(i);
        doc.setFontSize(7);
        doc.setTextColor(160);
        doc.text(
            `Atlas de Infraestructura Escolar de Puebla — CAPCEE  |  Página ${i} de ${totalPags}  |  ${fecha}`,
            14,
            doc.internal.pageSize.height - 8
        );
    }

    doc.save('CAPCEE_' + (tipo || 'reporte') + '_' + new Date().toISOString().slice(0,10) + '.pdf');
}
</script>

</body>
</html>
