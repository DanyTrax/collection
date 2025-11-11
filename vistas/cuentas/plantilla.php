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
    <style id="template-styles">
        :root {
            color-scheme: light;
        }
        body {
            background: #e2e8f0;
        }
        .preview-wrapper {
            width: min(1100px, 95vw);
            max-width: 1100px;
            border-radius: 32px;
        }
        .preview-content {
            padding: 1rem 2rem;
        }
        @page {
            size: Letter;
            margin: 10mm;
        }
        @media print {
            .no-print,
            .content-header,
            .main-header,
            .main-sidebar,
            .main-footer { display: none !important; }
        }
    </style>
</head>
<body class="min-h-screen font-sans text-slate-700 text-[13px] leading-tight" data-print-container="area-cuenta">
    <div class="w-full px-4 pb-8 flex justify-center">
        <div class="no-print flex justify-center gap-3 absolute top-6">
            <button onclick="imprimirCuenta()" class="bg-emerald-600 text-white px-6 py-2 rounded-lg shadow hover:bg-emerald-700 transition">Imprimir</button>
            <button onclick="descargarCuenta()" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">Descargar PDF</button>
        </div>
        <div id="area-cuenta" class="preview-wrapper bg-white border border-slate-200 shadow-xl mt-20">
            <div class="preview-content space-y-6">
                <?php include __DIR__ . '/partials/cuenta_detalle.php'; ?>
            </div>
        </div>
    </div>

    <script>
        function imprimirCuenta() {
            window.open('/cuentas/imprimir/<?= $cuenta->cuentaID ?>', '_blank');
        }

        function descargarCuenta() {
            window.open('/cuentas/pdf/<?= $cuenta->cuentaID ?>', '_blank');
        }
    </script>
</body>
</html>
