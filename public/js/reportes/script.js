const form = document.getElementById('formulario');
            const tabla = document.getElementById('tabla').getElementsByTagName('tbody')[0];
            let filaEditada = null;

            form.addEventListener('submit', (event) => {
                event.preventDefault();

                const descripcion = document.getElementById('descripcion').value;
                const unidad_medida = document.getElementById('unidad_medida').value;
                const cantidad = document.getElementById('cantidad').value;
                const observaciones = document.getElementById('observaciones').value;

                if (filaEditada) {
                    filaEditada.cells[0].innerHTML = descripcion;
                    filaEditada.cells[1].innerHTML = unidad_medida;
                    filaEditada.cells[2].innerHTML = cantidad;
                    filaEditada.cells[3].innerHTML = observaciones;
                    filaEditada.cells[4].innerHTML = `
      <button class="editar">Editar</button>
      <button class="eliminar">Eliminar</button>
    `;
                    filaEditada = null;
                    form.reset();
                } else {
                    const fila = tabla.insertRow();

                    const celdaDescripcion = fila.insertCell(0);
                    celdaDescripcion.innerHTML = descripcion;

                    const celdaUnidadMedida = fila.insertCell(1);
                    celdaUnidadMedida.innerHTML = unidad_medida;

                    const celdaCantidad = fila.insertCell(2);
                    celdaCantidad.innerHTML = cantidad;

                    const celdaObservaciones = fila.insertCell(3);
                    celdaObservaciones.innerHTML = observaciones;

                    const celdaAcciones = fila.insertCell(4);
                    celdaAcciones.innerHTML = `
      <button class="editar">Editar</button>
      <button class="eliminar">Eliminar</button>
    `;
                    const botonEditar = celdaAcciones.querySelector('.editar');
                    botonEditar.addEventListener('click', () => {
                        filaEditada = fila;
                        document.getElementById('descripcion').value = fila.cells[0].innerHTML;
                        document.getElementById('unidad_medida').value = fila.cells[1].innerHTML;
                        document.getElementById('cantidad').value = fila.cells[2].innerHTML;
                        document.getElementById('observaciones').value = fila.cells[3].innerHTML;
                        celdaAcciones.innerHTML = `
    <button class="guardar">Guardar</button>
    <button class="cancelar">Cancelar</button>
  `;

                        const botonGuardar = celdaAcciones.querySelector('.guardar');
                        botonGuardar.addEventListener('click', () => {
                            form.dispatchEvent(new Event('submit'));
                        });

                        const botonCancelar = celdaAcciones.querySelector('.cancelar');
                        botonCancelar.addEventListener('click', () => {
                            filaEditada = null;
                            form.reset();
                            celdaAcciones.innerHTML = `
      <button class="editar">Editar</button>
      <button class="eliminar">Eliminar</button>
    `;
                        });
                    });

                    const botonEliminar = celdaAcciones.querySelector('.eliminar');
                    botonEliminar.addEventListener('click', () => {
                        fila.remove();
                    });
                    celdaEliminar.appendChild(botonEliminar);

                    form.reset();
                }
            });