<?php
$valor = $cotizacion ? number_format($cotizacion->valorTotal, 2, '.', '') : '';
?>
<div class="card card-success">
    <div class="card-header">
        <h3 class="card-title">Detalles de la cotización</h3>
    </div>
    <form action="<?= $accion ?>" method="post">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="NumeroCotizacion">Número de cotización</label>
                    <input type="text" name="NumeroCotizacion" id="NumeroCotizacion" class="form-control" required value="<?= htmlspecialchars($cotizacion->numeroCotizacion ?? '') ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="ClienteID">Cliente</label>
                    <select name="ClienteID" id="ClienteID" class="form-control" required>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $clienteItem): ?>
                            <option value="<?= $clienteItem->clienteID ?>" <?= isset($cotizacion) && $cotizacion && $cotizacion->clienteID === $clienteItem->clienteID ? 'selected' : '' ?>>
                                <?= htmlspecialchars($clienteItem->nombreCliente) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="FechaEmision">Fecha de emisión</label>
                    <input type="date" name="FechaEmision" id="FechaEmision" class="form-control" value="<?= htmlspecialchars($cotizacion->fechaEmision ?? date('Y-m-d')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="FechaVencimiento">Vigencia hasta</label>
                    <input type="date" name="FechaVencimiento" id="FechaVencimiento" class="form-control" value="<?= htmlspecialchars($cotizacion->fechaVencimiento ?? '') ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="Estado">Estado</label>
                    <select name="Estado" id="Estado" class="form-control">
                        <?php
                        $estados = ['Pendiente', 'Aprobada', 'Rechazada'];
                        $estadoActual = $cotizacion->estado ?? 'Pendiente';
                        foreach ($estados as $estado): ?>
                            <option value="<?= $estado ?>" <?= $estadoActual === $estado ? 'selected' : '' ?>><?= $estado ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="Concepto">Concepto / descripción</label>
                <textarea name="Concepto" id="Concepto" rows="5" class="form-control" required><?= htmlspecialchars($cotizacion->concepto ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="ValorTotal">Valor total</label>
                <input type="number" step="0.01" min="0" name="ValorTotal" id="ValorTotal" class="form-control" required value="<?= htmlspecialchars($valor) ?>">
            </div>
            <div class="form-group">
                <label for="Terminos">Términos y condiciones</label>
                <textarea name="Terminos" id="Terminos" rows="3" class="form-control"><?= htmlspecialchars($cotizacion->terminos ?? '') ?></textarea>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="/cotizaciones" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-success">Guardar</button>
        </div>
    </form>
</div>
