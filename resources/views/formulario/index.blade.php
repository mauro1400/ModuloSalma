@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>
                            <h5>FORMULARIO DE INEXISTENCIA</h5>
                        </b>
                    </div>
                    <div class="card-body">
                        <form id="formulario">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="descripcion" class="form-label">Descripción:</label>
                                    <input type="text" id="descripcion" name="descripcion" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="unidad_medida" class="form-label">Unidad de medida:</label>
                                    <input type="text" id="unidad_medida" name="unidad_medida" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label for="cantidad" class="form-label">Cantidad:</label>
                                    <input type="number" id="cantidad" name="cantidad" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones:</label>
                                <textarea id="observaciones" name="observaciones" class="form-control"></textarea>
                            </div>
                            <input type="hidden" id="datos_tabla" name="datos_tabla" value="">

                            <button type="submit" id="agregar" class="btn btn-primary">Agregar</button>
                        </form>

                        <table id="tabla" class="table">
                            <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th>Unidad de medida</th>
                                    <th>Cantidad</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <br>
                        <button type="submit" id="guardar-btn" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Esperamos a que el documento se cargue
            $(document).ready(function() {
                // Escuchamos el envío del boton agregar
                $("#agregar").click(function(event) {
                    event.preventDefault(); // Prevenimos el envío por defecto del formulario
                    agregar();
                    eliminar();
                    editar();
                    evaluar();
                    guardar();
                });
            });

            $("#guardar").hide();
            var cont = 0;

            function agregar() {
                // Obtenemos los valores del formulario
                var descripcion = $("#descripcion").val();
                var unidad_medida = $("#unidad_medida").val();
                var cantidad = $("#cantidad").val();
                var observaciones = $("#observaciones").val();

                // Verificamos si ya hay una fila con la misma descripción
                var fila_existente = $("#tabla tbody tr").filter(function() {
                    return $(this).find("td:first-child").text() === descripcion;
                });

                // Si ya existe una fila con la misma descripción, la actualizamos
                if (fila_existente.length) {
                    fila_existente.find("td:nth-child(2)").text(unidad_medida);
                    fila_existente.find("td:nth-child(3)").text(cantidad);
                    fila_existente.find("td:nth-child(4)").text(observaciones);
                } else {
                    // Si no existe una fila con la misma descripción, creamos una nueva fila
                    var fila = "<tr>" +
                        "<td>" + descripcion + "</td>" +
                        "<td>" + unidad_medida + "</td>" +
                        "<td>" + cantidad + "</td>" +
                        "<td>" + observaciones + "</td>" +
                        "<td>" +
                        "<button class='btn btn-primary editar'>Editar</button> " +
                        "<button class='btn btn-danger eliminar'>Eliminar</button>" +
                        "</td>" +
                        "</tr>";
                    cont++
                    // Agregamos la fila a la tabla
                    $("#tabla tbody").append(fila);
                }

                // Limpiamos el formulario
                $("#formulario")[0].reset();

            }

            function editar() {
                // Escuchamos el click en el botón "Editar"
                $("#tabla").on("click", ".editar", function() {
                    var fila = $(this).parents("tr");
                    var descripcion = fila.find("td:first-child").text();
                    var unidad_medida = fila.find("td:nth-child(2)").text();
                    var cantidad = fila.find("td:nth-child(3)").text();
                    var observaciones = fila.find("td:nth-child(4)").text();
                    // Rellenamos el formulario con los valores de la fila
                    $("#descripcion").val(descripcion);
                    $("#unidad_medida").val(unidad_medida);
                    $("#cantidad").val(cantidad);
                    $("#observaciones").val(observaciones);

                    // Eliminamos la fila correspondiente
                    fila.remove();
                });
            }

            function eliminar() {
                // Escuchamos el click en el botón "Eliminar"
                $("#tabla").on("click", ".eliminar", function() {
                    $(this).parents("tr").remove(); // Eliminamos la fila correspondiente
                });
            }

            function evaluar() {
                if (cont > 0) {
                    $("#guardar").show();
                } else {
                    $("#guardar").hide();
                }
            }

            function guardar() {
                $("#guardar-btn").click(function(event) {
                    event.preventDefault(); // Prevenimos el envío por defecto del formulario

                    // Obtenemos los datos de la tabla
                    var datos_tabla = [];
                    $("#tabla tbody tr").each(function() {
                        var fila = $(this);
                        var descripcion = fila.find("td:first-child").text();
                        var unidad_medida = fila.find("td:nth-child(2)").text();
                        var cantidad = fila.find("td:nth-child(3)").text();
                        var observaciones = fila.find("td:nth-child(4)").text();
                        datos_tabla.push({
                            descripcion: descripcion,
                            unidad_medida: unidad_medida,
                            cantidad: cantidad,
                            observaciones: observaciones
                        });
                    });

                    // Creamos un objeto con los datos del formulario y de la tabla
                    var formulario_data = $("#formulario").serializeArray();
                    formulario_data.push({
                        name: "datos_tabla",
                        value: JSON.stringify(datos_tabla)
                    });

                    // Enviamos la petición AJAX al controlador
                    $.ajax({
                        url: "/guardar-datos",
                        type: "POST",
                        data: formulario_data,
                        dataType: "json",
                        success: function(response) {
                            // Manejamos la respuesta del servidor
                            alert("Datos guardados correctamente");
                            $("#tabla tbody").empty();
                        },
                        error: function(xhr, status, error) {
                            alert("Error al guardar los datos");
                        }
                    });
                });

            }
        </script>
    @endpush
@endsection
