
//Añadir Palet
const enterPalet = {
    //id
    observations,
    estado,
    idAlmacen,
    cajas: [
        {
            idArticulo,
            gs1128,
            pesoBruto,
            pesoNeto,
        },
        {
            idArticulo,
            gs1128,
            pesoBruto,
            pesoNeto,
        },
    ],
}

//Editar Palet
const editPalet = {
    id,
    observations,
    estado,
    idAlmacen,
    cajas: [
        {
            idArticulo,
            gs1128,
            pesoBruto,
            pesoNeto,
        },
        {
            idArticulo,
            gs1128,
            pesoBruto,
            pesoNeto,
        },
    ],
}


//SHOW Cajas
const showCajas = {
    id,
    idPalet,
    idArticulo,
    gs1128,
    pesoBruto,
    pesoNeto,
}

//Show Almacen

const showAlmacen = {
    id,
    name,
    temperature,
    capacity,
    palets,
    cajas,
    tinas,

}


const almacen = [
    {
        id: 1,
        name: "Cámara de congelados",
        temperature: "-18.50",
        capacity: "80000.50",
        pesoNetoPalets: 0,
        pesoNetoTotal: 0,
        palets: [
            {
                id: 2,
                observations: "Prueba Palet Almacenado",
                state_id: 2,
                store_id: 1,
                created_at: null,
                updated_at: null,
                cajas: []
            },
            {
                id: 3,
                observations: "Prueba Palet Almacenado 2",
                state_id: 2,
                store_id: 1,
                created_at: null,
                updated_at: null,
                cajas: []
            }
        ]
    }
]


