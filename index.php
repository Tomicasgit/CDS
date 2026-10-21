<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Sistema de gestión de pacientes, profesionales, pasantes y turnos de la Clínica Paula Saucedo.">
  <title>Clínica Paula Saucedo - Sistema de Gestión</title>
  <link rel="stylesheet" href="/css/style.css">
  <script src="/js/app.js" defer></script>
</head>
<body>
<div class="app">
  <aside class="sidebar">
    <div class="logo"><h1>Clínica Paula Saucedo</h1><p>Sistema de gestión</p></div>
    <nav aria-label="Navegación principal">
      <ul class="menu">
        <li><button type="button" class="active" data-section="inicio">Inicio</button></li>
        <li><button type="button" data-section="pacientes">Pacientes</button></li>
        <li><button type="button" data-section="profesionales">Profesionales</button></li>
        <li><button type="button" data-section="pasantes">Pasantes</button></li>
        <li><button type="button" data-section="turnos">Turnos</button></li>
      </ul>
    </nav>
  </aside>
  <main class="main">
    <div id="app-message" class="notice" role="status" aria-live="polite" hidden></div>
    <section id="inicio" class="section active">
      <header class="header"><h2>Inicio</h2><p>Resumen general de la clínica.</p></header>
      <div class="cards">
        <article class="card"><h3>Pacientes</h3><div class="number" id="total-pacientes">0</div></article>
        <article class="card"><h3>Profesionales</h3><div class="number" id="total-profesionales">0</div></article>
        <article class="card"><h3>Pasantes</h3><div class="number" id="total-pasantes">0</div></article>
        <article class="card"><h3>Turnos</h3><div class="number" id="total-turnos">0</div></article>
      </div>
      <div class="panel"><h3>Panel de gestión</h3><p>Seleccione un módulo del menú para consultar, registrar, modificar o eliminar información. Los cambios se guardan en la base de datos de la clínica.</p></div>
    </section>
    <section id="pacientes" class="section">
      <header class="header"><div class="header-content"><div><h2>Pacientes</h2><p>Administración de los datos de pacientes.</p></div><button type="button" class="btn btn-primary" id="btn-nuevo-paciente">+ Nuevo paciente</button></div></header>
      <div class="panel form-panel" id="formulario-paciente" style="display:none" hidden>
        <h3 id="titulo-formulario-paciente">Nuevo paciente</h3>
        <form id="form-paciente"><input type="hidden" id="paciente-id">
          <div class="form-grid">
            <div class="form-group"><label for="paciente-dni">DNI *</label><input id="paciente-dni" required maxlength="20"></div>
            <div class="form-group"><label for="paciente-nombre">Nombre *</label><input id="paciente-nombre" required maxlength="100"></div>
            <div class="form-group"><label for="paciente-apellido">Apellido *</label><input id="paciente-apellido" required maxlength="100"></div>
            <div class="form-group"><label for="paciente-fecha">Fecha de nacimiento</label><input id="paciente-fecha" type="date"></div>
            <div class="form-group"><label for="paciente-telefono">Teléfono</label><input id="paciente-telefono" type="tel" maxlength="30"></div>
            <div class="form-group"><label for="paciente-email">Email</label><input id="paciente-email" type="email" maxlength="150"></div>
            <div class="form-group form-group-full"><label for="paciente-direccion">Dirección</label><input id="paciente-direccion" maxlength="255"></div>
          </div>
          <div class="form-actions"><button type="submit" class="btn btn-primary">Guardar paciente</button><button type="button" class="btn btn-secondary" id="btn-cancelar-paciente">Cancelar</button></div>
        </form>
      </div>
      <div class="panel"><h3>Listado de pacientes</h3><div class="table-container"><table><thead><tr><th>ID</th><th>DNI</th><th>Nombre</th><th>Apellido</th><th>Teléfono</th><th>Email</th><th>Acciones</th></tr></thead><tbody id="tabla-pacientes"><tr><td colspan="7">Cargando...</td></tr></tbody></table></div></div>
    </section>
    <section id="profesionales" class="section">
      <header class="header"><div class="header-content"><div><h2>Profesionales</h2><p>Administración de profesionales y especialidades.</p></div><button type="button" class="btn btn-primary" id="btn-nuevo-profesional">+ Nuevo profesional</button></div></header>
      <div class="panel form-panel" id="formulario-profesional" style="display:none" hidden>
        <h3 id="titulo-formulario-profesional">Nuevo profesional</h3>
        <form id="form-profesional"><input type="hidden" id="profesional-id">
          <div class="form-grid">
            <div class="form-group"><label for="profesional-nombre">Nombre *</label><input id="profesional-nombre" required maxlength="100"></div>
            <div class="form-group"><label for="profesional-apellido">Apellido *</label><input id="profesional-apellido" required maxlength="100"></div>
            <div class="form-group"><label for="profesional-matricula">Matrícula</label><input id="profesional-matricula" maxlength="50"></div>
            <div class="form-group"><label for="profesional-especialidad">Especialidad *</label><input id="profesional-especialidad" required maxlength="100"></div>
            <div class="form-group"><label for="profesional-telefono">Teléfono</label><input id="profesional-telefono" type="tel" maxlength="30"></div>
            <div class="form-group"><label for="profesional-email">Email</label><input id="profesional-email" type="email" maxlength="150"></div>
          </div>
          <div class="form-actions"><button type="submit" class="btn btn-primary">Guardar profesional</button><button type="button" class="btn btn-secondary" id="btn-cancelar-profesional">Cancelar</button></div>
        </form>
      </div>
      <div class="panel"><h3>Listado de profesionales</h3><div class="table-container"><table><thead><tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Matrícula</th><th>Especialidad</th><th>Teléfono</th><th>Email</th><th>Acciones</th></tr></thead><tbody id="tabla-profesionales"><tr><td colspan="8">Cargando...</td></tr></tbody></table></div></div>
    </section>
    <section id="pasantes" class="section">
      <header class="header"><div class="header-content"><div><h2>Pasantes</h2><p>Administración de pasantes y períodos de práctica.</p></div><button type="button" class="btn btn-primary" id="btn-nuevo-pasante">+ Nuevo pasante</button></div></header>
      <div class="panel form-panel" id="formulario-pasante" style="display:none" hidden>
        <h3 id="titulo-formulario-pasante">Nuevo pasante</h3>
        <form id="form-pasante"><input type="hidden" id="pasante-id">
          <div class="form-grid">
            <div class="form-group"><label for="pasante-nombre">Nombre *</label><input id="pasante-nombre" required maxlength="100"></div>
            <div class="form-group"><label for="pasante-apellido">Apellido *</label><input id="pasante-apellido" required maxlength="100"></div>
            <div class="form-group"><label for="pasante-dni">DNI</label><input id="pasante-dni" maxlength="20"></div>
            <div class="form-group"><label for="pasante-telefono">Teléfono</label><input id="pasante-telefono" type="tel" maxlength="30"></div>
            <div class="form-group"><label for="pasante-email">Email</label><input id="pasante-email" type="email" maxlength="150"></div>
            <div class="form-group"><label for="pasante-institucion">Institución</label><input id="pasante-institucion" maxlength="150"></div>
            <div class="form-group"><label for="pasante-inicio">Fecha de inicio</label><input id="pasante-inicio" type="date"></div>
            <div class="form-group"><label for="pasante-fin">Fecha de finalización</label><input id="pasante-fin" type="date"></div>
          </div>
          <div class="form-actions"><button type="submit" class="btn btn-primary">Guardar pasante</button><button type="button" class="btn btn-secondary" id="btn-cancelar-pasante">Cancelar</button></div>
        </form>
      </div>
      <div class="panel"><h3>Listado de pasantes</h3><div class="table-container"><table><thead><tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>DNI</th><th>Institución</th><th>Inicio</th><th>Fin</th><th>Acciones</th></tr></thead><tbody id="tabla-pasantes"><tr><td colspan="8">Cargando...</td></tr></tbody></table></div></div>
    </section>
    <section id="turnos" class="section">
      <header class="header"><div class="header-content"><div><h2>Turnos</h2><p>Programación y seguimiento de turnos.</p></div><button type="button" class="btn btn-primary" id="btn-nuevo-turno">+ Nuevo turno</button></div></header>
      <div class="panel form-panel" id="formulario-turno" style="display:none" hidden>
        <h3 id="titulo-formulario-turno">Nuevo turno</h3>
        <form id="form-turno"><input type="hidden" id="turno-id">
          <div class="form-grid">
            <div class="form-group"><label for="turno-paciente">Paciente *</label><select id="turno-paciente" required><option value="">Cargando pacientes...</option></select></div>
            <div class="form-group"><label for="turno-profesional">Profesional *</label><select id="turno-profesional" required><option value="">Cargando profesionales...</option></select></div>
            <div class="form-group"><label for="turno-fecha">Fecha *</label><input id="turno-fecha" type="date" required></div>
            <div class="form-group"><label for="turno-hora">Hora *</label><input id="turno-hora" type="time" required></div>
            <div class="form-group"><label for="turno-estado">Estado</label><select id="turno-estado"><option value="pendiente">Pendiente</option><option value="confirmado">Confirmado</option><option value="atendido">Atendido</option><option value="cancelado">Cancelado</option></select></div>
            <div class="form-group"><label for="turno-motivo">Motivo</label><input id="turno-motivo" maxlength="1000"></div>
            <div class="form-group form-group-full"><label for="turno-observaciones">Observaciones</label><textarea id="turno-observaciones" rows="3"></textarea></div>
          </div>
          <div class="form-actions"><button type="submit" class="btn btn-primary">Guardar turno</button><button type="button" class="btn btn-secondary" id="btn-cancelar-turno">Cancelar</button></div>
        </form>
      </div>
      <div class="panel"><h3>Listado de turnos</h3><div class="table-container"><table><thead><tr><th>ID</th><th>Paciente</th><th>Profesional</th><th>Fecha</th><th>Hora</th><th>Motivo</th><th>Estado</th><th>Acciones</th></tr></thead><tbody id="tabla-turnos"><tr><td colspan="8">Cargando...</td></tr></tbody></table></div></div>
    </section>
    <footer class="footer"><p>Clínica Paula Saucedo · Sistema de gestión</p></footer>
  </main>
</div>
</body>
</html>