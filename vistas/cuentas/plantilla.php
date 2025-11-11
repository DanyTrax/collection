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
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print { display: none !important; }
            .page-wrapper {
                box-shadow: none !important;
                margin: 0 !important;
            }
        }
        @page {
            size: Letter;
            margin: 15mm;
        }
        body {
            background: #f1f5f9;
        }
        .page-wrapper {
            width: 7.15in;
            max-width: 100%;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans p-4 md:p-8 text-gray-700">
    <div class="max-w-4xl mx-auto mb-4 flex flex-col sm:flex-row gap-2 justify-end no-print">
        <button onclick="imprimirCuenta()" class="bg-emerald-600 text-white py-2 px-6 rounded-lg shadow-md hover:bg-emerald-700 transition">Imprimir</button>
        <button onclick="descargarCuenta()" class="bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition">Descargar PDF</button>
    </div>

    <div id="area-cuenta" class="page-wrapper mx-auto bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-200 text-[0.92rem] leading-relaxed">
        <div class="p-6 md:p-8">
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 pb-6 border-b border-gray-200">
                <div class="space-y-1">
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight"><?= htmlspecialchars($datosEmisor['NombreCompleto'] ?? '') ?></h2>
                    <?php if (!empty($datosEmisor['Telefono'])): ?>
                        <p class="text-sm text-gray-500">Tel. <?= htmlspecialchars($datosEmisor['Telefono']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($datosEmisor['Direccion'])): ?>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($datosEmisor['Direccion']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($datosEmisor['Email'])): ?>
                        <p class="text-sm text-gray-500">E-Mail: <?= htmlspecialchars($datosEmisor['Email']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($datosEmisor['DocumentoIdentidad'])): ?>
                        <p class="text-sm text-gray-500">Documento: <?= htmlspecialchars($datosEmisor['DocumentoIdentidad']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="text-start md:text-end space-y-3">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Cuenta de Cobro</span>
                        <h1 class="text-3xl font-black text-gray-900 tracking-[0.35em]">Nº <?= htmlspecialchars($cuenta->numeroCuenta) ?></h1>
                    </div>
                    <div class="text-sm text-gray-500 leading-relaxed">
                        <p><span class="font-semibold">Ciudad y fecha:</span> <?= htmlspecialchars(($datosEmisor['Ciudad'] ?? '')) ?>, <?= htmlspecialchars($cuenta->fechaEmision) ?></p>
                        <?php if (!empty($cuenta->fechaVencimiento)): ?>
                            <p><span class="font-semibold">Vence:</span> <?= htmlspecialchars($cuenta->fechaVencimiento) ?></p>
                        <?php endif; ?>
                        <p><span class="font-semibold">Estado:</span> <?= htmlspecialchars($cuenta->estado) ?></p>
                    </div>
                </div>
            </header>

            <section class="mt-6 mb-6 bg-gray-50 border border-gray-200 rounded-2xl p-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Señor(a)</h3>
                <p class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($datosCliente['NombreCliente'] ?? '') ?></p>
                <?php if (!empty($datosCliente['NIT_CC'])): ?>
                    <p class="text-sm text-gray-600">NIT / CC: <?= htmlspecialchars($datosCliente['NIT_CC']) ?></p>
                <?php endif; ?>
                <?php if (!empty($datosCliente['Direccion'])): ?>
                    <p class="text-sm text-gray-600"><?= htmlspecialchars($datosCliente['Direccion']) ?></p>
                <?php endif; ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm text-gray-600">
                    <?php if (!empty($datosCliente['Email'])): ?>
                        <p><span class="font-semibold">Correo:</span> <?= htmlspecialchars($datosCliente['Email']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($datosCliente['Telefono'])): ?>
                        <p><span class="font-semibold">Teléfono:</span> <?= htmlspecialchars($datosCliente['Telefono']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($datosCliente['Ciudad'])): ?>
                        <p><span class="font-semibold">Ciudad:</span> <?= htmlspecialchars($datosCliente['Ciudad']) ?></p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="mb-8">
                <h3 class="text-base font-semibold text-gray-900 uppercase tracking-wide mb-4 border-b border-gray-200 pb-2">Concepto</h3>
                <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap"><?= nl2br(htmlspecialchars($cuenta->concepto)) ?></p>

                <div class="mt-6 flex justify-end">
                    <table class="w-full md:w-1/2 text-right text-sm border border-gray-200 rounded-xl overflow-hidden">
                        <tbody>
                            <tr class="bg-gray-50 text-gray-600">
                                <td class="p-3 font-semibold">Subtotal</td>
                                <td class="p-3 font-mono text-gray-800">$<?= $valorFormateado ?></td>
                            </tr>
                            <tr class="border-t-2 border-gray-900 text-gray-900">
                                <td class="p-4 text-lg font-bold uppercase">Valor total</td>
                                <td class="p-4 text-lg font-bold font-mono">$<?= $valorFormateado ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <?php if (!empty($datosEmisor['InformacionBancaria'])): ?>
                <section class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-6">
                    <h4 class="text-base font-semibold text-blue-900 mb-3">Información de Pago</h4>
                    <p class="text-sm text-blue-800 leading-relaxed whitespace-pre-wrap"><?= nl2br(htmlspecialchars($datosEmisor['InformacionBancaria'])) ?></p>
                </section>
            <?php endif; ?>

            <footer class="pt-10 border-t border-gray-200">
                <div class="mb-6 text-center">
                    <?php if (!empty($datosEmisor['FirmaImagenURL'])): ?>
                        <img src="<?= htmlspecialchars($datosEmisor['FirmaImagenURL']) ?>" alt="Firma" class="h-20 mx-auto mb-2 object-contain">
                    <?php else: ?>
                        <div class="h-16 w-48 border-b border-gray-300 mx-auto mb-2 flex items-end justify-center">
                            <span class="text-xs italic text-gray-400">[Firma]</span>
                        </div>
                    <?php endif; ?>
                    <p class="text-sm font-semibold text-gray-700"><?= htmlspecialchars($datosEmisor['NombreCompleto'] ?? '') ?></p>
                </div>
                <p class="text-xs text-gray-500 text-center">Se firma en <?= htmlspecialchars($datosEmisor['Ciudad'] ?? '________') ?>, a los <?= htmlspecialchars($diaTexto) ?> días del mes de <?= htmlspecialchars($mesNombre) ?> de <?= htmlspecialchars($anioTexto) ?>.</p>
            </footer>

            <?php if (!empty($datosEmisor['NotaLegal'])): ?>
                <section class="mt-8 bg-gray-50 border border-gray-200 rounded-2xl p-5">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Nota</h4>
                    <p class="text-xs text-gray-500 leading-relaxed whitespace-pre-wrap"><?= nl2br(htmlspecialchars($datosEmisor['NotaLegal'])) ?></p>
                </section>
            <?php endif; ?>
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
                style: '@page { size: Letter; margin: 20mm; }'
            });
        }

        function descargarCuenta() {
            const elemento = document.getElementById('area-cuenta');
            if (typeof html2pdf === 'undefined') {
                alert('No fue posible cargar el generador de PDF. Inténtelo de nuevo.');
                return;
            }
            html2pdf(elemento, {
                margin: 10,
                filename: 'cuenta-<?= htmlspecialchars($cuenta->numeroCuenta) ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'mm', format: 'letter', orientation: 'portrait' }
            });
        }
    </script>
</body>
</html>
