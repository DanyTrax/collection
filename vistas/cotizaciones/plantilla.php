<?php
$valorFormateado = $cotizacion->valorFormateado();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none; }
        }
        @page {
            size: Letter;
            margin: 1in;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans p-4 md:p-8">
    <div class="max-w-4xl mx-auto mb-4 text-right no-print">
        <button onclick="imprimirCotizacion()" class="bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition">Imprimir / PDF</button>
    </div>
    <div id="area-cotizacion" class="max-w-4xl mx-auto bg-white shadow-2xl rounded-xl overflow-hidden">
        <div class="p-8 md:p-12">
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
                <div class="mb-6 md:mb-0">
                    <h2 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($datosEmisor['NombreCompleto'] ?? '') ?></h2>
                    <p class="text-gray-600">Cel. <?= htmlspecialchars($datosEmisor['Telefono'] ?? '') ?></p>
                    <p class="text-gray-600"><?= htmlspecialchars($datosEmisor['Direccion'] ?? '') ?></p>
                    <p class="text-gray-600">E-Mail: <?= htmlspecialchars($datosEmisor['Email'] ?? '') ?></p>
                </div>
                <div class="text-left md:text-right">
                    <h1 class="text-3xl font-extrabold text-gray-900 uppercase tracking-widest">COTIZACIÓN</h1>
                    <div class="mt-2">
                        <span class="bg-gray-200 text-gray-800 font-mono py-2 px-4 rounded-md text-lg font-bold"># <?= htmlspecialchars($cotizacion->numeroCotizacion) ?></span>
                    </div>
                    <p class="text-gray-600 mt-4"><span class="font-semibold">Fecha:</span> <?= htmlspecialchars($cotizacion->fechaEmision) ?></p>
                    <p class="text-gray-600 mt-1"><span class="font-semibold">Válida hasta:</span> <?= htmlspecialchars($cotizacion->fechaVencimiento ?? 'N/A') ?></p>
                </div>
            </header>
            <section class="mb-10 bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">CLIENTE:</h3>
                <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($datosCliente['NombreCliente'] ?? '') ?></p>
                <p class="text-gray-600">NIT. <?= htmlspecialchars($datosCliente['NIT_CC'] ?? '') ?></p>
            </section>
            <section class="mb-10">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Concepto</h3>
                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap"><?= nl2br(htmlspecialchars($cotizacion->concepto)) ?></p>
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
            <section class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Términos y Condiciones:</h4>
                <p class="text-xs text-gray-600 leading-relaxed whitespace-pre-wrap"><?= nl2br(htmlspecialchars($cotizacion->terminos ?? '')) ?></p>
            </section>
            <footer class="mt-10 pt-10 border-t border-gray-200 text-center">
                <p class="text-gray-600">Gracias por su interés.</p>
                <p class="text-gray-800 font-semibold mt-2"><?= htmlspecialchars($datosEmisor['NombreCompleto'] ?? '') ?></p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/print-js@1.6.0/dist/print.min.js"></script>
    <script>
        function imprimirCotizacion() {
            printJS({
                printable: 'area-cotizacion',
                type: 'html',
                scanStyles: true,
                documentTitle: 'Cotización <?= htmlspecialchars($cotizacion->numeroCotizacion) ?>',
                style: '@page { size: Letter; margin: 1in; }'
            });
        }
    </script>
</body>
</html>
