document.addEventListener('DOMContentLoaded', () => {
  const API = {
    pacientes: '/api/pacientes/',
    profesionales: '/api/profesionales/',
    pasantes: '/api/pasantes/',
    turnos: '/api/turnos/'
  };
  const cache = { pacientes: [], profesionales: [], pasantes: [], turnos: [] };
  const configs = {
    pacientes: { singular:'paciente', form:'form-paciente', box:'formulario-paciente', id:'paciente-id', title:'titulo-formulario-paciente', button:'btn-nuevo-paciente', cancel:'btn-cancelar-paciente', table:'tabla-pacientes', fields:{dni:'paciente-dni',nombre:'paciente-nombre',apellido:'paciente-apellido',fecha_nacimiento:'paciente-fecha',telefono:'paciente-telefono',email:'paciente-email',direccion:'paciente-direccion'}, columns:[['id','ID'],['dni','DNI'],['nombre','Nombre'],['apellido','Apellido'],['telefono','Teléfono'],['email','Email']] },
    profesionales: { singular:'profesional', form:'form-profesional', box:'formulario-profesional', id:'profesional-id', title:'titulo-formulario-profesional', button:'btn-nuevo-profesional', cancel:'btn-cancelar-profesional', table:'tabla-profesionales', fields:{nombre:'profesional-nombre',apellido:'profesional-apellido',matricula:'profesional-matricula',especialidad:'profesional-especialidad',telefono:'profesional-telefono',email:'profesional-email'}, columns:[['id','ID'],['nombre','Nombre'],['apellido','Apellido'],['matricula','Matrícula'],['especialidad','Especialidad'],['telefono','Teléfono'],['email','Email']] },
    pasantes: { singular:'pasante', form:'form-pasante', box:'formulario-pasante', id:'pasante-id', title:'titulo-formulario-pasante', button:'btn-nuevo-pasante', cancel:'btn-cancelar-pasante', table:'tabla-pasantes', fields:{nombre:'pasante-nombre',apellido:'pasante-apellido',dni:'pasante-dni',telefono:'pasante-telefono',email:'pasante-email',institucion:'pasante-institucion',fecha_inicio:'pasante-inicio',fecha_fin:'pasante-fin'}, columns:[['id','ID'],['nombre','Nombre'],['apellido','Apellido'],['dni','DNI'],['institucion','Institución'],['fecha_inicio','Inicio'],['fecha_fin','Fin']] },
    turnos: { singular:'turno', form:'form-turno', box:'formulario-turno', id:'turno-id', title:'titulo-formulario-turno', button:'btn-nuevo-turno', cancel:'btn-cancelar-turno', table:'tabla-turnos', fields:{paciente_id:'turno-paciente',profesional_id:'turno-profesional',fecha:'turno-fecha',hora:'turno-hora',motivo:'turno-motivo',estado:'turno-estado',observaciones:'turno-observaciones'}, columns:[['id','ID'],['paciente_nombre','Paciente'],['profesional_nombre','Profesional'],['fecha','Fecha'],['hora','Hora'],['motivo','Motivo'],['estado','Estado']] }
  };
  const $ = id => document.getElementById(id);
  const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[ch]));
  function notify(message, isError=false) {
    let box = $('app-message');
    if (!box) { box=document.createElement('div'); box.id='app-message'; box.setAttribute('role','status'); document.querySelector('.main').prepend(box); }
    box.textContent=message; box.className=isError?'notice error':'notice'; box.hidden=false;
    window.clearTimeout(notify.timer); notify.timer=window.setTimeout(()=>{box.hidden=true;},6000);
  }
  async function request(url, options={}) {
    const response=await fetch(url,{...options,headers:{'Accept':'application/json','Content-Type':'application/json',...(options.headers||{})}});
    let result;
    try { result=await response.json(); } catch { throw new Error('El servidor devolvió una respuesta no válida. Revisá PHP y la conexión con la base de datos.'); }
    if (!response.ok || result.success===false) throw new Error(result.error || result.message || 'La operación no se pudo completar.');
    return result.data;
  }
  async function loadAll() {
    const results=await Promise.allSettled(Object.entries(API).map(async ([key,url])=>[key,await request(url)]));
    const failures=[];
    results.forEach((result,i)=>{
      const key=Object.keys(API)[i];
      if(result.status==='fulfilled') cache[result.value[0]]=Array.isArray(result.value[1])?result.value[1]:[];
      else { cache[key]=[]; failures.push(key+': '+result.reason.message); }
    });
    Object.keys(configs).forEach(key=>renderTable(key));
    populateTurnoSelects();
    if(failures.length) notify('No se pudieron cargar algunos módulos. '+failures.join(' | '),true);
  }
  function renderTable(key) {
    const cfg=configs[key], table=$(cfg.table); if(!table) return;
    const rows=cache[key];
    const counter=$('total-'+key); if(counter) counter.textContent=rows.length;
    if(!rows.length) { table.innerHTML='<tr><td colspan="'+(cfg.columns.length+1)+'">No hay registros para mostrar.</td></tr>'; return; }
    table.innerHTML=rows.map(row=>'<tr>'+cfg.columns.map(([field])=>'<td>'+escapeHtml(field==='hora' ? String(row[field]||'').slice(0,5) : row[field])+'</td>').join('')+'<td class="acciones"><button type="button" class="btn btn-edit" data-edit="'+key+'" data-id="'+Number(row.id)+'">Editar</button> <button type="button" class="btn btn-delete" data-delete="'+key+'" data-id="'+Number(row.id)+'">Eliminar</button></td></tr>').join('');
  }
  function populateTurnoSelects() {
    const patient=$('turno-paciente'), professional=$('turno-profesional');
    if(patient) { const selected=patient.value; patient.innerHTML='<option value="">Seleccionar paciente</option>'+cache.pacientes.map(p=>'<option value="'+Number(p.id)+'">'+escapeHtml(p.apellido+', '+p.nombre+' (DNI '+p.dni+')')+'</option>').join(''); patient.value=selected; }
    if(professional) { const selected=professional.value; professional.innerHTML='<option value="">Seleccionar profesional</option>'+cache.profesionales.map(p=>'<option value="'+Number(p.id)+'">'+escapeHtml(p.apellido+', '+p.nombre+' — '+p.especialidad)+'</option>').join(''); professional.value=selected; }
  }
  function resetForm(key) {
    const cfg=configs[key]; $(cfg.form).reset(); $(cfg.id).value=''; $(cfg.title).textContent='Nuevo '+cfg.singular; $(cfg.box).hidden=true; $(cfg.box).style.display='none';
  }
  function openForm(key,row=null) {
    const cfg=configs[key]; $(cfg.form).reset(); $(cfg.id).value=row?.id ?? '';
    $(cfg.title).textContent=(row?'Editar ':'Nuevo ')+cfg.singular;
    Object.entries(cfg.fields).forEach(([field,id])=>{ const el=$(id); if(el) el.value=row?.[field] ?? (field==='estado'?'pendiente':''); });
    $(cfg.box).hidden=false; $(cfg.box).style.display='block'; $(cfg.box).scrollIntoView({behavior:'smooth',block:'start'});
  }
  function formPayload(key) {
    const cfg=configs[key], payload={};
    Object.entries(cfg.fields).forEach(([field,id])=>{const el=$(id); if(el) payload[field]=el.value.trim()===''?null:el.value.trim();});
    if(key==='turnos' && payload.paciente_id) payload.paciente_id=Number(payload.paciente_id);
    if(key==='turnos' && payload.profesional_id) payload.profesional_id=Number(payload.profesional_id);
    return payload;
  }
  Object.entries(configs).forEach(([key,cfg])=>{
    if($(cfg.button)) $(cfg.button).addEventListener('click',()=>openForm(key));
    if($(cfg.cancel)) $(cfg.cancel).addEventListener('click',()=>resetForm(key));
    if($(cfg.form)) $(cfg.form).addEventListener('submit',async event=>{
      event.preventDefault();
      const id=$(cfg.id).value, method=id?'PUT':'POST', url=API[key]+(id?'?id='+encodeURIComponent(id):'');
      try { await request(url,{method,body:JSON.stringify(formPayload(key))}); notify((id?'Se actualizó':'Se creó')+' el '+cfg.singular+' correctamente.'); resetForm(key); await loadAll(); }
      catch(error) { notify(error.message,true); }
    });
  });
  document.addEventListener('click',async event=>{
    const edit=event.target.closest('[data-edit]'), del=event.target.closest('[data-delete]');
    if(edit) { const key=edit.dataset.edit, row=cache[key].find(item=>Number(item.id)===Number(edit.dataset.id)); if(row) openForm(key,row); }
    if(del) {
      const key=del.dataset.delete, id=del.dataset.id, cfg=configs[key];
      const row=cache[key].find(item=>Number(item.id)===Number(id));
      const label=key==='turnos'?'el turno':(cfg.singular+' '+(row?.nombre||row?.paciente_nombre||''));
      if(!window.confirm('¿Seguro que desea eliminar '+label+'? Esta acción no se puede deshacer.')) return;
      try { await request(API[key]+'?id='+encodeURIComponent(id),{method:'DELETE'}); notify('Registro eliminado correctamente.'); await loadAll(); }
      catch(error) { notify(error.message+' Si tiene turnos asociados, primero debe reasignarlos o eliminarlos.',true); }
    }
  });
  document.querySelectorAll('.menu button[data-section]').forEach(button=>button.addEventListener('click',()=>{
    document.querySelectorAll('.menu button').forEach(item=>item.classList.toggle('active',item===button));
    document.querySelectorAll('.section').forEach(section=>section.classList.toggle('active',section.id===button.dataset.section));
  }));
  loadAll();
});