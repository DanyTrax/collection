<?php
$valor = $cuenta ? number_format($cuenta->valorTotal, 2, '.', '') : '';
?>
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Datos de la cuenta de cobro</h3>
    </div>
    <form action="<?= $accion ?>" method="post">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="NumeroCuenta">Número de cuenta</label>
                    <input type="text" name="NumeroCuenta" id="NumeroCuenta" class="form-control" required value="<?= htmlspecialchars($cuenta->numeroCuenta ?? '') ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="ClienteID">Cliente</label>
                    <select name="ClienteID" id="ClienteID" class="form-control" required>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $clienteItem): ?>
                            <option value="<?= $clienteItem->clienteID ?>" <?= isset($cuenta) && $cuenta && $cuenta->clienteID === $clienteItem->clienteID ? 'selected' : '' ?>>
                                <?= htmlspecialchars($clienteItem->nombreCliente) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="FechaEmision">Fecha de emisión</label>
                    <input type="date" name="FechaEmision" id="FechaEmision" class="form-control" value="<?= htmlspecialchars($cuenta->fechaEmision ?? date('Y-m-d')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="FechaVencimiento">Fecha de vencimiento</label>
                    <input type="date" name="FechaVencimiento" id="FechaVencimiento" class="form-control" value="<?= htmlspecialchars($cuenta->fechaVencimiento ?? '') ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="Estado">Estado</label>
                    <select name="Estado" id="Estado" class="form-control">
                        <?php
                        $estados = ['Pendiente', 'Pagada', 'Anulada'];
                        $estadoActual = $cuenta->estado ?? 'Pendiente';
                        foreach ($estados as $estado): ?>
                            <option value="<?= $estado ?>" <?= $estadoActual === $estado ? 'selected' : '' ?>><?= $estado ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="Concepto">Concepto / detalle</label>
                <textarea name="Concepto" id="Concepto" rows="5" class="form-control" required><?= htmlspecialchars($cuenta->concepto ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="ValorTotal">Valor total</label>
                <input type="number" step="0.01" min="0" name="ValorTotal" id="ValorTotal" class="form-control" required value="<?= htmlspecialchars($valor) ?>">
            </div>
            <div class="form-group">
                <label for="CotizacionID">Cotización origen (opcional)</label>
                <input type="number" name="CotizacionID" id="CotizacionID" class="form-control" value="<?= htmlspecialchars($cuenta->cotizacionID ?? '') ?>">
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="/cuentas" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-warning">Guardar</button>
        </div>
    </form>
</div>
