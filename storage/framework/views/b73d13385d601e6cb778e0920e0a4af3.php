

<?php $__env->startSection('content'); ?>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

  :root {
    --bg: #0f1117;
    --surface: #16192a;
    --surface-2: #1e2238;
    --border: rgba(255,255,255,0.07);
    --border-hover: rgba(255,255,255,0.15);
    --accent-blue: #4f8ef7;
    --accent-blue-glow: rgba(79,142,247,0.18);
    --accent-green: #34d399;
    --accent-green-glow: rgba(52,211,153,0.18);
    --text-primary: #f0f2f8;
    --text-secondary: #8892a4;
    --text-muted: #4b5263;
    --radius: 14px;
    --radius-sm: 8px;
    --transition: 0.22s cubic-bezier(.4,0,.2,1);
  }

  body {
    font-family: 'Outfit', sans-serif;
    background-color: var(--bg);
    color: var(--text-primary);
    min-height: 100vh;
  }

  .forms-wrapper {
    max-width: 760px;
    margin: 0 auto;
    padding: 3rem 1.5rem 4rem;
  }

  /* ─── Page header ─── */
  .page-header {
    margin-bottom: 2.8rem;
  }
  .page-header .eyebrow {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.7rem;
    font-weight: 500;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--accent-blue);
    margin-bottom: 0.5rem;
  }
  .page-header h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.02em;
    margin: 0 0 0.4rem;
  }
  .page-header p {
    color: var(--text-secondary);
    font-size: 0.95rem;
    font-weight: 300;
    margin: 0;
  }

  /* ─── Card ─── */
  .form-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    margin-bottom: 1.5rem;
    overflow: hidden;
    transition: border-color var(--transition), box-shadow var(--transition);
  }
  .form-card:hover {
    border-color: var(--border-hover);
    box-shadow: 0 8px 40px rgba(0,0,0,0.4);
  }

  /* ─── Card header ─── */
  .card-top {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.3rem 1.6rem;
    border-bottom: 1px solid var(--border);
  }
  .card-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .card-icon svg {
    width: 18px;
    height: 18px;
  }
  .card-icon.blue  { background: var(--accent-blue-glow);  color: var(--accent-blue); }
  .card-icon.green { background: var(--accent-green-glow); color: var(--accent-green); }

  .card-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
  }
  .card-subtitle {
    font-size: 0.78rem;
    color: var(--text-secondary);
    margin: 0;
    font-weight: 300;
  }

  /* ─── Card body ─── */
  .card-body {
    padding: 1.6rem;
  }

  /* ─── Form grid ─── */
  .form-grid {
    display: grid;
    gap: 1.2rem;
  }
  .form-grid-2 {
    grid-template-columns: 1fr 1fr;
  }
  @media (max-width: 580px) {
    .form-grid-2 { grid-template-columns: 1fr; }
  }

  /* ─── Field ─── */
  .field label {
    display: block;
    font-size: 0.78rem;
    font-weight: 500;
    letter-spacing: 0.04em;
    color: var(--text-secondary);
    text-transform: uppercase;
    margin-bottom: 0.45rem;
  }
  .field .hint {
    font-size: 0.73rem;
    color: var(--text-muted);
    margin-top: 0.3rem;
    font-weight: 300;
    text-transform: none;
    letter-spacing: 0;
    display: block;
  }

  /* ─── Inputs ─── */
  .form-control,
  .form-select {
    width: 100%;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-family: 'Outfit', sans-serif;
    font-size: 0.92rem;
    font-weight: 400;
    padding: 0.65rem 0.9rem;
    outline: none;
    transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
    -webkit-appearance: none;
    appearance: none;
    box-sizing: border-box;
  }
  .form-control::placeholder { color: var(--text-muted); }
  .form-control:hover,
  .form-select:hover {
    border-color: rgba(255,255,255,0.12);
  }
  .form-control:focus,
  .form-select:focus {
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 3px var(--accent-blue-glow);
    background: #1a1e30;
  }

  /* Custom select arrow */
  .select-wrapper {
    position: relative;
  }
  .select-wrapper::after {
    content: '';
    position: absolute;
    right: 0.9rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 5px solid var(--text-muted);
    pointer-events: none;
    transition: border-top-color var(--transition);
  }
  .select-wrapper:focus-within::after {
    border-top-color: var(--accent-blue);
  }
  .form-select option {
    background: #1e2238;
    color: var(--text-primary);
  }

  /* Number input - hide arrows */
  input[type=number]::-webkit-inner-spin-button,
  input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; }
  input[type=number] { -moz-appearance: textfield; }

  /* ─── Divider ─── */
  .form-divider {
    height: 1px;
    background: var(--border);
    margin: 0.4rem 0 1.2rem;
  }

  /* ─── Footer / actions ─── */
  .card-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 1.1rem 1.6rem;
    border-top: 1px solid var(--border);
    background: rgba(0,0,0,0.12);
  }

  /* ─── Buttons ─── */
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-family: 'Outfit', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    padding: 0.6rem 1.3rem;
    border-radius: var(--radius-sm);
    border: none;
    cursor: pointer;
    transition: transform var(--transition), box-shadow var(--transition), background var(--transition), opacity var(--transition);
  }
  .btn:active { transform: scale(0.97); }
  .btn svg { width: 15px; height: 15px; }

  .btn-blue {
    background: var(--accent-blue);
    color: #fff;
    box-shadow: 0 2px 14px rgba(79,142,247,0.3);
  }
  .btn-blue:hover {
    background: #6aa0f9;
    box-shadow: 0 4px 20px rgba(79,142,247,0.45);
  }

  .btn-green {
    background: var(--accent-green);
    color: #0f1117;
    box-shadow: 0 2px 14px rgba(52,211,153,0.3);
  }
  .btn-green:hover {
    background: #52e0ab;
    box-shadow: 0 4px 20px rgba(52,211,153,0.45);
  }

  /* ─── Focus ring for green fields ─── */
  .card-green .form-control:focus,
  .card-green .form-select:focus {
    border-color: var(--accent-green);
    box-shadow: 0 0 0 3px var(--accent-green-glow);
  }
  .card-green .select-wrapper:focus-within::after {
    border-top-color: var(--accent-green);
  }

  /* ─── Fade-in animation ─── */
  .form-card {
    animation: cardIn 0.45s ease both;
  }
  .form-card:nth-child(2) { animation-delay: 0.12s; }
  @keyframes cardIn {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
  }
