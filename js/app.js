document.addEventListener('DOMContentLoaded', () => {

    // ==========================================
    // MENÚ
    // ==========================================

    const menuButtons = document.querySelectorAll('.menu button');
    const sections = document.querySelectorAll('.section');

    menuButtons.forEach(button => {

        button.addEventListener('click', () => {

            const target = button.dataset.section;

            menuButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            sections.forEach(section => {
                section.classList.remove('active');
            });

            button.classList.add('active');

            const section = document.getElementById(target);

            if (section) {
                section.classList.add('active');
            }

        });

    });


    // ==========================================
    // CARGAR TODOS LOS DATOS
    // ==========================================

    cargarDatos();


    async function cargarDatos() {

        try {

            const pacientes = await obtenerDatos(
                '/api/pacientes/'
            );

            const profesionales = await obtenerDatos(
                '/api/profesionales/'
            );

            const pasantes = await obtenerDatos(
                '/api/pasantes/'
            );

            const turnos = await obtenerDatos(
                '/api/turnos/'
            );


            mostrarPacientes(pacientes);

            mostrarProfesionales(profesionales);

            mostrarPasantes(pasantes);

            mostrarTurnos(turnos);


        } catch (error) {

            console.error(
                'Error al cargar los datos:',
                error
            );

        }

    }


    // ==========================================
    // OBTENER DATOS DE LA API
    // ==========================================

    async function obtenerDatos(url) {

        const respuesta = await fetch(url);

        const resultado = await respuesta.json();


        if (!respuesta.ok) {

            throw new Error(
                resultado.message ||
                `Error HTTP ${respuesta.status}`
            );

        }


        return resultado.data;

    }


    // ==========================================
    // PACIENTES
    // ==========================================

    function mostrarPacientes(pacientes) {

        const total =
            document.getElementById(
                'total-pacientes'
            );

        const tabla =
            document.getElementById(
                'tabla-pacientes'
            );


        total.textContent = pacientes.length;

        tabla.innerHTML = '';


        if (pacientes.length === 0) {

            tabla.innerHTML = `
                <tr>
                    <td colspan="7">
                        No hay pacientes registrados.
                    </td>
                </tr>
            `;

            return;

        }


        pacientes.forEach(paciente => {

            const fila =
                document.createElement('tr');


            fila.innerHTML = `

                <td>
                    ${paciente.id}
                </td>

                <td>
                    ${paciente.dni}
                </td>

                <td>
                    ${paciente.nombre}
                </td>

                <td>
                    ${paciente.apellido}
                </td>

                <td>
                    ${paciente.telefono ?? ''}
                </td>

                <td>
                    ${paciente.email ?? ''}
                </td>

                <td class="acciones">

                    <button
                        class="btn btn-edit"
                        onclick="editarPaciente(${paciente.id})"
                    >
                        Editar
                    </button>

                    <button
                        class="btn btn-delete"
                        onclick="eliminarPaciente(${paciente.id})"
                    >
                        Eliminar
                    </button>

                </td>

            `;


            tabla.appendChild(fila);

        });

    }


    // ==========================================
    // PROFESIONALES
    // ==========================================

    function mostrarProfesionales(profesionales) {

        const total =
            document.getElementById(
                'total-profesionales'
            );

        const tabla =
            document.getElementById(
                'tabla-profesionales'
            );


        total.textContent =
            profesionales.length;

        tabla.innerHTML = '';


        if (profesionales.length === 0) {

            tabla.innerHTML = `
                <tr>
                    <td colspan="5">
                        No hay profesionales registrados.
                    </td>
                </tr>
            `;

            return;

        }


        profesionales.forEach(profesional => {

            const fila =
                document.createElement('tr');


            fila.innerHTML = `

                <td>
                    ${profesional.id}
                </td>

                <td>
                    ${profesional.nombre}
                </td>

                <td>
                    ${profesional.apellido}
                </td>

                <td>
                    ${profesional.matricula ?? ''}
                </td>

                <td>
                    ${profesional.especialidad}
                </td>

            `;


            tabla.appendChild(fila);

        });

    }


    // ==========================================
    // PASANTES
    // ==========================================

    function mostrarPasantes(pasantes) {

        const total =
            document.getElementById(
                'total-pasantes'
            );

        const tabla =
            document.getElementById(
                'tabla-pasantes'
            );


        total.textContent =
            pasantes.length;

        tabla.innerHTML = '';


        if (pasantes.length === 0) {

            tabla.innerHTML = `
                <tr>
                    <td colspan="5">
                        No hay pasantes registrados.
                    </td>
                </tr>
            `;

            return;

        }


        pasantes.forEach(pasante => {

            const fila =
                document.createElement('tr');


            fila.innerHTML = `

                <td>
                    ${pasante.id}
                </td>

                <td>
                    ${pasante.nombre}
                </td>

                <td>
                    ${pasante.apellido}
                </td>

                <td>
                    ${pasante.dni ?? ''}
                </td>

                <td>
                    ${pasante.institucion ?? ''}
                </td>

            `;


            tabla.appendChild(fila);

        });

    }


    // ==========================================
    // TURNOS
    // ==========================================

    function mostrarTurnos(turnos) {

        const total =
            document.getElementById(
                'total-turnos'
            );

        const tabla =
            document.getElementById(
                'tabla-turnos'
            );


        total.textContent =
            turnos.length;

        tabla.innerHTML = '';


        if (turnos.length === 0) {

            tabla.innerHTML = `
                <tr>
                    <td colspan="6">
                        No hay turnos registrados.
                    </td>
                </tr>
            `;

            return;

        }


        turnos.forEach(turno => {

            const fila =
                document.createElement('tr');


            fila.innerHTML = `

                <td>
                    ${turno.id}
                </td>

                <td>
                    ${turno.paciente_id}
                </td>

                <td>
                    ${turno.profesional_id}
                </td>

                <td>
                    ${turno.fecha}
                </td>

                <td>
                    ${turno.hora}
                </td>

                <td>
                    ${turno.estado}
                </td>

            `;


            tabla.appendChild(fila);

        });

    }


    // ==========================================
    // FORMULARIO DE PACIENTES
    // ==========================================

    const btnNuevoPaciente =
        document.getElementById(
            'btn-nuevo-paciente'
        );

    const btnCancelarPaciente =
        document.getElementById(
            'btn-cancelar-paciente'
        );

    const formularioPaciente =
        document.getElementById(
            'formulario-paciente'
        );

    const formPaciente =
        document.getElementById(
            'form-paciente'
        );


    // ==========================================
    // NUEVO PACIENTE
    // ==========================================

    if (btnNuevoPaciente) {

        btnNuevoPaciente.addEventListener(
            'click',
            () => {

                formularioPaciente.style.display =
                    'block';


                document.getElementById(
                    'titulo-formulario-paciente'
                ).textContent =
                    'Nuevo paciente';


                formPaciente.reset();


                document.getElementById(
                    'paciente-id'
                ).value = '';

            }
        );

    }


    // ==========================================
    // CANCELAR FORMULARIO
    // ==========================================

    if (btnCancelarPaciente) {

        btnCancelarPaciente.addEventListener(
            'click',
            () => {

                formularioPaciente.style.display =
                    'none';


                formPaciente.reset();


                document.getElementById(
                    'paciente-id'
                ).value = '';

            }
        );

    }


    // ==========================================
    // GUARDAR / EDITAR PACIENTE
    // ==========================================

    if (formPaciente) {

        formPaciente.addEventListener(
            'submit',
            async event => {

                event.preventDefault();


                const id =
                    document.getElementById(
                        'paciente-id'
                    ).value;


                const datos = {

                    dni:
                        document.getElementById(
                            'paciente-dni'
                        ).value,

                    nombre:
                        document.getElementById(
                            'paciente-nombre'
                        ).value,

                    apellido:
                        document.getElementById(
                            'paciente-apellido'
                        ).value,

                    fecha_nacimiento:
                        document.getElementById(
                            'paciente-fecha'
                        ).value || null,

                    telefono:
                        document.getElementById(
                            'paciente-telefono'
                        ).value,

                    email:
                        document.getElementById(
                            'paciente-email'
                        ).value,

                    direccion:
                        document.getElementById(
                            'paciente-direccion'
                        ).value

                };


                try {

                    const url = id
                        ? `/api/pacientes/?id=${id}`
                        : '/api/pacientes/';


                    const metodo = id
                        ? 'PUT'
                        : 'POST';


                    const respuesta =
                        await fetch(
                            url,
                            {
                                method: metodo,

                                headers: {
                                    'Content-Type':
                                        'application/json'
                                },

                                body:
                                    JSON.stringify(datos)
                            }
                        );


                    const resultado =
                        await respuesta.json();


                    if (!respuesta.ok) {

                        throw new Error(
                            resultado.message ||
                            'No se pudo guardar el paciente.'
                        );

                    }


                    alert(
                        id
                            ? 'Paciente actualizado correctamente.'
                            : 'Paciente creado correctamente.'
                    );


                    formularioPaciente.style.display =
                        'none';


                    formPaciente.reset();


                    document.getElementById(
                        'paciente-id'
                    ).value = '';


                    await cargarDatos();


                } catch (error) {

                    console.error(
                        'Error al guardar paciente:',
                        error
                    );


                    alert(
                        'Error al guardar paciente: ' +
                        error.message
                    );

                }

            }
        );

    }


    // ==========================================
    // EDITAR PACIENTE
    // ==========================================

    window.editarPaciente =
        async function(id) {

            try {

                const respuesta =
                    await fetch(
                        `/api/pacientes/?id=${id}`
                    );


                const resultado =
                    await respuesta.json();


                if (!respuesta.ok) {

                    throw new Error(
                        resultado.message ||
                        'No se pudo obtener el paciente.'
                    );

                }


                const paciente =
                    resultado.data;


                document.getElementById(
                    'titulo-formulario-paciente'
                ).textContent =
                    'Editar paciente';


                document.getElementById(
                    'paciente-id'
                ).value =
                    paciente.id;


                document.getElementById(
                    'paciente-dni'
                ).value =
                    paciente.dni;


                document.getElementById(
                    'paciente-nombre'
                ).value =
                    paciente.nombre;


                document.getElementById(
                    'paciente-apellido'
                ).value =
                    paciente.apellido;


                document.getElementById(
                    'paciente-fecha'
                ).value =
                    paciente.fecha_nacimiento || '';


                document.getElementById(
                    'paciente-telefono'
                ).value =
                    paciente.telefono || '';


                document.getElementById(
                    'paciente-email'
                ).value =
                    paciente.email || '';


                document.getElementById(
                    'paciente-direccion'
                ).value =
                    paciente.direccion || '';


                formularioPaciente.style.display =
                    'block';


            } catch (error) {

                console.error(
                    'Error al editar paciente:',
                    error
                );


                alert(
                    'Error al cargar paciente: ' +
                    error.message
                );

            }

        };


    // ==========================================
    // ELIMINAR PACIENTE
    // ==========================================

    window.eliminarPaciente =
        async function(id) {

            const confirmar =
                confirm(
                    '¿Está seguro de eliminar este paciente?'
                );


            if (!confirmar) {
                return;
            }


            try {

                const respuesta =
                    await fetch(
                        `/api/pacientes/?id=${id}`,
                        {
                            method: 'DELETE'
                        }
                    );


                const resultado =
                    await respuesta.json();


                if (!respuesta.ok) {

                    throw new Error(
                        resultado.message ||
                        'No se pudo eliminar el paciente.'
                    );

                }


                alert(
                    'Paciente eliminado correctamente.'
                );


                await cargarDatos();


            } catch (error) {

                console.error(
                    'Error al eliminar paciente:',
                    error
                );


                alert(
                    'Error al eliminar paciente: ' +
                    error.message
                );

            }

        };

});
