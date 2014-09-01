# language: es
Característica: actualizar empresa
  Como usuario del sistema
  Necesito poder actualizar una empresa existente

  Antecedentes:
    Dado que no hay empresas
    Dado una nueva empresa
    Dado el nit "800234567-1"
    Dado el nombre "DINCO"
    Entonces crea una nueva empresa

  Escenario: actualizar un solo campo
    Dado que existe una empresa
    Dado una nueva empresa
    Dado el nombre "DINCO 2"
    Entonces actualiza la empresa