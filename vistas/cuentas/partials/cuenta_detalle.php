<?php
/** @var array $datosEmisor */
/** @var array $datosCliente */
/** @var Modelos\CuentaCobro $cuenta */
/** @var string $valorFormateado */
/** @var string $diaTexto */
/** @var string $mesNombre */
/** @var string $anioTexto */
?>
<header class="grid gap-4 md:grid-cols-2">
    <div class="space-y-1">
        <h2 class="text-xl font-medium text-slate-900 tracking-tight"><?= htmlspecialchars($datosEmisor['NombreCompleto'] ?? '') ?></h2>
        <?php if (!empty($datosEmisor['Telefono'])): ?>
            <p class="text-sm text-slate-500">Tel. <?= htmlspecialchars($datosEmisor['Telefono']) ?></p>
        <?php endif; ?>
        <?php if (!empty($datosEmisor['Direccion'])): ?>
            <p class="text-sm text-slate-500"><?= htmlspecialchars($datosEmisor['Direccion']) ?></p>
        <?php endif; ?>
        <?php if (!empty($datosEmisor['Email'])): ?>
            <p class="text-sm text-slate-500">E-Mail: <?= htmlspecialchars($datosEmisor['Email']) ?></p>
        <?php endif; ?>
        <?php if (!empty($datosEmisor['DocumentoIdentidad'])): ?>
            <p class="text-sm text-slate-500">Documento: <?= htmlspecialchars($datosEmisor['DocumentoIdentidad']) ?></p>
        <?php endif; ?>
    </div>
    <div class="md:text-right space-y-2">
        <p class="text-[10px] uppercase tracking-[0.25em] text-slate-400">Cuenta de Cobro</p>
        <p class="text-2xl font-semibold text-slate-900 tracking-[0.2em]">Nº <?= htmlspecialchars($cuenta->numeroCuenta) ?></p>
        <dl class="text-[12px] text-slate-600 space-y-1">
            <div><dt class="font-medium inline">Ciudad y fecha:</dt> <dd class="inline"><?= htmlspecialchars($datosEmisor['Ciudad'] ?? '') ?>, <?= htmlspecialchars($cuenta->fechaEmision) ?></dd></div>
            <?php if (!empty($cuenta->fechaVencimiento)): ?>
                <div><dt class="font-medium inline">Vence:</dt> <dd class="inline"><?= htmlspecialchars($cuenta->fechaVencimiento) ?></dd></div>
            <?php endif; ?>
            <div><dt class="font-medium inline">Estado:</dt> <dd class="inline"><?= htmlspecialchars($cuenta->estado) ?></dd></div>
        </dl>
    </div>
</header>

<section class="bg-slate-50 border border-slate-200 rounded-2xl px-7 py-0 space-y-0.5">
    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Señor(a)</h3>
    <p class="text-base font-medium text-slate-900"><?= htmlspecialchars($datosCliente['NombreCliente'] ?? '') ?></p>
    <?php if (!empty($datosCliente['NIT_CC'])): ?>
        <p class="text-sm text-slate-600">NIT / CC: <?= htmlspecialchars($datosCliente['NIT_CC']) ?></p>
    <?php endif; ?>
    <?php if (!empty($datosCliente['Direccion'])): ?>
        <p class="text-sm text-slate-600"><?= htmlspecialchars($datosCliente['Direccion']) ?></p>
    <?php endif; ?>
    <div class="grid gap-3 md:grid-cols-3 pt-2 text-sm text-slate-600">
        <?php if (!empty($datosCliente['Email'])): ?><p><span class="font-semibold">Correo:</span> <?= htmlspecialchars($datosCliente['Email']) ?></p><?php endif; ?>
        <?php if (!empty($datosCliente['Telefono'])): ?><p><span class="font-semibold">Teléfono:</span> <?= htmlspecialchars($datosCliente['Telefono']) ?></p><?php endif; ?>
        <?php if (!empty($datosCliente['Ciudad'])): ?><p><span class="font-semibold">Ciudad:</span> <?= htmlspecialchars($datosCliente['Ciudad']) ?></p><?php endif; ?>
    </div>
</section>

<section class="space-y-3">
    <div class="pt-3">
        <h3 class="text-xs font-semibold text-slate-900 uppercase tracking-wide border-b border-slate-200 pb-1">Concepto</h3>
        <p class="text-[11px] text-slate-600 leading-tight whitespace-pre-wrap"><?= nl2br(htmlspecialchars($cuenta->concepto)) ?></p>
    </div>
    <div class="flex justify-end">
        <table class="w-full md:w-1/2 text-right text-[12px] border border-slate-200 rounded-2xl overflow-hidden">
            <tbody>
                <tr class="bg-slate-50 text-slate-600">
                    <td class="px-3 py-2 font-medium">Subtotal</td>
                    <td class="px-3 py-2 font-mono text-slate-800">$<?= $valorFormateado ?></td>
                </tr>
                <tr class="border-t border-slate-400 text-slate-900">
                    <td class="px-3 py-3 text-base font-semibold uppercase">Valor total</td>
                    <td class="px-3 py-3 text-base font-semibold font-mono">$<?= $valorFormateado ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php if (!empty($datosEmisor['InformacionBancaria'])): ?>
    <section class="bg-blue-50 border border-blue-200 rounded-2xl px-7 py-0 space-y-1">
        <h4 class="text-sm font-medium text-blue-900">Información de Pago</h4>
        <p class="text-[11px] text-blue-800 whitespace-pre-wrap leading-tight"><?= nl2br(htmlspecialchars($datosEmisor['InformacionBancaria'])) ?></p>
    </section>
<?php endif; ?>

<footer class="space-y-4 pt-3 border-t border-slate-200">
    <div class="text-center space-y-2">
        <?php if (!empty($datosEmisor['FirmaImagenURL'])): ?>
            <img src="<?= htmlspecialchars($datosEmisor['FirmaImagenURL']) ?>" alt="Firma" class="h-16 mx-auto object-contain">
        <?php else: ?>
            <div class="mx-auto h-16 w-48 border-b border-slate-300 flex items-end justify-center"><span class="text-xs italic text-slate-400">[Firma]</span></div>
        <?php endif; ?>
        <p class="text-xs font-semibold text-slate-700"><?= htmlspecialchars($datosEmisor['NombreCompleto'] ?? '') ?></p>
    </div>
    <p class="text-xs text-slate-500 text-center">Se firma en <?= htmlspecialchars($datosEmisor['Ciudad'] ?? '________') ?>, a los <?= htmlspecialchars($diaTexto) ?> días del mes de <?= htmlspecialchars($mesNombre) ?> de <?= htmlspecialchars($anioTexto) ?>.</p>
    <?php if (!empty($datosEmisor['NotaLegal'])): ?>
        <section class="bg-slate-50 border border-slate-200 rounded-2xl px-7 py-0 space-y-1">
            <h4 class="text-[10px] font-medium text-slate-500 uppercase tracking-wide">Nota</h4>
            <p class="text-[10px] text-slate-500 leading-tight whitespace-pre-wrap"><?= nl2br(htmlspecialchars($datosEmisor['NotaLegal'])) ?></p>
        </section>
    <?php endif; ?>
</footer>
