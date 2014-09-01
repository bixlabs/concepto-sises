# language: es
@contrato
Característica: Crear contrato
  Como usuario del sistema
  Necesito poder crear un contrato

  Antecedentes:
    Dado que no hay contratos
    Dado que no hay empresas
    Dado una nueva empresa
    Dado el "nit" de la empresa "800234567-1"
    Dado el "nombre" de la empresa "DINCO"
    Entonces crea una nueva empresa

  Escenario: crear contrato
    Dado un nuevo contrato
    Dado el contrato de nombre "CONTRATO 1"
    Dada la descripcion del contrato "CONTRATO DE ALIMENTACION"
    Dada la resolucion del contrato "1024 de Diciembre de 2014"
    Dado el valor 15345678765.43
    Entonces crea un nuevo contrato

  Escenario: crear contrato invalido sin resolucion
    Dado un nuevo contrato
    Dado el contrato de nombre "CONTRATO 1"
    Dada la descripcion del contrato "CONTRATO DE ALIMENTACION"
    Dado el valor 15345678765.43
    Entonces crea un nuevo contrato invalido

  Escenario: crea contrato invalido con una misma resolucion
    Dado un nuevo contrato
    Dado el contrato de nombre "CONTRATO 1"
    Dada la descripcion del contrato "CONTRATO DE ALIMENTACION"
    Dada la resolucion del contrato "1024 de Diciembre de 2014"
    Dado el valor 15345678765.43
    Entonces crea un nuevo contrato
    Dado un nuevo contrato
    Dado el contrato de nombre "CONTRATO 2"
    Dada la descripcion del contrato "CONTRATO DE ALIMENTACION 2"
    Dada la resolucion del contrato "1024 de Diciembre de 2014"
    Dado el valor 15345678765.43
    Entonces crea un nuevo contrato invalido