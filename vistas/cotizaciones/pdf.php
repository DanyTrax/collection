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
    <title>Cotización <?= htmlspecialchars($cotizacion->numeroCotizacion) ?></title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: #e2e8f0;
            color: #1e293b;
        }
        #pdf-document {
            width: 8.27in;
            min-height: 11in;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 32px;
            border: 1px solid #cbd5f5;
            padding: 24px 40px;
            box-shadow: 0 12px 42px rgba(15, 23, 42, 0.12);
        }
        h1, h2, h3, h4, h5, h6 { margin: 0; }
        .muted { color: #64748b; }
        .uppercase { text-transform: uppercase; letter-spacing: 0.18em; font-size: 10px; }
        .header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
        }
        .header-col { flex: 1; }
        .title-number {
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 0.2em;
        }
        dl { margin: 0; }
        dl div { margin-bottom: 4px; font-size: 12px; color: #475569; }
        dl dt { font-weight: 600; }
        dl dd { margin: 0 0 0 4px; display: inline; }
        .card {
            border: 1px solid #cbd5f5;
            border-radius: 20px;
            background: #f8fafc;
            padding: 16px 28px;
        }
        .card h3 { font-size: 11px; color: #64748b; letter-spacing: 0.16em; margin-bottom: 6px; }
        .card p { margin: 2px 0; }
        .concepto { margin-top: 18px; }
        .concepto h3 {
            font-size: 11px;
            color: #1f2937;
            letter-spacing: 0.16em;
            border-bottom: 1px solid #cbd5f5;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .totales {
            width: 45%;
            margin-left: auto;
            border: 1px solid #cbd5f5;
            border-radius: 20px;
            overflow: hidden;
        }
        .totales table { width: 100%; border-collapse: collapse; }
        .totales td {
            padding: 10px 14px;
            text-align: right;
            font-size: 12px;
        }
        .totales tr:first-child td {
            background: #f8fafc;
            color: #475569;
            font-weight: 500;
        }
        .totales tr:last-child td {
            border-top: 1px solid #94a3b8;
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .section { margin-top: 18px; }
        .section h4 { font-size: 12px; margin-bottom: 6px; color: #1f2937; }
        .section p { font-size: 11px; margin: 2px 0; line-height: 1.4; white-space: pre-wrap; }
        .firma {
            margin-top: 24px;
            text-align: center;
            padding-top: 16px;
            border-top: 1px solid #cbd5f5;
        }
        .firma img { height: 60px; object-fit: contain; }
        .nota {
            margin-top: 16px;
            border: 1px solid #cbd5f5;
            border-radius: 20px;
            background: #f8fafc;
            padding: 12px 20px;
        }
        .nota h4 { font-size: 10px; text-transform: uppercase; letter-spacing: 0.14em; color: #64748b; margin-bottom: 6px; }
        .nota p { font-size: 10px; color: #64748b; line-height: 1.4; white-space: pre-wrap; }
        @page { size: Letter; margin: 0; }
    </style>
</head>
<body>
    <div id="pdf-document">
        <div class="header">
            <div class="header-col">
                <h2 style="font-size:22px;font-weight:500;color:#111827;letter-spacing:-0.01em;">
                    <?= htmlspecialchars($datosEmisor['NombreCompleto'] ?? '') ?>
                </h2>
                <?php if (!empty($datosEmisor['Telefono'])): ?>
                    <p class="muted">Tel. <?= htmlspecialchars($datosEmisor['Telefono']) ?></p>
                <?php endif; ?>
                <?php if (!empty($datosEmisor['Direccion'])): ?>
                    <p class="muted"><?= htmlspecialchars($datosEmisor['Direccion']) ?></p>
                <?php endif; ?>
                <?php if (!empty($datosEmisor['Email'])): ?>
                    <p class="muted">E-Mail: <?= htmlspecialchars($datosEmisor['Email']) ?></p>
                <?php endif; ?>
                <?php if (!empty($datosEmisor['DocumentoIdentidad'])): ?>
                    <p class="muted">Documento: <?= htmlspecialchars($datosEmisor['DocumentoIdentidad']) ?></p>
                <?php endif; ?>
            </div>
            <div class="header-col" style="text-align:right;">
                <p class="uppercase muted">Cotización</p>
                <p class="title-number">Nº <?= htmlspecialchars($cotizacion->numeroCotizacion) ?></p>
                <dl>
                    <div><dt>Ciudad y fecha:</dt><dd><?= htmlspecialchars($datosEmisor['Ciudad'] ?? '') ?>, <?= htmlspecialchars($cotizacion->fechaEmision) ?></dd></div>
                    <?php if (!empty($cotizacion->fechaVencimiento)): ?>
                        <div><dt>Válida hasta:</dt><dd><?= htmlspecialchars($cotizacion->fechaVencimiento) ?></dd></div>
                    <?php endif; ?>
                    <div><dt>Estado:</dt><dd><?= htmlspecialchars($cotizacion->estado) ?></dd></div>
                </dl>
            </div>
        </div>

        <div class="section card">
            <h3>Cliente</h3>
            <p style="font-size:15px;color:#0f172a;font-weight:500;"><?= htmlspecialchars($datosCliente['NombreCliente'] ?? '') ?></p>
            <?php if (!empty($datosCliente['NIT_CC'])): ?>
                <p class="muted" style="font-size:12px;">NIT / CC: <?= htmlspecialchars($datosCliente['NIT_CC']) ?></p>
            <?php endif; ?>
            <?php if (!empty($datosCliente['Direccion'])): ?>
                <p class="muted" style="font-size:12px;"><?= htmlspecialchars($datosCliente['Direccion']) ?></p>
            <?php endif; ?>
            <div style="display:flex;flex-wrap:wrap;gap:16px;margin-top:8px;font-size:12px;color:#475569;">
                <?php if (!empty($datosCliente['Email'])): ?><p style="margin:0;"><strong>Correo:</strong> <?= htmlspecialchars($datosCliente['Email']) ?></p><?php endif; ?>
                <?php if (!empty($datosCliente['Telefono'])): ?><p style="margin:0;"><strong>Teléfono:</strong> <?= htmlspecialchars($datosCliente['Telefono']) ?></p><?php endif; ?>
                <?php if (!empty($datosCliente['Ciudad'])): ?><p style="margin:0;"><strong>Ciudad:</strong> <?= htmlspecialchars($datosCliente['Ciudad']) ?></p><?php endif; ?>
            </div>
        </div>

        <div class="concepto">
            <h3>Concepto</h3>
            <p style="font-size:11px;color:#475569;line-height:1.45;white-space:pre-wrap;">
                <?= nl2br(htmlspecialchars($cotizacion->concepto)) ?>
            </p>
        </div>

        <div class="totales">
            <table>
                <tbody>
                    <tr>
                        <td style="text-align:left;font-weight:500;">Subtotal</td>
                        <td>$<?= $valorFormateado ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:left;">Valor total</td>
                        <td>$<?= $valorFormateado ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php if (!empty($cotizacion->terminos)): ?>
            <div class="section card" style="background:#eff6ff;border-color:#bfdbfe;color:#1e3a8a;">
                <h4 style="margin:0 0 4px 0;font-size:12px;">Términos y Condiciones</h4>
                <p style="white-space:pre-wrap;font-size:11px;line-height:1.35;">
                    <?= nl2br(htmlspecialchars($cotizacion->terminos)) ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (!empty($datosEmisor['InformacionBancaria'])): ?>
            <div class="section card" style="background:#eff6ff;border-color:#bfdbfe;color:#1e3a8a;">
                <h4 style="margin:0 0 4px 0;font-size:12px;">Información de Pago</h4>
                <p style="white-space:pre-wrap;font-size:11px;line-height:1.35;">
                    <?= nl2br(htmlspecialchars($datosEmisor['InformacionBancaria'])) ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="firma">
            <?php if (!empty($datosEmisor['FirmaImagenURL'])): ?>
                <img src="<?= htmlspecialchars($datosEmisor['FirmaImagenURL']) ?>" alt="Firma">
            <?php else: ?>
                <div style="height:60px;border-bottom:1px solid #cbd5f5;width:180px;margin:0 auto 6px auto;"></div>
            <?php endif; ?>
            <p style="font-size:11px;font-weight:600;"><?= htmlspecialchars($datosEmisor['NombreCompleto'] ?? '') ?></p>
            <p class="muted" style="font-size:10px;margin-top:4px;">Se expide en <?= htmlspecialchars($datosEmisor['Ciudad'] ?? '________') ?>, a los <?= htmlspecialchars($diaTexto) ?> días del mes de <?= htmlspecialchars($mesNombre) ?> de <?= htmlspecialchars($anioTexto) ?>.</p>
        </div>

        <?php if (!empty($datosEmisor['NotaLegal'])): ?>
            <div class="nota">
                <h4>Nota</h4>
                <p><?= nl2br(htmlspecialchars($datosEmisor['NotaLegal'])) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const elemento = document.getElementById('pdf-document');
            html2pdf().set({
                margin: [5, 5, 5, 5],
                filename: 'cotizacion-<?= htmlspecialchars($cotizacion->numeroCotizacion) ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            }).from(elemento).save().then(() => {
                setTimeout(() => window.close(), 400);
            });
        });
    </script>
</body>
</html>
