<?php
$valorFormateado = $cotizacion->valorFormateado();
$fechaEmision = new DateTime($cotizacion->fechaEmision);
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
    <title>Cotización</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style id="template-styles">
        :root {
            color-scheme: light;
        }
        body {
            background: #e2e8f0;
        }
        .preview-wrapper {
            width: min(1080px, 95vw);
            max-width: 1100px;
            border-radius: 32px;
        }
        .preview-content {
            padding: 1rem 2rem;
        }
        @page {
            size: Letter;
            margin: 0;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="min-h-screen font-sans text-slate-700 text-[13px] leading-tight" data-print-container="area-cotizacion">
    <div class="w-full px-4 pb-8 flex justify-center">
        <div class="no-print flex justify-center gap-3 absolute top-6">
            <button onclick="imprimirCotizacion()" class="bg-emerald-600 text-white px-6 py-2 rounded-lg shadow hover:bg-emerald-700 transition">Imprimir</button>
            <button onclick="descargarCotizacion()" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">Descargar PDF</button>
        </div>
        <div id="area-cotizacion" class="preview-wrapper bg-white border border-slate-200 shadow-xl mt-20">
            <div class="preview-content space-y-6">
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
                        <p class="text-[10px] uppercase tracking-[0.25em] text-slate-400">Cotización</p>
                        <p class="text-2xl font-semibold text-slate-900 tracking-[0.2em]">Nº <?= htmlspecialchars($cotizacion->numeroCotizacion) ?></p>
                        <dl class="text-[12px] text-slate-600 space-y-1">
                            <div><dt class="font-medium inline">Ciudad y fecha:</dt> <dd class="inline"><?= htmlspecialchars(($datosEmisor['Ciudad'] ?? '')) ?>, <?= htmlspecialchars($cotizacion->fechaEmision) ?></dd></div>
                            <?php if (!empty($cotizacion->fechaVencimiento)): ?>
                                <div><dt class="font-medium inline">Válida hasta:</dt> <dd class="inline"><?= htmlspecialchars($cotizacion->fechaVencimiento) ?></dd></div>
                            <?php endif; ?>
                            <div><dt class="font-medium inline">Estado:</dt> <dd class="inline"><?= htmlspecialchars($cotizacion->estado) ?></dd></div>
                        </dl>
                    </div>
                </header>

                <section class="bg-slate-50 border border-slate-200 rounded-2xl px-7 py-0 space-y-0.5">
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Cliente</h3>
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
                        <p class="text-[11px] text-slate-600 leading-tight whitespace-pre-wrap"><?= nl2br(htmlspecialchars($cotizacion->concepto)) ?></p>
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

                <?php if (!empty($cotizacion->terminos)): ?>
                    <section class="bg-blue-50 border border-blue-200 rounded-2xl px-7 py-0 space-y-1">
                        <h4 class="text-sm font-medium text-blue-900">Términos y Condiciones</h4>
                        <p class="text-[11px] text-blue-800 whitespace-pre-wrap leading-tight"><?= nl2br(htmlspecialchars($cotizacion->terminos)) ?></p>
                    </section>
                <?php endif; ?>

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
                    <p class="text-xs text-slate-500 text-center">Se expide en <?= htmlspecialchars($datosEmisor['Ciudad'] ?? '________') ?>, a los <?= htmlspecialchars($diaTexto) ?> días del mes de <?= htmlspecialchars($mesNombre) ?> de <?= htmlspecialchars($anioTexto) ?>.</p>
                    <?php if (!empty($datosEmisor['NotaLegal'])): ?>
                        <section class="bg-slate-50 border border-slate-200 rounded-2xl px-7 py-0 space-y-1">
                            <h4 class="text-[10px] font-medium text-slate-500 uppercase tracking-wide">Nota</h4>
                            <p class="text-[10px] text-slate-500 leading-tight whitespace-pre-wrap"><?= nl2br(htmlspecialchars($datosEmisor['NotaLegal'])) ?></p>
            </section>
                    <?php endif; ?>
            </footer>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function imprimirCotizacion() {
            const contenido = document.getElementById('area-cotizacion').outerHTML;
            const estilos = document.getElementById('template-styles').innerHTML;
            const ventana = window.open('', '_blank', 'width=1000,height=800');
            ventana.document.write(`<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Cotización <?= htmlspecialchars($cotizacion->numeroCotizacion) ?></title><script src="https://cdn.tailwindcss.com"></script><style>${estilos}</style></head><body class=\"min-h-screen font-sans text-slate-700 text-[13px] leading-tight bg-slate-100\"><div class=\"w-full px-4 pb-8 flex justify-center\">${contenido}</div></body></html>`);
            ventana.document.close();
            ventana.focus();
            ventana.onload = () => {
                ventana.print();
                ventana.close();
            };
        }

        function prepararNodoParaPDF(id) {
            const original = document.getElementById(id);
            const clon = original.cloneNode(true);
            clon.id = id + '-pdf';
            clon.style.width = '8.27in';
            clon.style.maxWidth = '8.27in';
            clon.style.margin = '0 auto';
            clon.querySelector('.preview-content').style.padding = '1rem 1.9rem';
            clon.classList.add('no-print');
            document.body.appendChild(clon);
            return clon;
        }

        function descargarCotizacion() {
            const clon = prepararNodoParaPDF('area-cotizacion');
            html2pdf().set({
                margin: [5, 5, 5, 5],
                filename: 'cotizacion-<?= htmlspecialchars($cotizacion->numeroCotizacion) ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            }).from(clon).save().then(() => clon.remove());
        }
    </script>
</body>
</html>
