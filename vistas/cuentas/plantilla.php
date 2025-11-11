<?php
$valorFormateado = $cuenta->valorFormateado();
$fechaEmision = new DateTime($cuenta->fechaEmision);
$meses = [1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril', 5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto', 9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'];
$mesNombre = $meses[(int) $fechaEmision->format('n')] ?? $fechaEmision->format('F');
$diaTexto = $fechaEmision->format('d');
$anioTexto = $fechaEmision->format('Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta de Cobro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            color-scheme: light;
        }
        body {
            background: #e2e8f0;
        }
        @page {
            size: Letter;
            margin: 15mm;
        }
        @media print {
            body {
                background: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print { display: none !important; }
            #area-cuenta {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen font-sans text-slate-700 text-[13px] leading-relaxed">
    <div class="w-full flex justify-center mb-4 gap-3 no-print pt-6">
        <button onclick="imprimirCuenta()" class="bg-emerald-600 text-white px-6 py-2 rounded-lg shadow hover:bg-emerald-700 transition">Imprimir</button>
        <button onclick="descargarCuenta()" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">Descargar PDF</button>
    </div>

    <div class="w-full px-4 pb-8 flex justify-center">
        <div id="area-cuenta" class="w-full max-w-[8.5in] bg-white border border-slate-200 shadow-xl rounded-[32px] px-12 py-12 space-y-10">
            <header class="grid gap-10 md:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-2xl font-semibold text-slate-900 tracking-tight"><?= htmlspecialchars($datosEmisor['NombreCompleto'] ?? '') ?></h2>
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
                <div class="md:text-right space-y-3">
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Cuenta de Cobro</p>
                    <p class="text-4xl font-black text-slate-900 tracking-[0.35em]">Nº <?= htmlspecialchars($cuenta->numeroCuenta) ?></p>
                    <dl class="text-sm text-slate-600 space-y-1">
                        <div><dt class="font-semibold inline">Ciudad y fecha:</dt> <dd class="inline"><?= htmlspecialchars(($datosEmisor['Ciudad'] ?? '')) ?>, <?= htmlspecialchars($cuenta->fechaEmision) ?></dd></div>
                        <?php if (!empty($cuenta->fechaVencimiento)): ?>
                            <div><dt class="font-semibold inline">Vence:</dt> <dd class="inline"><?= htmlspecialchars($cuenta->fechaVencimiento) ?></dd></div>
                        <?php endif; ?>
                        <div><dt class="font-semibold inline">Estado:</dt> <dd class="inline"><?= htmlspecialchars($cuenta->estado) ?></dd></div>
                    </dl>
                </div>
            </header>

            <section class="bg-slate-50 border border-slate-200 rounded-2xl px-8 py-6 space-y-1">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Señor(a)</h3>
                <p class="text-lg font-semibold text-slate-900"><?= htmlspecialchars($datosCliente['NombreCliente'] ?? '') ?></p>
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

            <section class="space-y-6">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide border-b border-slate-200 pb-2">Concepto</h3>
                    <p class="text-[12px] text-slate-600 leading-relaxed whitespace-pre-wrap"><?= nl2br(htmlspecialchars($cuenta->concepto)) ?></p>
                </div>
                <div class="flex justify-end">
                    <table class="w-full md:w-1/2 text-right text-sm border border-slate-200 rounded-2xl overflow-hidden">
                        <tbody>
                            <tr class="bg-slate-50 text-slate-600">
                                <td class="px-4 py-3 font-semibold">Subtotal</td>
                                <td class="px-4 py-3 font-mono text-slate-800">$<?= $valorFormateado ?></td>
                            </tr>
                            <tr class="border-t-2 border-slate-900 text-slate-900">
                                <td class="px-4 py-4 text-lg font-bold uppercase">Valor total</td>
                                <td class="px-4 py-4 text-lg font-bold font-mono">$<?= $valorFormateado ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <?php if (!empty($datosEmisor['InformacionBancaria'])): ?>
                <section class="bg-blue-50 border border-blue-200 rounded-2xl px-8 py-6">
                    <h4 class="text-base font-semibold text-blue-900 mb-2">Información de Pago</h4>
                    <p class="text-sm text-blue-800 whitespace-pre-wrap leading-relaxed"><?= nl2br(htmlspecialchars($datosEmisor['InformacionBancaria'])) ?></p>
                </section>
            <?php endif; ?>

            <footer class="space-y-6 pt-4 border-t border-slate-200">
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
                    <section class="bg-slate-50 border border-slate-200 rounded-2xl px-8 py-5">
                        <h4 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide mb-2">Nota</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed whitespace-pre-wrap"><?= nl2br(htmlspecialchars($datosEmisor['NotaLegal'])) ?></p>
                    </section>
                <?php endif; ?>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/print-js@1.6.0/dist/print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function imprimirCuenta() {
            printJS({
                printable: 'area-cuenta',
                type: 'html',
                scanStyles: true,
                documentTitle: 'Cuenta <?= htmlspecialchars($cuenta->numeroCuenta) ?>',
                style: '@page { size: Letter; margin: 15mm; }'
            });
        }

        function descargarCuenta() {
            const elemento = document.getElementById('area-cuenta');
            if (typeof html2pdf === 'undefined') {
                alert('No fue posible cargar el generador de PDF. Inténtelo de nuevo.');
                return;
            }
            html2pdf(elemento, {
                margin: 0,
                filename: 'cuenta-<?= htmlspecialchars($cuenta->numeroCuenta) ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            });
        }
    </script>
</body>
</html>
