# language: es
Característica: actualizar empresa
  Como usuario del sistema
  Necesito poder actualizar una empresa existente

  Antecedentes:
    Dado que no hay empresas
    Dado una nueva empresa
    Dado el "nit" de la empresa "800234567-1"
    Dado el "nombre" de la empresa "DINCO"
    Entonces crea una nueva empresa

  Escenario: actualizar un solo campo
    Dado una nueva empresa
    Dado el "nombre" de la empresa "DINCO 2"
    Entonces actualiza la empresa