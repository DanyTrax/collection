<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Cuentas de cobro</h3>
        <a href="/cuentas/crear" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva cuenta</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped tabla-datatable">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Cliente</th>
                    <th>Emisión</th>
                    <th>Valor</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cuentas as $cuenta): ?>
                    <?php $valor = number_format($cuenta->valorTotal, 2, ',', '.'); ?>
                    <tr>
                        <td><?= htmlspecialchars($cuenta->numeroCuenta) ?></td>
                        <td><?= htmlspecialchars($cuenta->datosCliente['NombreCliente'] ?? '') ?></td>
                        <td><?= htmlspecialchars($cuenta->fechaEmision) ?></td>
                        <td>$<?= $valor ?></td>
                        <td><span class="badge badge-secondary"><?= htmlspecialchars($cuenta->estado) ?></span></td>
                        <td class="text-center">
                            <a href="/cuentas/editar/<?= $cuenta->cuentaID ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                            <a href="/cuentas/imprimir/<?= $cuenta->cuentaID ?>" class="btn btn-sm btn-info" target="_blank" title="Imprimir"><i class="fas fa-print"></i></a>
                            <form action="/cuentas/borrar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta cuenta de cobro?');">
                                <input type="hidden" name="id" value="<?= $cuenta->cuentaID ?>">
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
