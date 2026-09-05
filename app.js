(() => {
  const body = document.body;
  const page = body.dataset.page || '';
  body.classList.add(`page-${page}`);
  if (body.dataset.role) sessionStorage.setItem('payroll-role', body.dataset.role);
  const userRole = body.dataset.role || sessionStorage.getItem('payroll-role') || '0';

  const publicPages = new Set(['logininiciosesiones', 'altaUsuarios', 'recuperaContrasenia', 'confirmacionUsuario']);
  const labels = {
    mostrarsueldoneto: 'Liquidaciones',
    altasueldoneto: 'Nueva liquidación',
    altaempleados: 'Nuevo empleado',
    modificarsueldobruto: 'Actualizar salarios',
    consultardatos: 'Equipo',
    generarReporte: 'Reportes'
  };
  const icons = {
    mostrarsueldoneto: '≡', altasueldoneto: '+', altaempleados: '＋',
    modificarsueldobruto: '↗', consultardatos: '◎', generarReporte: '▥'
  };
  const descriptions = {
    mostrarsueldoneto: 'Consultar y administrar recibos',
    altasueldoneto: 'Calcular un nuevo período',
    altaempleados: 'Incorporar personal',
    modificarsueldobruto: 'Actualizar remuneraciones',
    consultardatos: 'Ver y editar legajos',
    generarReporte: 'Analizar evolución salarial'
  };

  const header = document.createElement('header');
  header.className = 'app-header';
  header.innerHTML = `
    <a class="app-brand" href="${publicPages.has(page) ? 'logininiciosesiones.php' : 'menusesiones.php'}" aria-label="Ir al inicio">
      <span class="app-brand-mark">GS</span>
      <span><strong>Gestión de Sueldos</strong><span>Administración de personal</span></span>
    </a>
    ${publicPages.has(page) ? '' : '<button class="app-menu-toggle" type="button" aria-expanded="false" aria-controls="app-menu">Menú&nbsp; ☰</button>'}
  `;
  body.prepend(header);

  if (!publicPages.has(page)) {
    const menu = document.createElement('nav');
    menu.id = 'app-menu';
    menu.className = 'app-menu';
    menu.setAttribute('aria-label', 'Navegación principal');
    const availableItems = userRole === '1' ? [] : Object.entries(labels);
    menu.innerHTML = `<div class="app-menu-label">${userRole === '1' ? 'Perfil empleado' : 'Operaciones'}</div>` +
      availableItems.map(([href, label]) => `
        <a href="${href}.php" class="${page === href ? 'is-current' : ''}">
          <span class="app-menu-icon" aria-hidden="true">${icons[href]}</span>${label}
        </a>`).join('') +
      '<a class="logout" href="cerrarsesion.php"><span class="app-menu-icon" aria-hidden="true">↪</span>Cerrar sesión</a>';
    body.append(menu);

    const toggle = header.querySelector('.app-menu-toggle');
    const closeMenu = () => { menu.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); };
    toggle.addEventListener('click', (event) => {
      event.stopPropagation();
      const open = menu.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(open));
    });
    document.addEventListener('click', (event) => {
      if (!menu.contains(event.target) && !toggle.contains(event.target)) closeMenu();
    });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') closeMenu(); });
  }

  document.querySelectorAll('table').forEach((table) => {
    if (table.parentElement?.classList.contains('table-shell')) return;
    const shell = document.createElement('div');
    shell.className = 'table-shell';
    table.parentNode.insertBefore(shell, table);
    shell.append(table);
  });

  if (page === 'menusesiones') {
    document.querySelectorAll('fieldset a[href], fieldset button').forEach((item) => {
      const href = item.getAttribute('href') || '';
      const key = href.replace('.php', '');
      if (descriptions[key]) item.dataset.description = descriptions[key];
    });
  }

  if (page === 'logininiciosesiones') {
    const title = document.querySelector('h2');
    if (title) title.textContent = 'Acceso al sistema';
    const user = document.querySelector('#usuario');
    const password = document.querySelector('#contrasenia');
    const dni = document.querySelector('#dni');
    if (user) { user.placeholder = 'Ingresá tu usuario'; user.autocomplete = 'username'; }
    if (password) { password.placeholder = 'Ingresá tu contraseña'; password.autocomplete = 'current-password'; }
    if (dni) dni.placeholder = 'Número de documento';
    const submit = document.querySelector('input[type="submit"]');
    if (submit) submit.value = 'Ingresar';
  }

  let toastTimer;
  const showToast = (message) => {
    let toast = document.querySelector('.app-toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.className = 'app-toast';
      toast.setAttribute('role', 'status');
      body.append(toast);
    }
    toast.textContent = message;
    requestAnimationFrame(() => toast.classList.add('is-visible'));
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('is-visible'), 3600);
  };

  const nativeAlert = window.alert.bind(window);
  window.alert = (message) => typeof message === 'string' ? showToast(message) : nativeAlert(message);

  const askToDelete = (kind, proceed) => {
    const isEmployee = kind === 'employee';
    const modal = document.createElement('div');
    modal.className = 'app-modal';
    modal.innerHTML = `<div class="app-modal-card danger-modal" role="alertdialog" aria-modal="true" aria-labelledby="delete-title">
      <span class="modal-icon" aria-hidden="true">!</span>
      <h3 id="delete-title">Confirmar eliminación</h3>
      <p>${isEmployee ? 'Se eliminará este empleado y su información asociada.' : 'Se eliminará esta liquidación del historial.'} Esta acción no se puede deshacer.</p>
      <div class="modal-actions"><button type="button" class="secondary-action cancel-delete">Cancelar</button><button type="button" class="confirm-delete">Sí, eliminar</button></div>
    </div>`;
    body.append(modal);
    requestAnimationFrame(() => modal.classList.add('is-visible'));
    const close = () => { modal.classList.remove('is-visible'); setTimeout(() => modal.remove(), 180); };
    modal.querySelector('.cancel-delete').addEventListener('click', close);
    modal.querySelector('.confirm-delete').addEventListener('click', () => { close(); proceed(); });
    modal.addEventListener('click', (event) => { if (event.target === modal) close(); });
    modal.querySelector('.cancel-delete').focus();
  };

  document.querySelectorAll('form[data-confirm-delete]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (form.dataset.confirmed === 'true') return;
      event.preventDefault();
      askToDelete(form.dataset.confirmDelete, () => { form.dataset.confirmed = 'true'; form.requestSubmit(); });
    });
  });
  document.querySelectorAll('a[data-confirm-delete]').forEach((link) => {
    link.addEventListener('click', (event) => {
      event.preventDefault();
      askToDelete(link.dataset.confirmDelete, () => { window.location.href = link.href; });
    });
  });

  const liquidationForm = document.querySelector('#liquidacion-form');
  if (liquidationForm) {
    liquidationForm.addEventListener('submit', (event) => {
      if (liquidationForm.dataset.confirmed === 'true') return;
      event.preventDefault();
      const employee = liquidationForm.querySelector('#DNI')?.selectedOptions[0]?.textContent.trim() || '';
      const period = liquidationForm.querySelector('#periodo')?.value || '';
      const values = [
        ['Empleado', employee], ['Período', period],
        ['Ausencias remuneradas', liquidationForm.querySelector('#cantidadAR')?.value || '0'],
        ['Ausencias no remuneradas', liquidationForm.querySelector('#cantidadANR')?.value || '0'],
        ['Horas extra en feriados', liquidationForm.querySelector('#cantidadHEFER')?.value || '0'],
        ['Horas extra regulares', liquidationForm.querySelector('#cantidadHE')?.value || '0']
      ];
      const modal = document.createElement('div');
      modal.className = 'app-modal';
      modal.innerHTML = `<div class="app-modal-card review-modal" role="dialog" aria-modal="true" aria-labelledby="review-title"><span class="eyebrow">Revisión final</span><h3 id="review-title">Confirmar liquidación</h3><p>Comprobá los datos antes de registrar el período.</p><div class="review-list">${values.map(([label, value]) => `<div><span>${label}</span><strong>${value}</strong></div>`).join('')}</div><div class="modal-actions"><button type="button" class="secondary-action cancel-review">Volver a editar</button><button type="button" class="confirm-review">Confirmar y liquidar</button></div></div>`;
      body.append(modal);
      requestAnimationFrame(() => modal.classList.add('is-visible'));
      const close = () => { modal.classList.remove('is-visible'); setTimeout(() => modal.remove(), 180); };
      modal.querySelector('.cancel-review').addEventListener('click', close);
      modal.querySelector('.confirm-review').addEventListener('click', () => { close(); liquidationForm.dataset.confirmed = 'true'; liquidationForm.requestSubmit(); });
      modal.addEventListener('click', (e) => { if (e.target === modal) close(); });
    });
  }

  if (page === 'modificarsueldobruto') {
    const params = new URLSearchParams(window.location.search);
    if (params.has('salary_updated') || params.has('salary_error')) {
      const success = params.has('salary_updated');
      const modal = document.createElement('div');
      modal.className = 'app-modal';
      const employee = params.get('employee') || 'el empleado';
      const amount = params.get('amount') || '';
      modal.innerHTML = `<div class="app-modal-card result-modal" role="dialog" aria-modal="true" aria-labelledby="salary-result-title"><span class="modal-icon ${success ? 'success-icon' : ''}" aria-hidden="true">${success ? '✓' : '!'}</span><h3 id="salary-result-title">${success ? 'Salario actualizado' : 'No pudimos actualizar el salario'}</h3><p>${success ? `El nuevo salario bruto de ${employee} es $${amount}.` : 'Revisá la información e intentá nuevamente.'}</p><div class="modal-actions"><button type="button" class="confirm-review close-result">Entendido</button></div></div>`;
      body.append(modal);
      requestAnimationFrame(() => modal.classList.add('is-visible'));
      modal.querySelector('.close-result').addEventListener('click', () => { modal.classList.remove('is-visible'); setTimeout(() => modal.remove(), 180); });
      history.replaceState({}, '', 'modificarsueldobruto.php');
    }
  }

  document.querySelectorAll('.info-btn').forEach((button) => {
    button.addEventListener('click', (event) => {
      event.preventDefault();
      event.stopImmediatePropagation();
      const modal = document.createElement('div');
      modal.className = 'app-modal';
      modal.innerHTML = `<div class="app-modal-card" role="dialog" aria-modal="true" aria-labelledby="info-title">
        <h3 id="info-title">Información</h3><p></p><button type="button">Entendido</button></div>`;
      modal.querySelector('p').textContent = button.dataset.info || 'Información adicional.';
      body.append(modal);
      requestAnimationFrame(() => modal.classList.add('is-visible'));
      const close = () => { modal.classList.remove('is-visible'); setTimeout(() => modal.remove(), 180); };
      modal.querySelector('button').addEventListener('click', close);
      modal.addEventListener('click', (e) => { if (e.target === modal) close(); });
    }, true);
  });
})();
