
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


const almacen= [
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