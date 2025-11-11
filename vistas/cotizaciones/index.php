<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Cotizaciones registradas</h3>
        <a href="/cotizaciones/crear" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva cotización</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped tabla-datatable">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Cliente</th>
                    <th>Fecha emisión</th>
                    <th>Valor total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cotizaciones as $cotizacion): ?>
                    <?php
                        $clienteNombre = $cotizacion->datosCliente['NombreCliente'] ?? '';
                        $valor = number_format($cotizacion->valorTotal, 2, ',', '.');
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($cotizacion->numeroCotizacion) ?></td>
                        <td><?= htmlspecialchars($clienteNombre) ?></td>
                        <td><?= htmlspecialchars($cotizacion->fechaEmision) ?></td>
                        <td>$<?= $valor ?></td>
                        <td><span class="badge badge-secondary"><?= htmlspecialchars($cotizacion->estado) ?></span></td>
                        <td class="text-center">
                            <a href="/cotizaciones/editar/<?= $cotizacion->cotizacionID ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                            <a href="/cotizaciones/imprimir/<?= $cotizacion->cotizacionID ?>" class="btn btn-sm btn-info" target="_blank" title="Imprimir"><i class="fas fa-print"></i></a>
                            <form action="/cotizaciones/borrar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta cotización?');">
                                <input type="hidden" name="id" value="<?= $cotizacion->cotizacionID ?>">
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                            </form>
                            <form action="/cotizaciones/convertir/<?= $cotizacion->cotizacionID ?>" method="post" class="d-inline" onsubmit="return confirm('Se creará una cuenta de cobro a partir de esta cotización. ¿Continuar?');">
                                <input type="hidden" name="NumeroCuenta" value="CC-<?= htmlspecialchars($cotizacion->numeroCotizacion) ?>">
                                <input type="hidden" name="FechaEmision" value="<?= date('Y-m-d') ?>">
                                <button type="submit" class="btn btn-sm btn-success" title="Convertir en cuenta de cobro"><i class="fas fa-exchange-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
