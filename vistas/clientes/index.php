<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Listado de clientes</h3>
        <a href="/clientes/crear" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo cliente</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped tabla-datatable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>NIT / CC</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Ciudad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= htmlspecialchars($cliente->nombreCliente) ?></td>
                        <td><?= htmlspecialchars($cliente->nitCc ?? '') ?></td>
                        <td><?= htmlspecialchars($cliente->email ?? '') ?></td>
                        <td><?= htmlspecialchars($cliente->telefono ?? '') ?></td>
                        <td><?= htmlspecialchars($cliente->ciudad ?? '') ?></td>
                        <td class="text-center">
                            <a href="/clientes/editar/<?= $cliente->clienteID ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="/clientes/borrar" method="post" class="d-inline" onsubmit="return confirm('¿Desea eliminar este cliente?');">
                                <input type="hidden" name="id" value="<?= $cliente->clienteID ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
