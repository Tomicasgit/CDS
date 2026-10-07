<?php
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Clínica Paula Saucedo - Sistema de Gestión
    </title>

    <link
        rel="stylesheet"
        href="/css/style.css"
    >

</head>


<body>

    <div class="app">


        <!-- ==========================================
             BARRA LATERAL
             ========================================== -->

        <aside class="sidebar">

            <div class="logo">

                <h1>
                    Clínica Paula Saucedo
                </h1>

                <p>
                    Sistema de gestión
                </p>

            </div>


            <ul class="menu">

                <li>

                    <button
                        class="active"
                        data-section="inicio"
                    >
                        Inicio
                    </button>

                </li>


                <li>

                    <button
                        data-section="pacientes"
                    >
                        Pacientes
                    </button>

                </li>


                <li>

                    <button
                        data-section="profesionales"
                    >
                        Profesionales
                    </button>

                </li>


                <li>

                    <button
                        data-section="pasantes"
                    >
                        Pasantes
                    </button>

                </li>


                <li>

                    <button
                        data-section="turnos"
                    >
                        Turnos
                    </button>

                </li>

            </ul>

        </aside>



        <!-- ==========================================
             CONTENIDO PRINCIPAL
             ========================================== -->

        <main class="main">


            <!-- ======================================
                 INICIO
                 ====================================== -->

            <section
                id="inicio"
                class="section active"
            >

                <div class="header">

                    <h2>
                        Inicio
                    </h2>

                    <p>
                        Resumen general de la clínica.
                    </p>

                </div>


                <div class="cards">


                    <div class="card">

                        <h3>
                            Pacientes
                        </h3>

                        <div
                            class="number"
                            id="total-pacientes"
                        >
                            0
                        </div>

                    </div>


                    <div class="card">

                        <h3>
                            Profesionales
                        </h3>

                        <div
                            class="number"
                            id="total-profesionales"
                        >
                            0
                        </div>

                    </div>


                    <div class="card">

                        <h3>
                            Pasantes
                        </h3>

                        <div
                            class="number"
                            id="total-pasantes"
                        >
                            0
                        </div>

                    </div>


                    <div class="card">

                        <h3>
                            Turnos
                        </h3>

                        <div
                            class="number"
                            id="total-turnos"
                        >
                            0
                        </div>

                    </div>


                </div>

            </section>



            <!-- ======================================
                 PACIENTES
                 ====================================== -->

            <section
                id="pacientes"
                class="section"
            >


                <div class="header">

                    <div class="header-content">

                        <div>

                            <h2>
                                Pacientes
                            </h2>

                            <p>
                                Gestión de pacientes de la clínica.
                            </p>

                        </div>


                        <button
                            class="btn btn-primary"
                            id="btn-nuevo-paciente"
                        >
                            + Nuevo paciente
                        </button>

                    </div>

                </div>



                <!-- FORMULARIO DE PACIENTES -->

                <div
                    class="panel form-panel"
                    id="formulario-paciente"
                    style="display: none;"
                >

                    <h3 id="titulo-formulario-paciente">
                        Nuevo paciente
                    </h3>


                    <form id="form-paciente">


                        <input
                            type="hidden"
                            id="paciente-id"
                        >


                        <div class="form-grid">


                            <div class="form-group">

                                <label for="paciente-dni">
                                    DNI
                                </label>

                                <input
                                    type="text"
                                    id="paciente-dni"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label for="paciente-nombre">
                                    Nombre
                                </label>

                                <input
                                    type="text"
                                    id="paciente-nombre"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label for="paciente-apellido">
                                    Apellido
                                </label>

                                <input
                                    type="text"
                                    id="paciente-apellido"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label for="paciente-fecha">
                                    Fecha de nacimiento
                                </label>

                                <input
                                    type="date"
                                    id="paciente-fecha"
                                >

                            </div>


                            <div class="form-group">

                                <label for="paciente-telefono">
                                    Teléfono
                                </label>

                                <input
                                    type="text"
                                    id="paciente-telefono"
                                >

                            </div>


                            <div class="form-group">

                                <label for="paciente-email">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    id="paciente-email"
                                >

                            </div>


                            <div class="form-group form-group-full">

                                <label for="paciente-direccion">
                                    Dirección
                                </label>

                                <input
                                    type="text"
                                    id="paciente-direccion"
                                >

                            </div>


                        </div>



                        <div class="form-actions">


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Guardar paciente
                            </button>


                            <button
                                type="button"
                                class="btn btn-secondary"
                                id="btn-cancelar-paciente"
                            >
                                Cancelar
                            </button>


                        </div>


                    </form>

                </div>



                <!-- TABLA DE PACIENTES -->

                <div class="panel">

                    <h3>
                        Lista de pacientes
                    </h3>


                    <div class="table-container">

                        <table>

                            <thead>

                                <tr>

                                    <th>
                                        ID
                                    </th>

                                    <th>
                                        DNI
                                    </th>

                                    <th>
                                        Nombre
                                    </th>

                                    <th>
                                        Apellido
                                    </th>

                                    <th>
                                        Teléfono
                                    </th>

                                    <th>
                                        Email
                                    </th>

                                    <th>
                                        Acciones
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="tabla-pacientes">

                            </tbody>

                        </table>

                    </div>

                </div>


            </section>



            <!-- ======================================
                 PROFESIONALES
                 ====================================== -->

            <section
                id="profesionales"
                class="section"
            >


                <div class="header">

                    <div class="header-content">

                        <div>

                            <h2>
                                Profesionales
                            </h2>

                            <p>
                                Gestión de profesionales de la clínica.
                            </p>

                        </div>


                        <button
                            class="btn btn-primary"
                            id="btn-nuevo-profesional"
                        >
                            + Nuevo profesional
                        </button>

                    </div>

                </div>



                <!-- FORMULARIO DE PROFESIONALES -->

                <div
                    class="panel form-panel"
                    id="formulario-profesional"
                    style="display: none;"
                >

                    <h3 id="titulo-formulario-profesional">
                        Nuevo profesional
                    </h3>


                    <form id="form-profesional">


                        <input
                            type="hidden"
                            id="profesional-id"
                        >


                        <div class="form-grid">


                            <div class="form-group">

                                <label for="profesional-nombre">
                                    Nombre
                                </label>

                                <input
                                    type="text"
                                    id="profesional-nombre"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label for="profesional-apellido">
                                    Apellido
                                </label>

                                <input
                                    type="text"
                                    id="profesional-apellido"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label for="profesional-matricula">
                                    Matrícula
                                </label>

                                <input
                                    type="text"
                                    id="profesional-matricula"
                                >

                            </div>


                            <div class="form-group">

                                <label for="profesional-especialidad">
                                    Especialidad
                                </label>

                                <input
                                    type="text"
                                    id="profesional-especialidad"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label for="profesional-telefono">
                                    Teléfono
                                </label>

                                <input
                                    type="text"
                                    id="profesional-telefono"
                                >

                            </div>


                            <div class="form-group">

                                <label for="profesional-email">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    id="profesional-email"
                                >

                            </div>


                        </div>



                        <div class="form-actions">


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Guardar profesional
                            </button>


                            <button
                                type="button"
                                class="btn btn-secondary"
                                id="btn-cancelar-profesional"
                            >
                                Cancelar
                            </button>


                        </div>


                    </form>

                </div>



                <!-- TABLA DE PROFESIONALES -->

                <div class="panel">

                    <h3>
                        Lista de profesionales
                    </h3>


                    <div class="table-container">

                        <table>

                            <thead>

                                <tr>

                                    <th>
                                        ID
                                    </th>

                                    <th>
                                        Nombre
                                    </th>

                                    <th>
                                        Apellido
                                    </th>

                                    <th>
                                        Matrícula
                                    </th>

                                    <th>
                                        Especialidad
                                    </th>

                                    <th>
                                        Teléfono
                                    </th>

                                    <th>
                                        Email
                                    </th>

                                    <th>
                                        Acciones
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="tabla-profesionales">

                            </tbody>

                        </table>

                    </div>

                </div>


            </section>



            <!-- ======================================
                 PASANTES
                 ====================================== -->

            <section
                id="pasantes"
                class="section"
            >


                <div class="header">

                    <h2>
                        Pasantes
                    </h2>

                    <p>
                        Gestión de pasantes de la clínica.
                    </p>

                </div>


                <div class="panel">

                    <h3>
                        Lista de pasantes
                    </h3>


                    <div class="table-container">

                        <table>

                            <thead>

                                <tr>

                                    <th>
                                        ID
                                    </th>

                                    <th>
                                        Nombre
                                    </th>

                                    <th>
                                        Apellido
                                    </th>

                                    <th>
                                        DNI
                                    </th>

                                    <th>
                                        Institución
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="tabla-pasantes">

                            </tbody>

                        </table>

                    </div>

                </div>


            </section>



            <!-- ======================================
                 TURNOS
                 ====================================== -->

            <section
                id="turnos"
                class="section"
            >


                <div class="header">

                    <h2>
                        Turnos
                    </h2>

                    <p>
                        Gestión de turnos de la clínica.
                    </p>

                </div>


                <div class="panel">

                    <h3>
                        Lista de turnos
                    </h3>


                    <div class="table-container">

                        <table>

                            <thead>

                                <tr>

                                    <th>
                                        ID
                                    </th>

                                    <th>
                                        Paciente
                                    </th>

                                    <th>
                                        Profesional
                                    </th>

                                    <th>
                                        Fecha
                                    </th>

                                    <th>
                                        Hora
                                    </th>

                                    <th>
                                        Estado
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="tabla-turnos">

                            </tbody>

                        </table>

                    </div>

                </div>


            </section>


        </main>

    </div>


    <!-- ==========================================
         JAVASCRIPT
         ========================================== -->

    <script src="/js/app.js"></script>

</body>

</html>
