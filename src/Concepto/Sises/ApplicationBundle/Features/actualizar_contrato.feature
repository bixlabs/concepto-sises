# language: es
@contrato
Característica: actualizar contrato
  Como usuario del sistema
  Necesito poder actualizar una contrato existente

  Antecedentes:
    Dado que no hay contratos
    Dado que no hay empresas
    Dado una nueva empresa
    Dado el "nit" de la empresa "800234567-1"
    Dado el "nombre" de la empresa "DINCO"
    Entonces crea una nueva empresa
    Dado un nuevo contrato
    Dado el contrato de nombre "CONTRATO 1"
    Dada la descripcion del contrato "CONTRATO DE ALIMENTACION"
    Dada la resolucion del contrato "1024 de Diciembre de 2014"
    Dado el valor 15345678765.43
    Entonces crea un nuevo contrato

  Escenario: actualizar un solo campo
    Dado un nuevo contrato
    Dado el contrato de nombre "CONTRATO 2"
    Entonces actualiza el contrato