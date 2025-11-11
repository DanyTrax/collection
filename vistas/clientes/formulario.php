<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Datos del cliente</h3>
    </div>
    <form action="<?= $accion ?>" method="post">
        <div class="card-body">
            <div class="form-group">
                <label for="NombreCliente">Nombre completo</label>
                <input type="text" name="NombreCliente" id="NombreCliente" class="form-control" required value="<?= htmlspecialchars($cliente->nombreCliente ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="NIT_CC">NIT / CC</label>
                    <input type="text" name="NIT_CC" id="NIT_CC" class="form-control" value="<?= htmlspecialchars($cliente->nitCc ?? '') ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="Ciudad">Ciudad</label>
                    <input type="text" name="Ciudad" id="Ciudad" class="form-control" value="<?= htmlspecialchars($cliente->ciudad ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="Direccion">Dirección</label>
                    <input type="text" name="Direccion" id="Direccion" class="form-control" value="<?= htmlspecialchars($cliente->direccion ?? '') ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="Telefono">Teléfono</label>
                    <input type="text" name="Telefono" id="Telefono" class="form-control" value="<?= htmlspecialchars($cliente->telefono ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="Email">Correo electrónico</label>
                <input type="email" name="Email" id="Email" class="form-control" value="<?= htmlspecialchars($cliente->email ?? '') ?>">
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="/clientes" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>