</style>

<div class="forms-wrapper">

  
  <div class="page-header">
    <p class="eyebrow">Infraestructura educativa</p>
    <h1>Comparación de datos</h1>
    <p>Registra y compara información de niveles educativos y edificios escolares.</p>
  </div>

  
  <div class="form-card">
    <div class="card-top">
      <div class="card-icon blue">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-4-4l4 4 4-4"/>
        </svg>
      </div>
      <div>
        <p class="card-title">Comparar Niveles Educativos</p>
        <p class="card-subtitle">Vincula un CCT a su nivel e impartición activa</p>
      </div>
    </div>

    <div class="card-body">
      <form method="POST" action="<?php echo e(route('infraestructura.comparar')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-grid">

          <div class="field">
            <label for="cct_nivel">CCT</label>
            <input type="text" name="cct" id="cct_nivel"
                   class="form-control" placeholder="Ej. 21DPR0001A" required>
            <span class="hint">Clave del Centro de Trabajo</span>
          </div>

          <div class="form-grid form-grid-2">
            <div class="field">
              <label for="nivel">Nivel educativo</label>
              <div class="select-wrapper">
                <select name="nivel" id="nivel" class="form-select" required>
                  <option value="" disabled selected>Selecciona…</option>
                  <option value="inicial">Inicial</option>
                  <option value="preescolar">Preescolar</option>
                  <option value="primaria">Primaria</option>
                  <option value="secundaria">Secundaria</option>
                </select>
              </div>
            </div>

            <div class="field">
              <label for="imparte">¿Imparte clases?</label>
              <div class="select-wrapper">
                <select name="imparte" id="imparte" class="form-select" required>
                  <option value="" disabled selected>Selecciona…</option>
                  <option value="1">Sí imparte</option>
                  <option value="0">No imparte</option>
                </select>
              </div>
            </div>
          </div>

        </div>
      </form>
    </div>

    <div class="card-footer">
      <button type="submit" form="form-niveles" class="btn btn-blue"
              onclick="this.closest('.card-body').querySelector('form').submit()">
        <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        Guardar nivel
      </button>
    </div>
  </div>

  
  <div class="form-card card-green">
    <div class="card-top">
      <div class="card-icon green">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 21h18M9 21V7l6-4v18M9 11h6M9 15h6"/>
        </svg>
      </div>
      <div>
        <p class="card-title">Comparar Número de Edificios</p>
        <p class="card-subtitle">Registra la cantidad de edificios y su fuente de datos</p>
      </div>
    </div>

    <div class="card-body">
      <form method="POST" action="<?php echo e(route('comparacion.edificios.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-grid">

          <div class="form-grid form-grid-2">
            <div class="field">
              <label for="cct_edificios">CCT</label>
              <input type="text" name="cct" id="cct_edificios"
                     class="form-control" placeholder="Ej. 21DPR0001A" required>
            </div>

            <div class="field">
              <label for="numero_edificios">Número de edificios</label>
              <input type="number" name="numero_edificios" id="numero_edificios"
                     class="form-control" placeholder="0" min="0" required>
            </div>
          </div>

          <div class="field">
            <label for="fuente">Fuente</label>
            <input type="text" name="fuente" id="fuente"
                   class="form-control" placeholder="Ej. INEGI 2024, Censo escolar…">
            <span class="hint">Opcional — deja en blanco si no aplica</span>
          </div>

        </div>
      </form>
    </div>

    <div class="card-footer">
      <button type="submit" form="form-edificios" class="btn btn-green"
              onclick="this.closest('.card-body').querySelector('form').submit()">
        <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        Guardar edificios
      </button>
    </div>
  </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/comparar.blade.php ENDPATH**/ ?>