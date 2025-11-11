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
    <title>Imprimir Cotización</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { color-scheme: light; }
        body { background: #e2e8f0; }
        @page { size: Letter; margin: 10mm; }
    </style>
</head>
<body class="min-h-screen font-sans text-slate-700 text-[13px] leading-tight bg-slate-100">
    <div class="w-full px-4 pb-8 flex justify-center">
        <div class="preview-wrapper bg-white border border-slate-200 shadow-xl" style="width:8.27in;max-width:8.27in;border-radius:32px;">
            <div class="preview-content space-y-6" style="padding:1rem 2rem;">
                 <?php include __DIR__ . '/partials/cotizacion_detalle.php'; ?>
            </div>
        </div>
     </div>
    <script>
        window.addEventListener('load', () => {
            setTimeout(() => {
                window.print();
                window.close();
            }, 150);
        });
    </script>
</body>
</html>
