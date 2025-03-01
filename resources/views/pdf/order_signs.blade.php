<!-- resources/views/pdf/delivery_note.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Delivery Note </title>
    {{-- Tailwind no funciona, lo cojo todo directamente de un cdn --}}

    <script src="https://cdn.tailwindcss.com"></script>



    <style>
        body {
            font-family: 'DejaVu Sans';
        }

        .bold-first-line::first-line {
            font-weight: bold;
        }

        @page {
            size: landscape;
            /* margin: 10mm 30mm 10mm 10mm;  */
            /* top, right, bottom, left */
        }

        /*  table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; } */
    </style>
</head>

<body>
    @foreach ($order->pallets as $pallet)
        <div className="w-full p-4 text-black h-svh flex flex-col gap-3 bg-white border-black rounded-lg">
            <div className="flex items-center justify-between mb-4">
                <div className="text-start">
                    <div className="text-3xl font-bold  tracking-tight">#4369</div>
                    <div className="text-sm ">Nº Pedido</div>
                </div>
                <div className="flex flex-col items-center w-96 justify-center">
                    <img alt='Barcode Generator TEC-IT'
                        src='https://barcode.tec-it.com/barcode.ashx?data=This+is+a+QR+Code+by+TEC-IT&code=QRCode&eclevel=L'
                        className='h-14' />
                </div>

            </div>


            <div className='grid grid-cols-2 w-full gap-2'>
                <div className="space-y-2 border rounded-lg p-2">
                    <div className="text-md font-semibold ">Expedidor:</div>
                    <div className="text-2xl font-semibold ">Ejemplo S.L.</div>
                    <p className="text-gray-600">
                        Polígono Industrial Las Atalayas, Calle del Mar 15, 03114 Alicante
                    </p>
                </div>

                <div className="space-y-2 border rounded-lg p-2">
                    <div className="text-md font-semibold ">Consignatario:</div>
                    <div className="text-2xl font-semibold ">Ejemplo S.L.</div>
                    <p className="text-gray-600">
                        Polígono Industrial Las Atalayas, Calle del Mar 15, 03114 Alicante
                    </p>
                </div>
            </div>


            <div className="grid grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg border w-full">
                <div className="text-center">
                    <div className="text-3xl font-bold ">#9087</div>
                    <div className="text-sm font-medium ">Nº PALET</div>
                </div>
                <div className="text-center border-r border-l">
                    <div className="text-3xl font-bold ">56</div>
                    <div className="text-sm font-medium ">CAJAS</div>
                </div>
                <div className="text-center">
                    <div className="text-3xl font-bold ">1.090,00</div>
                    <div className="text-sm font-medium ">PESO NETO</div>
                </div>
            </div>

            <div className='w-full flex items-center flex-1'>
                <div className="flex-1">
                    <div className="flex items-center gap-1 p-2 bg-white">
                        <div className='flex flex-col items-start '>
                            <div className="font-semibold">Gamba Argentina</div>
                            <div className="flex items-center justify-start gap-2 text-sm">
                                <div>- 10 cajas</div>
                                <div>- GTIN: 32456436435</div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>

            <div className="text-3xl font-semibold  border-l-4 border-gray-300 pl-3">
                Transportes Narval S.L.
            </div>




        </div>
    @endforeach


</body>

</html>
