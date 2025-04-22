<!DOCTYPE html>
<html>

<head>
    <title>Incidencia del Pedido</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'DejaVu Sans';
        }

        @page {
            size: A4 portrait;
        }
    </style>
</head>

<body class="h-full text-xs text-black">

    <div class="flex flex-col max-w-[210mm] mx-auto p-6 bg-white rounded min-h-screen space-y-6">

        <!-- ENCABEZADO -->
        <div class="flex justify-between items-start">
            <div class="space-y-1">
                <h1 class="text-lg font-bold">Congelados Brisamar S.L.</h1>
                <p>C/Dieciocho de Julio de 1922 Nº2 - 21410 Isla Cristina</p>
                <p>Tel: +34 613 09 14 94</p>
                <p>administracion@congeladosbrisamar.es</p>
                <p>ES 12.021462/H CE</p>
            </div>
            <div class="flex flex-col items-end">
                <h2 class="text-lg font-bold text-right">INCIDENCIA</h2>
                <p><span class="font-semibold">Pedido:</span> {{ $entity->formattedId }}</p>
                <p><span class="font-semibold">Fecha carga:</span> {{ date('d/m/Y', strtotime($entity->load_date)) }}
                </p>
                <p><span class="font-semibold">Cliente:</span> {{ $entity->customer->name }}</p>
                <p><span class="font-semibold">Transportista:</span> {{ $entity->transport->name }}</p>
            </div>
        </div>

        <!-- QR CODE -->
        <div class="flex justify-end">
            <div class="p-1 border rounded bg-white">
                <img alt='QR Incidencia'
                    src="{{ 'https://barcode.tec-it.com/barcode.ashx?data=Incidencia%3A' . $entity->incident->id . '&code=QRCode&eclevel=L' }}"
                    class="w-[4.1rem] h-[4.1rem]" />
            </div>
        </div>

        <!-- DETALLE INCIDENCIA -->
        <div class="border rounded-lg bg-gray-50 p-4 space-y-4">
            <h3 class="text-md font-bold">Detalle de la Incidencia</h3>

            <p><span class="font-semibold">Fecha de creación:</span>
                {{ date('d/m/Y', strtotime($entity->incident->created_at)) }}</p>

            <div>
                <p class="font-semibold">Descripción:</p>
                <p class="ml-2">{!! nl2br(e($entity->incident->description)) !!}</p>
            </div>

            @if($entity->incident->resolved_at)
                <p><span class="font-semibold">Fecha de resolución:</span>
                    {{ date('d/m/Y', strtotime($entity->incident->resolved_at)) }}</p>
                <p><span class="font-semibold">Tipo de resolución:</span> {{ $entity->incident->resolution_type }}</p>
                <div>
                    <p class="font-semibold">Notas de resolución:</p>
                    <p class="ml-2">{!! nl2br(e($entity->incident->resolution_notes)) !!}</p>
                </div>
            @else
                <p class="text-red-600 font-semibold">Incidencia pendiente de resolución</p>
            @endif
        </div>

        <!-- PIE -->
        <div class="pt-8 text-center text-[10px] text-gray-600">
            <p>Gracias por confiar en Congelados Brisamar S.L.</p>
        </div>
    </div>

</body>

</html>