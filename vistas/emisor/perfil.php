<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Información del emisor</h3>
    </div>
    <form action="/perfil" method="post">
        <div class="card-body">
            <div class="form-group">
                <label for="NombreCompleto">Nombre completo</label>
                <input type="text" name="NombreCompleto" id="NombreCompleto" class="form-control" required value="<?= htmlspecialchars($emisor->nombreCompleto ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="DocumentoIdentidad">Documento de identidad</label>
                    <input type="text" name="DocumentoIdentidad" id="DocumentoIdentidad" class="form-control" value="<?= htmlspecialchars($emisor->documentoIdentidad ?? '') ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="Email">Correo electrónico</label>
                    <input type="email" name="Email" id="Email" class="form-control" value="<?= htmlspecialchars($emisor->email ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="Telefono">Teléfono</label>
                    <input type="text" name="Telefono" id="Telefono" class="form-control" value="<?= htmlspecialchars($emisor->telefono ?? '') ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="Ciudad">Ciudad</label>
                    <input type="text" name="Ciudad" id="Ciudad" class="form-control" value="<?= htmlspecialchars($emisor->ciudad ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="Direccion">Dirección</label>
                <input type="text" name="Direccion" id="Direccion" class="form-control" value="<?= htmlspecialchars($emisor->direccion ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="InformacionBancaria">Información bancaria</label>
                <textarea name="InformacionBancaria" id="InformacionBancaria" class="form-control" rows="3"><?= htmlspecialchars($emisor->informacionBancaria ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="NotaLegal">Nota legal / términos</label>
                <textarea name="NotaLegal" id="NotaLegal" class="form-control" rows="3"><?= htmlspecialchars($emisor->notaLegal ?? '') ?></textarea>
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
    </form>
</div>