const almacenAPI = {
    id: 1,
    name: "Cámara de congelados",
    temperature: "-18.50",
    capacity: "80000.50",
    netWeightPallets: 0,
    totalNetWeight: 0,
    pallets: [
        {
            id: 2,
            observations: "Prueba Palet Almacenado",
            state: {
                id: 2,
                name: "almacenado"
            },
            boxes: [
                {
                    id: 6,
                    palletId: null,
                    article: {
                        id: 16,
                        name: "Pulpo eviscerado congelado en bloque T6",
                        category: {
                            id: 1,
                            name: "product"
                        },
                        species: {
                            id: 1,
                            name: "Pulpo común",
                            scientificName: "Octopus Vulgaris",
                            fao: "OCC",
                            image: "/app/assets/images/especies/pulpo_comun.jpg"
                        },
                        captureZone: {
                            id: 1,
                            name: "Zona 27.IX.a - Atlántico, nordestes"
                        },
                        articleGtin: "8436613930113",
                        boxGtin: "98436613930116",
                        palletGtin: "98436613930123",
                        fixedWeight: "20.00"
                    },
                    lot: "040223OCC0112",
                    gs1128: "156149819",
                    grossWeight: "15.00",
                    netWeight: "3.00"
                }
            ],
            position: null
        }
    ],
    map: {
        posiciones: [
            {
                id: 1,
                nombre: "A6",
                x: 40,
                y: 40,
                tipo: "left"
            },
            {
                id: 2,
                nombre: "A5",
                x: 40,
                y: 280,
                tipo: "left"
            },
            {
                id: 3,
                nombre: "A4",
                x: 40,
                y: 520,
                tipo: "left"
            },
            {
                id: 4,
                nombre: "A3",
                x: 40,
                y: 760,
                tipo: "left"
            },
            {
                id: 5,
                nombre: "A2",
                x: 40,
                y: 1000,
                tipo: "left"
            },
            {
                id: 6,
                nombre: "A1",
                x: 40,
                y: 1240,
                tipo: "left"
            },
            {
                id: 7,
                nombre: "M6",
                x: 240,
                y: 40,
                tipo: "center"
            },
            {
                id: 8,
                nombre: "M5",
                x: 240,
                y: 280,
                tipo: "center"
            },
            {
                id: 9,
                nombre: "M4",
                x: 240,
                y: 520,
                tipo: "center"
            },
            {
                id: 10,
                nombre: "M3",
                x: 240,
                y: 760,
                tipo: "center"
            },
            {
                id: 11,
                nombre: "M2",
                x: 240,
                y: 1000,
                tipo: "center"
            },
            {
                id: 12,
                nombre: "M1",
                x: 240,
                y: 1240,
                tipo: "center"
            },
            {
                id: 13,
                nombre: "B6",
                x: 440,
                y: 40,
                tipo: "right"
            },
            {
                id: 14,
                nombre: "B5",
                x: 440,
                y: 280,
                tipo: "right"
            },
            {
                id: 15,
                nombre: "B4",
                x: 440,
                y: 520,
                tipo: "right"
            },
            {
                id: 16,
                nombre: "B3",
                x: 440,
                y: 760,
                tipo: "right"
            },
            {
                id: 17,
                nombre: "B2",
                x: 440,
                y: 1000,
                tipo: "right"
            },
            {
                id: 18,
                nombre: "B1",
                x: 440,
                y: 1240,
                tipo: "right"
            },
            {
                id: 19,
                nombre: "A6",
                x: 940,
                y: 40,
                tipo: "left"
            },
            {
                id: 20,
                nombre: "A5",
                x: 940,
                y: 280,
                tipo: "left"
            },
            {
                id: 21,
                nombre: "A4",
                x: 940,
                y: 520,
                tipo: "left"
            },
            {
                id: 22,
                nombre: "A3",
                x: 940,
                y: 760,
                tipo: "left"
            },
            {
                id: 23,
                nombre: "A2",
                x: 940,
                y: 1000,
                tipo: "left"
            },
            {
                id: 24,
                nombre: "A1",
                x: 940,
                y: 1240,
                tipo: "left"
            },
            {
                id: 25,
                nombre: "M6",
                x: 1140,
                y: 40,
                tipo: "center"
            },
            {
                id: 26,
                nombre: "M5",
                x: 1140,
                y: 280,
                tipo: "center"
            },
            {
                id: 27,
                nombre: "M4",
                x: 1140,
                y: 520,
                tipo: "center"
            },
            {
                id: 28,
                nombre: "M3",
                x: 1140,
                y: 760,
                tipo: "center"
            },
            {
                id: 29,
                nombre: "M2",
                x: 1140,
                y: 1000,
                tipo: "center"
            },
            {
                id: 30,
                nombre: "M1",
                x: 1140,
                y: 1240,
                tipo: "center"
            },
            {
                id: 31,
                nombre: "B6",
                x: 1340,
                y: 40,
                tipo: "right"
            },
            {
                id: 32,
                nombre: "B5",
                x: 1340,
                y: 280,
                tipo: "right"
            },
            {
                id: 33,
                nombre: "B4",
                x: 1340,
                y: 520,
                tipo: "right"
            },
            {
                id: 34,
                nombre: "B3",
                x: 1340,
                y: 760,
                tipo: "right"
            },
            {
                id: 35,
                nombre: "B2",
                x: 1340,
                y: 1000,
                tipo: "right"
            },
            {
                id: 36,
                nombre: "B1",
                x: 1340,
                y: 1240,
                tipo: "right"
            },
            {
                id: 37,
                nombre: "A6",
                x: 1840,
                y: 40,
                tipo: "left"
            },
            {
                id: 38,
                nombre: "A5",
                x: 1840,
                y: 280,
                tipo: "left"
            },
            {
                id: 39,
                nombre: "A4",
                x: 1840,
                y: 520,
                tipo: "left"
            },
            {
                id: 40,
                nombre: "A3",
                x: 1840,
                y: 760,
                tipo: "left"
            },
            {
                id: 41,
                nombre: "A2",
                x: 1840,
                y: 1000,
                tipo: "left"
            },
            {
                id: 42,
                nombre: "A1",
                x: 1840,
                y: 1240,
                tipo: "left"
            },
            {
                id: 43,
                nombre: "M6",
                x: 2040,
                y: 40,
                tipo: "center"
            },
            {
                id: 44,
                nombre: "M5",
                x: 2040,
                y: 280,
                tipo: "center"
            },
            {
                id: 45,
                nombre: "M4",
                x: 2040,
                y: 520,
                tipo: "center"
            },
            {
                id: 46,
                nombre: "M3",
                x: 2040,
                y: 760,
                tipo: "center"
            },
            {
                id: 47,
                nombre: "M2",
                x: 2040,
                y: 1000,
                tipo: "center"
            },
            {
                id: 48,
                nombre: "M1",
                x: 2040,
                y: 1240,
                tipo: "center"
            },
            {
                id: 49,
                nombre: "B6",
                x: 2240,
                y: 40,
                tipo: "right"
            },
            {
                id: 50,
                nombre: "B5",
                x: 2240,
                y: 280,
                tipo: "right"
            },
            {
                id: 51,
                nombre: "B4",
                x: 2240,
                y: 520,
                tipo: "right"
            },
            {
                id: 52,
                nombre: "B3",
                x: 2240,
                y: 760,
                tipo: "right"
            },
            {
                id: 53,
                nombre: "B2",
                x: 2240,
                y: 1000,
                tipo: "right"
            },
            {
                id: 54,
                nombre: "B1",
                x: 2240,
                y: 1240,
                tipo: "right"
            },
            {
                id: 55,
                nombre: "A6",
                x: 2740,
                y: 40,
                tipo: "left"
            },
            {
                id: 56,
                nombre: "A5",
                x: 2740,
                y: 280,
                tipo: "left"
            },
            {
                id: 57,
                nombre: "A4",
                x: 2740,
                y: 520,
                tipo: "left"
            },
            {
                id: 58,
                nombre: "A3",
                x: 2740,
                y: 760,
                tipo: "left"
            },
            {
                id: 59,
                nombre: "A2",
                x: 2740,
                y: 1000,
                tipo: "left"
            },
            {
                id: 60,
                nombre: "A1",
                x: 2740,
                y: 1240,
                tipo: "left"
            },
            {
                id: 61,
                nombre: "M6",
                x: 2940,
                y: 40,
                tipo: "center"
            },
            {
                id: 62,
                nombre: "M5",
                x: 2940,
                y: 280,
                tipo: "center"
            },
            {
                id: 63,
                nombre: "M4",
                x: 2940,
                y: 520,
                tipo: "center"
            },
            {
                id: 64,
                nombre: "M3",
                x: 2940,
                y: 760,
                tipo: "center"
            },
            {
                id: 65,
                nombre: "M2",
                x: 2940,
                y: 1000,
                tipo: "center"
            },
            {
                id: 66,
                nombre: "M1",
                x: 2940,
                y: 1240,
                tipo: "center"
            },
            {
                id: 67,
                nombre: "B6",
                x: 3140,
                y: 40,
                tipo: "right"
            },
            {
                id: 68,
                nombre: "B5",
                x: 3140,
                y: 280,
                tipo: "right"
            },
            {
                id: 69,
                nombre: "B4",
                x: 3140,
                y: 520,
                tipo: "right"
            },
            {
                id: 70,
                nombre: "B3",
                x: 3140,
                y: 760,
                tipo: "right"
            },
            {
                id: 71,
                nombre: "B2",
                x: 3140,
                y: 1000,
                tipo: "right"
            },
            {
                id: 72,
                nombre: "B1",
                x: 3140,
                y: 1240,
                tipo: "right"
            },
            {
                id: 73,
                nombre: "A6",
                x: 3640,
                y: 40,
                tipo: "left"
            },
            {
                id: 74,
                nombre: "A5",
                x: 3640,
                y: 280,
                tipo: "left"
            },
            {
                id: 75,
                nombre: "A4",
                x: 3640,
                y: 520,
                tipo: "left"
            },
            {
                id: 76,
                nombre: "A3",
                x: 3640,
                y: 760,
                tipo: "left"
            },
            {
                id: 77,
                nombre: "A2",
                x: 3640,
                y: 1000,
                tipo: "left"
            },
            {
                id: 78,
                nombre: "A1",
                x: 3640,
                y: 1240,
                tipo: "left"
            },
            {
                id: 79,
                nombre: "M6",
                x: 3840,
                y: 40,
                tipo: "center"
            },
            {
                id: 80,
                nombre: "M5",
                x: 3840,
                y: 280,
                tipo: "center"
            },
            {
                id: 81,
                nombre: "M4",
                x: 3840,
                y: 520,
                tipo: "center"
            },
            {
                id: 82,
                nombre: "M3",
                x: 3840,
                y: 760,
                tipo: "center"
            },
            {
                id: 83,
                nombre: "M2",
                x: 3840,
                y: 1000,
                tipo: "center"
            },
            {
                id: 84,
                nombre: "M1",
                x: 3840,
                y: 1240,
                tipo: "center"
            },
            {
                id: 85,
                nombre: "B6",
                x: 4040,
                y: 40,
                tipo: "right"
            },
            {
                id: 86,
                nombre: "B5",
                x: 4040,
                y: 280,
                tipo: "right"
            },
            {
                id: 87,
                nombre: "B4",
                x: 4040,
                y: 520,
                tipo: "right"
            },
            {
                id: 88,
                nombre: "B3",
                x: 4040,
                y: 760,
                tipo: "right"
            },
            {
                id: 89,
                nombre: "B2",
                x: 4040,
                y: 1000,
                tipo: "right"
            },
            {
                id: 90,
                nombre: "B1",
                x: 4040,
                y: 1240,
                tipo: "right"
            }
        ],
        elementos: {
            fondos: [
                {
                    x: 0,
                    y: 0,
                    width: 665,
                    height: 1510
                },
                {
                    x: 900,
                    y: 0,
                    width: 665,
                    height: 1510
                },
                {
                    x: 1800,
                    y: 0,
                    width: 665,
                    height: 1510
                },
                {
                    x: 2700,
                    y: 0,
                    width: 665,
                    height: 1510
                },
                {
                    x: 3600,
                    y: 0,
                    width: 665,
                    height: 1510
                }
            ],
            textos: [
                {
                    x: 332,
                    y: 1600,
                    contenido: "1"
                },
                {
                    x: 1232,
                    y: 1600,
                    contenido: "2"
                },
                {
                    x: 2132,
                    y: 1600,
                    contenido: "3"
                },
                {
                    x: 3032,
                    y: 1600,
                    contenido: "4"
                },
                {
                    x: 3932,
                    y: 1600,
                    contenido: "5"
                }
            ]
        }
    }
}

const paletAPI = 
    {
        observations: "Prueba Palet Almacenado API",
        boxes: [
            {
                article: {
                    id: 16,
                },
                lot: "040223OCC0112",
                gs1128: "156149819",
                grossWeight: "15.00",
                netWeight: "3.00"
            }
        ],
    }





