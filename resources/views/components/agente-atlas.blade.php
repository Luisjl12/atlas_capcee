{{-- ============================================================
     Agente IA Atlas CAPCEE — Widget Flotante
     Incluir al final de tu layout principal, antes de </body>:
       @include('components.agente-atlas')
     ============================================================ --}}

{{-- Fuente --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">

<style>
  /* ── Variables de tema CAPCEE ───────────────────────────── */
  :root {
    --cap-brand:     #1A5FA8;
    --cap-brand-lt:  #E8F1FB;
    --cap-brand-dk:  #0D3E72;
    --cap-text:      #1C1C1E;
    --cap-muted:     #6B7280;
    --cap-bg:        #FFFFFF;
    --cap-surface:   #F5F7FA;
    --cap-border:    rgba(0,0,0,.10);
    --cap-radius:    14px;
    --cap-shadow:    0 8px 32px rgba(0,0,0,.14), 0 2px 8px rgba(0,0,0,.08);
    --cap-font:      'DM Sans', sans-serif;
  }

  /* ── Botón flotante ─────────────────────────────────────── */
  #cap-fab {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9998;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--cap-brand);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 18px rgba(26,95,168,.40);
    transition: transform .2s, box-shadow .2s;
  }
  #cap-fab:hover { transform: scale(1.07); box-shadow: 0 6px 24px rgba(26,95,168,.50); }
  #cap-fab svg  { width: 26px; height: 26px; fill: #fff; transition: transform .3s; }
  #cap-fab.open svg { transform: rotate(90deg); }

  /* Burbuja de notificación */
  #cap-fab-dot {
    position: absolute;
    top: 4px; right: 4px;
    width: 10px; height: 10px;
    border-radius: 50%;
    background: #F59E0B;
    border: 2px solid #fff;
  }

  /* ── Panel del chat ─────────────────────────────────────── */
  #cap-panel {
    position: fixed;
    bottom: 96px;
    right: 28px;
    z-index: 9999;
    width: 360px;
    max-height: 560px;
    background: var(--cap-bg);
    border-radius: var(--cap-radius);
    box-shadow: var(--cap-shadow);
    display: flex;
    flex-direction: column;
    font-family: var(--cap-font);
    border: 1px solid var(--cap-border);
    transform: translateY(16px) scale(.97);
    opacity: 0;
    pointer-events: none;
    transition: transform .25s cubic-bezier(.34,1.56,.64,1), opacity .2s;
  }
  #cap-panel.visible {
    transform: translateY(0) scale(1);
    opacity: 1;
    pointer-events: all;
  }

  /* Cabecera */
  .cap-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    background: var(--cap-brand);
    border-radius: var(--cap-radius) var(--cap-radius) 0 0;
    flex-shrink: 0;
  }
  .cap-header-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,.20);
    display: flex; align-items: center; justify-content: center;
  }
  .cap-header-avatar svg { width: 20px; height: 20px; fill: #fff; }
  .cap-header-info { flex: 1; }
  .cap-header-name  { color: #fff; font-size: 13.5px; font-weight: 500; margin: 0; line-height: 1.3; }
  .cap-header-sub   { color: rgba(255,255,255,.70); font-size: 11px; margin: 0; }
  .cap-header-close {
    background: rgba(255,255,255,.15); border: none; cursor: pointer;
    border-radius: 6px; padding: 4px; display: flex; align-items: center; justify-content: center;
    transition: background .15s;
  }
  .cap-header-close:hover { background: rgba(255,255,255,.28); }
  .cap-header-close svg { width: 16px; height: 16px; fill: #fff; }

  /* Área de mensajes */
  #cap-messages {
    flex: 1;
    overflow-y: auto;
    padding: 14px 12px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: var(--cap-surface);
    scroll-behavior: smooth;
  }
  #cap-messages::-webkit-scrollbar { width: 4px; }
  #cap-messages::-webkit-scrollbar-thumb { background: rgba(0,0,0,.15); border-radius: 2px; }

  /* Burbujas */
  .cap-bubble-row { display: flex; gap: 7px; align-items: flex-end; }
  .cap-bubble-row.user { flex-direction: row-reverse; }
  .cap-bubble-icon {
    width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
    background: var(--cap-brand-lt); display: flex; align-items: center; justify-content: center;
  }
  .cap-bubble-icon svg { width: 14px; height: 14px; fill: var(--cap-brand); }
  .cap-bubble {
    max-width: 80%;
    padding: 9px 13px;
    font-size: 13px;
    line-height: 1.6;
    border-radius: 14px 14px 14px 4px;
    background: var(--cap-bg);
    color: var(--cap-text);
    border: 1px solid var(--cap-border);
    white-space: pre-wrap;
    word-break: break-word;
  }
  .cap-bubble-row.user .cap-bubble {
    background: var(--cap-brand);
    color: #fff;
    border-color: transparent;
    border-radius: 14px 14px 4px 14px;
  }

  /* Chips de acceso rápido */
  .cap-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    padding: 10px 12px 6px;
    background: var(--cap-surface);
    border-top: 1px solid var(--cap-border);
    flex-shrink: 0;
  }
  .cap-chip {
    font-family: var(--cap-font);
    font-size: 11.5px;
    padding: 4px 10px;
    border-radius: 99px;
    border: 1px solid var(--cap-brand);
    background: var(--cap-brand-lt);
    color: var(--cap-brand-dk);
    cursor: pointer;
    transition: background .15s;
    white-space: nowrap;
  }
  .cap-chip:hover { background: #d0e4f7; }

  /* Input */
  .cap-input-row {
    display: flex;
    gap: 8px;
    padding: 10px 12px;
    background: var(--cap-bg);
    border-top: 1px solid var(--cap-border);
    border-radius: 0 0 var(--cap-radius) var(--cap-radius);
    flex-shrink: 0;
  }
  #cap-input {
    flex: 1;
    font-family: var(--cap-font);
    font-size: 13px;
    padding: 8px 12px;
    border: 1px solid var(--cap-border);
    border-radius: 8px;
    background: var(--cap-surface);
    color: var(--cap-text);
    outline: none;
    transition: border-color .15s;
  }
  #cap-input:focus { border-color: var(--cap-brand); }
  #cap-send {
    font-family: var(--cap-font);
    background: var(--cap-brand);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0 14px;
    font-size: 13px;
    cursor: pointer;
    display: flex; align-items: center; gap: 5px;
    transition: background .15s, transform .1s;
    white-space: nowrap;
  }
  #cap-send:hover   { background: var(--cap-brand-dk); }
  #cap-send:active  { transform: scale(.97); }
  #cap-send:disabled { opacity: .55; cursor: not-allowed; }
  #cap-send svg { width: 14px; height: 14px; fill: #fff; }

  /* Typing dots */
  .cap-typing span {
    display: inline-block;
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--cap-muted);
    animation: capDot .9s infinite;
  }
  .cap-typing span:nth-child(2) { animation-delay: .15s; }
  .cap-typing span:nth-child(3) { animation-delay: .30s; }
  @keyframes capDot {
    0%,80%,100% { transform: translateY(0); opacity:.4; }
    40%          { transform: translateY(-5px); opacity:1; }
  }

  /* Responsive */
  @media (max-width: 420px) {
    #cap-panel { width: calc(100vw - 24px); right: 12px; }
    #cap-fab   { right: 16px; bottom: 16px; }
  }
