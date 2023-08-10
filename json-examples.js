
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
    data: [
        {
            id: 1,
            name: "Cámara de congelados",
            temperature: "-18.50",
            capacity: "80000.50",
            netWeightPallets: 9,
            totalNetWeight: 9,
            pallets: [
                {
                    id: 2,
                    observations: "Prueba Palet Almacenado",
                    state: {
                        id: 2,
                        name: "almacenado"
                    },
                    storeId: 1,
                    boxes: [
                        {
                            id: 7,
                            palletId: 2,
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
                                    name: "Zona 27.IX.a - Atlántico, nordestess"
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
                        },
                        {
                            id: 8,
                            palletId: 2,
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
                                    name: "Zona 27.IX.a - Atlántico, nordestess"
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
                    ]
                },
                {
                    id: 3,
                    observations: "Prueba Palet Almacenado 2",
                    state: {
                        id: 2,
                        name: "almacenado"
                    },
                    storeId: 1,
                    boxes: [
                        {
                            id: 9,
                            palletId: 3,
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
                                    name: "Zona 27.IX.a - Atlántico, nordestess"
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
                    ]
                }
            ]
        }
    ]
}
