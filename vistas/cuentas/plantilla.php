<?php
$valorFormateado = $cuenta->valorFormateado();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta de Cobro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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
            margin: 1in;
        }
        .page-wrapper {
            max-width: 8.27in;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans p-4 md:p-8">
    <div class="max-w-4xl mx-auto mb-4 flex flex-col sm:flex-row gap-2 justify-end no-print">
        <button onclick="imprimirCuenta()" class="bg-green-600 text-white py-2 px-6 rounded-lg shadow-md hover:bg-green-700 transition">Imprimir</button>
        <button onclick="descargarCuenta()" class="bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition">Descargar PDF</button>
    </div>
    <div id="area-cuenta" class="page-wrapper mx-auto bg-white shadow-2xl rounded-xl overflow-hidden">
        <div class="p-8 md:p-12">
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
                <div class="mb-6 md:mb-0">
                    <h2 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($datosEmisor['NombreCompleto'] ?? '') ?></h2>
                    <p class="text-gray-600">Documento: <?= htmlspecialchars($datosEmisor['DocumentoIdentidad'] ?? '') ?></p>
                    <p class="text-gray-600">Tel: <?= htmlspecialchars($datosEmisor['Telefono'] ?? '') ?></p>
                    <p class="text-gray-600"><?= htmlspecialchars($datosEmisor['Direccion'] ?? '') ?> - <?= htmlspecialchars($datosEmisor['Ciudad'] ?? '') ?></p>
                    <p class="text-gray-600">E-Mail: <?= htmlspecialchars($datosEmisor['Email'] ?? '') ?></p>
                </div>
                <div class="text-left md:text-right">
                    <h1 class="text-3xl font-extrabold text-gray-900 uppercase tracking-widest">CUENTA DE COBRO</h1>
                    <div class="mt-2">
                        <span class="bg-gray-200 text-gray-800 font-mono py-2 px-4 rounded-md text-lg font-bold"># <?= htmlspecialchars($cuenta->numeroCuenta) ?></span>
                    </div>
                    <p class="text-gray-600 mt-4"><span class="font-semibold">Fecha de emisión:</span> <?= htmlspecialchars($cuenta->fechaEmision) ?></p>
                    <p class="text-gray-600 mt-1"><span class="font-semibold">Fecha de vencimiento:</span> <?= htmlspecialchars($cuenta->fechaVencimiento ?? 'N/A') ?></p>
                    <p class="text-gray-600 mt-1"><span class="font-semibold">Estado:</span> <?= htmlspecialchars($cuenta->estado) ?></p>
                </div>
            </header>
            <section class="mb-10 bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">CLIENTE:</h3>
                <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($datosCliente['NombreCliente'] ?? '') ?></p>
                <p class="text-gray-600">NIT / CC: <?= htmlspecialchars($datosCliente['NIT_CC'] ?? '') ?></p>
                <p class="text-gray-600">Correo: <?= htmlspecialchars($datosCliente['Email'] ?? '') ?></p>
                <p class="text-gray-600">Teléfono: <?= htmlspecialchars($datosCliente['Telefono'] ?? '') ?></p>
                <p class="text-gray-600">Dirección: <?= htmlspecialchars($datosCliente['Direccion'] ?? '') ?> - <?= htmlspecialchars($datosCliente['Ciudad'] ?? '') ?></p>
            </section>
            <section class="mb-10">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Concepto</h3>
                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap"><?= nl2br(htmlspecialchars($cuenta->concepto)) ?></p>
                <div class="mt-8 flex justify-end">
                    <table class="w-full md:w-1/2 text-right">
                        <tbody>
                            <tr class="border-t-2 border-gray-900">
                                <td class="p-4 text-xl font-bold text-gray-900">VALOR TOTAL</td>
                                <td class="p-4 text-xl font-bold font-mono text-gray-900">$<?= $valorFormateado ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <?php if (!empty($datosEmisor['InformacionBancaria'])): ?>
                <section class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-8">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Información bancaria</h4>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap"><?= nl2br(htmlspecialchars($datosEmisor['InformacionBancaria'])) ?></p>
                </section>
            <?php endif; ?>
            <?php if (!empty($datosEmisor['NotaLegal'])): ?>
                <section class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Nota legal</h4>
                    <p class="text-xs text-gray-600 whitespace-pre-wrap"><?= nl2br(htmlspecialchars($datosEmisor['NotaLegal'])) ?></p>
                </section>
            <?php endif; ?>
            <footer class="mt-10 pt-10 border-t border-gray-200 text-center">
                <p class="text-gray-600">Gracias por su pago oportuno.</p>
                <p class="text-gray-800 font-semibold mt-2"><?= htmlspecialchars($datosEmisor['NombreCompleto'] ?? '') ?></p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/print-js@1.6.0/dist/print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-YcsIPk14IOHjzqvS4FHmUb4crTz/ZxKBP5ZMx1DPZWS9Vyuk3F7S3w7Dnk3a1JpN96CB2A+qsSVqZ4E9dXQtNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function imprimirCuenta() {
            printJS({
                printable: 'area-cuenta',
                type: 'html',
                scanStyles: true,
                documentTitle: 'Cuenta <?= htmlspecialchars($cuenta->numeroCuenta) ?>',
                style: '@page { size: Letter; margin: 1in; }'
            });
        }

        function descargarCuenta() {
            const elemento = document.getElementById('area-cuenta');
            html2pdf()
                .set({
                    margin: [10, 10, 10, 10],
                    filename: 'cuenta-<?= htmlspecialchars($cuenta->numeroCuenta) ?>.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'mm', format: 'letter', orientation: 'portrait' }
                })
                .from(elemento)
                .save();
        }
    </script>
</body>
</html>