</style>

{{-- ── Botón flotante ───────────────────────────────────── --}}
<button id="cap-fab" aria-label="Abrir asistente Atlas" onclick="capToggle()">
  <div id="cap-fab-dot"></div>
  {{-- Ícono robot --}}
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7v2a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2a7 7 0 0 1 7-7h1V5.73A2 2 0 0 1 10 4a2 2 0 0 1 2-2zM9 11a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm6 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-5 4h4v1H10v-1z"/>
  </svg>
</button>

{{-- ── Panel del chat ───────────────────────────────────── --}}
<div id="cap-panel" role="dialog" aria-label="Asistente IA Atlas CAPCEE" aria-modal="false">

  {{-- Cabecera --}}
  <div class="cap-header">
    <div class="cap-header-avatar">
      <svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7v2a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2a7 7 0 0 1 7-7h1V5.73A2 2 0 0 1 10 4a2 2 0 0 1 2-2z"/></svg>
    </div>
    <div class="cap-header-info">
      <p class="cap-header-name">Agente Atlas — CAPCEE</p>
      <p class="cap-header-sub">Infraestructura Escolar · Puebla</p>
    </div>
    <button class="cap-header-close" onclick="capToggle()" aria-label="Cerrar">
      <svg viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12" stroke="#fff" stroke-width="2" stroke-linecap="round" fill="none"/></svg>
    </button>
  </div>

  {{-- Mensajes --}}
  <div id="cap-messages">
    <div class="cap-bubble-row">
      <div class="cap-bubble-icon">
        <svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7v2a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2a7 7 0 0 1 7-7h1V5.73A2 2 0 0 1 10 4a2 2 0 0 1 2-2z"/></svg>
      </div>
      <div class="cap-bubble">
        Hola 👋 Soy el asistente del <strong>Atlas CAPCEE</strong>. Puedo responder preguntas sobre obras, planteles, geodatos, expedientes y el sistema en general. ¿En qué te puedo ayudar?
      </div>
    </div>
  </div>

  {{-- Chips de acceso rápido --}}
  <div class="cap-chips">
    <button class="cap-chip" onclick="capQuick('Dime un resumen de las obras totales')">Resumen de obras</button>
    <button class="cap-chip" onclick="capQuick('¿Cuántas escuelas tienen paneles solares?')">Paneles solares</button>
    <button class="cap-chip" onclick="capQuick('¿Qué escuelas cuentan con suministro eléctrico general?')">Suministro eléctrico</button>
    <button class="cap-chip" onclick="capQuick('¿Cómo consulto el avance de una obra?')">Avance de obra</button>
    <button class="cap-chip" onclick="capQuick('¿Cuáles son los módulos del Atlas?')">Módulos</button>
  </div>

  {{-- Input --}}
  <div class="cap-input-row">
    <input id="cap-input" type="text" placeholder="Escribe tu consulta…"
           onkeydown="if(event.key==='Enter' && !event.shiftKey){ event.preventDefault(); capSend(); }" />
    <button id="cap-send" onclick="capSend()">
      <svg viewBox="0 0 24 24"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
      Enviar
    </button>
  </div>
