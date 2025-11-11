<div class="row">
    <div class="col-lg-4 col-12">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $conteos['clientes'] ?? 0 ?></h3>
                <p>Clientes registrados</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="/clientes" class="small-box-footer">Ver clientes <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-12">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $conteos['cotizaciones'] ?? 0 ?></h3>
                <p>Cotizaciones creadas</p>
            </div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <a href="/cotizaciones" class="small-box-footer">Ver cotizaciones <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-12">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $conteos['cuentas'] ?? 0 ?></h3>
                <p>Cuentas de cobro emitidas</p>
            </div>
            <div class="icon"><i class="fas fa-file-invoice"></i></div>
            <a href="/cuentas" class="small-box-footer">Ver cuentas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
