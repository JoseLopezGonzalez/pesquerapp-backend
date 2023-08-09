
//Añadir Palet
const enterPalet = {
    //id
    observaciones,
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
    observaciones,
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
    nombre,
    temperatura,
    capacidad,
    palets,
    cajas,
    tinas,

}


const almacen= [
    {
      id: 1,
      nombre: "Cámara de congelados",
      temperatura: "-18.50",
      capacidad: "80000.50",
      pesoNetoPalets: 0,
      pesoNetoTotal: 0,
      palets: [
        {
          id: 2,
          observaciones: "Prueba Palet Almacenado",
          id_estado: 2,
          id_almacen: 1,
          created_at: null,
          updated_at: null,
          cajas: []
        },
        {
          id: 3,
          observaciones: "Prueba Palet Almacenado 2",
          id_estado: 2,
          id_almacen: 1,
          created_at: null,
          updated_at: null,
          cajas: []
        }
      ]
    }
  ]