</div>

<script>
(function () {
  /* ── Estado ──────────────────────────────────────────────── */
  var isOpen    = false;
  var isBusy    = false;
  var history   = [];
  var dotEl     = document.getElementById('cap-fab-dot');

  /* ── Abrir / cerrar ──────────────────────────────────────── */
  window.capToggle = function () {
    isOpen = !isOpen;
    var panel = document.getElementById('cap-panel');
    var fab   = document.getElementById('cap-fab');
    panel.classList.toggle('visible', isOpen);
    fab.classList.toggle('open', isOpen);
    if (isOpen) {
      if (dotEl) dotEl.style.display = 'none';
      document.getElementById('cap-input').focus();
    }
  };

  /* ── Prompt rápido ───────────────────────────────────────── */
  window.capQuick = function (text) {
    document.getElementById('cap-input').value = text;
    capSend();
  };

  /* ── Añadir burbuja ──────────────────────────────────────── */
  function addBubble(role, text, id) {
    var msgs = document.getElementById('cap-messages');
    var row  = document.createElement('div');
    row.className = 'cap-bubble-row' + (role === 'user' ? ' user' : '');
    if (id) row.id = id;

    var icon = '';
    if (role !== 'user') {
      icon = '<div class="cap-bubble-icon"><svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7v2a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2a7 7 0 0 1 7-7h1V5.73A2 2 0 0 1 10 4a2 2 0 0 1 2-2z"/></svg></div>';
    }
    var bubble = document.createElement('div');
    bubble.className = 'cap-bubble';

    if (text === '__typing__') {
      bubble.innerHTML = '<div class="cap-typing"><span></span><span></span><span></span></div>';
    } else {
      bubble.textContent = text;
    }

    if (role !== 'user') {
      row.innerHTML = icon;
      row.appendChild(bubble);
    } else {
      row.appendChild(bubble);
    }
    msgs.appendChild(row);
    msgs.scrollTop = msgs.scrollHeight;
    return bubble;
  }

  /* ── Enviar mensaje ──────────────────────────────────────── */
  window.capSend = function () {
    if (isBusy) return;
    var input = document.getElementById('cap-input');
    var text  = input.value.trim();
    if (!text) return;

    input.value = '';
    addBubble('user', text);
    history.push({ role: 'user', content: text });
    isBusy = true;
    document.getElementById('cap-send').disabled = true;

    /* Indicador de escritura */
    var typingRow    = document.createElement('div');
    typingRow.id     = 'cap-typing-row';
    typingRow.className = 'cap-bubble-row';
    typingRow.innerHTML = '<div class="cap-bubble-icon"><svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7v2a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2a7 7 0 0 1 7-7h1V5.73A2 2 0 0 1 10 4a2 2 0 0 1 2-2z"/></svg></div><div class="cap-bubble"><div class="cap-typing"><span></span><span></span><span></span></div></div>';
    document.getElementById('cap-messages').appendChild(typingRow);
    document.getElementById('cap-messages').scrollTop = 9999;

    fetch('/agente-atlas/chat', { // <-- Regresamos a la ruta relativa
      method: 'POST',
      credentials: 'include', // Cambiar
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json', // Mantén esto
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
          ? document.querySelector('meta[name="csrf-token"]').content
          : ''
      },
      body: JSON.stringify({ messages: history })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
      var reply = data.reply || 'No pude obtener una respuesta. Inténtalo de nuevo.';
      var el = document.getElementById('cap-typing-row');
      if (el) el.remove();
      addBubble('assistant', reply);
      history.push({ role: 'assistant', content: reply });
    })
    .catch(function () {
      var el = document.getElementById('cap-typing-row');
      if (el) el.remove();
      addBubble('assistant', 'Error de conexión con el servidor. Por favor recarga e intenta de nuevo.');
    })
    .finally(function () {
      isBusy = false;
      document.getElementById('cap-send').disabled = false;
      document.getElementById('cap-input').focus();
    });
  };
})();
</script>
